<?php
class Mask
{
	function CpfCnpj($campo)
	{
		$campo->class='tfield cpf_cnpj';
		/*TScript::create("
		var options = {
			onKeyPress: function (cpf, ev, el, op) {
				var masks = ['000.000.000-000', '00.000.000/0000-00'];
				$('input[name=\"cpf_cnpj\"]').mask((cpf.length > 14) ? masks[1] : masks[0], op);
			}
		}
		var value = $('input[name=\"cpf_cnpj\"]').val();
		if(value)
		{			
		if(value.length < 14)
		{
		$('input[name=\"cpf_cnpj\"]').mask('000.000.000-000', options);		
		}else{
		$('input[name=\"cpf_cnpj\"]').mask('00.000.000/0000-00', options);		
		}
		}else{
			$('input[name=\"cpf_cnpj\"]').mask('000.000.000-000', options);	
		}
		");		*/
		echo "<script>
		var options = {
    onKeyPress: function (cpf, ev, el, op) {
        var masks = ['000.000.000-000', '00.000.000/0000-00'];
        $('.cpf_cnpj').mask((cpf.length > 14) ? masks[1] : masks[0], op);
    }
}

$('.cpf_cnpj').length > 11 ? $('.cpf_cnpj').mask('00.000.000/0000-00', options) : $('.cpf_cnpj').mask('000.000.000-00#', options);		
		</script>";
	}
}
?>