<?php

use Adianti\Widget\Form\TDate;
use Adianti\Widget\Wrapper\TDBCombo;
use Adianti\Widget\Wrapper\TDBUniqueSearch;

class Campo
{
    function CarteiraTeorica()
    {
        $campo = new TDBCombo('carteira_teorica_id','permission','CarteiraTeorica','id','nome','nome',Criteria::UsuarioLogado());
        $campo->enableSearch(true);

        return $campo;
    }
    function Plano()
    {
        $campo = new TDBCombo('plano_id','permission','Plano','id','nome','nome');
        $campo->enableSearch(true);

        return $campo;
    }
    function DataCadastro()
    {
        $campo = new TDate('data_cadastro');
        $campo->setMask('dd/mm/yyyy');
        $campo->setDatabaseMask('yyyy-mm-dd');
        return $campo;
    }
    function DataExpiracao()
    {
        $campo = new TDate('data_expiracao');
        $campo->setMask('dd/mm/yyyy');
        $campo->setDatabaseMask('yyyy-mm-dd');
        return $campo;
    }
    function DataUltimoLogin()
    {
        $campo = new TDate('data_ultimo_login');
        $campo->setMask('dd/mm/yyyy');
        $campo->setDatabaseMask('yyyy-mm-dd');
        $campo->setEditable(false);
        return $campo;
    }

    static function Ativo($key='id')
    {
        $campo = new TDBUniqueSearch('ativo_id','permission','Ativo',$key,'nome,sigla');
        $campo->setMask("({sigla}) {nome}");
        $campo->setMinLength(1);
        $campo->setSize('100%');
        return $campo;
    }
    static function TipoEvento()
    {
        $campo = new TDBCombo('tipo_id','permission','EventoTipo','id','descricao');
        return $campo;
    }
    function Setor()
    {
        $campo = new TDBCombo('setor_id','padrao','Setor','id','nome','nome');
        $campo->enableSearch(true);

        return $campo;
    }
    function Classe()
    {
        $campo = new TDBCombo('classe_id','padrao','Classe','id','nome','nome');
        $campo->enableSearch(true);

        return $campo;
    }
    static function PeriodoInicial()
    {
        $campo = new TDate('periodo_inicial');
        $campo->setMask('dd/mm/yyyy');
        $campo->setDatabaseMask('yyyy-mm-dd');
        $campo->placeholder='Inicial';
        
        return $campo;
    }
    static function PeriodoFinal()
    {
        $campo = new TDate('periodo_final');
        $campo->setMask('dd/mm/yyyy');
        $campo->setDatabaseMask('yyyy-mm-dd');
        $campo->placeholder='Final';

        return $campo;
    }
}