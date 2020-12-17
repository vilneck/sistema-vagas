<?php

class UsuarioFicha extends TRecord
{
    const TABLENAME  = 'usuario_ficha';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $usuario;
    private $cidade;
    private $uf;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('usuario_id');
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
     * Method getUsuarioFichaExperienciass
     */
    public function getUsuarioFichaExperienciass()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('usuario_ficha_id', '=', $this->id));
        return UsuarioFichaExperiencias::getObjects( $criteria );
    }

    
}

