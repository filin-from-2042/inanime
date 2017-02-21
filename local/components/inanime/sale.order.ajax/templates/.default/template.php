<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

use Bitrix\Sale\DiscountCouponsManager;
use Bitrix\Sale\Internals;

if ($USER->IsAuthorized() || $arParams["ALLOW_AUTO_REGISTER"] == "Y") {
    if ($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y") {
        if (strlen($arResult["REDIRECT_URL"]) > 0) {
            $APPLICATION->RestartBuffer();
            ?>
            <script type="text/javascript">
                window.top.location.href = '<?= CUtil::JSEscape($arResult["REDIRECT_URL"]) ?>';
            </script>
            <?
            die();
        }
    }
}

function parseFullAddress($fullAdress)
{
    $fullAddressArray = explode(',',$fullAdress);
    $resultArray = array('street'=>'', 'house'=>'', 'apartment'=>'');
    if(count($fullAddressArray)>0)
    {
        if(array_key_exists(0,$fullAddressArray)) $resultArray['street'] = $fullAddressArray[0];
        if(array_key_exists(1,$fullAddressArray)) $resultArray['house'] = $fullAddressArray[1];
        if(array_key_exists(2,$fullAddressArray)) $resultArray['apartment'] = $fullAddressArray[2];
    }
    return $resultArray;
}

//$APPLICATION->SetAdditionalCSS($templateFolder . "/style_cart.css");
$APPLICATION->SetAdditionalCSS($templateFolder . "/style.css");
$APPLICATION->AddHeadScript($templateFolder . "/script.js");

CJSCore::Init(array('fx', 'popup', 'window', 'ajax'));
?>
<script type="text/javascript">
    var ajaxPages = ajaxPages || {};
    $(document).on("click", ".button_submit-order", function() {
        submitForm("Y");
        return false;
    });
    $(document).on("click", "#orderWithoutReg", function() {
        order_values = {};
        order_values["page"] = "order";
        order_values["ALLOW_AUTO_REGISTER"] = "Y";
        setSessionValues(order_values, function() {location.reload()});
    });

    $(document).on("click", "#orderWithReg", function() {
        order_values = {};
        order_values["page"] = "order";
        order_values["ALLOW_AUTO_REGISTER"] = "N";
        setSessionValues(order_values, function() {location.reload()});
    });
    function setSessionValues(values, callback) {

        $.ajax({
            url: "/include/session.php",
            type: 'post',
            contentType: "application/x-www-form-urlencoded",
            dataType: "json",
            data: {values: values},
            success: function(data, status) {
                if (data.success)
                    if (typeof callback === 'function')
                        callback();
            },
            error: function(xhr, desc, err) {

            }
        });

    }
</script>
<a name="order_form"></a>
<div id="order_form_div" class="order-checkout">
    <NOSCRIPT>
        <div class="errortext"><?= GetMessage("SOA_NO_JS") ?></div>
    </NOSCRIPT>
    <?
    if (!function_exists("getColumnName")) {

        function getColumnName($arHeader) {
            return (strlen($arHeader["name"]) > 0) ? $arHeader["name"] : GetMessage("SALE_" . $arHeader["id"]);
        }

    }

    if (!function_exists("cmpBySort")) {

        function cmpBySort($array1, $array2) {
            if (!isset($array1["SORT"]) || !isset($array2["SORT"]))
                return -1;
            if ($array1["SORT"] > $array2["SORT"])
                return 1;
            if ($array1["SORT"] < $array2["SORT"])
                return -1;
            if ($array1["SORT"] == $array2["SORT"])
                return 0;
        }

    }
    ?>
