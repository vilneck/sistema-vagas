<?php
class Utils
{
	static function debugBanco()
{
	TTransaction::setLoggerFunction( function($message) {
		echo $message . '<br>';
	});
}
	static function logBanco()
	{
		TTransaction::setLoggerFunction( function($message) {
			echo $message . '<br>';
		});
	}
	static function criarFiltro($filters,$campo,$operador,$valor)
	{
		if (isset($valor) AND ( (is_scalar($valor) AND $valor !== '') OR (is_array($valor) AND (!empty($valor)) )) )
		{
			if($operador == 'like')
			{
				$filters[] = new TFilter($campo, $operador, "%{$valor}%");// create the filter 
			}else{
				$filters[] = new TFilter($campo, $operador, $valor);// create the filter 
			}
		}
		return $filters;
	}
	static function notEmpty($valor)
	{
		if(isset($valor) and $valor != null)
		{
			return true;
		}
		return false;
	}
function setMensagem($tipo_mensagem,$mensagem)
{
	TSession::setValue('tipo_mensagem',$tipo_mensagem);
	TSession::setValue('mensagem',$mensagem);
}
function getMensagem()
{
	if(TSession::getValue('mensagem')!=null)
	{
		new TMessage(TSession::getValue('tipo_mensagem'),TSession::getValue('mensagem'));
		TSession::setValue('tipo_mensagem',null);
		TSession::setValue('mensagem',null);
	}
	
}	
function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
{
$lmin = 'abcdefghijklmnopqrstuvwxyz';
$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$num = '1234567890';
$simb = '!@#$%*-';
$retorno = '';
$caracteres = '';
$caracteres .= $lmin;
if ($maiusculas) $caracteres .= $lmai;
if ($numeros) $caracteres .= $num;
if ($simbolos) $caracteres .= $simb;
$len = strlen($caracteres);
for ($n = 1; $n <= $tamanho; $n++) {
$rand = mt_rand(1, $len);
$retorno .= $caracteres[$rand-1];
}
return $retorno;
}
function criaTmp()
{
	if(!file_exists('tmp'))
	{
		mkdir('tmp',0777);
	}
}
function criaPasta($caminho)
{
	if(!file_exists($caminho))
	{
		mkdir($caminho,0777,true);
	}
}
function spanRequired()
{
    return "<span style='color:red;font-weight:bold;'>*</span> ";
}
function getCor($value)
	{
	$cores=array();
	$cores[0]='#dd4b39';
	$cores[1]='#00a65a';
	$cores[2]='purple';
	$cores[3]='orange';
	$cores[4]='#C0FF3E';
	$cores[5]='#FFB5C5';
	$cores[6]='#00BFFF';
	$cores[7]='#BF3EFF';
	$cores[8]='#FF8247';
	$cores[9]='#FFC125';
	$cores[10]='#CAFF70';
	return $cores[$value];
	}
public static function datetime2us($date)
    {
        if ($date)
        {
            // get the date parts   01-01-2018 15:00
            $day  = substr($date,0,2);
            $mon  = substr($date,3,2);
            $year = substr($date,6,4);
            $hour = substr($date,11,2);
            $min  = substr($date,14,2);
            return "{$year}-{$mon}-{$day} {$hour}:{$min}";
        }
    }
function corAleatoria()
{
	$letters = '0123456789ABCDEF';
	$color = '#';
	for($i = 0; $i < 6; $i++) {
		$index = rand(0,15);
		$color .= $letters[$index];
	}
	return $color;
}
function setPage($classe)
	{
		TSession::setValue('lastPage',$classe);
	}
	function getPage()
	{
		return TSession::getValue('lastPage');
	}
	function setFormName($formName)
	{
		TSession::setValue('lastFormName',$formName);
	}
	function getFormName()
	{
		return TSession::getValue('lastFormName');
	}
static function Ordenacao($classe,$campo)
{
	$order = new TAction(array($classe, 'onReload'));
    $order->setParameter('order', $campo);
	return $order;
}
function recolheMenu()
{
	TScript::create('var width = screen.width;
	var height = screen.height;
		$(document).ready(function(){
		$("body").addClass("sidebar-collapse");});');
}
function debug($param)
{
	echo "<pre>";
	var_dump($param);
	echo "</pre>";
}
function transformRadio($object)
{
	$object->setLayout('horizontal');
	$object->setUseButton(true);
	return $object;
}
function transformDateTime($object)
{
	$object->setMask('dd/mm/yyyy hh:ii');
	$object->setDatabaseMask('yyyy-mm-dd hh:ii');
	return $object;
}
function transformDate($object)
{
	$object->setMask('dd/mm/yyyy');
	$object->setDatabaseMask('yyyy-mm-dd');
	return $object;
}
function setLastWindow($window)
{
	TSession::SetValue('lastWindow',$window->get(0)->getId());
}
function closeLastWindow()
{
	$id_modal = TSession::getValue('lastWindow');
	TScript::create("$('#{$id_modal}').remove();");
}
}
?>