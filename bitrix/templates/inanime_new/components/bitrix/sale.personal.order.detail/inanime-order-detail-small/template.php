<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!empty($arResult['ERRORS']['FATAL']))
{
    foreach ($arResult['ERRORS']['FATAL'] as $error)
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
        foreach ($arResult['ERRORS']['NONFATAL'] as $error)
        {
            ShowError($error);
        }
    }
    ?>

    <div class="more-data-container">
        <div class="product-list">
            <ul>
                <?
                $counter = 1;
                foreach ($arResult['BASKET'] as $basketItem)
                {
                    ?>
                    <li class="row">
                        <div class="col-xs-17 col-sm-12 col-md-7 col-lg-7 product-name-column">
                            <a href="<?=$basketItem['DETAIL_PAGE_URL']?>">
                                <span class="brown-text product-name"><?=$counter?>.</span>
                                <span class="brown-text product-name"><?=htmlspecialcharsbx($basketItem['NAME'])?></span>
                            </a>
                        </div>
                        <div class="hidden-xs hidden-sm col-md-4 col-lg-4 price-column"><span class="yellow-text price"><?=$basketItem['PRICE_FORMATED']?></span></div>
                        <div class="hidden-xs hidden-sm col-sm-4 col-md-4 col-lg-4 count-column"><span class="count gray-text"><?=$basketItem['QUANTITY']?></span></div>
                        <div class="hidden-xs col-sm-1 hidden-md hidden-lg count-column"><span class="count gray-text"><?=$basketItem['QUANTITY']?></span></div>
                        <div class="col-xs-7 col-sm-4 hidden-md hidden-lg price-column"><span class="yellow-text price"><?=$basketItem['PRICE_FORMATED']?></span></div>
                    </li>
                <?
                    $counter++;
                }?>
            </ul>
        </div>
        <?
        if (isset($arResult["ORDER_PROPS"]))
        {
            $phone='';
            $payment = '';
            $address = '';
            $fio='';
            foreach ($arResult["ORDER_PROPS"] as $property)
            {

                if($property['CODE']=='PHONE') $phone = $property['VALUE'];
                if($property['CODE']=='ADDRESS') $address = $property['VALUE'];
                if($property['CODE']=='ADDRESS') $address = $property['VALUE'];
                if($property['CODE']=='FIO') $fio = $property['VALUE'];
            }
        ?>
            <div class="data-list hidden-sm hidden-xs">
                <div class="row">
                    <div class="col-md-4 col-lg-4 title">Фамилия, имя:</div>
                    <div class="col-md-8 col-lg-8 gray-text value"><?=$arResult["USER"]["NAME"] ." ". $arResult["USER"]["SECOND_NAME"] ." ". $arResult["USER"]["LAST_NAME"]?></div>
                    <div class="col-sm-7 col-md-4 col-lg-4 title">Телефон:</div>
                    <div class="col-sm-12 col-md-8 col-lg-8 gray-text value"><?=$phone?></div>
                </div>
                <div class="row">
                    <?
                    foreach ($arResult['SHIPMENT'] as $shipment)
                    {
                        ?>
                        <?if (strlen($shipment["DELIVERY_NAME"]))
                        {?>
                            <div class="col-md-4 col-lg-4 title">Способ доставки:</div>
                            <div class="col-md-8 col-lg-8 gray-text value"><?=$shipment["DELIVERY_NAME"]?></div>
                        <?}?>
                    <?}

                    foreach ($arResult['PAYMENT'] as $payment)
                    {?>
                        <div class="col-md-4 col-lg-4 title">Способ оплаты:</div>
                        <div class="col-md-8 col-lg-8 gray-text value"><?=$payment['PAY_SYSTEM_NAME']?></div>
                    <?}?>
                </div>
                <div class="row">
                    <div class="col-md-4 col-lg-4 title">Адрес доставки:</div>
                    <div class="col-md-8 col-lg-8 gray-text value"><?=$address?></div>
                </div>
            </div>
            <div class="data-list hidden-md hidden-lg">
                <div class="row">
                    <div class="col-xs-24 col-sm-7 title">Фамилия, имя:</div>
                    <div class="col-xs-24 col-sm-15 gray-text value"><?=$fio?></div>
                </div>
                <div class="row"><?
                foreach ($arResult['SHIPMENT'] as $shipment)
                {?>
                    <?if (strlen($shipment["DELIVERY_NAME"]))
                    {?>
                        <div class="col-sm-7 title">Способ доставки:</div>
                        <div class="col-sm-15 col-lg-8 gray-text value"><?=$shipment["DELIVERY_NAME"]?></div>
                    <?}?>
                <?}?>
                </div>
                <div class="row">
                    <div class="col-sm-7 title">Адрес доставки:</div>
                    <div class="col-sm-15 gray-text value"><?=$address?></div>
                </div>
                <div class="row hidden-xs">
                    <div class="col-sm-7 col-md-4 col-lg-4 title">Телефон:</div>
                    <div class="col-sm-15 col-md-8 col-lg-8 gray-text value"><?=$phone?></div>
                </div>
                <div class="row">
                    <?foreach ($arResult['PAYMENT'] as $payment)
                    {?>
                        <div class="col-sm-7 col-lg-4 title">Способ оплаты:</div>
                        <div class="col-sm-15 col-lg-8 gray-text value"><?=$payment['PAY_SYSTEM_NAME']?></div>
                    <?}?>
                </div>
            </div>
        <?}?>
        <div class="shipping-progress-container">
            <div class="row hidden-md hidden-lg">
                <div class="col-xs-4 col-sm-3 title">Статус:</div>
                <div class="col-xs-15 col-sm-15 value"><?=$arResult['STATUS']['NAME']?></div>
            </div>
            <ul class="shipping-bar clearfix hidden-sm hidden-xs">
                <?

                $currStatus = $arResult['STATUS'];
                $completedMark = true;
                $current = false;
                $rs = CSaleStatus::GetList(array("SORT" => "ASC"));
                while($status = $rs->Fetch())
                {
                    if($status['LID']=='ru')
                    {
                        if($currStatus['ID']==$status['ID']){
                            $completedMark=false;
                            $current = true;
                        }
                        ?>
                        <li class="<?=($completedMark)?' completed ':' not-completed '?> <?=($current)?' current ':''?>">
                            <span class="icon-wrap">
                                <i></i>
                            </span>
                            <span class="stage-text"><?=$status['NAME']?></span>
                        </li>
                    <?
                        $current = false;
                    }
                }
                ?>
            </ul>
        </div>
        <div class="close-button-container">
            <span class="yellow-text-underline">Свернуть заказ</span>
        </div>
    </div>
<?
}?>