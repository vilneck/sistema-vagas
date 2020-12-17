<?php
class Format
{
	public static function zeroIfIsNotSet($array,$key)
    {
        if(isset($array[$key]))
        {
            return $array[$key];
        }
        return 0;
    }
	function formataDinheiro($valor)
	{		
		return @number_format($valor,2,",",".");
	}
	public static  function desformataDinheiro($numero)
	{
	    return str_replace(',','.',str_replace('.','',$numero));
	}
	function toDouble($valor)
	{		
		return @number_format($valor, 2, '.', '');
	}
	function toPeso($valor)
	{		
		return @number_format($valor, 3, '.', '');
	}
	function Numerico($valor)
	{
		if($valor != 0 )
		{
			return $this->formataDinheiro($valor);
		}else{
		return null;
		}
	}
	function verificaNulo($valor)
	{
		return ($valor==0 or $valor==null)?'':$valor;
	}
	public function desformata($valor)
	{
		$palavras_busca = ['-','/','.',',','(',')',' '];
		return str_replace($palavras_busca, '', $valor);
	}
	function datetime2br($datetime)
	{
		return date('Y-m-d H:i',strtotime(str_replace('/','-',$datetime)));		
	}
	
	}
?>