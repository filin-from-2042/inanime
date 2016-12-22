<?

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$onlyCurrent = ($arParams['ONLY_CURRENT'] && $arParams['ONLY_CURRENT']==='Y') ? true : false;

if (!empty($arResult['ERRORS']['FATAL']))
{
    foreach($arResult['ERRORS']['FATAL'] as $error)
    {
        ShowError($error);
    }
    $component = $this->__component;
    if ($arParams['AUTH_FORM_IN_TEMPLATE'] && isset($arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED]))
    {
        $APPLICATION->AuthForm('', false, false, 'N', false);
    }

}
else
{
    if (!empty($arResult['ERRORS']['NONFATAL']))
    {
        foreach($arResult['ERRORS']['NONFATAL'] as $error)
        {
            ShowError($error);
        }
    }
    ?>

    <div class="bottom-pager-container">
        <div class="pager-container hidden-xs">
            <?=$arResult["NAV_STRING"]?>
        </div>
    </div>
    <div class="row hidden-xs">
        <div class="col-sm-24 col-md-24 col-lg-24">
            <div class="row grey-container table-header">
                <div class="col-sm-6 col-md-4 col-lg-4 order-number-column"><span class="title">№ заказа</span></div>
                <div class="col-sm-6 col-md-4 col-lg-4 order-date-column"><span class="title">Дата заказа</span></div>
                <div class="col-sm-4 col-md-3 col-lg-3 price-column"><span class="title">Сумма</span></div>
                <div class="col-md-4 col-lg-4 hidden-sm hidden-xs count-column"><span class="title">Кол-во товаров</span></div>
                <div class="col-md-3 col-lg-3 hidden-sm hidden-xs shipping-column"><span class="title">Доставка</span></div>
                <div class="col-sm-8 col-md-6 col-lg-6 controls-buttons-column"></div>
            </div>

        </div>
    </div>
    <div class="row" >
        <div class="col-sm-24 col-md-24 col-lg-24">
    <?
    foreach ($arResult['ORDERS'] as $key => $order)
    {
        if($onlyCurrent && $order['ORDER']['STATUS_ID']!='E') continue;
        ?>
                <div class="row table-row" id="<?=$order['ORDER']['ID']?>">
                    <div class="general-data-container clearfix">
                        <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4 order-number-column"><span class="brown-dotted-text order-number"><?=$order['ORDER']['ACCOUNT_NUMBER']?></span></div>
                        <div class="col-xs-5 col-sm-6 col-md-4 col-lg-4 order-date-column">
                            <?
                            $oldLocale = setlocale(LC_TIME, 'ru_RU.UTF-8');

                            $date = $order['ORDER']['DATE_INSERT']->getTimestamp();
                            $format = '%e&nbsp;%bg&nbsp;%Y&nbsp;';
                            $months = explode("|", '|января|февраля|марта|апреля|мая|июня|июля|августа|сентября|октября|ноября|декабря');
                            $format = preg_replace("~\%bg~", $months[date('n', $date)], $format);
                            $res = strftime($format, $date);
                            echo  '<span class="order-date gray-text hidden-xs">'.strtolower($res).'</span>' ;
                            echo '<span class="order-date gray-text hidden-sm hidden-md hidden-lg">'.$order['ORDER']['DATE_INSERT']->format('d.j.y').'</span>';
                            setlocale(LC_TIME, $oldLocale);?>
                        </div>
                        <div class="hidden-xs col-sm-4 col-md-3 col-lg-3 price-column"><span class="yellow-text price"><?=$order['ORDER']['FORMATED_PRICE']?></span></div>
                        <?
                            $allQuantity = 0.0;
                            foreach($order['BASKET_ITEMS'] as $item)
                            {
                                $allQuantity += $item['QUANTITY'];
                            }
                        ?>
                        <div class="hidden-xs col-md-4 col-lg-4 hidden-sm hidden-xs count-column"><span class="count gray-text"><?=$allQuantity;?></span></div>
                        <div class="hidden-xs col-md-3 col-lg-3 hidden-sm hidden-xs shipping-column">
                            <?foreach ($order['SHIPMENT'] as $shipment)
                            {
                                if (empty($shipment))
                                {
                                    continue;
                                }
                                ?>
                                <span class="yellow-text shiping"><?=$shipment['FORMATED_DELIVERY_PRICE']?></span>
                                <?
                            }
                                ?>
                        </div>
                        <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6 controls-buttons-column">
                            <?
                            foreach ($order['PAYMENT'] as $payment)
                            {
                                if ($payment['PAID'] === 'N' && $payment['IS_CASH'] !== 'Y')
                                {
                                ?>
                                    <button type="submit" class="btn btn-default ia-btn text-btn yellow-btn pay-btn">
                                        <a target="_blank" href="<?=htmlspecialcharsbx($payment['PSA_ACTION_FILE'])?>">Оплатить</a>
                                    </button>
                                <?}
                            }?>
                            <?/*?>
                            <button type="submit" class="btn btn-default ia-btn image-btn blue-btn repeat-btn"><i class="fa fa-repeat" aria-hidden="true"></i></button>
                            <?*/?>
                            <button type="submit" class="btn btn-default ia-btn image-btn yellow-btn option-btn"><span class="glyphicon glyphicon-option-horizontal"></span></button>
                        </div>
                    </div>
                    <div class="more-data-container">
                    </div>
                </div>
<?
    }
    ?>

        </div>
    </div>
    <div class="bottom-pager-container">
        <div class="pager-container hidden-xs">
                <?=$arResult["NAV_STRING"]?>
        </div>
    </div>
    <?
    $javascriptParams = array(
        "url" => CUtil::JSEscape($templateFolder.'/ajax.php')
    );
    $javascriptParams = CUtil::PhpToJSObject($javascriptParams);
    ?>
    <script>
        BX.Sale.PersonalOrderComponent.PersonalOrderList.init(<?=$javascriptParams?>);
    </script>
            <?
}