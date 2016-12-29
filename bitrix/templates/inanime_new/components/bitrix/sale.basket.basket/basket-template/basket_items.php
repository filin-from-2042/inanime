<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
use Bitrix\Sale\DiscountCouponsManager;

if (!empty($arResult["ERROR_MESSAGE"]))
{
    ShowError($arResult["ERROR_MESSAGE"]);
}

$bDelayColumn  = false;
$bDeleteColumn = false;
$bWeightColumn = false;
$bPropsColumn  = false;
$bPriceType    = false;
$arJSParams = array();
$arJSParams["currentPage"] = $APPLICATION->GetCurPage();
function getProductArticul($productID)
{
    $artNumber='';
    if(CModule::IncludeModule("iblock") && CModule::IncludeModule("catalog"))
    {
        $arSelect = Array("PROPERTY_ARTNUMBER1");
        $arFilter = Array("IBLOCK_ID" =>  19, "ID"=> $productID);
        $res = CIBlockElement::GetList(Array("ACTIVE_FROM" => "DESC", "SORT" => "ASC"), $arFilter, false, Array("nPageSize" => 15), $arSelect);
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            $artNumber=$arFields["PROPERTY_ARTNUMBER1_VALUE"];
        }
    }
    return $artNumber;
}

if ($normalCount > 0):
?>
<div class="my-basket-tab-container">
    <div class="row table-header grey-container hidden-xs">
        <?
        foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader){
            //var_dump($arHeader["id"]);
            $arHeader["name"] = (isset($arHeader["name"]) ? (string)$arHeader["name"] : '');
            if ($arHeader["name"] == '')
                $arHeader["name"] = GetMessage("SALE_".$arHeader["id"]);
            $arHeaders[] = $arHeader["id"];

            // remember which values should be shown not in the separate columns, but inside other columns
            if (in_array($arHeader["id"], array("TYPE")))
            {
                $bPriceType = true;
                continue;
            }
            elseif ($arHeader["id"] == "PROPS")
            {
                $bPropsColumn = true;
                continue;
            }
            elseif ($arHeader["id"] == "DELAY")
            {
                $bDelayColumn = true;
                continue;
            }
            elseif ($arHeader["id"] == "DELETE")
            {
                $bDeleteColumn = true;
                continue;
            }
            elseif ($arHeader["id"] == "WEIGHT")
            {
                $bWeightColumn = true;
            }
            elseif ($arHeader["id"] == "DISCOUNT")
            {
                $bWeightColumn = true;
            }

            if ($arHeader["id"] == "NAME"):
                ?>
                <div class="col-sm-14 col-md-6 col-lg-9 column-header column-name">
                    <span class="column-title"><?=$arHeader["name"]; ?></span>
                </div>
                <div class="hidden-sm col-md-6 col-lg-4 column-header column-units">
                <span class="column-title">Единица измерения</span>
            </div>
            <?elseif($arHeader["id"] == "PRICE"):
                ?>
                <div class="hidden-sm col-md-3 col-lg-4 column-header column-cost">
                    <span class="column-title"><?=$arHeader["name"]; ?></span>
                </div>
            <?elseif($arHeader["id"] == "QUANTITY"):
                ?>
                <div class="col-sm-4 col-md-3 col-lg-4 column-header column-count">
                    <span class="column-title"><?=$arHeader["name"]; ?></span>
                </div>
            <?elseif($arHeader["id"] == "SUM"):
                ?>
                <div class="col-sm-6 col-md-6 col-lg-2 column-header column-all-cost">
                    <span class="column-title"><?=$arHeader["name"]; ?></span>
                </div>
            <?endif;
            ?>
        <?
        }
        ?>
    </div>
    <div class="table-content-container">
        <?
        foreach ($arResult["GRID"]["ROWS"] as $k => $arItem):
            if ($arItem["DELAY"] == "N" && $arItem["CAN_BUY"] == "Y"):
                ?>
                <div class="row content-row" id="<?=$arItem["ID"]?>">

                    <?
                    foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):

                        if (in_array($arHeader["id"], array("PROPS", "DELAY", "DELETE", "TYPE"))) // some values are not shown in the columns in this template
                            continue;
                        if ($arHeader["name"] == '')
                            $arHeader["name"] = GetMessage("SALE_".$arHeader["id"]);

                        if ($arHeader["id"] == "NAME"):
                    ?>
                            <div class="col-xs-24 col-sm-15 col-md-10 col-lg-10 column-content column-name">
                                <div class="ia-checkbox">
                                    <input type="checkbox" id="checkbox_<?=$arItem["ID"]?>" >
                                    <label for="checkbox_<?=$arItem["ID"]?>"><?
                                        if (strlen($arItem["PREVIEW_PICTURE_SRC"]) > 0):
                                            $url = $arItem["PREVIEW_PICTURE_SRC"];
                                        elseif (strlen($arItem["DETAIL_PICTURE_SRC"]) > 0):
                                            $url = $arItem["DETAIL_PICTURE_SRC"];
                                        else:
                                            $url = $templateFolder."/images/no_photo.png";
                                        endif;
                                        ?>
                                        <img class="product-preview" src="<?=$url?>"/>
                                    </label>
                                    <div class="mobile-row-data-container">
                                        <div class="column-data-all-cost  yellow-text"><?=$arItem["PRICE_FORMATED"]?></div>
                                        <div class="ia-counter-container">
                                            <div class="increase button"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
                                            <div class="decrease button"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
                                            <input type="text" class="counter-value" value="1"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-title">
                                    <div class="product-name">
                                    <?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?>
                                        <a href="<?=$arItem["DETAIL_PAGE_URL"] ?>"><?endif;?>
                                            <?=$arItem["NAME"]?>
                                    <?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?>
                                        </a>
                                    <?endif;?>
                                    </div>
                                    <?
                                        $currProductArticul =  getProductArticul($arItem["PRODUCT_ID"]);
                                    if($currProductArticul){
                                    ?>
                                        <div class="product-article">арт. <?=$currProductArticul?></div>
                                    <?}?>
                                </div>
                            </div>
                            <div class="hidden-xs hidden-sm col-md-2 col-lg-2 column-content column-units">
                                <span class="column-data">шт.</span>
                            </div>

                        <?
                        elseif ($arHeader["id"] == "PRICE"):
                        ?>
                            <div class="hidden-xs hidden-sm col-md-4 col-lg-4 column-content column-cost">
                                <span class="column-data">
                                    <div class="price">
                                        <span class="price old">
                                            <?if (floatval($arItem["DISCOUNT_PRICE_PERCENT"]) > 0):?>
                                                <?=$arItem["FULL_PRICE_FORMATED"]?>
                                            <?endif;?>
                                        </span>
                                        <span class="price yellow-text"><?=$arItem["PRICE_FORMATED"]?></span>
                                    </div>
                                </span>
                            </div>

                        <?
                        elseif ($arHeader["id"] == "QUANTITY"):
                            ?>
                            <div class="hidden-xs col-sm-4 col-md-4 col-lg-4 column-content column-count">
                                <span class="column-data">
                                    <div class="ia-counter-container">
                                        <div class="increase button"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
                                        <div class="decrease button"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
                                        <input type="text" class="counter-value" value="<?=$arItem["QUANTITY"]?>"/>
                                    </div>
                                </span>
                            </div>
                        <?
                        elseif ($arHeader["id"] == "SUM"):
                            ?>
                            <div class="hidden-xs col-sm-5 col-md-4 col-lg-4 column-content column-all-cost">
                                <span class="column-data  yellow-text"><?=$arItem[$arHeader["id"]]; ?></span>
                            </div>
                        <?
                        endif;?>
                    <?
                    endforeach;

                    if ($bDelayColumn || $bDeleteColumn){
                            if ($bDeleteColumn)$arJSParams['deleteActions'][$arItem["ID"]] = str_replace("#ID#", $arItem["ID"], $arUrls["delete"]);
                            if ($bDelayColumn)$arJSParams['delayActions'][$arItem["ID"]] = str_replace("#ID#", $arItem["ID"], $arUrls["delay"]);
                    }
                    ?>

                </div>
            <?
            endif;
        endforeach;
        ?>
    </div>


    <input type="hidden" id="column_headers" value="<?=CUtil::JSEscape(implode($arHeaders, ","))?>" />
    <input type="hidden" id="offers_props" value="<?=CUtil::JSEscape(implode($arParams["OFFERS_PROPS"], ","))?>" />
    <input type="hidden" id="action_var" value="<?=CUtil::JSEscape($arParams["ACTION_VARIABLE"])?>" />
    <input type="hidden" id="quantity_float" value="<?=$arParams["QUANTITY_FLOAT"]?>" />
    <input type="hidden" id="count_discount_4_all_quantity" value="<?=($arParams["COUNT_DISCOUNT_4_ALL_QUANTITY"] == "Y") ? "Y" : "N"?>" />
    <input type="hidden" id="price_vat_show_value" value="<?=($arParams["PRICE_VAT_SHOW_VALUE"] == "Y") ? "Y" : "N"?>" />
    <input type="hidden" id="hide_coupon" value="<?=($arParams["HIDE_COUPON"] == "Y") ? "Y" : "N"?>" />
    <input type="hidden" id="use_prepayment" value="<?=($arParams["USE_PREPAYMENT"] == "Y") ? "Y" : "N"?>" />
    <input type="hidden" id="auto_calculation" value="<?=($arParams["AUTO_CALCULATION"] == "N") ? "N" : "Y"?>" />

    <div class="row actions-container">
        <div class="col-xs-24 col-sm-12 col-md-12 col-lg-12 table-action-column">
            <div class="action-button-container">
                <button type="button" class="btn btn-default ia-btn text-btn blue-btn put-aside-action" disabled="disabled">Отложить</button>
                <button type="button" class="btn btn-default ia-btn text-btn red-btn remove-action" disabled="disabled">Удалить</button>
            </div>
            <div class="coupon-container">

                <?
                if ($arParams["HIDE_COUPON"] != "Y")
                {
                    ?>

                    <div class="input-container">
                        <input type="text" name="coupon-code" value="" id="coupon" name="COUPON" placeholder="Код подарочного сертификата*" class="form-control coupon-code-input">
                    </div>
                    <button type="button" class="btn btn-default ia-btn text-btn yellow-btn apply-action" onclick="iaBasket.enterCoupon();" >Применить</button>

                    <?
                    if (!empty($arResult['COUPON_LIST']))
                    {
                        foreach ($arResult['COUPON_LIST'] as $oneCoupon)
                        {
                            $couponClass = 'disabled';
                            switch ($oneCoupon['STATUS'])
                            {
                                case DiscountCouponsManager::STATUS_NOT_FOUND:
                                case DiscountCouponsManager::STATUS_FREEZE:
                                    $couponClass = 'bad';
                                    break;
                                case DiscountCouponsManager::STATUS_APPLYED:
                                    $couponClass = 'good';
                                    break;
                            }
                            ?>

                            <div class="discount-container">
                                <div class="number grey-container gray-text <? echo $couponClass; ?>"><?=htmlspecialcharsbx($oneCoupon['COUPON']);?></div>
                                <input disabled readonly type="text" name="OLD_COUPON[]" value="<?=htmlspecialcharsbx($oneCoupon['COUPON']);?>" class="hidden <? echo $couponClass; ?>">
                                <button type="button" class="ia-close-btn" data-dismiss="modal" aria-label="Close" data-coupon="<? echo htmlspecialcharsbx($oneCoupon['COUPON']); ?>"
                                    onclick="iaBasket.deleteCoupon(this)">
                                  <span aria-hidden="true" class="clearfix ">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                  </span>
                                </button>
                                <div class="note">
                                    <?/*
                                    if (isset($oneCoupon['CHECK_CODE_TEXT']))
                                    {?>
                                        <span class="text gray-text">
                                            <?echo (is_array($oneCoupon['CHECK_CODE_TEXT']) ? implode('<br>', $oneCoupon['CHECK_CODE_TEXT']) : $oneCoupon['CHECK_CODE_TEXT']);?>
                                        </span>
                                    <?}*/
                                    ?>
                                </div>
                            </div>
                        <?
                        }
                        unset($couponClass, $oneCoupon);
                    }
                }
                else
                {
                    ?>&nbsp;<?
                }
                ?>
            </div>
        </div>
        <div class="col-xs-24 col-sm-12 col-md-12 col-lg-12 basket-action-column">
            <div class="total-container">
                <span class="total-title">Итого:</span>
                <span class="total-value yellow-text"><?=$arResult["allSum_FORMATED"]?> </span>
            </div>
            <div class="action-button-container">
                <button type="button" class="btn btn-default ia-btn text-btn gray-btn clear-btn" onclick="iaBasket.clearAll()">Очистить корзину</button>
                <button type="button" class="btn btn-default ia-btn text-btn yellow-btn checkout-btn" onclick="iaBasket.checkOut()">Оформить заказ</button>
            </div>
        </div>
    </div>
    <?/*?>
    <div class="general-text">
        Не следует, однако забывать, что постоянное информационно-пропагандистское обеспечение нашей деятельности требуют от
        нас анализа форм развития. Равным образом постоянное информационно-пропагандистское обеспечение нашей деятельности в
        значительной степени обуславливает создание направлений прогрессивного развития. С другой стороны реализация намеченных
        плановых заданий требуют определения и уточнения направлений прогрессивного развития.
    </div>
    <?*/?>
    <script>
        $(document).ready(function() {


            iaBasket = new InAnimeBasket(<? echo CUtil::PhpToJSObject($arJSParams, false, true);?>);

        });
    </script>
</div>
<?
else:
    ?>
    <div id="basket_items_list">
        <table>
            <tbody>
            <tr>
                <td style="text-align:center">
                    <div class=""><?=GetMessage("SALE_NO_ITEMS");?></div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
<?
endif;
?>