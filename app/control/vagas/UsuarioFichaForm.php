<?php

use Adianti\Registry\TSession;
use Adianti\Widget\Dialog\TToast;
use Adianti\Widget\Form\TCombo;
use Adianti\Widget\Wrapper\TDBUniqueSearch;

class UsuarioFichaForm extends BasePage
{
    protected $form;
    private $formFields = [];
    private static $database = 'geral';
    private static $activeRecord = 'UsuarioFicha';
    private static $primaryKey = 'id';
    private static $formName = 'form_UsuarioFicha';

    use Adianti\Base\AdiantiFileSaveTrait;
    use BuilderMasterDetailFieldListTrait;
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
        $this->form->setFormTitle("Ficha de Cadastro");


        $email = new TEntry('email');
        $ficha_id = new TEntry('ficha_id');
        $usuario_id = new TDBCombo('usuario_id', 'permission', 'SystemUsers', 'id', '{name}','name asc'  );
        $flag_sexo = new TRadioGroup('flag_sexo');
        $idade = new TEntry('idade');
        $sn_filhos = new TRadioGroup('sn_filhos');
        $flag_estado_civil = new TCombo('flag_estado_civil');
        $cidade_id = new TDBUniqueSearch('cidade_id', 'geral', 'Cidade', 'id', 'nome','nome asc'  );
        $uf_id = new TDBCombo('uf_id', 'geral', 'Uf', 'id', '{nome}','nome asc'  );
        $endereco = new TEntry('endereco');
        $telefones = new TEntry('telefones');
        $curriculo = new TFile('curriculo');
        $sn_trabalha_finais_semana = new TRadioGroup('sn_trabalha_finais_semana');
        $sn_trabalha_horario_noturno = new TRadioGroup('sn_trabalha_horario_noturno');
        $experiencias = new TText('experiencias');
        $formacao_academica = new TText('formacao_academica');
        $cursos = new TText('cursos');

        $cidade_id->setMask('{nome} ({uf->sigla})');
        $cidade_id->setChangeAction(new TAction([$this,'onChangeCidade']));

        $usuario_id->setEditable(false);

        // $cidade_id->addValidation("A cidade é obrigatória", new TRequiredValidator()); 
        // $telefones->addValidation("O telefone é obrigatório", new TRequiredValidator()); 
        // $flag_sexo->addValidation("O sexo é obrigatório", new TRequiredValidator()); 
        // $idade->addValidation("A idade é obrigatória", new TRequiredValidator()); 
        // $flag_estado_civil->addValidation("O estado Civil é obrigatório", new TRequiredValidator()); 
      //  $curriculo->addValidation("O currículo é obrigatório", new TRequiredValidator()); 

        $curriculo->setTip("Aceitamos PDF ou Doc");
        $curriculo->enableFileHandling();
        $curriculo->setAllowedExtensions(["pdf","doc","docx"]);

        $idade->setMask('9!');
        $ficha_id->setEditable(false);
        $email->setEditable(false);
        $uf_id->setEditable(false);
        $flag_estado_civil->setValue(1);

        $endereco->setMaxLength(150);
        $telefones->setMaxLength(200);

        $sn_filhos->setBooleanMode();
        $sn_trabalha_finais_semana->setBooleanMode();
        $sn_trabalha_horario_noturno->setBooleanMode();

        $flag_sexo->setLayout('horizontal');
        $sn_filhos->setLayout('horizontal');
        $sn_trabalha_finais_semana->setLayout('horizontal');
        $sn_trabalha_horario_noturno->setLayout('horizontal');

        $flag_sexo->setValue('1');
        $sn_filhos->setValue('2');
        $sn_trabalha_finais_semana->setValue('1');
        $sn_trabalha_horario_noturno->setValue('1');

        $flag_sexo->setUseButton();
        $sn_filhos->setUseButton();
        $sn_trabalha_finais_semana->setUseButton();
        $sn_trabalha_horario_noturno->setUseButton();

        $sn_filhos->addItems(['1'=>'Sim','2'=>'Não']);
        $flag_sexo->addItems(['1'=>'Masculino','2'=>'Feminino']);
        $sn_trabalha_finais_semana->addItems(['1'=>'Sim','2'=>'Não']);
        $sn_trabalha_horario_noturno->addItems(['1'=>'Sim','2'=>'Não']);
        $flag_estado_civil->addItems(['1'=>' Solteiro(a)','2'=>' Casado(a)','3'=>' Separado(a)','4'=>' Divorciado(a)','5'=>' Viúvo(a)']);

