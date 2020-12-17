<?php

class Cargo extends TRecord
{
    const TABLENAME  = 'cargo';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
            
    }

    /**
     * Method getVagas
     */
    public function getVagas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cargo_id', '=', $this->id));
        return Vaga::getObjects( $criteria );
    }
    /**
     * Method getUsuarioCargos
     */
    public function getUsuarioCargos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cargo_id', '=', $this->id));
        return UsuarioCargo::getObjects( $criteria );
    }
    /**
     * Method getUsuarioFichaExperienciass
     */
    public function getUsuarioFichaExperienciass()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cargo_id', '=', $this->id));
        return UsuarioFichaExperiencias::getObjects( $criteria );
    }

    
}

