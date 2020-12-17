<?php

use Adianti\Database\TTransaction;
use RadicalLoop\Eod\Config;
use RadicalLoop\Eod\Eod;

class CrawlerService
{
	function start($request)
	{
		try{
        TTransaction::open('padrao');
        set_time_limit(360);
        ini_set("default_socket_timeout", 360);
        if($request['crawler_id']==1)//Crawler para atualização do valor do ativo
        {
            $crawler = new CrawlerRotina();
            $crawler->iniciaProcessamentoCotacaoAtual();
            $this->atualizaDataUltimoLog(1);
        }
        // if($request['crawler_id']==2)
        // {
        //    $crawler = new RotinaCotacao();
        //     $crawler->iniciaProcessamento();
        //     $this->atualizaDataUltimoLog(2);
        // }
        // if($request['crawler_id']==3)
        // {
        //    RotinaCotacao::zerarRotina();
        // }
        if($request['crawler_id']==4)
        {
            //Rotina para atualizar dividendos
           RotinaDividendo::Processar();
        }
        if($request['crawler_id']==5)
        {
           CrawlerTarefa::gerarTarefasEndOfDay();
        }
        if($request['crawler_id']==6)
        {
           CrawlerTarefa::gerarTarefasDividendo();
        }
        if($request['crawler_id']==7)
        {
           CrawlerTarefa::gerarTarefasCalculoProvento();
        }
        TTransaction::close();
        if($request['crawler_id']==99)
        {
           CrawlerFila::executar();
        }
      
        }catch(Exception $e){
            CrawlerLog::add("Erro - ".$e->getMessage(),0,0,$request['crawler_id']);
        new TMessage('error',$e->getMessage());
        }		
    }
    static function atualizaDataUltimoLog($crawler_id)
    {
        $crawler = new CrawlerModelo($crawler_id);
        $crawler->data_ultima_atualizacao = date('Y-m-d H:i');
        $crawler->store();

        $preference = new SystemPreference();
        $preference->id = 'data_ultimo_crawler_id_'.$crawler_id;
        $preference->preference = date('Y-m-d H:i');
        $preference->store();
    }
    
}

?>