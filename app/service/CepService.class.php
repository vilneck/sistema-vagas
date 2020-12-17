<?php
/**
 * ConsultaCepService
 *
 * webservice cliente para buscar os dados do CEP, fornecidos pelo site www.viacep.com.br
 * @package service
 * @author Ricardo Câmara (camaramachado@gmail.com)
 * @version 1.0
 */
 
class CepService
{
    
    /**
     * Method getCep
     * Consulta o CEP utilizando o webservice do site www.viacep.com.br
     * @param $cep string - CEP a ser consultado
     * @param $formato string - formato da resposta. Suportados json, xml, piped ou querty.
     * @return stdClass, XML, string (depende do formato) com os dados do CEP
     * Se o CEP não possuir 8 digitos ou não existir, $stdClass->erro retorna TRUE e $stdClass->mensagem informa o erro.
     * Exemplo de uso: $endereco = ConsultaCepService::getCep('99999999');
     */
    public static function getCep($cep, $formato = 'json')
    {
        try
        {                                        
            if( isset($cep) )
            {
                //valida os formatos
                $formatos = ['json', 'xml', 'piped', 'querty'];
                if( !in_array($formato, $formatos) )
                {
                    $retorno             = new stdClass;
                    $retorno->erro         = TRUE;
                    $retorno->mensagem     = "Formato <b>{$formato}</b> não suportado!";
                    return $retorno;
                }
                
                //pega apenas os numeros, retirando os demais caracteres
                $cep = preg_replace("/[^0-9]/", "", $cep);
                
                //o CEP deve ter 8 digitos
                if( strlen($cep) != 8 )
                {
                    $retorno             = new stdClass;
                    $retorno->erro         = TRUE;
                    $retorno->mensagem     = "CEP: <b>{$cep}</b> não possui 8 digitos!";    
                    return $retorno;                                    
                }
                
                switch($formato)
                {
                    case 'json':                        
                        $retorno = json_decode( file_get_contents("https://viacep.com.br/ws/{$cep}/{$formato}") );
                        break;
                        
                    case 'xml':                        
                        $retorno = htmlentities(file_get_contents("https://viacep.com.br/ws/{$cep}/{$formato}"));
                        break;
                
                    case 'piped':                        
                        $retorno = file_get_contents("https://viacep.com.br/ws/{$cep}/{$formato}");
                        break;
                
                    case 'querty':                        
                        $retorno = file_get_contents("https://viacep.com.br/ws/{$cep}/{$formato}");
                        break;
                }
                                                                                           
                //checa se o cep não existe, neste caso o atributo erro será TRUE
                if( isset($retorno->erro) )
                {
                    $retorno->mensagem = "CEP: <b>{$cep}</b> não existe na base de dados!";
                }
                
                return $retorno;
            }
        }
        catch (Exception $e)
        {
            echo 'Error: ' . $e->getMessage();
        }
    }
    
}

?>