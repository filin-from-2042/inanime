<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Персональные данные");
?> 
<div class="section-personal profile"> 
  <div class="container"> 
    <div class="row"> 
      <div class="col-xs-24 col-sm-8 col-md-6 col-lg-6 menu-column">
          <?
          $APPLICATION->IncludeFile("/include/personal_left_menu.php", Array(), Array(
              "MODE" => "html", // будет редактировать в веб-редакторе
              "NAME" => "Редактирование включаемой области раздела", // текст всплывающей подсказки на иконке
          ));
          ?>
      </div>
     
      <div class="col-xs-24 col-sm-16 col-md-18 col-lg-18 fields-column"> <?$APPLICATION->IncludeComponent(
	"bitrix:main.profile",
	"eshop",
	Array(
		"SET_TITLE" => "Y"
	)
);?> </div>
     </div>
   </div>
 </div>
 <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>