<?
if (!$USER->IsAuthorized() && $arParams["ALLOW_AUTO_REGISTER"] == "N") {

    include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/auth.php");
}
else
{
    if ($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y") {
        if (strlen($arResult["REDIRECT_URL"]) == 0) {
            include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/confirm.php");
        }
    } else {
    ?>
    <script type="text/javascript">
        function submitForm(val)
        {
            if (val != 'Y')
                BX('confirmorder').value = 'N';

            var orderForm = BX('ORDER_FORM');

            BX.ajax.submitComponentForm(orderForm, 'order_form_content', true);
            BX.submit(orderForm);
            BX.closeWait();
            ajaxPages["order_page_wrapper"] = "N";
            ajaxPages["basket_form_container"] = "N";
            ajaxPages["ajax-basket-line"] = "N";
            return true;
        }

        function SetContact(profileId)
        {
            BX("profile_change").value = "Y";
            submitForm();
        }
    </script>
            <div class="order-drawing-up">
                <div class="order-drawing-up-header">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:breadcrumb",
                        "catalog-chain",
                        Array(
                            "START_FROM" => "0",
                            "PATH" => "",
                            "SITE_ID" => SITE_ID
                        )
                    );
                    ?>
                    <h1 class="ia-page-title">Оформление заказа</h1>
                </div>
                <div class="container">
                <div class="row">
                    <div class="col-md-offset-3 col-lg-offset-3" >
                        <h2>Информация о получателе</h2>
                    </div>
                </div>

                <?
                if ($_POST["is_ajax_post"] != "Y")
                {?>
                    <form action="<?= $APPLICATION->GetCurPage(); ?>" method="POST" name="ORDER_FORM" id="ORDER_FORM" class="form form_order" enctype="multipart/form-data">
                        <?= bitrix_sessid_post() ?>
                        <div id="order_form_content">
                <?
                } else {
                    $APPLICATION->RestartBuffer();
                    $APPLICATION->ShowHead();
                }
                include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/person_type.php");
                ?>
                <?
                if (!$USER->IsAuthorized()) {
                    ?>
                        <div class="row new-user-radio">
                            <div class="col-md-offset-3 col-lg-offset-3  col-sm-24 col-md-21 col-lg-21">
                                <div class="radio-button-container user-type">
                                    <div class="ia-radio-button small active" data-contentid="user-new-data">
                                        <span class="value hidden">choise_user</span>
                                        <div class="radio-dot"></div>
                                    </div>
                                    <div class="button-title">Я &mdash; новый пользователь</div>
                                </div>

                                <? if ($_SESSION["USER_VALUES"]["order"]["ALLOW_AUTO_REGISTER"] == "Y") { ?>
                                    <a href="javascript:void(0);" class="yellow-text-underline" id="orderWithReg">Заказ с регистрацией</a>
                                <? } else { ?>
                                    <a href="javascript:void(0);"  class="yellow-text-underline" id="orderWithoutReg">Заказ без регистрации</a>
                                <? } ?>

                            </div>
                        </div>
                <? }

                  // поля в удободоступном виде $arFields['имя поля']
                    $arFields = array();
                    foreach($arResult["ORDER_PROP"]["USER_PROPS_Y"] as $arProperties)
                    {
                        if ($arProperties["TYPE"] == "LOCATION")continue;
                        $arFields[$arProperties["CODE"]] = $arProperties;
                    }
                    ?>
                    <div class="row">
                        <div class="col-md-offset-3 col-lg-offset-3 col-sm-12 col-md-8 col-lg-8 fio-column">
                            <div class="input-container">
                                <input type="text" name="<?=$arFields['FIO']['FIELD_NAME']?>" value="<?=$arFields['FIO']['VALUE']?>" placeholder="Имя" class="form-control first-name-input">
                            </div>
                            <div class="input-container">
                                <input type="text" name="<?=$arFields['EMAIL']['FIELD_NAME']?>" value="<?=$arFields['EMAIL']['VALUE']?>" placeholder="Email" class="form-control email-input">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 address-column">
                            <?
                            // профили пользователей
                            if(isset($arResult['USER_PROFILES']) && count($arResult['USER_PROFILES'])>0)
                            {
                                $currentUserProfileID = intval($arResult['USER_HELPFULL_VALUES']['CURRENT_PROFILE_ID']);
                            ?>
                                <div class="radio-values-container">
                                <div class="radio-container">
                                    <?
                                    $addressCounter = 1;
                                    foreach($arResult['USER_PROFILES'] as $profileID => $profile)
                                    {?>
                                        <div class="radio-button-container" id="<?=$profileID?>">
                                            <div class="ia-radio-button small<?=(intval($profileID)==$currentUserProfileID)?' active':''?>">
                                                <span class="value hidden"><?=$profile['PROPS']['ADDRESS']?></span>
                                                <div class="radio-dot"></div>
                                            </div>
                                            <div class="button-title">Сохраненный адрес <?=$addressCounter?></div>
                                        </div>
                                        <?$addressCounter++;?>
                                    <?}?>
                                    <?// поле для хранение ид выбранного профайла для дальнейшего сохранения при изменении активного профиля?>
                                    <input type="hidden" name="addresProfile" value="<?=$arResult['USER_HELPFULL_VALUES']['CURRENT_PROFILE_ID']?>" id="addreesProfile" />
                                    <input type="hidden" name="<?=$arFields['ADDRESS']['FIELD_NAME']?>"
                                           value="<?=($_POST["is_ajax_post"] == "Y") ? $arFields['ADDRESS']['VALUE'] : $arResult['USER_PROFILES'][$currentUserProfileID]['PROPS']['ADDRESS']?>"
                                           id="<?=$arFields['ADDRESS']['FIELD_NAME'] ?>" class="ia-radio-value full-address">
                                </div>
                                <div class="values-container">
                                    <div class="address-container">

                                        <div class="input-container">
                                            <?
                                            if (isset($arResult['USER_HELPFULL_VALUES']) && isset($arResult['USER_HELPFULL_VALUES']['LOCATION_PROP']) && $arResult['USER_HELPFULL_VALUES']['LOCATION_PROP'])
                                            {
                                                $locationProp = $arResult['USER_HELPFULL_VALUES']['LOCATION_PROP'];
                                                $GLOBALS["APPLICATION"]->IncludeComponent(
                                                    "bitrix:sale.ajax.locations", "popup", array(
                                                        "AJAX_CALL" => "N",
                                                        "COUNTRY_INPUT_NAME" => "COUNTRY",
                                                        "REGION_INPUT_NAME" => "REGION",
                                                        "CITY_INPUT_NAME" => $locationProp["FIELD_NAME"],
                                                        "CITY_OUT_LOCATION" => "Y",
                                                        "LOCATION_VALUE" => ($_POST["is_ajax_post"] == "Y") ? $arResult['TEMPORARY_FIELDS_DATA'][$locationProp['FIELD_NAME']] : $arResult['USER_PROFILES'][$currentUserProfileID]['PROPS']['LOCATION'],
                                                        "ORDER_PROPS_ID" => $locationProp["ID"],
                                                        "ONCITYCHANGE" => ($locationProp["IS_LOCATION"] == "Y" || $locationProp["IS_LOCATION4TAX"] == "Y") ? "submitForm()" : "",
                                                        "SIZE1" => $locationProp["SIZE1"],
                                                    ), null, array('HIDE_ICONS' => 'Y')
                                                );
                                            }
                                            ?>
                                        </div>
                                        <div class="street-data-fields">
                                            <div class="input-container">
                                                <input type="text" name="street" value="<?if($_POST["is_ajax_post"] == "Y"){
                                                                                            echo ($arResult['TEMPORARY_FIELDS_DATA'] && $arResult['TEMPORARY_FIELDS_DATA']['street'])?$arResult['TEMPORARY_FIELDS_DATA']['street']:'';
                                                                                        }else{
                                                                                            echo str_replace('ул.','',$arResult['USER_PROFILES'][$currentUserProfileID]['parsedAddressText']['street']);
                                                                                        }
                                                                                        ?>"
                                                       placeholder="Улица" class="form-control street-input">
                                            </div>
                                            <div class="input-container">
                                                <input type="text" name="house-number" value="<?if($_POST["is_ajax_post"] == "Y"){
                                                                                                    echo ($arResult['TEMPORARY_FIELDS_DATA'] && $arResult['TEMPORARY_FIELDS_DATA']['house-number'])?$arResult['TEMPORARY_FIELDS_DATA']['house-number']:'';
                                                                                                }else{
                                                                                                    echo str_replace('д.','',$arResult['USER_PROFILES'][$currentUserProfileID]['parsedAddressText']['house']);
                                                                                                }
                                                                                                ?>"
                                                       placeholder="Дом" class="form-control house-number-input">
                                            </div>
                                            <div class="input-container apartment-container">
                                                <input type="text" name="apartment" value="<?if($_POST["is_ajax_post"] == "Y"){
                                                                                                echo ($arResult['TEMPORARY_FIELDS_DATA'] && $arResult['TEMPORARY_FIELDS_DATA']['apartment'])?$arResult['TEMPORARY_FIELDS_DATA']['apartment']:'';
                                                                                            }else{
                                                                                                echo str_replace('кв.','',$arResult['USER_PROFILES'][$currentUserProfileID]['parsedAddressText']['apartment']);
                                                                                            }?>"
                                                       placeholder="Квартира" class="form-control apartment-input">
                                            </div>
                                            <div class="input-container">
                                                <input type="text" name="<?=$arFields['ZIP']["FIELD_NAME"]?>" value="<?=($_POST["is_ajax_post"] == "Y") ? $arFields['ZIP']['VALUE'] : $arResult['USER_PROFILES'][$currentUserProfileID]['PROPS']['ZIP_CODE'] ?>" placeholder="Индекс" class="form-control zip-input">
                                            </div>
                                            <div>
                                                <div class="input-container">
                                                    <input type="text" name="<?=$arFields['PHONE']['FIELD_NAME']?>" value="<?=($_POST["is_ajax_post"] == "Y") ? $arFields['PHONE']['VALUE'] :$arResult['USER_PROFILES'][$currentUserProfileID]['PROPS']['PHONE']?>" placeholder="Телефон" class="form-control phone-input">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?}
                            else
                            {?>
                                <div class="values-container">
                                    <div class="address-container">
                                        <input type="hidden" name="<?=$arFields['ADDRESS']['FIELD_NAME']?>" value="<?=$arFields['ADDRESS']["VALUE"]?>" id="<?=$arFields['ADDRESS']['FIELD_NAME'] ?>" class="full-address" />
                                        <div class="input-container">
                                            <?
                                            if (isset($arResult['USER_HELPFULL_VALUES']) && isset($arResult['USER_HELPFULL_VALUES']['LOCATION_PROP']) && $arResult['USER_HELPFULL_VALUES']['LOCATION_PROP'])
                                            {
                                                $locationProp = $arResult['USER_HELPFULL_VALUES']['LOCATION_PROP'];
                                                $GLOBALS["APPLICATION"]->IncludeComponent(
                                                    "bitrix:sale.ajax.locations", "popup", array(
                                                        "AJAX_CALL" => "N",
                                                        "COUNTRY_INPUT_NAME" => "COUNTRY",
                                                        "REGION_INPUT_NAME" => "REGION",
                                                        "CITY_INPUT_NAME" => $locationProp["FIELD_NAME"],
                                                        "CITY_OUT_LOCATION" => "Y",
                                                        "LOCATION_VALUE" => ($_POST["is_ajax_post"] == "Y") ? $arResult['TEMPORARY_FIELDS_DATA'][$locationProp['FIELD_NAME']] : $_SESSION['USER_VALUES']['CURRENT_LOCATION_DATA']['ID'],
                                                        "ORDER_PROPS_ID" => $locationProp["ID"],
                                                        "ONCITYCHANGE" => ($locationProp["IS_LOCATION"] == "Y" || $locationProp["IS_LOCATION4TAX"] == "Y") ? "submitForm()" : "",
                                                        "SIZE1" => $locationProp["SIZE1"],
                                                    ), null, array('HIDE_ICONS' => 'Y')
                                                );
                                            }
                                            ?>
                                        </div>
                                        <div class="street-data-fields">
                                            <div class="input-container">
                                                <input type="text" name="street" value="<?=($arResult['TEMPORARY_FIELDS_DATA'] && $arResult['TEMPORARY_FIELDS_DATA']['street'])?$arResult['TEMPORARY_FIELDS_DATA']['street']:''?>" placeholder="Улица1" class="form-control street-input">
                                            </div>
                                            <div class="input-container">
                                                <input type="text" name="house-number" value="<?=($arResult['TEMPORARY_FIELDS_DATA'] && $arResult['TEMPORARY_FIELDS_DATA']['house-number'])?$arResult['TEMPORARY_FIELDS_DATA']['house-number']:''?>" placeholder="Дом1" class="form-control house-number-input">
                                            </div>
                                            <div class="input-container apartment-container">
                                                <input type="text" name="apartment" value="<?=($arResult['TEMPORARY_FIELDS_DATA'] && $arResult['TEMPORARY_FIELDS_DATA']['apartment'])?$arResult['TEMPORARY_FIELDS_DATA']['apartment']:''?>" placeholder="Квартира1" class="form-control apartment-input">
                                            </div>
                                            <div class="input-container">
                                                <input type="text" name="<?=$arFields['ZIP']["FIELD_NAME"]?>"
                                                       value="<?
                                                                if($_POST["is_ajax_post"] == "Y")
                                                                {
                                                                    echo $arFields['ZIP']['VALUE'];
                                                                }
                                                                else
                                                                {
                                                                    $db_zip = CSaleLocation::GetLocationZIP($_SESSION['USER_VALUES']['CURRENT_LOCATION_DATA']['ID']);
                                                                    if($zipProp = $db_zip->Fetch())
                                                                        echo $zipProp['ZIP'];
                                                                }
                                                                ?>"
                                                       placeholder="Индекс" class="form-control zip-input">
                                            </div>
                                            <div>
                                                <div class="input-container">
                                                    <input type="text" name="<?=$arFields['PHONE']['FIELD_NAME']?>" value="<?=($_POST["is_ajax_post"] == "Y") ? $arFields['PHONE']['VALUE'] : ''?>" placeholder="Телефон" class="form-control phone-input">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?}?>
                        </div>
                    </div>
                    <div class="row radio-row">
                        <div class="col-md-offset-3 col-lg-offset-3 col-sm-12 col-md-8 col-lg-8 shipping-column">
                            <?include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/delivery.php");?>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 payment-column">
                            <?include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/paysystem.php");?>

                        </div>
                        <script>
                            $(document).ready(function(){
                                $('body').on('click', '.shipping-column .ia-radio-button,.shipping-column .radio-button-container .button-title', function(){
                                    if ($(this).hasClass('ia-radio-button')) var radioButton = $(this);
                                    else var radioButton = $(this).closest('.radio-button-container').find('.ia-radio-button');
                                    radioButton.closest('.radio-container').find('input.ia-radio-value').val(radioButton.find('span.value.hidden').text());
                                    radioButton.closest('.radio-container').find('.ia-radio-button').removeClass('active');
                                    radioButton.addClass('active');

                                    submitForm();
                                });
                            });
                        </script>
                    </div>
                    <div class="row order-comment-row">
                        <div class="col-md-offset-3 col-lg-offset-3 col-sm-24 col-md-21 col-lg-19">
                            <div class="input-container order-comment-container">
                                <textarea  name="ORDER_DESCRIPTION" id="ORDER_DESCRIPTION" placeholder="Комментарий" class="form-control order-comment-textarea"><? //$arResult["USER_VALS"]["ORDER_DESCRIPTION"] ?></textarea>
                            </div>
                        </div>
                    </div>
                <?if(count($arResult['arCoupons'])){?>
                    <div class="row discounts-coupon-row">
                        <div class="col-md-offset-3 col-lg-offset-3 col-sm-24 col-md-21 col-lg-21 discounts-column">
                            <h3>Информация о купонах и скидках</h3>
                        </div>
                            <?
                                $couponCounter = 1;
                                foreach($arResult['arCoupons'] as $coupon)
                                {
                                    $couponClass = 'disabled';
                                    switch ($coupon['STATUS'])
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
                                    <?if($couponCounter%2>0){?>
                                    <div class="col-md-offset-3 col-lg-offset-3 col-sm-12 col-md-8 col-lg-8 discounts-column">
                                    <?}else{?>
                                    <div class="col-sm-12 col-md-10 col-lg-10 coupon-column">
                                    <?}?>
                                        <div class="coupon-container">
                                            <div class="number grey-container gray-text <?=$couponClass?>"><?=$coupon['COUPON']?></div>
                                            <input type="hidden" name="removeCoupon" value="<?=$coupon['COUPON']?>" disabled="disabled">
                                            <button type="submit" class="ia-close-btn" data-dismiss="modal" aria-label="Close" name="removeCoupon" value="<?=$coupon['COUPON']?>"
                                                    onclick="$(this).prev().removeAttr('disabled');submitForm();">
                                              <span aria-hidden="true" class="clearfix ">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                              </span>
                                            </button>
                                            <div class="note">
                                                <span class="text gray-text"><?=$coupon['DISCOUNT_NAME']?></span>
                                                <?

                                                if($coupon['MODULE']=='sale')
                                                {
                                                    if (CModule::IncludeModule("sale"))
                                                    {
                                                        $couponData = Internals\DiscountCouponTable::getList(array(
                                                            'select' => array('*'),
                                                            'filter' => array('=ID' => $coupon['ID'])
                                                        ))->fetch();

                                                        $couponType = '';
                                                        if($couponData['TYPE'] == Internals\DiscountCouponTable::TYPE_BASKET_ROW)
                                                            $couponType = '1 позиция';
                                                        else if($couponData['TYPE'] == Internals\DiscountCouponTable::TYPE_ONE_ORDER)
                                                            $couponType = '1 заказ';
                                                        else if($couponData['TYPE'] == Internals\DiscountCouponTable::TYPE_MULTI_ORDER)
                                                            $couponType = 'многоразовый';
                                                    }
                                                }
                                                elseif($coupon['MODULE']=='catalog')
                                                {
                                                    if (CModule::IncludeModule("catalog"))
                                                    {
                                                        $arFilter = array('COUPON' => $coupon['COUPON']);
                                                        $dbCoupon = CCatalogDiscountCoupon::GetList (array(), $arFilter);
                                                        if($arCoupon = $dbCoupon->Fetch())
                                                        {
                                                            $couponType = '';
                                                            if($arCoupon['ONE_TIME']=='Y')
                                                                $couponType='1 позиция';
                                                            elseif($arCoupon['ONE_TIME']=='O')
                                                                $couponType='1 заказ';
                                                            else
                                                                $couponType='многоразовый';
                                                        }
                                                    }
                                                }
                                                echo '<span class="period gray-text">Тип купона: '.$couponType.' </span>';

                                                if($coupon['DISCOUNT_ACTIVE_FROM'] && $coupon['DISCOUNT_ACTIVE_TO'])
                                                {
                                                    function plural_form($number, $after) {
                                                        $cases = array (2, 0, 1, 1, 1, 2);
                                                        return $number.' '.$after[ ($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)] ];
                                                    }

                                                    $days = ((strtotime($coupon['DISCOUNT_ACTIVE_TO'])-strtotime($coupon['DISCOUNT_ACTIVE_FROM']))/86400);
                                                    echo '<span class="period gray-text">Срок действия: '.plural_form($days,array('день','дня','дней')).' </span>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                <?$couponCounter++;
                                }
                            ?>
                    </div>
            <?}?>

                    <div class="row costs-button-row">
                        <div class="col-md-offset-11 col-sm-24 col-md-10 col-lg-10">
                            <div class="row costs-row">
                                <div class="col-xs-16 col-sm-offset-5 col-md-offset-0 col-sm-11 col-md-17 col-lg-16 text-column">

                                    <div class="order-cost clearfix">
                                        <span class="text">Стоимость товаров:</span>
                                    </div>
                                    <div class="all-order-cost clearfix">
                                        <span class="text hidden-xs">Общая стоимость заказа(с учетом доставки):</span>
                                        <span class="text hidden-sm hidden-md hidden-lg">Общая стоимость заказа:</span>
                                    </div>
                                </div>
                                <div class="col-xs-8 col-sm-5 col-md-7 col-lg-8 cost-column">

                                    <div class="order-cost clearfix">
                                        <span class="cost"><?=$arResult["ORDER_PRICE_FORMATED"]?></span>
                                    </div>
                                    <div class="all-order-cost clearfix">
                                        <span class="cost"><?=$arResult['ORDER_TOTAL_PRICE_FORMATED']?></span>
                                    </div>
                                </div>
                            </div>

                            <?
                            if (!empty($arResult["ERROR"]) && $arResult["USER_VALS"]["FINAL_STEP"] == "Y") {
                                echo '<div class="error-container">';
                                foreach ($arResult["ERROR"] as $errorKey => $v)
                                    // if (!in_array($errorKey, array("ZIP", "COMPANY", "CONTACT_PERSON", "FIO", "EMAIL", "PHONE", "COMPANY_ADR", "FIO", "FAX", "INN", "KPP", "ADDRESS", "ACCEPT_NEW")))
                                    echo ShowError($v);
                                echo '</div>';
                            }
                            ?>

                            <div class="row costs-row">
                                <div class="col-xs-16 col-sm-offset-5 col-md-offset-0 col-sm-11 col-md-17 col-lg-16 text-column">
                                    <div class="button-container">
                                        <button type="submit" class="btn btn-default ia-btn text-btn yellow-btn button_submit-order">Оплатить</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        $(document).ready(function(){
                            var inAnimeOrderAjax = new InAnimeOrderAjax(<? echo CUtil::PhpToJSObject($arResult['arJSParams'], false, true);?>);
                            $('body').on('change','.order-drawing-up .address-container .form-control', inAnimeOrderAjax.changeAddressData);

                            $('body').on('click','.radio-values-container .ia-radio-button,.radio-values-container .radio-button-container .button-title',
                                function(event){
                                inAnimeOrderAjax.changeSaleProfile(event);
                            });
                        });
                    </script>

                <?
                if ($_POST["is_ajax_post"] != "Y") {
                ?>
                        </div>

                        <input type="hidden" name="confirmorder" id="confirmorder" value="Y">
                        <input type="hidden" name="profile_change" id="profile_change" value="N">
                        <input type="hidden" name="is_ajax_post" id="is_ajax_post" value="Y">

                    </form>
                    <script type="text/javascript">

                        ajaxPages["order_page_wrapper"] = "N";
                        ajaxPages["basket_form_container"] = "N";
                        ajaxPages["ajax-basket-line"] = "N";
                    </script>


                    <?
                } else {
                    //$APPLICATION->ShowHead();
                ?>
                    <script type="text/javascript">
                        top.BX('confirmorder').value = 'Y';
                        top.BX('profile_change').value = 'N';
                    </script>

                    <?
                    die();
                }
                    ?>

                <hr class="general-content-bottom-line">
                </div>
            </div>
    </div>

    <?}?>
<?}?>