<?php
class Bs4
{
    function cardComImagem($caminho,$conteudo=null,$classe='col-12 mt-2')
    {
        return "
        <div class='{$classe}'>
            <div class='card text-left'>       
                <div class='card-body p-2'>
                    <div class='row'>
                        <div class='col-4  m-none '>
                        <img class='card-img-top' style='height:100%;' src='{$caminho}' alt=''></div>
                        {$conteudo}
                    </div>         
                </div>
            </div>
        </div>";
    }

    function cardTituloSubtitulo($titulo,$subtitulo,$classe='col-4 mt-2')
    {
        return "<div class='{$classe}'>
        <h4 class='card-title'>{$titulo}</h4>
         <p class='card-text'>{$subtitulo}</p>
        </div>";
    }
}