        $ficha_id->setSize(100);
        $idade->setSize('100%');
        $uf_id->setSize('100%');
        $endereco->setSize('100%');
        $flag_sexo->setSize('100%');
        $sn_filhos->setSize('100%');
        $cidade_id->setSize('100%');
        $telefones->setSize('100%');
        $curriculo->setSize('100%');
        $usuario_id->setSize('100%');
        $cursos->setSize('100%', 70);
        $experiencias->setSize('100%', 70);
        $flag_estado_civil->setSize('100%');
        $formacao_academica->setSize('100%', 70);
        $sn_trabalha_finais_semana->setSize('100%');
        $sn_trabalha_horario_noturno->setSize('100%');
        
        $usuario_ficha_experiencias_usuario_ficha_id = new THidden('usuario_ficha_experiencias_usuario_ficha_id[]');
        $usuario_ficha_experiencias_usuario_ficha___row__id = new THidden('usuario_ficha_experiencias_usuario_ficha___row__id[]');
        $usuario_ficha_experiencias_usuario_ficha___row__data = new THidden('usuario_ficha_experiencias_usuario_ficha___row__data[]');
        $usuario_ficha_experiencias_usuario_ficha_atividades_desenvolvidas = new TEntry('usuario_ficha_experiencias_usuario_ficha_atividades_desenvolvidas[]');
        $usuario_ficha_experiencias_usuario_ficha_cargo_id = new TDBCombo('usuario_ficha_experiencias_usuario_ficha_cargo_id[]','geral','Cargo','id','nome');
        $usuario_ficha_experiencias_usuario_ficha_empresa = new TEntry('usuario_ficha_experiencias_usuario_ficha_empresa[]');
        
       $usuario_ficha_experiencias_usuario_ficha_cargo_id->enableSearch(true);

        $this->fieldList_experiencias = new TFieldList();

        $usuario_ficha_experiencias_usuario_ficha_empresa->setSize('100%');
        $usuario_ficha_experiencias_usuario_ficha_cargo_id->setSize('100%');
        $usuario_ficha_experiencias_usuario_ficha_atividades_desenvolvidas->setSize('100%');

        $this->fieldList_experiencias->addField(null, $usuario_ficha_experiencias_usuario_ficha_id, []);
        $this->fieldList_experiencias->addField(null, $usuario_ficha_experiencias_usuario_ficha___row__id, ['uniqid' => true]);
        $this->fieldList_experiencias->addField(null, $usuario_ficha_experiencias_usuario_ficha___row__data, []);
        $this->fieldList_experiencias->addField(new TLabel("Empresa", null, '14px', null), $usuario_ficha_experiencias_usuario_ficha_empresa,['width'=>'20%']);
        $this->fieldList_experiencias->addField(new TLabel("Cargo", null, '14px', null), $usuario_ficha_experiencias_usuario_ficha_cargo_id,['width'=>'20%']);
        $this->fieldList_experiencias->addField(new TLabel("Atividades desenvolvidas", null, '14px', null), $usuario_ficha_experiencias_usuario_ficha_atividades_desenvolvidas,['width'=>'60%']);

        $this->fieldList_experiencias->width = '100%';
        $this->fieldList_experiencias->setFieldPrefix('usuario_ficha_experiencias_usuario_ficha');

        $this->form->addField($usuario_ficha_experiencias_usuario_ficha_id);
        $this->form->addField($usuario_ficha_experiencias_usuario_ficha___row__id);
        $this->form->addField($usuario_ficha_experiencias_usuario_ficha___row__data);
        $this->form->addField($usuario_ficha_experiencias_usuario_ficha_atividades_desenvolvidas);
        $this->form->addField($usuario_ficha_experiencias_usuario_ficha_cargo_id);
        $this->form->addField($usuario_ficha_experiencias_usuario_ficha_empresa);

        $this->fieldList_experiencias->setRemoveAction(null, 'fas:times #dd5a43', "Excluír");

