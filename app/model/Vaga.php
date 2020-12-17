<?php

class Vaga extends TRecord
{
    const TABLENAME  = 'vaga';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'data_registro';
    const UPDATEDAT  = 'data_atualizacao';

    private $cargo;
    private $uf;
    private $cidade;
    private $situacao;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cargo_id');
        parent::addAttribute('data_fechamento');
        parent::addAttribute('titulo');
        parent::addAttribute('data_registro');
        parent::addAttribute('data_atualizacao');
        parent::addAttribute('data_abertura');
        parent::addAttribute('setor');
        parent::addAttribute('salario');
        parent::addAttribute('resumo');
        parent::addAttribute('requisitos_essenciais');
        parent::addAttribute('localizacao');
        parent::addAttribute('cidade_id');
        parent::addAttribute('uf_id');
        parent::addAttribute('situacao_id');
            
    }

    function get_popover()
    {
        return "<table class='table table-condensed'><tr><td><b>Setor:</b></td><td>{$this->setor}</td></tr><tr><td><b>Resumo:</b></td></tr><tr><td>{$this->resumo}</td></tr></table>";
    }

    function get_total_inscritos()
    {
        return VagaUsuario::where('vaga_id','=',$this->id)->count();    
    }

    /**
     * Method set_cargo
     * Sample of usage: $var->cargo = $object;
     * @param $object Instance of Cargo
     */
    public function set_cargo(Cargo $object)
    {
        $this->cargo = $object;
        $this->cargo_id = $object->id;
    }

    /**
     * Method get_cargo
     * Sample of usage: $var->cargo->attribute;
     * @returns Cargo instance
     */
    public function get_cargo()
    {
    
        // loads the associated object
        if (empty($this->cargo))
            $this->cargo = new Cargo($this->cargo_id);
    
        // returns the associated object
        return $this->cargo;
    }
    /**
     * Method set_uf
     * Sample of usage: $var->uf = $object;
     * @param $object Instance of Uf
     */
    public function set_uf(Uf $object)
    {
        $this->uf = $object;
        $this->uf_id = $object->id;
    }

    /**
     * Method get_uf
     * Sample of usage: $var->uf->attribute;
     * @returns Uf instance
     */
    public function get_uf()
    {
    
        // loads the associated object
        if (empty($this->uf))
            $this->uf = new Uf($this->uf_id);
    
        // returns the associated object
        return $this->uf;
    }
    /**
     * Method set_cidade
     * Sample of usage: $var->cidade = $object;
     * @param $object Instance of Cidade
     */
    public function set_cidade(Cidade $object)
    {
        $this->cidade = $object;
        $this->cidade_id = $object->id;
    }

    /**
     * Method get_cidade
     * Sample of usage: $var->cidade->attribute;
     * @returns Cidade instance
     */
    public function get_cidade()
    {
    
        // loads the associated object
        if (empty($this->cidade))
            $this->cidade = new Cidade($this->cidade_id);
    
        // returns the associated object
        return $this->cidade;
    }
    /**
     * Method set_vaga_situacao
     * Sample of usage: $var->vaga_situacao = $object;
     * @param $object Instance of VagaSituacao
     */
    public function set_situacao(VagaSituacao $object)
    {
        $this->situacao = $object;
        $this->situacao_id = $object->id;
    }

    /**
     * Method get_situacao
     * Sample of usage: $var->situacao->attribute;
     * @returns VagaSituacao instance
     */
    public function get_situacao()
    {
    
        // loads the associated object
        if (empty($this->situacao))
            $this->situacao = new VagaSituacao($this->situacao_id);
    
        // returns the associated object
        return $this->situacao;
    }

    /**
     * Method getVagaUsuarios
     */
    public function getVagaUsuarios()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('vaga_id', '=', $this->id));
        return VagaUsuario::getObjects( $criteria );
    }

    
}

