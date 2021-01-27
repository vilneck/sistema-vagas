<?php

use Adianti\Widget\Dialog\TMessage;

/**
 * SystemUnitForm
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class CandidatoForm extends TStandardForm
{
    protected $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        
        $this->setDatabase('permission');              // defines the database
        $this->setActiveRecord('SystemUsers');     // defines the active record
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Candidato');
        $this->form->setFormTitle('Novo Candidato');
        
        // create the form fields
        $id = new TEntry('id');
        $name = new TEntry('name');
        $email = new TEntry('email');
        
        // add the fields
        $this->form->addFields( [new TLabel('Id')], [$id] );
        $this->form->addFields( [new TLabel(_t('Name'))], [$name] );
        $this->form->addFields( [new TLabel('Email')], [$email] );
        
        $id->setEditable(FALSE);
        $id->setSize('30%');
        $name->setSize('70%');
        $name->addValidation( _t('Name'), new TRequiredValidator );
        
        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction(array($this, 'onSave')), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('Clear'),  new TAction(array($this, 'onEdit')), 'fa:eraser red');
        $this->form->addActionLink('Voltar',new TAction(array('UsuarioFichaList','onReload')),'far:arrow-alt-circle-left blue');
        $this->form->addAction('Preencher Curriculo',new TAction(array($this,'onPreencherCurriculo')),'far:file blue');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(TBreadCrumb::create(["Candidatos","Novo"]));
        $container->add($this->form);
        
        parent::add($container);
    }
    static function onPreencherCurriculo($param)
    {
        if(empty($param['id']))
        {
            new TMessage('error','Salve o candidato antes de ir para seu curriculo!');
        }else{
            TApplication::loadPage('UsuarioFichaForm','onEdit',['id'=>$param['id'],'key'=>$param['id']]);
        }
    }
}
