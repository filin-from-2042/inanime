<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if (!empty($arResult["ORDER"]))
{
        if($arResult['ORDER_PROPS_DATA'])
        {?>
            <div class="order-drawing-up-complete">
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
                    <div class="row message-container">
                        <div class="general-message">Спасибо за покупку!</div>
                        <div class="contacts-text gray-text">Мы свяжемся с Вами в ближайшее время по телефону <span class="phone-number"><?=$arResult['ORDER_PROPS_DATA']['PHONE']?></span></div>
                    </div>
                    <div class="row data-wrap">
                        <div class="data-container">
                            <div class="order-number-container data-field"><span class="title">№ заказа:</span><span class="value"><?=$arResult["ORDER"]["ACCOUNT_NUMBER"]?></span></div>
                            <div class="fio-container data-field"><span class="title">ФИО покупателя:</span><span class="value"><?=$arResult['ORDER_PROPS_DATA']['FIO']?></span></div>
                            <div class="fio-container data-field"><span class="title">E-mail:</span><span class="value"><?=$arResult['ORDER_PROPS_DATA']['EMAIL']?></span></div>
                        </div>
                    </div>
                    <div class="button-container">
                        <a href="/catalog/kategorii/" class="btn btn-default ia-btn text-btn blue-btn">В каталог</a>
                    </div>
                    <hr class="general-content-bottom-line">
                </div>
            </div>
<?      }
}?>