<?php

class UsuarioFichaExperiencias extends TRecord
{
    const TABLENAME  = 'usuario_ficha_experiencias';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $usuario_ficha;
    private $cargo;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('usuario_ficha_id');
        parent::addAttribute('usuario_id');
        parent::addAttribute('cargo_id');
        parent::addAttribute('atividades_desenvolvidas');
        parent::addAttribute('horario_trabalho');
        parent::addAttribute('ultimo_salario');
        parent::addAttribute('motivo_saida');
            
    }

    /**
     * Method set_usuario_ficha
     * Sample of usage: $var->usuario_ficha = $object;
     * @param $object Instance of UsuarioFicha
     */
    public function set_usuario_ficha(UsuarioFicha $object)
    {
        $this->usuario_ficha = $object;
        $this->usuario_ficha_id = $object->id;
    }

    /**
     * Method get_usuario_ficha
     * Sample of usage: $var->usuario_ficha->attribute;
     * @returns UsuarioFicha instance
     */
    public function get_usuario_ficha()
    {
    
        // loads the associated object
        if (empty($this->usuario_ficha))
            $this->usuario_ficha = new UsuarioFicha($this->usuario_ficha_id);
    
        // returns the associated object
        return $this->usuario_ficha;
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

    
}

