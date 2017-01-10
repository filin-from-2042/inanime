<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Страница не найдена");?>
<div class="container page-404">
    <div class="image-container">
        <img src="/images/404.png">
    </div>
    <div class="general-text-container">
        <div class="text-title">
            Извините! Произошла ошибка
        </div>
        <div class="text-content">
            <div class="text-wrap">
                Страница, которую Вы ищете, не может быть найдена
            </div>
        </div>
    </div>
    <?if(!$USER->IsAuthorized()){?>
    <div class="coupon-text-container">
        <?$APPLICATION->IncludeFile(
            "/include/coupon_text_404.php",
            Array(),
            Array("MODE"=>"html")
        );?>
    </div>
    <?}?>
    <div class="button-container">
        <a href="/" class="btn btn-default ia-btn text-btn blue-btn">На главную</a>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>