<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<div class="news-list-container">
    <?foreach($arResult["ITEMS"] as $arItem):?>
    <div class="new-container grey-container">
        <div class="new-title">
            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="clearfix">
                <span class="title"><?=$arItem["NAME"]?></span>
                <span class="date"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></span>
            </a>
        </div>
        <div class="new-content">
            <?=substr($arItem["PREVIEW_TEXT"], 0, 240). '...'?>
        </div>
    </div>
    <?endforeach;?>
</div>