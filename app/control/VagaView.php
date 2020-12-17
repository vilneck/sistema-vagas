<?php

use Adianti\Database\TTransaction;
use Adianti\Registry\TSession;
use Adianti\Widget\Container\TPanelGroup;
use Adianti\Widget\Dialog\TToast;

class VagaView extends TPage
{
    /**
     * Class constructor
     * Creates the page
     */
    function __construct($param)
    {
        parent::__construct();
        
        try{
        TTransaction::open('geral');
        $chave_sessao = TSession::getValue('candidatura_vaga_id');
        if(isset($param['key']) and $param['key']!=null)
        {
            TSession::setValue('candidatura_vaga_id',$param['key']);
        }
        $chave = $chave_sessao!=null? $chave_sessao:$param['key'];
        $vaga = new Vaga($chave);
       

        $painel_vaga = new TPanelGroup("Detalhes da vaga");

        $texto = "
        <table class='table table-condensed'>
        <tr>
            <td class='text-center' colspan='4'><h3>{$vaga->titulo}</h3></td>
        </tr>
        <tr>
        <td>Periodo de Inscrição de ".Transform::Data($vaga->data_abertura)." até ".Transform::Data($vaga->data_fechamento)."</td>
        </tr>
        <tr>
        <td><b>Cargo:</b> {$vaga->cargo->nome}</td>
        <td><b>Setor:</b> {$vaga->setor}</td>
        </tr>
        <tr>
        <td><b>Cidade:</b> {$vaga->cidade->nome}/{$vaga->cidade->uf->sigla} </td>
        <td><b>Localização:</b> {$vaga->localizacao} </td>
        </tr>
        <tr>
            <td class='text-center' colspan='4'><h4>Resumo da Vaga</h4></td>
        </tr>
        <tr>
            <td class='text-center' colspan='4'>{$vaga->resumo}</td>
        </tr>       
        ";
        if($vaga->requisitos_essenciais)
        {
            $texto .= "
            <tr><td class='text-center' colspan='4'><h4>Requisitos Essenciais</h4></td></tr>
            <tr><td class='text-center' colspan='4'>{$vaga->requisitos_essenciais}</td></tr>";
        }
        $texto .= "</table>";
        $painel_vaga->add($texto);
        $texto_footer = "<a class='btn btn-default mr-1' href='index.php?class=VagaList' generator='adianti'><i class='fas fa-search'></i> Todas as Vagas</a>";
        if(SystemUsers::isLogged() and !SystemUsers::isAdmin())
        {
            $count = VagaUsuario::where('usuario_id','=',TSession::getValue('userid'))->count();
            if($count == 0)
            {
                $texto_footer .= "<a class='btn btn-success' href='index.php?class=VagaView&method=onCandidatar&key={$vaga->id}&static=1' generator='adianti'><i class='fas fa-check white'></i> Candidatar-me!</a>";
            }else{
               $texto_footer .= "<a class='btn btn-warning text-white' href='index.php?class=VagaView&method=onCancelar&key={$vaga->id}&static=1' generator='adianti'><i class='fas fa-times white'></i> Cancelar candidatura</a>";
            }
        }
        $painel_vaga->addFooter($texto_footer);
        TTransaction::close();
    }catch(Exception $e){
    new TMessage('error',$e->getMessage());
    }
        parent::add($painel_vaga);
    }
    static function onCancelar($param)
    {
        try{
        TTransaction::open('geral');
        Validador::Required('código vaga',$param['key']);
        Validador::Required('código usuario',TSession::getValue('userid'));
        $vaga_id = $param['key'];
        VagaUsuario::where('usuario_id','=',TSession::getValue('userid'))
        ->where('vaga_id','=',$vaga_id)
        ->delete();
        TToast::show('success','Candidatura cancelada com sucesso!');
        TTransaction::close();
        TApplication::loadPage('VagaView');
        }catch(Exception $e){
        new TMessage('error',$e->getMessage());
        }
    }
    static function onCandidatar($param)
    {
        try{
            TTransaction::open('geral');
            Validador::Required('código vaga',$param['key']);
            Validador::Required('código usuario',TSession::getValue('userid'));
            $vaga_id = $param['key'];
            $vaga_usuario = new VagaUsuario();
            $vaga_usuario->vaga_id = $vaga_id;
            $vaga_usuario->usuario_id = TSession::getValue('userid');
            $vaga_usuario->store(); 
            new TMessage('info','Candidatura realizada com sucesso! Lembre-se de ajustar os dados do seu currículo!');
            TTransaction::close();
            TApplication::loadPage('VagaView');
            }catch(Exception $e){
            new TMessage('error',$e->getMessage());
            }
    }
    function onEdit()
    {
        
    }
}
