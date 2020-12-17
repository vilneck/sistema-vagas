<?php
class Validador
{
	static function Required($campo,$valor=null)
	{
		if($valor == null or !isset($valor))
		{
			throw new Exception("O campo <b>{$campo}</b> é obrigatório!");
		}
	}
	static function ApenasLogado()
	{
		if(!SystemUsers::isLogged())
        {
            throw new Exception("Você não tem permissão para isso!");
        }
	}
}