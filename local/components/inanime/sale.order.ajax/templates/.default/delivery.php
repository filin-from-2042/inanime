<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<h3>Способ доставки</h3>

<?
if (!empty($arResult["DELIVERY"])) {
    uasort($arResult["DELIVERY"], 'cmpBySort'); // resort delivery arrays according to SORT value
    ?>

<div class="radio-container">
    <?
    $activeDeliveryValue = 0;
    $activeDeliveryFieldName = current($arResult["DELIVERY"])['FIELD_NAME'];
    foreach ($arResult["DELIVERY"] as $delivery_id => $arDelivery)
    {
       // echo '<pre>';var_dump($arDelivery);echo '</pre>';
        if($delivery_id !== 0 && intval($delivery_id) <= 0)
        {
            foreach ($arDelivery["PROFILES"] as $profile_id => $arProfile)
            {
                if(($arProfile["CHECKED"] == "Y")){
                 $activeDeliveryFieldName = htmlspecialcharsbx($arProfile["FIELD_NAME"]);
                 $activeDeliveryValue = $delivery_id . ":" . $profile_id;
                }
            ?>
                <div class="radio-button-container">

                    <div class="ia-radio-button small<?=($arProfile["CHECKED"] == "Y")?' active':''?>">
                        <span class="value hidden"><?=  $delivery_id . ":" . $profile_id;  ?></span>
                        <div class="radio-dot"></div>
                    </div>
                    <div class="button-title">
                        <?if (count($arDelivery["LOGOTIP"]) > 0):?>
                            <img src="<?=CFile::ResizeImageGet($arDelivery['LOGOTIP']['ID'],array("width" => "41", "height" => "28"), BX_RESIZE_IMAGE_PROPORTIONAL, true)["src"]?>" class="shipping-icon">
                        <?endif;?>
                        <?= htmlspecialcharsbx($arDelivery["TITLE"]) . " (" . htmlspecialcharsbx($arProfile["TITLE"]) . ")"; ?>
                    </div>
                </div>
            <?}
        }
        else
        {
            if(($arDelivery["CHECKED"] == "Y")){
                $activeDeliveryValue = $arDelivery['ID'];
            }
            ?>
            <div class="radio-button-container">
                <div class="ia-radio-button small<?=($arDelivery["CHECKED"] == "Y")?' active':''?>">
                    <span class="value hidden"><?= $arDelivery["ID"] ?></span>
                    <div class="radio-dot"></div>
                </div>
                <div class="button-title">
                    <?if($arDelivery['LOGOTIP'] && $arDelivery['LOGOTIP']['SRC']):?>
                    <img src="<?=$arDelivery['LOGOTIP']['SRC']?>" class="shipping-icon">
                    <?endif;?>
                    <?= ($arDelivery["NAME"])?$arDelivery["NAME"]:$arDelivery["TITLE"]  ?>
                    <?if(intval($arDelivery["PRICE"])>0):?>
                    <span class="shipping-money">+<?= $arDelivery["PRICE_FORMATED"]; ?></span>
                    <?endif;?>
                </div>
            </div>
        <?}
        }
    reset($arResult["DELIVERY"]);
    ?>
    <input type="hidden" name="<?=$activeDeliveryFieldName?>" class="ia-radio-value" <?=($activeDeliveryValue)?'value="'.$activeDeliveryValue.'"':''?>>
</div>

<?
}
?>