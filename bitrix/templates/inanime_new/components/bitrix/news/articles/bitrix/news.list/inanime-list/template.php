<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<div class="ia-news-list">
    <h1 class="ia-page-title"><?=$arResult['NAME'];?></h1>
    <?if($arParams["DISPLAY_TOP_PAGER"]):?>
        <?=$arResult["NAV_STRING"]?>
    <?endif;?>

    <div class="list-container">
        <?foreach($arResult["ITEMS"] as $arItem):?>
            <div class="news-single-wrap">
                <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <div class="news-single-container grey-container">
                    <div class="row">
                        <?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
                            <div class="col-xs-24 col-sm-6 col-md-4 col-lg-4 image-column">
                                        <img
                                            class="news-image-preview"
                                            border="0"
                                            src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
                                            alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
                                            title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
                                            />
                            </div>
                        <?endif?>
                        <div class="col-xs-24  col-sm-18 col-md-20 col-lg-20 text-column">
                            <div class="title-container clearfix">
                                <?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
                                    <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                                        <a href="<?echo $arItem["DETAIL_PAGE_URL"]?>" class="title-link text-underline"><?echo $arItem["NAME"]?></a>
                                    <?else:?>
                                        <?echo $arItem["NAME"]?>
                                    <?endif;?>
                                <?endif;?>
                                <?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
                                    <span class="date-text hidden-xs"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></span>
                                <?endif?>
                            </div>
                            <?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
                                <div class="announce-text-container">
                                    <?echo $arItem["PREVIEW_TEXT"];?>
                                </div>
                            <?endif;?>
                        </div>
                    </div>
                </div>
            </div>
        <?endforeach;?>
    </div>
    <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
        <?=$arResult["NAV_STRING"]?>
    <?endif;?>
</div>