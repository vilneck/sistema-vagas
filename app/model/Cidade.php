<?php

class Cidade extends TRecord
{
    const TABLENAME  = 'cidade';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $uf;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('uf_id');
        parent::addAttribute('codigo_ibge');
        parent::addAttribute('nome');
            
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
     * Method getUsuarioFichas
     */
    public function getUsuarioFichas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cidade_id', '=', $this->id));
        return UsuarioFicha::getObjects( $criteria );
    }
    /**
     * Method getVagas
     */
    public function getVagas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cidade_id', '=', $this->id));
        return Vaga::getObjects( $criteria );
    }

    
}

