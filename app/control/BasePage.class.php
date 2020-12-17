<?php

use Adianti\Control\TAction;

class BasePage extends TPage
{

    function addBotaoVoltar($pagina)
    {
        $this->form->addAction("Voltar", new TAction([$pagina,'onReload']), 'fas:undo blue');
    }
}