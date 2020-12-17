<?php
class Widget
{
	function Label($texto,$cor)
	{
		return "<span style=\"background-color:{$cor} !important;\" class=\"label label-primary\" style=\"text-shadow:none; font-size:12px; font-weight:lighter\">{$texto}</span>";
	}
	function CaixaSimples($titulo='Indefinido',$valor='Indefinido',$classe='col-md-6 col-sm-12')
	{
		
		return "<div class='{$classe}'>
                  <div class='description-block border-right border-left'>
                    <!--<span class='description-percentage text-green'><i class='fa fa-caret-up'></i></span>-->
                    <h5 class='description-header'>{$valor}</h5>
                    <span class='description-text'>{$titulo}</span>
                  </div>
                </div>";
	}
	
	function InfoBox($tipo_id,$titulo,$valor,$classe,$cor=null,$icone=null,$progresso=100,$texto_progresso=null,$porcentagem=null)
	{
    if($tipo_id == 1)
    {
      return "<div class='{$classe}'><div class=\"info-box bg-{$cor}\">
      <span class=\"info-box-icon\"><i class=\"{$icone}\"></i></span>

      <div class=\"info-box-content\">
        <span class=\"info-box-text\" style='color:white;'>{$titulo}</span>
        <span class=\"info-box-number\" style='color:white;'>{$valor}</span>

        <div class=\"progress\">
          <div class=\"progress-bar\" style=\"width: {$progresso}%\"></div>
        </div>
        <span class=\"progress-description\">
              {$texto_progresso}
            </span>
      </div>
    </div>
</div>";
    }elseif($tipo_id == 2){
      if($porcentagem!=null)
      {
        $texto_progresso = "<span class='description-percentage text-{$cor}'><i class='{$icone}'></i> {$porcentagem}</span>";
      }
     return "<div class='{$classe}'>
     <div class='description-block border-right'>
     {$texto_progresso}
       
       <h5 class='description-header'>{$valor}</h5>
       <span class='description-text'>{$titulo} </span>
     </div>
   </div>";
    }
	
	}
}


?>