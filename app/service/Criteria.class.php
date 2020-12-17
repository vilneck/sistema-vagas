<?php

use Adianti\Database\TCriteria;
use Adianti\Database\TFilter;
use Adianti\Registry\TSession;

class Criteria extends TCriteria
{
	function filtrarUsuario($usuario_id = null)
	{
		if($usuario_id == null)
		{
			$usuario_id = TSession::getValue('userid');
		}
		$this->add(new TFilter('usuario_id','=',$usuario_id));
	}
	function UsuarioLogado($criteria = null,$flagVerificaAdmin = false)
	{
		if($criteria==null)
		{
		$criteria = new TCriteria;
		}
		if($flagVerificaAdmin == true)
		{
			if(!SystemUsers::isAdmin())
			{
				$criteria->add(new TFilter('usuario_id','=',TSession::getValue('userid')));
			}
		}else{
			$criteria->add(new TFilter('usuario_id','=',TSession::getValue('userid')));
		}
		return $criteria;
	}
	function ApenasCliente($criteria = null)
	{
		if($criteria==null)
		{
		$criteria = new TCriteria;
		}
		$criteria->add(new TFilter('flag_cliente','=',1));
		return $criteria;
	}
	function Categoria($criteria,$tipo)
	{
		if($criteria==null)
		{
		$criteria = new TCriteria;
		}
		$criteria->add(new TFilter('tipo_produto_servico_id','=',$tipo));
		return $criteria;
	}
	
	function Ativo($criteria=null)
	{
		if($criteria==null)
		{
			$criteria = new TCriteria;
		}
		$criteria->add(new TFilter('flag_ativo','=',1));
		return $criteria;
	}
	
	function FornecedorAtivo($criteria = null)
	{
		if($criteria==null)
		{
		$criteria = new TCriteria;
		}
		$criteria->add(new TFilter('flag_fornecedor','=',1));
		$criteria = Self::Ativo($criteria);
		return $criteria;
	}
	function ClienteAtivo($criteria = null)
	{
		if($criteria==null)
		{
		$criteria = new TCriteria;
		}
		$criteria->add(new TFilter('flag_cliente','=',1));
		$criteria = Self::Ativo($criteria);
		return $criteria;
	}
	function CaixaAberto($criteria = null)
	{
		if($criteria==null)
		{
		$criteria = new TCriteria;
		}
		$criteria->add(new TFilter('flag_aberto','=',1));
		return $criteria;
	}
	function ProfissionalAtivo($criteria = null)
	{
		if($criteria==null)
		{
		$criteria = new TCriteria;
		}
		$criteria->add(new TFilter('flag_profissional','=',1));
		$criteria = Self::Ativo($criteria);
		return $criteria;
	}
	function ServicoAtivo($criteria = null)
	{
		if($criteria==null)
		{
		$criteria = new TCriteria;
		}
		$criteria->add(new TFilter('tipo_produto_servico_id','=',2));
		$criteria = Self::Ativo($criteria);
		return $criteria;
	}
	function ProdutoAtivo($criteria = null)
	{
		if($criteria==null)
		{
		$criteria = new TCriteria;
		}
		$criteria->add(new TFilter('tipo_produto_servico_id','=',1));
		$criteria = Self::Ativo($criteria);
		return $criteria;
	}
}

?>