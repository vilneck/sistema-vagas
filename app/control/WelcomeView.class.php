<?php

use Adianti\Registry\TSession;
use Adianti\Widget\Container\TPanelGroup;
use Adianti\Widget\Dialog\TAlert;

/**
 * WelcomeView
 *
 * @version    1.0
 * @package    control
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class WelcomeView extends TPage
{
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();
        
        try{
        TTransaction::open('geral');
        $count_ficha = UsuarioFicha::where('usuario_id','=',TSession::getValue('userid'))->count();
        $minhas_candidaturas = VagaUsuario::where('usuario_id','=',TSession::getValue('userid'))->load();
        
        $panel_minha_ficha = new TPanelGroup("Minhas Ficha de Cadastro");
        if($count_ficha == 0)
        {
            $minha_ficha = new TAlert('bs4-danger',"Você ainda não iniciou o preenchimento de sua ficha de cadastro! Clique aqui para iniciar");
        }else{
            $minha_ficha = new TAlert('bs4-success',"Ficha preenchida! Clique aqui para edita-la!");
        }
        $panel_minha_ficha->add("<a href='index.php?class=UsuarioFichaForm' generator='adianti'>");
        $panel_minha_ficha->add($minha_ficha);
        $panel_minha_ficha->add("</a>");
        
        $panel_minhas_candidaturas = new TPanelGroup("Minhas Candidaturas");

        $this->div_minhas_candidaturas = TElement::tag('div','',['class'=>'div']);
        
        $this->div_minhas_candidaturas->add($panel_minhas_candidaturas);
        if($minhas_candidaturas)
        {
            $this->criarDatagridInscritos();
            $this->datagrid_inscritos->addItems($minhas_candidaturas);
            $panel_minhas_candidaturas->add($this->datagrid_inscritos);
        }else{
            
            $panel_minhas_candidaturas->add(new TAlert('bs4-info',"Você não se candidatou a nenhuma vaga!"));
        }
        // add the template to the page
        parent::add( $panel_minha_ficha );
        parent::add( $this->div_minhas_candidaturas );
        TTransaction::close();
        }catch(Exception $e){
        new TMessage('error',$e->getMessage());
        }
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
        $column_vaga_titulo = new TDataGridColumn('vaga->titulo', "Vaga", 'left');
        $column_data_registro = new TDataGridColumn('data_registro', "Data", 'center');

        $this->datagrid_inscritos->addColumn($column_id);
        $this->datagrid_inscritos->addColumn($column_vaga_titulo);
        $this->datagrid_inscritos->addColumn($column_data_registro);

        $column_data_registro->setTransformer(['Transform','DataHora']);

        // create the datagrid model
        $this->datagrid_inscritos->createModel();
        
    }
    function onReload()
    {
        
    }
}
