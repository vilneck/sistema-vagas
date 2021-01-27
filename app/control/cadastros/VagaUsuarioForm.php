<?php

use Adianti\Control\TAction;
use Adianti\Database\TCriteria;
use Adianti\Database\TFilter;
use Adianti\Registry\TSession;
use Adianti\Widget\Form\TFormSeparator;
use Adianti\Widget\Form\THidden;
use Adianti\Widget\Form\TSelect;
use Adianti\Widget\Wrapper\TDBCombo;
use Adianti\Widget\Wrapper\TDBUniqueSearch;

class VagaUsuarioForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'geral';
    private static $activeRecord = 'VagaUsuario';
    private static $primaryKey = 'id';
    private static $formName = 'form_VagaUsuario';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Víncular de vaga ao candidato");


        $id = new THidden('id');
        $usuario_id = new TDBCombo('usuario_id', 'permission', 'SystemUsers', 'id', '({cpf_cnpj}) {name}','name asc'  );
        $vaga_id = new TDBCombo('vaga_id', 'geral', 'Vaga', 'id', '{titulo}','titulo asc'  );
        $cargo_id = new TDBUniqueSearch('cargo_id', 'geral', 'Cargo', 'id', 'nome','nome asc'  );

        $id->setEditable(false);

        $cargo_id->setChangeAction(new TAction([$this,'onChangeCargo']));

        //$cargo_id->enableSearch();
        $vaga_id->enableSearch();
        $usuario_id->enableSearch();

        $id->setSize(100);
        $vaga_id->setSize('100%');
        $cargo_id->setSize('100%');
        $usuario_id->setSize('100%');

        $row1 = $this->form->addFields([$id]);
        $row1 = $this->form->addContent([new TFormSeparator("Filtros para o candidato:")]);
        $row2 = $this->form->addFields([new TLabel("Cargo:", null, '14px', null)],[$cargo_id]);
        $row1 = $this->form->addContent([new TFormSeparator("Informações: ")]);
        $row2 = $this->form->addFields([new TLabel("Candidato:", null, '14px', null)],[$usuario_id]);
        $row3 = $this->form->addFields([new TLabel("Vaga:", null, '14px', null)],[$vaga_id]);

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');

        parent::setTargetContainer('adianti_right_panel');

        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel("Fechar");
        $btnClose->setImage('fas:times');

        $this->form->addHeaderWidget($btnClose);

        parent::add($this->form);

    }
    static function onChangeCargo($param)
    {
        $criteria = new TCriteria;
        if(!empty($param['cargo_id']))
        {
            $criteria->add(new TFilter('id','in',"NOESC:(select usuario_id from usuario_ficha_experiencias where cargo_id = ".$param['cargo_id'].")"));
        }
        TDBCombo::reloadFromModel(Self::$formName,'usuario_id', 'permission', 'SystemUsers', 'id', '({cpf_cnpj}) {name}','name asc' ,$criteria,true );
    }
    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new VagaUsuario(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            /**
            // To define an action to be executed on the message close event:
            $messageAction = new TAction(['className', 'methodName']);
            **/
            $fromVaga = TSession::getValue("fromVaga");
            TSession::setValue("fromVaga",false);
            TToast::show('info', "Candidato Vinculado!"); 
            if($fromVaga)
            {
                TApplication::loadPage('VagaForm','onEdit',['key'=>$data->vaga_id,'id'=>$data->vaga_id,'aba'=>2]);
            }else{
                TApplication::loadPage('VagaUsuarioList');
            }
            TScript::create("Template.closeRightPanel();"); 

        }
        catch (Exception $e) // in case of exception
        {
            //</catchAutoCode> 

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function fromVaga($param)
    {
        TSession::setValue("fromVaga",true);
        $data = new stdClass;
        if(!empty($param['vaga_id']))
        {
            $data->vaga_id = $param['vaga_id'];
        }
        if(!empty($param['cargo_id']))
        {
            $data->cargo_id = $param['cargo_id'];
        }
        Self::onChangeCargo(['cargo_id'=>$param['cargo_id']]);
        $this->form->setData($data);
    }

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new VagaUsuario($key); // instantiates the Active Record 

                $this->form->setData($object); // fill the form 

                TTransaction::close(); // close the transaction 
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(true);

    }

    public function onShow($param = null)
    {

    } 

}

