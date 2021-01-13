<?php 

class Transform
{
	function Total($values)
	{		
        return array_sum((array)$values);
	}
	function TotalArredondado($values)
	{		
        return round(array_sum((array)$values));
	}
	function TotalValor($values)
	{
		$array=[];
		foreach($values as $value)
		{
			$array[] = Formats::desformataDinheiro($value);
		}
        return Formats::formataDinheiro(array_sum((array)$array));
	}
	function Quantidade($value, $object=null, $row=null) 
    {
            if(!$value)
            {
                $value = 0;
            }
           return number_format($value, 0, ",",".");
    }
	function QuantidadeDuasCasas($value, $object=null, $row=null) 
    {
            if(!$value)
            {
                $value = 0;
            }
           return number_format($value, 2, ",",".");
    }
	function BrDatetime($value)
	{
            if($value)
            {
                $date = new DateTime($value);
                return $date->format("d/m/Y H:i");
            }
    }

    function UsDatetime($value)
	{
            if($value)
            {
                $date = new DateTime($value);
                return $date->format("Y-m-d H:i");
            }
    }
	
	static function Dinheiro($value, $object=null, $row=null) 
    {
            if(!$value)
            {
                $value = 0;
            }
            return number_format($value, 2, ",", ".");
    }
	function MoedaOrigem($value, $object=null, $row=null) 
    {
            if(!$value)
            {
                $value = 0;
            }
            $sinal = $object->get_moeda()->icone;
            if($sinal=='R$')
            {
                $icone = '<b>'.$sinal.'</b> ';
            }else{
                $icone = "<i class='{$sinal}'></i> ";
            }
            return $icone.number_format($value, 2, ",", ".");
    }
	function DinheiroNulo($value, $object=null, $row=null) 
    {
            if($value>=0 and $value !=null)
            {
            return number_format($value, 2, ",", ".");
            }
			return '';
    }
	
	function Negocio($value, $object, $row)
	{
		return '# '.$value;
	}
	
	function Data($value, $object=null, $row=null) 
	{
		if($value)
		{
			$date = new DateTime($value);
			return $date->format("d/m/Y");
		}

	}
	function DataHora($value, $object=null, $row=null) 
	{
		if($value)
		{
			$date = new DateTime($value);
			return $date->format("d/m/Y H:i");
		}

	}
	function DiasVencidos($value, $object=null, $row=null)
	{	
		
		$calculo = round((strtotime($object->data_vencimento)-time())/86400);
		if($object->data_vencimento == date('Y-m-d'))
		{
			$calculo=0;
		}
		 if($calculo==0)
		 {
                    $classe= 'warning';	
					$texto = 'Hoje';					 
		 }elseif($calculo=='-1')
		 {
				$classe= 'danger';	
					$texto = 'Ontem';
		 }
		 elseif($calculo<0)
         {
					 $texto = $calculo.' dias';					 
                     $classe= 'danger';
         }else{
                     $classe= 'success';
					 $texto = $calculo.' dias';					 
         }
               $div = new TElement('span'); 
                    $div->class="label label-{$classe}";
                    $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
                    $div->add($texto);
                    return $div;
	}
	function SimNao($value, $object=null, $row=null)
	{
		
		 if($value==0)
             {
                 $texto = 'Não';
                     $classe= 'danger';
             }else{
                  $texto = 'Sim';
                     $classe= 'success';
             }
               $div = new TElement('span'); 
                    $div->class="label label-{$classe}";
                    $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
                    $div->add($texto);
                    return $div;
	}
	function NaoSim($value, $object=null, $row=null)
	{
		
		 if($value==0)
             {
                 $texto = 'Não';
                     $classe= 'success';
             }else{
                  $texto = 'Sim';
                     $classe= 'warning';
             }
               $div = new TElement('span'); 
                    $div->class="label label-{$classe}";
                    $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
                    $div->add($texto);
                    return $div;
	}
	function Situacao($value, $object=null, $row=null)
	{
               $div = new TElement('span'); 
                    $div->class="label label-{$object->situacao->classe}";
                    $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
                    $div->add($object->situacao->descricao);
                    return $div;
    }
	function StatusImportacao($value, $object=null, $row=null)
	{
        if($value==0)
        {
            $class='danger';
            $texto='Ativo não encontrado em nossa base';
        }else{
            $class='success';
            $texto='Importável';
        }

        $div = new TElement('span'); 
        $div->class="label label-{$class}";
        $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
        $div->add($texto);
        return $div;
    }
    function Tipo($value, $object=null, $row=null)
	{
               $div = new TElement('span'); 
                    $div->class="label label-{$object->tipo->classe}";
                    $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
                    $div->add($object->tipo->descricao);
                    return $div;
    }
    function CategoriaFinanceira($value, $object=null, $row=null)
	{
        $div = new TElement('span'); 
        $div->class="label label-primary";
        $div->style="text-shadow:none;background-color:".$object->financeiro_categoria->cor." !important;font-size:12px;font-weight:lighter";
        $div->add($object->financeiro_categoria->descricao);
        return $div;
             
	}
	function Origem($value, $object=null, $row=null)
	{
		return Widget::Label($object->origem->descricao,$object->origem->cor);
	}
	function TipoOperacao($value, $object=null, $row=null)
	{
               $div = new TElement('span');
				if($value==0)
				{
					$texto = 'Entrada';
					$classe ='success';
				}else{
					$texto = 'Saída';
					$classe ='primary';
				}					
               $div->class="label label-{$classe}";
               $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
               $div->add($texto);
               return $div;
	}
	
	function Marcador($value, $object=null, $row=null)
	{
               $div = new TElement('span'); 
                    $div->class="label label-primary";
                    $div->style="text-shadow:none;background-color:".$object->cor."!important;font-size:12px;padding:3px; font-weight:lighter";
                    $div->add($object->descricao[0]);
                    return $div;
    }
    function Cor($value, $object=null, $row=null)
	{
               $div = new TElement('span'); 
                    $div->class="label label-primary";
                    $div->style="text-shadow:none;background-color:".$object->cor." !important;font-size:12px; padding:3px;font-weight:lighter";
                    $div->add($object->cor);
                    return $div;
	}
}
?>