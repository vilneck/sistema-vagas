<?php

use Adianti\Core\AdiantiCoreApplication;
use Adianti\Registry\TSession;

/**
 * LoginForm
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class LoginForm extends TPage
{
    protected $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct($param)
    {
        parent::__construct();
        
        $ini  = AdiantiApplicationConfig::get();
        
        $this->style = 'clear:both';
        // creates the form
        $this->form = new BootstrapFormBuilder('form_login');
        $this->form->setFormTitle( 'Acesso ao sistema' );
        
        // create the form fields
        $login = new TEntry('login');
        $password = new TPassword('password');
        
        // define the sizes
        $login->setSize('70%', 40);
        $password->setSize('70%', 40);

        $login->style = 'height:35px; font-size:14px;float:left;border-bottom-left-radius: 0;border-top-left-radius: 0;';
        $password->style = 'height:35px;font-size:14px;float:left;border-bottom-left-radius: 0;border-top-left-radius: 0;';
        
        $login->placeholder = 'Email';
        $password->placeholder = _t('Password');
        
        $login->autofocus = 'autofocus';

        $user   = '<span class="login-avatar"><span class="fa fa-user"></span></span>';
        $locker = '<span class="login-avatar"><span class="fa fa-lock"></span></span>';
        $unit   = '<span class="login-avatar"><span class="fa fa-university"></span></span>';
        $lang   = '<span class="login-avatar"><span class="fa fa-globe"></span></span>';
        
        $row = $this->form->addFields( [$user, $login] );
        $row->layout = ['col-sm-12 display-flex'];
        $row = $this->form->addFields( [$locker, $password] );
        $row->layout = ['col-sm-12 display-flex'];
        
        
        $btn = $this->form->addAction('Login', new TAction(array($this, 'onLogin')), '');
        $btn->class = 'btn btn-default';
        $btn->style = 'height: 40px;width: 100%;display: block;font-size:17px;';

        $btn = $this->form->addAction('Cadastre-se', new TAction(array("SystemRegistrationForm", 'onClear')), '');
        $btn->class = 'btn btn-default';
        $btn->style = 'height: 40px;width: 100%; margin-top:5px;display: block;font-size:17px;';
        
        $wrapper = new TElement('div');
        $wrapper->style = 'margin:auto; margin-top:100px;max-width:460px;';
        $wrapper->id = 'login-wrapper';
        
        $h3 = new TElement('h1');
        $h3->style = 'text-align:center;';
        $h3->add('Sistema de Vagas');
        
        $wrapper->add($h3);
        $wrapper->add($this->form);
        
        // add the form to the page
        parent::add($wrapper);
    }
    
    /**
     * user exit action
     * Populate unit combo
     */
    public static function onExitUser($param)
    {
        try
        {
            TTransaction::open('permission');
            
            $user = SystemUsers::newFromLogin( $param['login'] );
            if ($user instanceof SystemUsers)
            {
                $units = $user->getSystemUserUnits();
                $options = [];
                
                if ($units)
                {
                    foreach ($units as $unit)
                    {
                        $options[$unit->id] = $unit->name;
                    }
                }
                TCombo::reload('form_login', 'unit_id', $options);
            }
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error',$e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * Authenticate the User
     */
    public static function onLogin($param)
    {
        // $QUERY_STRING_NOT_LOGGED = TSession::getValue('QUERY_STRING_NOT_LOGGED');
        $ini  = AdiantiApplicationConfig::get();
        
        try
        {
            $data = (object) $param;
            
            (new TRequiredValidator)->validate( _t('Login'),    $data->login);
            (new TRequiredValidator)->validate( _t('Password'), $data->password);
            
            if (!empty($ini['general']['multiunit']) and $ini['general']['multiunit'] == '1')
            {
                (new TRequiredValidator)->validate( _t('Unit'), $data->unit_id);
            }
            
            TSession::regenerate();
            $user = ApplicationAuthenticationService::authenticate( $data->login, $data->password );
            
            if ($user)
            {
                ApplicationAuthenticationService::setUnit( $data->unit_id ?? null );
                ApplicationAuthenticationService::setLang( $data->lang_id ?? null );
                SystemAccessLogService::registerLogin();
                
                if(TSession::getValue('candidatura_vaga_id')!=null)
                {
                    $key = TSession::getValue('candidatura_vaga_id');
                    TSession::setValue('candidatura_vaga_id',null);
                    AdiantiCoreApplication::gotoPage('VagaView','onEdit',['key'=> $key ]); // reload
                    

                }else{
                // if($QUERY_STRING_NOT_LOGGED)
                // {
                //     TScript::create("location.href='index.php?{$QUERY_STRING_NOT_LOGGED}';");
                // }
                // else
                // {
                    $frontpage = $user->frontpage;
                    if ($frontpage instanceof SystemProgram and $frontpage->controller)
                    {
                        AdiantiCoreApplication::gotoPage($frontpage->controller); // reload
                        TSession::setValue('frontpage', $frontpage->controller);
                    }
                    else
                    {
                        AdiantiCoreApplication::gotoPage('EmptyPage'); // reload
                        TSession::setValue('frontpage', 'EmptyPage');
                    }
                }
            }
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error',$e->getMessage());
            TSession::setValue('logged', FALSE);
            TTransaction::rollback();
        }
    }
    
    /** 
     * Reload permissions
     */
    public static function reloadPermissions()
    {
        try
        {
            TTransaction::open('permission');
            $user = SystemUsers::newFromLogin( TSession::getValue('login') );
            
            if ($user)
            {
                $programs = $user->getPrograms();
                $programs['LoginForm'] = TRUE;
                TSession::setValue('programs', $programs);
                
                $frontpage = $user->frontpage;
                if ($frontpage instanceof SystemProgram AND $frontpage->controller)
                {
                    TApplication::gotoPage($frontpage->controller); // reload
                }
                else
                {
                    TApplication::gotoPage('EmptyPage'); // reload
                }
            }
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     *
     */
    public function onLoad($param)
    {
    }
    
    /**
     * Logout
     */
    public static function onLogout()
    {
        SystemAccessLogService::registerLogout();
        TSession::freeSession();
        AdiantiCoreApplication::gotoPage('LoginForm', '');
    }
}