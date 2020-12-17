<?php

class VagaUsuario extends TRecord
{
    const TABLENAME  = 'vaga_usuario';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $vaga;
    private $usuario;

    const CREATEDAT  = 'data_registro';

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('usuario_id');
        parent::addAttribute('vaga_id');
        parent::addAttribute('data_registro');
            
    }

    /**
     * Method set_vaga
     * Sample of usage: $var->vaga = $object;
     * @param $object Instance of Vaga
     */
    public function set_vaga(Vaga $object)
    {
        $this->vaga = $object;
        $this->vaga_id = $object->id;
    }

    /**
     * Method get_vaga
     * Sample of usage: $var->vaga->attribute;
     * @returns Vaga instance
     */
    public function get_vaga()
    {
    
        // loads the associated object
        if (empty($this->vaga))
            $this->vaga = new Vaga($this->vaga_id);
    
        // returns the associated object
        return $this->vaga;
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

