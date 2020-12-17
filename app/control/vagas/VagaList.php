<?php

use Adianti\Registry\TSession;
use Adianti\Widget\Datagrid\TDataGridActionGroup;
use Adianti\Widget\Wrapper\TDBUniqueSearch;
use Adianti\Wrapper\BootstrapDatagridWrapper;

class VagaList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'geral';
    private static $activeRecord = 'Vaga';
    private static $primaryKey = 'id';
    private static $formName = 'formList_Vaga';
    private $showMethods = ['onReload', 'onSearch'];

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        // define the form title
        $this->form->setFormTitle("Listagem de vagas");

        $id = new TEntry('id');
        $titulo = new TEntry('titulo');
        $setor = new TEntry('setor');
        $situacao_id = new TDBCombo('situacao_id', 'geral', 'VagaSituacao', 'id', '{descricao}','descricao asc'  );
        $uf_id = new TDBCombo('uf_id', 'geral', 'Uf', 'id', '{nome}({sigla})','nome asc'  );
        $cidade_id = new TDBUniqueSearch('cidade_id', 'geral', 'Cidade', 'id', '{nome}','descricao asc'  );

        $cidade_id->setMask('{nome} ({uf->sigla})');
        $setor->setMaxLength(100);
        $titulo->setMaxLength(150);
        $uf_id->enableSearch(true);

        $setor->setSize('100%');
        $titulo->setSize('100%');
        $situacao_id->setSize('100%');
        $uf_id->setSize('100%');
        $cidade_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Código:", null, '14px', null)],[$id],[new TLabel("Título:", null, '14px', null)],[$titulo]);
        $row2 = $this->form->addFields([new TLabel("Setor:", null, '14px', null)],[$setor],[new TLabel("Situação:", null, '14px', null)],[$situacao_id]);
        $row2 = $this->form->addFields([new TLabel("Cidade:", null, '14px', null)],[$cidade_id],[new TLabel("Estado:", null, '14px', null)],[$uf_id]);

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $btn_onsearch->addStyleClass('btn-primary'); 

        if(SystemUsers::isAdmin())
        {
            $btn_onexportcsv = $this->form->addAction("Exportar como CSV", new TAction([$this, 'onExportCsv']), 'far:file-alt #000000');
            $btn_onshow = $this->form->addAction("Nova Vaga", new TAction(['VagaForm', 'onShow']), 'fas:plus #69aa46');
        }
        

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();
        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->enablePopover('Detalhes','{popover}');
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_titulo = new TDataGridColumn('titulo', "Título", 'left');
        $column_cargo_nome = new TDataGridColumn('cargo->nome', "Cargo", 'left');
        $column_data_abertura = new TDataGridColumn('data_abertura', "Data de Abertura", 'left');
        $column_data_fechamento = new TDataGridColumn('data_fechamento', "Data de Fechamento", 'left');
        $column_setor = new TDataGridColumn('setor', "Setor", 'left');
        $column_situacao_descricao = new TDataGridColumn('situacao_id', "Situação", 'left');
        $column_total_inscritos = new TDataGridColumn('total_inscritos', "Inscritos", 'left');
        $coluna_id = new TDataGridColumn('id',    'Código',    'center',50);
        $coluna_cidade = new TDataGridColumn('{cidade->nome}/{uf->sigla}',    'Cidade',    'center');
        
        
        $column_situacao_descricao->setTransformer(['Transform','Situacao']);
        $column_data_abertura->setTransformer(['Transform','Data']);
        $column_data_fechamento->setTransformer(['Transform','Data']);
        
        
        $this->datagrid->addColumn($coluna_id);
        $this->datagrid->addColumn($column_titulo);
        $this->datagrid->addColumn($column_cargo_nome);
        $this->datagrid->addColumn($column_data_abertura);
        $this->datagrid->addColumn($column_data_fechamento);
        $this->datagrid->addColumn($coluna_cidade);
        $this->datagrid->addColumn($column_situacao_descricao);

        if(SystemUsers::isAdmin())
        {
            $this->datagrid->addColumn($column_total_inscritos);
        }

        $action_onEdit = new TDataGridAction(array('VagaForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);
        $action_onEdit->setDisplayCondition([$this,'displayEditDelete']);

        $action_ver = new TDataGridAction(array('VagaView', 'onEdit'));
        $action_ver->setUseButton(false);
        $action_ver->setButtonClass('btn btn-default btn-sm');
        $action_ver->setLabel("Ver detalhes");
        $action_ver->setImage('far:eye');
        $action_ver->setField(self::$primaryKey);

        
        $action_onDelete = new TDataGridAction(array('VagaList', 'onDelete'));
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel("Excluir");
        $action_onDelete->setImage('fas:trash-alt #dd5a43');
        $action_onDelete->setField(self::$primaryKey);
        $action_onDelete->setDisplayCondition([$this,'displayEditDelete']);
        
       

        $action_candidatar = new TDataGridAction(array($this, 'onCandidatar'));
        $action_candidatar->setUseButton(true);
        $action_candidatar->setButtonClass('btn btn-default btn-sm');
        $action_candidatar->setLabel("Candidatar");
        $action_candidatar->setImage('fas:check green');
        $action_candidatar->setField(self::$primaryKey);
        $action_candidatar->setDisplayCondition([$this,'displayCandidatar']);

        $grupo = new TDataGridActionGroup("Ações");

        $grupo->addAction($action_ver);
        $grupo->addAction($action_onEdit);
        $grupo->addAction($action_onDelete);
        $grupo->addAction($action_candidatar);

        $this->datagrid->addActionGroup($grupo);

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup;
        $panel->add($this->datagrid);

        $panel->addFooter($this->pageNavigation);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(TBreadCrumb::create(["Vagas","Listagem"]));
        $container->add($this->form);
        $container->add($panel);

        parent::add($container);

    }

    public function onDelete($param = null) 
    { 
        
        try
        {
            Validador::ApenasLogado();
            if(isset($param['delete']) && $param['delete'] == 1)
            {
                    // get the paramseter $key
                    $key = $param['key'];
                    // open a transaction with database
                    TTransaction::open(self::$database);

                    // instantiates object
                    $object = new Vaga($key, FALSE); 

                    // deletes the object from the database
                    $object->delete();

                    // close the transaction
                    TTransaction::close();

                    // reload the listing
                    $this->onReload( $param );
                    // shows the success message
                    new TMessage('info', AdiantiCoreTranslator::translate('Record deleted'));
                
            }
            else
            {
                // define the delete action
                $action = new TAction(array($this, 'onDelete'));
                $action->setParameters($param); // pass the key paramseter ahead
                $action->setParameter('delete', 1);
                // shows a dialog to the user
                new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);   
            }
        }
        catch (Exception $e) // in case of exception        
        {

            // shows the exception error message
            TToast::show('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }

    function displayEditDelete($object)
    {
        if(!SystemUsers::isLogged())
        {
            return false;
        }
        return true;
    }
    function displayCandidatar($object)
    {
        if(!SystemUsers::isLogged())
        {
            return true;
        }
        if(SystemUsers::isAdmin())
        {
            return false;
        }
        return false;
    }
    public static function onCandidatar($param)
    {
        if(!SystemUsers::isLogged())
        {
            TSession::setValue('candidatura_vaga_id',$param['id']);
            TApplication::loadPage('LoginForm');
        }else{
            TApplication::loadPage('VagaView','onEdit',['key'=>$param['id']]);
        }
    }
    public function onExportCsv($param = null) 
    {
        try
        {
            $this->onSearch();

            TTransaction::open(self::$database); // open a transaction
            $repository = new TRepository(self::$activeRecord); // creates a repository for Customer
            $criteria = $this->filter_criteria;

            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            $records = $repository->load($criteria); // load the objects according to criteria
            if ($records)
            {
                $file = 'tmp/'.uniqid().'.csv';
                $handle = fopen($file, 'w');
                $columns = $this->datagrid->getColumns();

                $csvColumns = [];
                foreach($columns as $column)
                {
                    $csvColumns[] = $column->getLabel();
                }
                fputcsv($handle, $csvColumns, ';');

                foreach ($records as $record)
                {
                    $csvColumns = [];
                    foreach($columns as $column)
                    {
                        $name = $column->getName();
                        $csvColumns[] = $record->{$name};
                    }
                    fputcsv($handle, $csvColumns, ';');
                }
                fclose($handle);

                TPage::openFile($file);
            }
            else
            {
                new TMessage('info', _t('No records found'));       
            }

            TTransaction::close(); // close the transaction
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    /**
     * Register the filter in the session
     */
    public function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->titulo) AND ( (is_scalar($data->titulo) AND $data->titulo !== '') OR (is_array($data->titulo) AND (!empty($data->titulo)) )) )
        {

            $filters[] = new TFilter('titulo', 'like', "%{$data->titulo}%");// create the filter 
        }

        if (isset($data->setor) AND ( (is_scalar($data->setor) AND $data->setor !== '') OR (is_array($data->setor) AND (!empty($data->setor)) )) )
        {

            $filters[] = new TFilter('setor', 'like', "%{$data->setor}%");// create the filter 
        }

        if (isset($data->situacao_id) AND ( (is_scalar($data->situacao_id) AND $data->situacao_id !== '') OR (is_array($data->situacao_id) AND (!empty($data->situacao_id)) )) )
        {

            $filters[] = new TFilter('situacao_id', '=', $data->situacao_id);// create the filter 
        }
        $filters = Utils::criarFiltro($filters,'id','=',$data->id);
        $filters = Utils::criarFiltro($filters,'cidade_id','=',$data->cidade_id);
        $filters = Utils::criarFiltro($filters,'uf_id','=',$data->uf_id);

        $param = array();
        $param['offset']     = 0;
        $param['first_page'] = 1;

        // fill the form with data again
        $this->form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);
        TSession::setValue(__CLASS__.'_filters', $filters);

        $this->onReload($param);
    }

    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'geral'
            TTransaction::open(self::$database);

            // creates a repository for Vaga
            $repository = new TRepository(self::$activeRecord);
            $limit = 20;

            $criteria = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'id';    
            }

            if (empty($param['direction']))
            {
                $param['direction'] = 'desc';
            }

            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            if(!SystemUsers::isLogged())
            {
                $criteria->add(new TFilter('situacao_id','=',1));
            }
            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                    // add the object inside the datagrid

                    $this->datagrid->addItem($object);

                }
            }

            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);

            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit

            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }

    public function onShow($param = null)
    {

    }

    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  $this->showMethods))) )
        {
            if (func_num_args() > 0)
            {
                $this->onReload( func_get_arg(0) );
            }
            else
            {
                $this->onReload();
            }
        }
        parent::show();
    }

}

