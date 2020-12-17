<?php

use Adianti\Control\TPage;
use Adianti\Widget\Template\THtmlRenderer;

class GraficoWrapper extends TPage
{
    public $html;
    public $div_grafico;
    public $titulo_grafico='';
    public $titulo_y='';
    public $titulo_x='';
    public $uniqid;
    public $altura='300px';
    public $largura='100%';
    public $tipo;
    public $caminho='google_column_chart';

    function __construct($param=null)
    {
        parent::__construct();
        if($param['tipo'])
        {
            $this->setTipo($param['tipo']);
        }
        if($this->uniqid==null)
        {
            $this->uniqid=uniqid();
        }
        $this->html = new THtmlRenderer('app/resources/'.$this->caminho.'.html');       
        $this->html->disableHtmlConversion();
        // add the template to the page
        $container = new TVBox;
        $container->style = "width:100%";       
        $container->add($this->html);
        parent::add($container);        
    }
    function reload($dados=null)
    {
        if($dados)
        {
            $this->setDados($dados);
        }
        $this->html->enableSection('main', array('data'   => json_encode($this->dados),
        'width'  => $this->largura,
        'height'  => $this->altura,
        'title'  => $this->titulo_grafico,
        'ytitle' => $this->titulo_y, 
        'xtitle' => $this->titulo_x,
        'uniqid' => $this->uniqid));
    }    
    function setTipo($tipo)
    {
        $this->tipo = $tipo;
        switch($this->tipo)
        {
            case 1:
                $this->caminho = 'google_column_chart';
            break;
            case 2:
                $this->caminho = 'grafico_bar';
            break;
            case 3:
                $this->caminho = 'grafico_pie';
            break;
            case 4:
                $this->caminho = 'grafico_composicao';
            break;
            case 5:
                $this->caminho = 'graficos/grafico_am_linhas';
            break;
        }
    }
    function setDados($dados)
    {
        $this->dados = $dados;
    }
    function setTituloX($valor)
    {
        $this->titulo_x = $valor;
    }
    function setTituloY($valor)
    {
        $this->titulo_y = $valor;
    }
    function setTituloGrafico($valor)
    {
        $this->titulo_grafico = $valor;
    }
    function setAltura($valor)
    {
        $this->altura = $valor;
    }
    function setLargura($valor)
    {
        $this->largura = $valor;
    }
}

?>