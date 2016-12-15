<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);


$carouselID = 'carousel-custom-vertical-'.$arResult["ID"];
$itemCounter = 0;
?>
<div id="<?=$carouselID?>" class="carousel-products vertical">
    <div class="title-container grey-container clearfix">
        <span class="title"><a href="<?=$arResult['SECTION_PAGE_URL']?>"><?=$arResult["NAME"]?></a></span>
        <div class="next button"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
        <div class="prev button"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
    </div>

    <ul>
        <?foreach($arResult["ITEMS"] as $arElement):?>
            <?if($itemCounter==0):?><li><?endif?>
                <div class="product-item-preview horizontal">
                    <div class="image-container">
                        <img src="<?=$arElement["PREVIEW_PICTURE"]["SRC"]?>" />
                        <div class="icons-container">
                            <?if($arElement["DATE_ACTIVE_FROM"]):?>
                                <?if(((strtotime("now")-strtotime($arElement["DATE_ACTIVE_FROM"]))/86400) <= 14):?>
                                    <div class="additional-icon new"></div>
                                <?endif?>
                            <?endif?>
                            <?if($arElement["PROPERTIES"]["IS_BESTSELLER"]["VALUE"]=="Да"):?>
                                <div class="additional-icon bestseller"></div>
                            <?endif?>
                            <?if($arElement["PROPERTIES"]["IS_RECOMMEND"]["VALUE"]=="Да"):?>
                                <div class="additional-icon recommended"></div>
                            <?endif?>
                        </div>
                    </div>
                    <div class="data-container">
                        <div class="price-container">
                            <span class="price yellow-text"><?=CPrice::GetBasePrice($arElement["ID"])["PRICE"]?> &#8381;</span>
                        </div>
                        <div class="title-container">
                            <a href="<?=$arElement["DETAIL_PAGE_URL"]?>" class="link">
                                <div class="text"><?=$arElement["NAME"]?></div>
                                    <div class="article"><?($arElement["PROPERTIES"]["ARTNUMBER1"]["VALUE"])?'арт.'.$arElement["PROPERTIES"]["ARTNUMBER1"]["VALUE"]:'';?></div>
                            </a>
                        </div>
                        <div class="rate-container">
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:iblock.vote",
                                "stars",
                                Array(
                                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                                    "ELEMENT_ID" => $arElement["ID"],
                                    "MAX_VOTE" => $arParams["MAX_VOTE"],
                                    "VOTE_NAMES" => $arParams["VOTE_NAMES"],
                                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                                ),
                                $component
                            );?>
                        </div>
                    </div>
                </div>
            <?if($itemCounter == 1){
                $itemCounter=0;
                echo '</li>';
            }else{
                $itemCounter=1;
            }?>
        <?endforeach?>
    </ul>
</div>
<script>
    $(document).ready(function () {
        inanime_new.init_custom_horizontal_carousel('<?=$carouselID?>', 1);
    });
    </script>