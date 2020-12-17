<?php

class Uf extends TRecord
{
    const TABLENAME  = 'uf';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('sigla');
        parent::addAttribute('nome');
        parent::addAttribute('codigo_ibge');
            
    }

    /**
     * Method getCidades
     */
    public function getCidades()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('uf_id', '=', $this->id));
        return Cidade::getObjects( $criteria );
    }
    /**
     * Method getUsuarioFichas
     */
    public function getUsuarioFichas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('uf_id', '=', $this->id));
        return UsuarioFicha::getObjects( $criteria );
    }
    /**
     * Method getVagas
     */
    public function getVagas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('uf_id', '=', $this->id));
        return Vaga::getObjects( $criteria );
    }

    
}

