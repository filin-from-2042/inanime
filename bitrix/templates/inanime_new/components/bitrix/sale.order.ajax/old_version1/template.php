<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($USER->IsAuthorized() || $arParams["ALLOW_AUTO_REGISTER"] == "Y")
{
    if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y")
    {
        if(strlen($arResult["REDIRECT_URL"]) > 0)
        {
            $APPLICATION->RestartBuffer();
            ?>
            <script type="text/javascript">
                window.top.location.href='<?=CUtil::JSEscape($arResult["REDIRECT_URL"])?>';
            </script>
            <?
            die();
        }

    }
}

$APPLICATION->SetAdditionalCSS($templateFolder."/style_cart.css");
$APPLICATION->SetAdditionalCSS($templateFolder."/style.css");
?>

<a name="order_form"></a>

<div id="order_form_div" class="order-checkout">
<NOSCRIPT>
    <div class="errortext"><?=GetMessage("SOA_NO_JS")?></div>
</NOSCRIPT>

<?
if (!function_exists("getColumnName"))
{
    function getColumnName($arHeader)
    {
        return (strlen($arHeader["name"]) > 0) ? $arHeader["name"] : GetMessage("SALE_".$arHeader["id"]);
    }
}

if (!function_exists("cmpBySort"))
{
    function cmpBySort($array1, $array2)
    {
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
    if(!$USER->IsAuthorized() && $arParams["ALLOW_AUTO_REGISTER"] == "N")
    {
        if(!empty($arResult["ERROR"]))
        {
            foreach($arResult["ERROR"] as $v)
                echo ShowError($v);
        }
        elseif(!empty($arResult["OK_MESSAGE"]))
        {
            foreach($arResult["OK_MESSAGE"] as $v)
                echo ShowNote($v);
        }

        include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/auth.php");
    }
    else
    {
        if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y")
        {
            if(strlen($arResult["REDIRECT_URL"]) == 0)
            {
                include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/confirm.php");
            }
        }
        else
        {
            ?>
            <script type="text/javascript">

            <?if(CSaleLocation::isLocationProEnabled()):?>

                <?
                // spike: for children of cities we place this prompt
                $city = \Bitrix\Sale\Location\TypeTable::getList(array('filter' => array('=CODE' => 'CITY'), 'select' => array('ID')))->fetch();
                ?>

                BX.saleOrderAjax.init(<?=CUtil::PhpToJSObject(array(
                                'source' => $this->__component->getPath().'/get.php',
                                'cityTypeId' => intval($city['ID']),
                                'messages' => array(
                                    'otherLocation' => '--- '.GetMessage('SOA_OTHER_LOCATION'),
                                    'moreInfoLocation' => '--- '.GetMessage('SOA_NOT_SELECTED_ALT'), // spike: for children of cities we place this prompt
                                    'notFoundPrompt' => '<div class="-bx-popup-special-prompt">'.GetMessage('SOA_LOCATION_NOT_FOUND').'.<br />'.GetMessage('SOA_LOCATION_NOT_FOUND_PROMPT', array(
                                        '#ANCHOR#' => '<a href="javascript:void(0)" class="-bx-popup-set-mode-add-loc">',
                                        '#ANCHOR_END#' => '</a>'
                                    )).'</div>'
                                )
                            ))?>);

            <?endif?>

            var BXFormPosting = false;
            function submitForm(val)
            {
                if (BXFormPosting === true)
                    return true;

                BXFormPosting = true;
                if(val != 'Y')
                    BX('confirmorder').value = 'N';

                var orderForm = BX('ORDER_FORM');
                BX.showWait();

                <?if(CSaleLocation::isLocationProEnabled()):?>
                    BX.saleOrderAjax.cleanUp();
                <?endif?>

                BX.ajax.submit(orderForm, ajaxResult);

                return true;
            }

            function ajaxResult(res)
            {
                var orderForm = BX('ORDER_FORM');
                try
                {
                    // if json came, it obviously a successfull order submit

                    var json = JSON.parse(res);
                    BX.closeWait();

                    if (json.error)
                    {
                        BXFormPosting = false;
                        return;
                    }
                    else if (json.redirect)
                    {
                        window.top.location.href = json.redirect;
                    }
                }
                catch (e)
                {
                    // json parse failed, so it is a simple chunk of html

                    BXFormPosting = false;
                    BX('order_form_content').innerHTML = res;

                    <?if(CSaleLocation::isLocationProEnabled()):?>
                    BX.saleOrderAjax.initDeferredControl();
                    <?endif?>
                }

                BX.closeWait();
                BX.onCustomEvent(orderForm, 'onAjaxSuccess');
            }

            function SetContact(profileId)
            {
                BX("profile_change").value = "Y";
                submitForm();
            }
            </script>

            <?if($_POST["is_ajax_post"] != "Y")
            {
            ?>
            <div class="order-drawing-up">
            <div class="container">
            <form action="<?=$APPLICATION->GetCurPage();?>" method="POST" name="ORDER_FORM" id="ORDER_FORM" enctype="multipart/form-data">
                <?=bitrix_sessid_post()?>
                <div id="order_form_content">
            <?
            }
            else
            {
                $APPLICATION->RestartBuffer();
            }

            if($_REQUEST['PERMANENT_MODE_STEPS'] == 1)
            {
            ?>
                <input type="hidden" name="PERMANENT_MODE_STEPS" value="1" />
            <?
            }

            if(!empty($arResult["ERROR"]) && $arResult["USER_VALS"]["FINAL_STEP"] == "Y")
            {
                foreach($arResult["ERROR"] as $v)
                    echo ShowError($v);
                ?>
                <script type="text/javascript">
                    top.BX.scrollToNode(top.BX('ORDER_FORM'));
                </script>
            <?
            }


            include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/person_type.php");
            include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props.php");
            ?>

            <?


            if($_POST["is_ajax_post"] != "Y")
            {
                ?>
                </div>
                <input type="hidden" name="confirmorder" id="confirmorder" value="Y">
                <input type="hidden" name="profile_change" id="profile_change" value="N">
                <input type="hidden" name="is_ajax_post" id="is_ajax_post" value="Y">
                <input type="hidden" name="json" value="Y">
                <div class="bx_ordercart_order_pay_center"><a href="javascript:void();" onclick="submitForm('Y'); return false;" id="ORDER_CONFIRM_BUTTON" class="checkout"><?=GetMessage("SOA_TEMPL_BUTTON")?></a></div>
            </form>
            </div>
            </div>
                <?
            if($arParams["DELIVERY_NO_AJAX"] == "N")
            {
                ?>
                <div style="display:none;"><?$APPLICATION->IncludeComponent("bitrix:sale.ajax.delivery.calculator", "", array(), null, array('HIDE_ICONS' => 'Y')); ?></div>
            <?
            }
            }
            else
            {
            ?>
                <script type="text/javascript">
                    top.BX('confirmorder').value = 'Y';
                    top.BX('profile_change').value = 'N';
                </script>
                <?
                die();
            }

            ?>

            <div class="order-drawing-up">
                <div class="container">
                    <div class="row">
                        <div class="col-md-offset-3 col-lg-offset-3" >
                            <h2>Информация о получателе</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-offset-3 col-lg-offset-3 col-sm-12 col-md-8 col-lg-8 fio-column">
                            <div class="input-container">
                                <input type="text" name="first-name" value="" placeholder="Анатлоий" class="form-control first-name-input">
                            </div>
                            <div class="input-container">
                                <input type="text" name="second-name" value="" placeholder="Корягин" class="form-control second-name-input">
                            </div>
                            <div class="input-container">
                                <input type="text" name="phone" value="" placeholder="89238138434" class="form-control phone-input">
                            </div>
                            <div class="input-container">
                                <input type="text" name="email" value="" placeholder="anatoly@mail.ru" class="form-control email-input">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 address-column">
                            <div class="radio-values-container">
                                <div class="radio-container">
                                    <input type="hidden" name="adress-radio" class="ia-radio-value">
                                    <div class="radio-button-container">
                                        <div class="ia-radio-button small">
                                            <span class="value hidden">address1</span>
                                            <div class="radio-dot"></div>
                                        </div>
                                        <div class="button-title">Сохраненный адрес 1</div>
                                    </div>
                                    <div class="radio-button-container">
                                        <div class="ia-radio-button small">
                                            <span class="value hidden">address2</span>
                                            <div class="radio-dot"></div>
                                        </div>
                                        <div class="button-title">Сохраненный адресс 2</div>
                                    </div>
                                </div>
                                <div class="values-container">
                                    <div class="address-container address1 selected">
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
                                        </div>
                                    </div>
                                    <div class="address-container address2">
                                        <div class="input-container">
                                            <input type="text" name="city" value="" placeholder="Город2" class="form-control city-input" disabled="disabled">
                                        </div>
                                        <div class="street-data-fields">
                                            <div class="input-container">
                                                <input type="text" name="street" value="" placeholder="Улица2" class="form-control street-input" disabled="disabled">
                                            </div>
                                            <div class="input-container">
                                                <input type="text" name="house-number" value="" placeholder="Дом2" class="form-control house-number-input" disabled="disabled">
                                            </div>
                                            <div class="input-container apartment-container">
                                                <input type="text" name="apartment" value="" placeholder="Квартира2" class="form-control apartment-input" disabled="disabled">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row radio-row">

                        <div class="col-md-offset-3 col-lg-offset-3 col-sm-12 col-md-8 col-lg-8 shipping-column">
                            <h3>Способ доставки</h3>
                            <div class="radio-container">
                                <input type="hidden" name="shipping-radio" class="ia-radio-value">
                                <div class="radio-button-container">
                                    <div class="ia-radio-button small">
                                        <span class="value hidden">courier</span>
                                        <div class="radio-dot"></div>
                                    </div>
                                    <div class="button-title"><img src="" class="shipping-icon">Курьером<span class="shipping-money">+300 ₽</span></div>
                                </div>
                                <div class="radio-button-container">
                                    <div class="ia-radio-button small">
                                        <span class="value hidden">russia-post</span>
                                        <div class="radio-dot"></div>
                                    </div>
                                    <div class="button-title"><img src="" class="shipping-icon">Почта России</div>
                                </div>
                                <div class="radio-button-container">
                                    <div class="ia-radio-button small">
                                        <span class="value hidden">pickup</span>
                                        <div class="radio-dot"></div>
                                    </div>
                                    <div class="button-title"><img src="" class="shipping-icon">Самовывоз</div>
                                </div>
                                <div class="radio-button-container">
                                    <div class="ia-radio-button small">
                                        <span class="value hidden">express-courier</span>
                                        <div class="radio-dot"></div>
                                    </div>
                                    <div class="button-title"><img src="" class="shipping-icon">Курьером компании "Express"</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 payment-column">
                            <h3>Способ оплаты</h3>
                            <div class="radio-container">
                                <input type="hidden" name="shipping-radio" class="ia-radio-value">
                                <div class="radio-button-container">
                                    <div class="ia-radio-button small">
                                        <span class="value hidden">visa-payment</span>
                                        <div class="radio-dot"></div>
                                    </div>
                                    <div class="button-title"><img src="" class="payment-icon">Visa Payment</div>
                                </div>
                                <div class="radio-button-container">
                                    <div class="ia-radio-button small">
                                        <span class="value hidden">master-card</span>
                                        <div class="radio-dot"></div>
                                    </div>
                                    <div class="button-title"><img src="" class="payment-icon">Master Card</div>
                                </div>
                                <div class="radio-button-container">
                                    <div class="ia-radio-button small">
                                        <span class="value hidden">paypal</span>
                                        <div class="radio-dot"></div>
                                    </div>
                                    <div class="button-title"><img src="" class="payment-icon">Paypal</div>
                                </div>
                                <div class="radio-button-container">
                                    <div class="ia-radio-button small">
                                        <span class="value hidden">cash</span>
                                        <div class="radio-dot"></div>
                                    </div>
                                    <div class="button-title"><img src="" class="payment-icon">Наличными при получении</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row order-comment-row">
                        <div class="col-md-offset-3 col-lg-offset-3 col-sm-24 col-md-21 col-lg-19">
                            <div class="input-container order-comment-container">
                                <textarea name="order-comment" class="form-control order-comment-textarea">
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row discounts-coupon-row">
                        <div class="col-md-offset-3 col-lg-offset-3 col-sm-24 col-md-21 col-lg-21 discounts-column">
                            <h3>Информация о купонах и скидках</h3>
                        </div>
                        <div class="col-md-offset-3 col-lg-offset-3 col-sm-12 col-md-8 col-lg-8 discounts-column">
                            <div class="discount-container">
                                <div class="number grey-container gray-text">234198-234123-232346-234177</div>
                                <button type="button" class="ia-close-btn" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true" class="clearfix ">
                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                          </span>
                                </button>
                                <div class="note">
                                    <span class="text gray-text">Скидка 20% на товары категории "Мягкие игрушки"</span>
                                    <span class="period gray-text">Срок действия: 2 дня</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-10 col-lg-10 coupon-column">
                            <div class="coupon-container">
                                <div class="number grey-container gray-text">234198-234123-232346-234177</div>
                                <button type="button" class="ia-close-btn" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true" class="clearfix ">
                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                          </span>
                                </button>
                                <div class="note">
                                    <span class="text gray-text">Купон на бесплатную доставку в пределах г. Москва</span>
                                    <span class="period gray-text">Срок действия: 1 покупка</span>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                        <span class="cost">1200 ₽</span>
                                    </div>
                                    <div class="all-order-cost clearfix">
                                        <span class="cost">1500 ₽</span>
                                    </div>
                                </div>
                            </div>
                            <div class="error-container error-text">
                                Ошибка! Заполните все обязательные поля
                            </div>
                            <div class="row costs-row">
                                <div class="col-xs-16 col-sm-offset-5 col-md-offset-0 col-sm-11 col-md-17 col-lg-16 text-column">
                                    <div class="button-container">
                                        <button type="submit" class="btn btn-default ia-btn text-btn yellow-btn">Оплатить</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?
        }
    }
    ?>

</div>

</div>