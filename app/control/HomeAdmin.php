<?php

use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Database\TTransaction;
use Adianti\Widget\Container\TPanelGroup;

class HomeAdmin extends TPage
{
    
    function __construct()
    {
        parent::__construct();
        
        $this->linha=TElement::tag('div','',['class'=>'row']);
        

        $painel_ultimos_inscritos = new TPanelGroup("Últimos 10 inscritos");
        $painel_ultimos_inscritos->addHeaderActionLink("Ver Todos",new TAction(['VagaUsuarioList','onReload']),'fa:search');
        $this->criarDatagridInscritos();
        $painel_ultimos_inscritos->add($this->datagrid_inscritos);
        
        
        parent::add( $this->linha );
        parent::add( $painel_ultimos_inscritos );
    }

    function criarDatagridInscritos()
    {
        // creates a Datagrid
        $this->datagrid_inscritos = new TDataGrid;
        $this->datagrid_inscritos->disableHtmlConversion();
        $this->datagrid_inscritos = new BootstrapDatagridWrapper($this->datagrid_inscritos);
        $this->datagrid_inscritos->datatable='true';

        $this->datagrid_inscritos->style = 'width: 100%';
        $this->datagrid_inscritos->setHeight(320);

        $column_id = new TDataGridColumn('id', "Código", 'center' , '70px');
        $column_usuario_name = new TDataGridColumn('usuario->name', "Candidato", 'left');
        $column_vaga_titulo = new TDataGridColumn('vaga->titulo', "Vaga", 'left');
        $column_data_registro = new TDataGridColumn('data_registro', "Data", 'center');

        $this->datagrid_inscritos->addColumn($column_id);
        $this->datagrid_inscritos->addColumn($column_usuario_name);
        $this->datagrid_inscritos->addColumn($column_vaga_titulo);
        $this->datagrid_inscritos->addColumn($column_data_registro);

        $column_data_registro->setTransformer(['Transform','DataHora']);

        // create the datagrid model
        $this->datagrid_inscritos->createModel();
    }

    function onReload()
    {
        try{
        TTransaction::open('geral');        

        $ultimos_incritos = VagaUsuario::take(10)->orderBy('id','desc')->load();
        $this->datagrid_inscritos->clear();
        $this->datagrid_inscritos->addItems($ultimos_incritos);

        $candidaturas_mes = VagaUsuario::where('data_registro','>',date('Y-m-d',strtotime("-1 month")))->count();
        $vagas_aberto = Vaga::where('situacao_id','=',1)->count();
        $total_candidatos = SystemUsers::where('id','!=',1)->count();

        $this->linha->clearChildren();
        $this->linha->add(Widget::InfoBox(1,"Candidaturas no mês",$candidaturas_mes,"col-md-4","purple",'fa fa-chart-bar'));
        $this->linha->add(Widget::InfoBox(1,"Vagas em aberto",$vagas_aberto,"col-md-4","green","fa fa-user-times"));
        $this->linha->add(Widget::InfoBox(1,"Total de Candidatos",$total_candidatos,"col-md-4","blue","fa fa-users"));
        TTransaction::close();
        }catch(Exception $e){
        new TMessage('error',$e->getMessage());
        }
    }

    function show()
    {
        $this->onReload();
        parent::show();
    }
}
