<?php

class UsuarioCargo extends TRecord
{
    const TABLENAME  = 'usuario_cargo';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $cargo;
    private $usuario;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('usuario_id');
        parent::addAttribute('cargo_id');
            
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
     * Method set_system_users
     * Sample of usage: $var->system_users = $object;
     * @param $object Instance of SystemUsers
     */
    public function set_usuario(SystemUsers $object)
    {
        $this->usuario = $object;
        $this->usuario_id = $object->id;
    }

    /**
     * Method get_usuario
     * Sample of usage: $var->usuario->attribute;
     * @returns SystemUsers instance
     */
    public function get_usuario()
    {
        TTransaction::open('permission');
        // loads the associated object
        if (empty($this->usuario))
            $this->usuario = new SystemUsers($this->usuario_id);
        TTransaction::close();
        // returns the associated object
        return $this->usuario;
    }

    
}

