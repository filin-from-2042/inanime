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
$arJSParams = array();
?>
<script type="text/javascript">
    var ajaxPages = ajaxPages || {};
    $(document).on("click", ".button_submit-order", function() {
        submitForm("Y");
        return false;
    });
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
                    <div class="choise-user">
                        <div class="radioWrap">
                            <input type="radio" name="choise_user" id="choise_user_new" checked="checked" />
                            <label for="choise_user_new">
                                Я &mdash; новый пользователь на сайте (заказ без регистрации)
                            </label>
                        </div>
                        <? /*
                                        <div class="radioWrap">
                                            <input type="radio" name="choise_user" id="choise_user_old" />
                                            <label for="choise_user_old">
                                                У меня уже есть аккаунт тут
                                            </label>
                                        </div>
									*/ ?>
                        <? if ($_SESSION["USER_VALUES"]["order"]["ALLOW_AUTO_REGISTER"] == "Y") { ?>
                            <a href="javascript:void(0);" id="orderWithReg">Заказ с регистрацией</a>
                        <? } else { ?>
                            <a href="javascript:void(0);" id="orderWithoutReg">Заказ без регистрации</a>
                        <? } ?>
                    </div>
                <? }
                else
                {
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
                                <input type="text" name="<?=$arFields['FIO']['FIELD_NAME']?>" value="<?=$arFields['FIO']['VALUE']?>" placeholder="Анатлоий" class="form-control first-name-input">
                            </div>
                            <?/*?>
                            <div class="input-container">
                                <input type="text" name="second-name" value="" placeholder="Корягин" class="form-control second-name-input">
                            </div>
                            <?*/?>
                            <?/*?>
                            <div class="input-container">
                                <input type="text" name="<?=$arFields['PHONE']['FIELD_NAME']?>" value="<?=$arFields['PHONE']['VALUE']?>" placeholder="89238138434" class="form-control phone-input">
                            </div>
                            <?*/?>
                            <div class="input-container">
                                <input type="text" name="<?=$arFields['EMAIL']['FIELD_NAME']?>" value="<?=$arFields['EMAIL']['VALUE']?>" placeholder="anatoly@mail.ru" class="form-control email-input">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 address-column">
                            <?
                            $rsUser = CUser::GetList(($by="ID"), ($order="desc"), array("ID"=>$USER->GetID()),array("SELECT"=>array("UF_CUSTOMER_PROFILE")));
                            $userData = $rsUser->Fetch();
                            $currentProfileID = intval($userData['UF_CUSTOMER_PROFILE']);

                            $profiles = CSaleOrderUserProps::GetList(
                                array("DATE_UPDATE" => "DESC"),
                                array("USER_ID" => $USER->GetID())
                            );
                            $profilesCount = $profiles->SelectedRowsCount();

                            if($profilesCount>0)
                            {
                            ?>
                                <div class="radio-values-container">
                                <div class="radio-container">
                                    <?
                                    $currFullAdress='';
                                    $currLocationID = 0;
                                    $currPhone = '';
                                    $currZip = '';
                                    $addressCounter = 1;
                                    while ($profile = $profiles->Fetch())
                                    {
                                        $profileID = $profile['ID'];
                                        $profileVals = CSaleOrderUserPropsValue::GetList(array("ID" => "ASC"), Array("USER_PROPS_ID"=>$profileID));

                                        $location = 0;
                                        $address = '';
                                        $phone = '';
                                        while ($profileVal = $profileVals->Fetch())
                                        {
                                            if($profileVal['PROP_CODE']=='LOCATION') $location = $profileVal["VALUE"];
                                            if($profileVal['PROP_CODE']=='ADDRESS') $address = $profileVal["VALUE"];
                                            if($profileVal['PROP_CODE']=='PHONE') $phone = $profileVal["VALUE"];
                                        }

                                        $addressData = array();
                                        $addressData = parseFullAddress($address);
                                        $locationTextData=CSaleLocation::GetByID($location);
                                        $addressData['locationFullName'] = $locationTextData['CITY_NAME_LANG'].', '.$locationTextData['REGION_NAME_LANG'].', '.$locationTextData['COUNTRY_NAME_LANG'];

                                        $db_props = CSaleOrderProps::GetList(
                                            array("SORT" => "ASC"), array(
                                                "PERSON_TYPE_ID" => $arResult["USER_VALS"]["PERSON_TYPE_ID"], "CODE" => "LOCATION"
                                            ), false, false, array()
                                        );

                                        $addressData['currFullCityName'] = $locationTextData['CITY_NAME_LANG'];

                                        $addressData['locationID'] = $location;
                                        if ($props = $db_props->Fetch())
                                            $locationProp = $arResult["ORDER_PROP"]["USER_PROPS_Y"][$props["ID"]];
                                        $addressData['locationPropID'] = 'ORDER_PROP_'.$locationProp["ID"];

                                        $addressData['phone'] = $phone;

                                        $zipCode = $arFields['ZIP']["VALUE"];
                                        $db_zip = CSaleLocation::GetLocationZIP($location);
                                        if($zipProp = $db_zip->Fetch())
                                        {
                                            $zipCode = $zipProp['ZIP'];
                                        }
                                        $addressData['zipCode'] = $zipCode;

                                        $arJSParams['saleProfiles'][$profileID] = $addressData;

                                        if(intval($profileID)==intval($currentProfileID))
                                        {
                                            $currLocationID = $location;
                                            $currFullAdress = $address;
                                            $currPhone = $phone;
                                            $currZip = $zipCode;
                                        }
                                        ?>
                                    <div class="radio-button-container" id="<?=$profileID?>">
                                        <div class="ia-radio-button small<?=(intval($profileID)==intval($currentProfileID))?' active':''?>">
                                            <span class="value hidden"><?=$address?></span>
                                            <div class="radio-dot"></div>
                                        </div>
                                        <div class="button-title">Сохраненный адрес <?=$addressCounter?></div>
                                    </div>
                                        <?$addressCounter++;?>
                                    <?}?>
                                    <input type="hidden" name="addresProfile" value="<?=$currentProfileID?>" id="addreesProfile" />
                                    <input type="hidden" name="<?=$arFields['ADDRESS']['FIELD_NAME']?>" value="<?=$currFullAdress?>" id="<?=$arFields['ADDRESS']['FIELD_NAME'] ?>" class="ia-radio-value">
                                </div>
                                    <?
                                        $streetDataString = '';
                                        $houseNumberString = '';
                                        $apartmentString = '';
                                        $currAddressData = parseFullAddress($currFullAdress);
                                        $streetDataString = $currAddressData['street'];
                                        $houseNumberString = $currAddressData['house'];
                                        $apartmentString = $currAddressData['apartment'];
                                    ?>
                                <div class="values-container">
                                    <div class="address-container">

                                        <div class="input-container">
                                            <?/*
                                            $db_props = CSaleOrderProps::GetList(
                                                array("SORT" => "ASC"), array(
                                                    "PERSON_TYPE_ID" => $arResult["USER_VALS"]["PERSON_TYPE_ID"], "CODE" => "LOCATION"
                                                ), false, false, array()
                                            );

                                            if ($props = $db_props->Fetch())
                                                $locationProp = $arResult["ORDER_PROP"]["USER_PROPS_Y"][$props["ID"]];
                                            else
                                                $locationProp = false;
                                            ?>
                                            <?$APPLICATION->IncludeComponent("bitrix:sale.location.selector.search", "template1", Array(
                                                    "COMPONENT_TEMPLATE" => ".default",
                                                    "ID" => $currLocationID,	// ID местоположения
                                                    "CODE" => "",	// Символьный код местоположения
                                                    "INPUT_NAME" => $locationProp["FIELD_NAME"],	// Имя поля ввода
                                                    "PROVIDE_LINK_BY" => "id",	// Сохранять связь через
                                                    "JS_CONTROL_GLOBAL_ID" => "addressLocationSelector",
                                                    "JS_CALLBACK" => "",	// Javascript-функция обратного вызова
                                                    "AJAX_MODE" => "Y",
                                                    "FILTER_BY_SITE" => "Y",	// Фильтровать по сайту
                                                    "SHOW_DEFAULT_LOCATIONS" => "Y",	// Отображать местоположения по-умолчанию
                                                    "CACHE_TYPE" => "A",	// Тип кеширования
                                                    "CACHE_TIME" => "36000000",	// Время кеширования (сек.)
                                                    "FILTER_SITE_ID" => SITE_ID,	// Cайт
                                                    "INITIALIZE_BY_GLOBAL_EVENT" => "",	// Инициализировать компонент только при наступлении указанного javascript-события на объекте window.document
                                                    "SUPPRESS_ERRORS" => "N",	// Не показывать ошибки, если они возникли при загрузке компонента
                                                ),
                                                false
                                            );*/?>

                                            <?
                                            $db_props = CSaleOrderProps::GetList(
                                                array("SORT" => "ASC"), array(
                                                    "PERSON_TYPE_ID" => $arResult["USER_VALS"]["PERSON_TYPE_ID"], "CODE" => "LOCATION"
                                                ), false, false, array()
                                            );

                                            if ($props = $db_props->Fetch())
                                                $locationProp = $arResult["ORDER_PROP"]["USER_PROPS_Y"][$props["ID"]];
                                            else
                                                $locationProp = false;

                                            //var_dump(CSaleLocation::GetByID($locationProp['VALUE']));
                                            $zipCode = $arFields['ZIP']["VALUE"];
                                            $db_zip = CSaleLocation::GetLocationZIP($locationProp['VALUE']);
                                            if($zipProp = $db_zip->Fetch())
                                            {
                                                $zipCode = $zipProp['ZIP'];
                                            }

                                            $value = 0;
                                            if ($locationProp) {
                                                if (is_array($locationProp["VARIANTS"]) && count($locationProp["VARIANTS"]) > 0) {
                                                    foreach ($locationProp["VARIANTS"] as $arVariant) {
                                                        if ($arVariant["SELECTED"] == "Y") {
                                                            $value = $arVariant["ID"];
                                                            break;
                                                        }
                                                    }
                                                }
                                                $GLOBALS["APPLICATION"]->IncludeComponent(
                                                    "bitrix:sale.ajax.locations", "popup", array(
                                                        "AJAX_CALL" => "N",
                                                        "COUNTRY_INPUT_NAME" => "COUNTRY",
                                                        "REGION_INPUT_NAME" => "REGION",
                                                        "CITY_INPUT_NAME" => $locationProp["FIELD_NAME"],
                                                        "CITY_OUT_LOCATION" => "Y",
                                                        "LOCATION_VALUE" => $currLocationID,
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
                                                <input type="text" name="street" value="<?=str_replace('ул.','',$streetDataString)?>" placeholder="Улица" class="form-control street-input">
                                            </div>
                                            <div class="input-container">
                                                <input type="text" name="house-number" value="<?=str_replace('д.','',$houseNumberString)?>" placeholder="Дом" class="form-control house-number-input">
                                            </div>
                                            <div class="input-container apartment-container">
                                                <input type="text" name="apartment" value="<?=str_replace('кв.','',$apartmentString)?>" placeholder="Квартира" class="form-control apartment-input">
                                            </div>
                                            <div class="input-container">
                                                <input type="text" name="<?=$arFields['ZIP']["FIELD_NAME"]?>" value="<?= $currZip ?>" placeholder="Индекс" class="form-control zip-input">
                                            </div>
                                            <div>
                                                <div class="input-container">
                                                    <input type="text" name="<?=$arFields['PHONE']['FIELD_NAME']?>" value="<?=$currPhone?>" placeholder="Телефон" class="form-control phone-input">
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
                                            <input type="text" name="city" value="" placeholder="Город1" class="form-control city-input">
                                        </div>
                                        <div class="street-data-fields">
                                            <div class="input-container">
                                                <input type="text" name="street" value="" placeholder="Улица1" class="form-control street-input">
                                            </div>
                                            <div class="input-container">
                                                <input type="text" name="house-number" value="" placeholder="Дом1" class="form-control house-number-input">
                                            </div>
                                            <div class="input-container apartment-container">
                                                <input type="text" name="apartment" value="" placeholder="Квартира1" class="form-control apartment-input">
                                            </div>
                                            <div class="input-container">
                                                <input type="text" name="<?=$arFields['ZIP']["FIELD_NAME"]?>" value="" placeholder="Индекс" class="form-control zip-input">
                                            </div>
                                            <div>
                                                <div class="input-container">
                                                    <input type="text" name="<?=$arFields['PHONE']['FIELD_NAME']?>" value="" placeholder="Телефон" class="form-control phone-input">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?}?>
                        </div>
                    </div>
                    <?}?>
                    <div class="row radio-row">
                        <div class="col-md-offset-3 col-lg-offset-3 col-sm-12 col-md-8 col-lg-8 shipping-column">
                            <?include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/delivery.php");?>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 payment-column">
                            <?include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/paysystem.php");?>

                        </div>
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

                            var inAnimeOrderAjax = new InAnimeOrderAjax(<? echo CUtil::PhpToJSObject($arJSParams, false, true);?>);
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

                </div>

            </div>
    </div>

    <?}?>
<?}?>