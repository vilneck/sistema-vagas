<?php

use Adianti\Control\TAction;
use Adianti\Registry\TSession;
use Adianti\Widget\Form\TButton;
use Adianti\Widget\Form\TFormSeparator;
use Adianti\Widget\Wrapper\TDBUniqueSearch;
use Adianti\Wrapper\BootstrapFormBuilder;

class VagaForm extends BasePage
{
    protected $form;
    private $formFields = [];
    private static $database = 'geral';
    private static $activeRecord = 'Vaga';
    private static $primaryKey = 'id';
    private static $formName = 'form_Vaga';

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
        $this->form->setFormTitle("Cadastro de vaga");


        $id = new TEntry('id');
        $situacao_id = new TDBCombo('situacao_id', 'geral', 'VagaSituacao', 'id', '{descricao}','descricao asc'  );
        $data_abertura = new TDate('data_abertura');
        $data_fechamento = new TDate('data_fechamento');
        $data_registro = new TDateTime('data_registro');
        $data_atualizacao = new TDateTime('data_atualizacao');
        $cargo_id = new TDBCombo('cargo_id', 'geral', 'Cargo', 'id', '{nome}','nome asc'  );
        $titulo = new TEntry('titulo');
        $setor = new TEntry('setor');
        $salario = new TNumeric('salario', '2', ',', '.' );
        $resumo = new THtmlEditor('resumo');
        $requisitos_essenciais = new THtmlEditor('requisitos_essenciais');
        $localizacao = new TEntry('localizacao');
        $horario_trabalho = new TEntry('horario_trabalho');
        $cidade_id = new TDBUniqueSearch('cidade_id', 'geral', 'Cidade', 'id', 'nome','nome asc'  );
        $uf_id = new TDBCombo('uf_id', 'geral', 'Uf', 'id', '{nome}','nome asc'  );

        $cidade_id->setMask('{nome} ({uf->sigla})');
        $cidade_id->setChangeAction(new TAction([$this,'onChangeCidade']));

        $situacao_id->addValidation("Situação", new TRequiredValidator()); 
        $data_abertura->addValidation("Data de Abertura da vaga", new TRequiredValidator()); 
        $data_fechamento->addValidation("Data de Fechamento da vaga", new TRequiredValidator()); 
        $cargo_id->addValidation("Cargo", new TRequiredValidator()); 
        $titulo->addValidation("Título", new TRequiredValidator()); 
        $setor->addValidation("Setor", new TRequiredValidator()); 
        $resumo->addValidation("Resumo da vaga", new TRequiredValidator()); 

        $titulo->setTip("Título público para busca dos usuários");
        $situacao_id->setValue('1');

        $localizacao->placeholder = "Dados adicionais de logradouro";

        $cargo_id->enableSearch();
        $uf_id->enableSearch();

        $id->setEditable(false);
        $uf_id->setEditable(false);
        $data_registro->setEditable(false);
        $data_atualizacao->setEditable(false);

        $data_abertura->setMask('dd/mm/yyyy');
        $data_fechamento->setMask('dd/mm/yyyy');
        $data_registro->setMask('dd/mm/yyyy hh:ii');
        $data_atualizacao->setMask('dd/mm/yyyy hh:ii');

        $data_abertura->setDatabaseMask('yyyy-mm-dd');
        $data_fechamento->setDatabaseMask('yyyy-mm-dd');
        $data_registro->setDatabaseMask('yyyy-mm-dd hh:ii');
        $data_atualizacao->setDatabaseMask('yyyy-mm-dd hh:ii');

        $setor->setMaxLength(100);
        $titulo->setMaxLength(150);
        $localizacao->setMaxLength(150);

        $id->setSize(100);
        $setor->setSize('100%');
        $uf_id->setSize('100%');
        $titulo->setSize('100%');
        $salario->setSize('100%');
        $cargo_id->setSize('100%');
        $cidade_id->setSize('100%');
        $situacao_id->setSize('100%');
        $resumo->setSize('100%', 200);
        $localizacao->setSize('100%');
        $data_fechamento->setSize('100%');
        $data_abertura->setSize('100%');
        $data_registro->setSize('100%');
        $data_atualizacao->setSize('100%');
        $requisitos_essenciais->setSize('100%', 200);

