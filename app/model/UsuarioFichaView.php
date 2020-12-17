<?php

use Adianti\Database\TRecord;

class UsuarioFichaView extends TRecord
{
    const TABLENAME  = 'usuario_ficha_view';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial';

    private $preencheu_curriculo; 

    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('name');
        parent::addAttribute('login');
        parent::addAttribute('password');
        parent::addAttribute('email');
        parent::addAttribute('frontpage_id');
        parent::addAttribute('system_unit_id');
        parent::addAttribute('active');
        parent::addAttribute('flag_candidato');
        parent::addAttribute('data_cadastro');
        parent::addAttribute('cidade_id');
        parent::addAttribute('uf_id');
        parent::addAttribute('flag_sexo');
        parent::addAttribute('idade');
        parent::addAttribute('flag_estado_civil');
        parent::addAttribute('sn_filhos');
        parent::addAttribute('telefones');
        parent::addAttribute('endereco');
        parent::addAttribute('sn_trabalha_finais_semana');
        parent::addAttribute('sn_trabalha_horario_noturno');
        parent::addAttribute('experiencias');
        parent::addAttribute('formacao_academica');
        parent::addAttribute('cursos');
        parent::addAttribute('curriculo');    
    }				

    function get_preencheu_curriculo()
    {
        var_dump($this->curriculo);
        if($this->curriculo==null)
        {
            return false;
        }
        return true;
    }
}