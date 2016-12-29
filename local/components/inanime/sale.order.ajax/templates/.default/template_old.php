<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
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

$APPLICATION->SetAdditionalCSS($templateFolder . "/style_cart.css");
$APPLICATION->SetAdditionalCSS($templateFolder . "/style.css");

CJSCore::Init(array('fx', 'popup', 'window', 'ajax'));
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

    <div class="bx_order_make">
        <?
        if (!$USER->IsAuthorized() && $arParams["ALLOW_AUTO_REGISTER"] == "N") {
            /* if (!empty($arResult["ERROR"])) {
              foreach ($arResult["ERROR"] as $v)
              echo ShowError($v);
              } elseif (!empty($arResult["OK_MESSAGE"])) {
              foreach ($arResult["OK_MESSAGE"] as $v)
              echo ShowNote($v);
              } */
            include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/auth.php");
        } else {
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
                <?
                if ($_POST["is_ajax_post"] != "Y") {
                    ?>
                <form action="<?= $APPLICATION->GetCurPage(); ?>" method="POST" name="ORDER_FORM" id="ORDER_FORM" class="form form_order" enctype="multipart/form-data">
                    <?= bitrix_sessid_post() ?>
                        <div id="order_form_content">
                            <?
                        } else {
                            $APPLICATION->RestartBuffer();
                        }
                        if (!empty($arResult["ERROR"]) && $arResult["USER_VALS"]["FINAL_STEP"] == "Y") {
                            foreach ($arResult["ERROR"] as $errorKey => $v)
                                if (!in_array($errorKey, array("ZIP", "COMPANY", "CONTACT_PERSON", "FIO", "EMAIL", "PHONE", "COMPANY_ADR", "FIO", "FAX", "INN", "KPP", "ADDRESS", "ACCEPT_NEW")))
                                    echo ShowError($v);
                        }

                        include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/person_type.php");
                        ?>
                        <div class="order-info group">
                            <div class="bg"></div>
                            <div class="banner banner_basket">
                                <?
                                $APPLICATION->IncludeComponent("bitrix:advertising.banner", "", Array(
                                    "TYPE" => "order_page",
                                    "CACHE_TYPE" => "A",
                                    "NOINDEX" => "Y",
                                    "CACHE_TIME" => "3600"
                                        )
                                );
                                ?>
                            </div>

                            <div class="d">
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
                                ?>
                                <div class="form__el form__el_city">
                                    <div class="label">Город доставки</div>
                                    <div class="w">
                                        <div class="inputWrap">
                                            <?
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
                                                    "LOCATION_VALUE" => $value,
                                                    "ORDER_PROPS_ID" => $locationProp["ID"],
                                                    "ONCITYCHANGE" => ($locationProp["IS_LOCATION"] == "Y" || $locationProp["IS_LOCATION4TAX"] == "Y") ? "submitForm()" : "",
                                                    "SIZE1" => $locationProp["SIZE1"],
                                                        ), null, array('HIDE_ICONS' => 'Y')
                                                );
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <?
                                if ($arParams["DELIVERY_TO_PAYSYSTEM"] == "p2d") {
                                    include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/paysystem.php");
                                    include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/delivery.php");
                                } else {
                                    include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/delivery.php");
                                    include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/paysystem.php");
                                }
                                ?>

                                <? if ($arResult["allVATSum"] > 0) { ?>
                                    <div class="form__el ">
                                        <div class="label">В том числе НДС:</div>
                                        <div class="w">
                                            <label><?= $arResult["allVATSum"] ?> P</label>
                                        </div>
                                    </div>
                                <? } ?>
                                <?
                                $discountCounter = 0;
                                $discountTotalPercent = 0;
                                foreach ($arResult["BASKET_ITEMS"] as $item) {
                                    $discountCounter++;
                                    $discountTotalPercent+=$item["DISCOUNT_PRICE_PERCENT"];
                                }
                                $averageDiscountPercent=(round($discountTotalPercent/$discountCounter*100)/100);
                                ?>
                                <div class="form__el form__el_coupon">
                                    <div class="label">Код купона</div>
                                    <div class="w">
                                        <div class="inputWrap">
                                            <input id="orderCouponInput" type="text" class="form__text" name="coupon_code" value="<?= $GLOBALS["_SESSION"]["CATALOG_CURRENT_COUPON"]; ?>">
                                        </div>
                                    </div>
                                </div>
                                <? if ($arResult["ORDER_WEIGHT"] > 0) { ?>
                                    <div class="form__el form__el_total">
                                        <div class="label"><?= GetMessage("SOA_TEMPL_SUM_WEIGHT_SUM") ?></div>
                                        <div class="w">
                                            <?= $arResult["ORDER_WEIGHT_FORMATED"] ?>
                                        </div>
                                    </div>
                                <? } ?>