        $this->form->appendPage("Dados da Vaga");
        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null)],[$id]);
        $row2 = $this->form->addFields([new TLabel(Utils::spanRequired()."Situação:", null, '14px', null)],[$situacao_id]);
        $row3 = $this->form->addFields([new TLabel(Utils::spanRequired()."Data de Abertura:", null, '14px', null)],[$data_abertura],[new TLabel(Utils::spanRequired()."Data de Fechamento:", null, '14px', null)],[$data_fechamento]);
        $row3 = $this->form->addFields([new TLabel("Data do registro:", null, '14px', null)],[$data_registro],[new TLabel("Data de Atualização:", null, '14px', null)],[$data_atualizacao]);
        $row4 = $this->form->addFields([new TLabel(Utils::spanRequired()."Cargo:", null, '14px', null)],[$cargo_id],[new TLabel("Horário de Trabalho")],[$horario_trabalho]);
        $row6 = $this->form->addFields([new TLabel(Utils::spanRequired()."Setor:", null, '14px', null)],[$setor],[new TLabel("Salário:", null, '14px', null)],[$salario]);
        $row8 = $this->form->addFields([new TFormSeparator("Informações sobre a localidade da Vaga")]);
        $row9 = $this->form->addFields([new TLabel("Localização:", null, '14px', null)],[$localizacao]);
        $row10 = $this->form->addFields([new TLabel("Cidade:", null, '14px', null)],[$cidade_id],[new TLabel("UF:", null, '14px', null)],[$uf_id]);
        $row8 = $this->form->addFields([new TFormSeparator("Título público da vaga")]);
        $row5 = $this->form->addFields([$titulo]);
        $row8 = $this->form->addFields([new TFormSeparator("Informações adicionais")]);
        $row7 = $this->form->addFields([new TLabel(Utils::spanRequired()."Resumo da vaga:", null, '14px', null)],[$resumo]);
        $row8 = $this->form->addFields([new TLabel("Requisitos Essenciais da vaga:", null, '14px', null)],[$requisitos_essenciais]);
        
        $botao_vincular_candidato = new TButton('botao_vincular_candidato');
        $botao_vincular_candidato->setAction(new TAction([$this,'onVincularCandidato'],['static'=>1]));
        $botao_vincular_candidato->setLabel("Vincular Candidato");
        $botao_vincular_candidato->setImage("fa:plus green");

        $this->form->appendPage("Candidatos");
        $this->criarDatagridCandidatos();
        $this->form->addContent([$this->datagrid]);
        $this->form->addContent([$botao_vincular_candidato]);
        $this->form->addField($botao_vincular_candidato);
        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $btn_onsave->addStyleClass('btn-primary'); 
        
        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->addBotaoVoltar('VagaList');
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        $container->add(TBreadCrumb::create(["Vagas","Cadastro de Vaga"]));
        $container->add($this->form);

        BootstrapFormBuilder::hideField(Self::$formName,'data_registro');

        parent::add($container);

    }

    static function onVincularCandidato($param)
    {
        if($param['id'])
        {
            TApplication::loadPage('VagaUsuarioForm','fromVaga',['vaga_id'=>$param['id'],'cargo_id'=>$param['cargo_id']]);
        }
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
        Self::onAtualizarTitulo($param);
        TForm::sendData(Self::$formName,$obj);
        TTransaction::close();
        }catch(Exception $e){
        new TMessage('error',$e->getMessage());
        }
    }
    static function onAtualizarTitulo($param)
    {
       if($param['titulo'] == null)
       {
        try{
            TTransaction::open('geral');
            
            $obj = new stdClass;
            $obj->titulo = "Vaga para ";
            if($param['cargo_id'])
            {
                $cargo = Cargo::find($param['cargo_id']);
                $obj->titulo .= $cargo->nome;
            }
            if($param['cidade_id'])
            {
                $cidade = Cidade::find($param['cidade_id']);
                $obj->titulo .= ' em '.$cidade->nome.' - '.$cidade->uf->sigla;
            }
            TForm::sendData(Self::$formName,$obj);
            TTransaction::close();
            }catch(Exception $e){
            new TMessage('error',$e->getMessage());
            }
       }
    }
    function criarDatagridCandidatos()
    {
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style="width:100%;";
        $this->datagrid->datatable="true";
        
        $coluna_nome = new TDataGridColumn('usuario->name',    'Candidato',    'center');
        $coluna_data_registro = new TDataGridColumn('data_registro',    'Data Vínculo',    'center');
        
        $coluna_data_registro->setTransformer(['Transform','DataHora']);

        $this->datagrid->addColumn($coluna_nome);
        $this->datagrid->addColumn($coluna_data_registro );
        $this->datagrid->createModel();
    }
    function onReload()
    {
        try{
        TTransaction::open('geral');
        $vaga_id = TSession::Getvalue('vaga_id');
        $this->datagrid->clear();
        if($vaga_id)
        {
            $vinculos = VagaUsuario::where('vaga_id','=',$vaga_id)->load();
            $this->datagrid->addItems($vinculos);
        }
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

            $object = new Vaga(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            TTransaction::close(); // close the transaction

            $this->onEdit(['key'=>$data->id]);
            /**
            // To define an action to be executed on the message close event:
            $messageAction = new TAction(['className', 'methodName']);
            **/

            new TMessage('info', "Registro salvo", $messageAction); 

        }
        catch (Exception $e) // in case of exception
        {
            //</catchAutoCode> 

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction
                if(!empty($param['aba']))
                {
                    $this->form->setCurrentPage(--$param['aba']);
                }
                $object = new Vaga($key); // instantiates the Active Record 

                $this->form->setData($object); // fill the form 

                TSession::setValue('vaga_id',$object->id);
                $this->onReload();
                BootstrapFormBuilder::showField(Self::$formName,'data_registro',100);
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
        TSession::setValue('vaga_id',null);
        $this->form->clear(true);
        $this->onReload([]);
    }

    public function onShow($param = null)
    {

    } 

    public function show()
    {
        $this->onReload();
        parent::show();
    }

}

