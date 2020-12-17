<?php

class VagaSituacao extends TRecord
{
    const TABLENAME  = 'vaga_situacao';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $cor;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('classe');
            
    }

    function cores()
    {
        return [
            'success'=>"Cor Verde",
            'danger'=>"Cor vermelha",
            'warning'=>"Cor Laranja",
            'primary'=>"Cor Azul",
            'info'=>"Cor Azul Claro"
        ];
    }
    function get_cor()
    {
        $cores = $this->cores();
        return $cores[$this->classe];
    }
    /**
     * Method getVagas
     */
    public function getVagas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('situacao_id', '=', $this->id));
        return Vaga::getObjects( $criteria );
    }

    
}

