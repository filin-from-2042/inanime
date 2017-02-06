<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

?>

<div class="container ia-news-single">
    <?$APPLICATION->IncludeComponent(
        "bitrix:breadcrumb",
        "catalog-chain",
        Array(
            "START_FROM" => "0",
            "PATH" => "",
            "SITE_ID" => SITE_ID
        )
    );?>
    <div class="title-container clearfix">
        <?if($arParams["DISPLAY_NAME"]!="N" && $arResult["NAME"]):?>
            <h1><?=$arResult["NAME"]?></h1>
        <?endif;?>
        <?if($arParams["DISPLAY_DATE"]!="N" && $arResult["DISPLAY_ACTIVE_FROM"]):?>
            <span class="news-date"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></span>
        <?endif;?>
    </div>
    <div class="text-container">
        <?if(strlen($arResult["DETAIL_TEXT"])>0):?>
            <?echo $arResult["DETAIL_TEXT"];?>
        <?else:?>
            <?echo $arResult["PREVIEW_TEXT"];?>
        <?endif?>
    </div>
    <hr class="general-content-bottom-line">
</div>