        $row1 = $this->form->addFields([new TLabel("Código da Ficha:", null, '14px', null)],[$ficha_id]);
        $row2 = $this->form->addContent([new TFormSeparator("Informações Pessoais", '#333333', '18', '#eeeeee')]);
        $row3 = $this->form->addFields([new TLabel("Candidato:", null, '14px', null)],[$usuario_id],[new TLabel("Email:")],[$email]);
        $row4 = $this->form->addFields([new TLabel("Sexo:", null, '14px', null)],[$flag_sexo],[new TLabel("Idade:", null, '14px', null)],[$idade]);
        $row5 = $this->form->addFields([new TLabel("Tem Filhos?", null, '14px', null)],[$sn_filhos],[new TLabel("Estado Civil:", null, '14px', null)],[$flag_estado_civil]);
        $row6 = $this->form->addFields([new TLabel("Cidade:", null, '14px', null)],[$cidade_id],[new TLabel("Estado:", null, '14px', null)],[$uf_id]);
        $row7 = $this->form->addFields([new TLabel("Endereço:", null, '14px', null)],[$endereco]);
        $row8 = $this->form->addFields([new TLabel("Telefones para contato:", null, '14px', null)],[$telefones]);
        $row9 = $this->form->addContent([new TFormSeparator("Experiências e informações adicionais", '#333333', '18', '#eeeeee')]);
        $row10 = $this->form->addFields([new TLabel("Currículo", null, '14px', null)],[$curriculo]);
        $row11 = $this->form->addFields([new TLabel("Você aceita trabalhar nos finais de Semana?", null, '14px', null)],[$sn_trabalha_finais_semana]);
        $row12 = $this->form->addFields([new TLabel("Você aceita trabalhar em horário noturno?", null, '14px', null)],[$sn_trabalha_horario_noturno]);
        $row14 = $this->form->addFields([new TLabel("Formações acadêmicas:", null, '14px', null)],[$formacao_academica]);
        $row15 = $this->form->addFields([new TLabel("Cursos que você já fez:", null, '14px', null)],[$cursos]);
        $row13 = $this->form->addFields([new TLabel("Você possui experiência de trabalho em qual área?", null, '14px', null)],[$experiencias]);
        $row9 = $this->form->addContent([new TFormSeparator("Experiências anteriores", '#333333', '18', '#eeeeee')]);
        $row17 = $this->form->addFields([$this->fieldList_experiencias]);
        $row17->layout = [' col-sm-12'];
        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static'=>1]), 'fas:save #ffffff');
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');

        if(SystemUsers::isAdmin())
        {
            $this->addBotaoVoltar('UsuarioFichaList');
        }else{
            $this->addBotaoVoltar('WelcomeView');
        }
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        $container->add(TBreadCrumb::create(["Candidato","Ficha"]));
        $container->add($this->form);

        parent::add($container);

    }

    static function onChangeCidade($param)
    {
        try{
        TTransaction::open('geral');
        
        $obj = new stdClass;
        $obj->uf_id = '';
        if($param['cidade_id'])
        {
            $cidade = Cidade::find($param['cidade_id']);
            $obj->uf_id = $cidade->uf_id;
        }
        TForm::sendData(Self::$formName,$obj);
        TTransaction::close();
        }catch(Exception $e){
        new TMessage('error',$e->getMessage());
        }
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

            $object = new UsuarioFicha(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data
            $object->id = $data->usuario_id;
            $curriculo_dir = 'files'; 

            $object->store(); // save the object 

            $this->saveFile($object, $data, 'curriculo', $curriculo_dir); 

            $usuario_ficha_experiencias_usuario_ficha_items = $this->storeItems('UsuarioFichaExperiencias', 'usuario_ficha_id', $object, $this->fieldList_experiencias, function($masterObject, $detailObject){ 

                //code here

            }); 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction


            TToast::show('success', "Currículo salvo com sucesso! &#128513;"); 

        }
        catch (Exception $e) // in case of exception
        {
            //</catchAutoCode> 

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function onEdit( $param = null)
    {
        try
        {
            if (isset($param['key']) or !SystemUsers::isAdmin())
            {
                $key = @$param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                if(SystemUsers::isAdmin())
                {
                    $object = new UsuarioFichaView($key); 
                }else{
                    $object = new UsuarioFichaView(TSession::getValue('userid'));
                }

                // $count = UsuarioFichaExperiencias::where('usuario_ficha_id','=',$key)->count();
                // if($count == 0)
                // {
                //     $this->fieldList_experiencias->addHeader();
                //     $this->fieldList_experiencias->addDetail( new stdClass );
                //     $this->fieldList_experiencias->addCloneAction(null, 'fas:plus #69aa46', "Clonar");
                // }
                $usuario_ficha_experiencias_usuario_ficha_items = $this->loadItems('UsuarioFichaExperiencias', 'usuario_ficha_id', $object, $this->fieldList_experiencias, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 
                
                $object->ficha_id = $object->id;
                $object->usuario_id = $object->id;

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

  
    public function onClear( $param )
    {
        $this->form->clear(true);

        $this->fieldList_experiencias->addHeader();
        $this->fieldList_experiencias->addDetail( new stdClass );

        $this->fieldList_experiencias->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

    }

    public function onShow($param = null)
    {
        $this->fieldList_experiencias->addHeader();
        $this->fieldList_experiencias->addDetail( new stdClass );

        $this->fieldList_experiencias->addCloneAction(null, 'fas:plus #69aa46', "Clonar");


    } 

}