<?if ($averageDiscountPercent>0) {?>
                                <div class="form__el form__el_total">
                                        <div class="label">Скидка</div>
                                        <div class="w">

                                            <div class="total"><?= $averageDiscountPercent.'%' ?></div>
                                        </div>
                                    </div>
<?}?>
                                <? if ($arResult["ORDER_PRICE"] > 0 && $arResult["ORDER_PRICE_FORMATED"] != $arResult["ORDER_TOTAL_PRICE_FORMATED"]) { ?>
                                    <div class="form__el form__el_total">
                                        <div class="label"><?= GetMessage("SOA_TEMPL_SUM_SUMMARY") ?></div>
                                        <div class="w">

                                            <div class="total"><?= $arResult["ORDER_PRICE_FORMATED"] ?></div>
                                        </div>
                                    </div>
                                <? } ?>

                                <? if (doubleval($arResult["DISCOUNT_PRICE"]) > 0) { ?>
                                    <div class="form__el form__el_total">
                                        <div class="label"><?= GetMessage("SOA_TEMPL_SUM_DISCOUNT") ?><? if (strLen($arResult["DISCOUNT_PERCENT_FORMATED"]) > 0): ?> (<? echo $arResult["DISCOUNT_PERCENT_FORMATED"]; ?>)<? endif; ?>:</div>
                                        <div class="w">

                                            <div class="total"><?= $arResult["DELIVERY_PRICE_FORMATED"] ?></div>
                                        </div>
                                    </div>
                                <? } ?>

                                <? if (doubleval($arResult["DELIVERY_PRICE"]) > 0) { ?>
                                    <div class="form__el form__el_total">
                                        <div class="label"><?= GetMessage("SOA_TEMPL_SUM_DELIVERY") ?></div>
                                        <div class="w">

                                            <div class="total"><?= $arResult["DELIVERY_PRICE_FORMATED"] ?></div>
                                        </div>
                                    </div>
                                <? } ?>

                                <?
                                if ($bUseDiscount) {
                                    ?>
                                    <div class="form__el form__el_total">
                                        <div class="label"><?= GetMessage("SOA_TEMPL_SUM_IT"); ?></div>
                                        <div class="w">

                                            <div class="total"><?= $arResult["ORDER_TOTAL_PRICE_FORMATED"]; ?></div>
                                        </div>
                                    </div>
                                    <div class="form__el form__el_total">
                                        <div class="label"></div>
                                        <div class="w">

                                            <div class="total"><?= $arResult["PRICE_WITHOUT_DISCOUNT_FORMATED"] ?></div>
                                        </div>
                                    </div>
                                    <?
                                } else {
                                    ?>
                                    <div class="form__el form__el_total">
                                        <div class="label"><?= GetMessage("SOA_TEMPL_SUM_IT") ?></div>
                                        <div class="w">

                                            <div class="total"><?= $arResult["ORDER_TOTAL_PRICE_FORMATED"] ?></div>
                                        </div>
                                    </div>
                                <? } ?>
                            </div>
                        </div>


                        <div class="user-info">
			    <? if ($USER->IsAuthorized() || true) { ?>
                            <div class="title"><span>Данные для заказа</span></div>
			    <?}?>
                            <?
                            // include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/person_type.php");
                            ?>
                            <? global $USER; ?>
                            <div class="form-wrapper<? if ($USER->IsAuthorized() && $arResult["USER_VALS"]["PERSON_TYPE_ID"] == 1) echo " personal-full-width-form"; elseif ($USER->IsAuthorized() && $arResult["USER_VALS"]["PERSON_TYPE_ID"] == 2) echo " company-full-width-form" ?>">
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
                                <? } ?>
                                <? include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/props_format.php"); ?>
                                <div class="user-info__form">
                                    <div class="overlay">
                                        <span class="icon icon_preloader"></span>
                                    </div>

                                    <div class="part part_user-new" id="part_user-new">
                                        <div class="col col_left">
										
										
										    <? $arAllPropCode = array("ZIP", "COMPANY", "CONTACT_PERSON", "FIO", "EMAIL", "PHONE", "COMPANY_ADR", "FAX", "INN", "KPP", "ADDRESS"); ?>
                                            <? $arErrors = $arResult["ERROR"]; ?>
                                            <? foreach ($arErrors as $key => $error): ?>
                                                <? if(array_search($key, $arAllPropCode)): ?>
                                                    <? unset($arErrors[$key]); ?>
                                                <? endif; ?>
                                            <? endforeach; ?>
                                            <? if(count($arErrors)): ?>
                                                <? $error = reset($arErrors); ?>
                                                <div class="form__error" title='<?= $error; ?>'><i class="fa fa-exclamation-triangle"></i> <span class="no-js-text"><?= $error; ?></span></div>
                                            <? endif; ?>
										
                                            <?
                                            PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_Y"], $arParams["TEMPLATE_LOCATION"], array("ZIP"), $arResult["ERROR"]);
                                            PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_Y"], $arParams["TEMPLATE_LOCATION"], array("COMPANY", "CONTACT_PERSON", "FIO", "EMAIL", "PHONE"), $arResult["ERROR"]);
                                            ?>
                                        </div>
                                        <? if ($USER->IsAuthorized() && $arResult["USER_VALS"]["PERSON_TYPE_ID"] == 2) { ?>
                                            <div class="col col_left second-col">
                                                <?
                                                PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_Y"], $arParams["TEMPLATE_LOCATION"], array("COMPANY_ADR", "FIO", "FAX", "INN", "KPP"));
                                                ?>
                                            </div>
                                        <? } ?>
                                        <div class="col col_right">
                                            <?
                                            PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_Y"], $arParams["TEMPLATE_LOCATION"], array("ADDRESS"), $arResult["ERROR"]);
                                            ?>

                                            <div class="form__el">
                                                <div class="labelWrap">
                                                    <label for="comment_new">Комментарии к заказу</label>
                                                </div>
                                                <div class="textareaWrap">
                                                    <textarea name="ORDER_DESCRIPTION" id="ORDER_DESCRIPTION" cols="30" rows="10" class="form__textarea"><?= $arResult["USER_VALS"]["ORDER_DESCRIPTION"] ?></textarea>
                                                </div>
                                            </div>
                                            <? if (!$USER->IsAuthorized()) { ?>
                                                <div class="form__el form__el_accept">
                                                    <div class="checkboxWrap">
                                                        <label for="ACCEPT_NEW">
                                                            <input type="checkbox" name="ACCEPT_NEW" id="ACCEPT_NEW" tabindex="10" value="Y">
                                                            <span class="multiline">Я прочитал и согласен с Условиями обработки моих персональных данных и ознакомлен с Политикой конфиденциальности компании.</span>
                                                        </label>

                                                    </div>
                                                    <? if (strlen($arResult["ERROR"]["ACCEPT_NEW"])) { ?>
                                                        <div class="form__error" title="<?= strip_tags($arResult["ERROR"]["ACCEPT_NEW"]); ?>"><i class="fa fa-exclamation-triangle"></i> <span class="no-js-text"><?= strip_tags($arResult["ERROR"]["ACCEPT_NEW"]); ?></span></div>
                                                    <? } ?>
                                                </div>

                                            <? } ?>
                                        </div>

                                        <div class="submitWrap">
                                            <button class="button button_submit-order" type="button"><span>Отправить заказ <i class="fa fa-arrow-right"></i></span></button>
                                        </div>
                                    </div>
								<? /*
                                    <div class="part part_user-old" id="part_user-old" style="display: none;">
                                        <div class="col col_left">
                                            <div class="form__el">
                                                <div class="labelWrap">
                                                    <label for="login">Логин</label>
                                                </div>
                                                <div class="inputWrap">
                                                    <input type="text" class="form__text" name="login" id="login">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col col_right">
                                            <div class="form__el">
                                                <div class="labelWrap">
                                                    <label for="password">Пароль</label>
                                                </div>
                                                <div class="inputWrap">
                                                    <input type="password" class="form__text" name="password" id="password">
                                                    <a href="javascript:void(0);" class="forgot">забыли пароль?</a>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="submitWrap">
                                            <button class="button button_submit-order"><span>Войти и завершить оформление <i class="fa fa-arrow-right"></i></span></button>
                                        </div>
                                    </div>
								*/ ?>
                                </div>
                            </div>
                        </div>

                        <div style="display:none;">
                            <?
                            $APPLICATION->IncludeComponent(
                                    "bitrix:sale.ajax.locations", $arParams["TEMPLATE_LOCATION"], array(
                                "AJAX_CALL" => "N",
                                "COUNTRY_INPUT_NAME" => "COUNTRY_tmp",
                                "REGION_INPUT_NAME" => "REGION_tmp",
                                "CITY_INPUT_NAME" => "tmp",
                                "CITY_OUT_LOCATION" => "Y",
                                "LOCATION_VALUE" => "",
                                "ONCITYCHANGE" => "submitForm()",
                                    ), null, array('HIDE_ICONS' => 'Y')
                            );
                            ?>
                        </div>

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
                    if ($arParams["DELIVERY_NO_AJAX"] == "N") {
                        ?>
                        <div style="display:none;"><? $APPLICATION->IncludeComponent("bitrix:sale.ajax.delivery.calculator", "", array(), null, array('HIDE_ICONS' => 'Y')); ?></div>
                        <?
                    }
                } else {
                    $APPLICATION->ShowHead();
                    ?>
                    <script type="text/javascript">
                        top.BX('confirmorder').value = 'Y';
                        top.BX('profile_change').value = 'N';
                    </script>
                    <?
                    die();
                }
            }
        }
        ?>
    </div>
</div>