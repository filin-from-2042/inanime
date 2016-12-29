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
    <input type="hidden" name="<?=current($arResult["DELIVERY"])['FIELD_NAME']?>" class="ia-radio-value">
    <?
    foreach ($arResult["DELIVERY"] as $delivery_id => $arDelivery)
    {
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
                <?= $arDelivery["NAME"] ?>
                <?if(intval($arDelivery["PRICE"])>0):?>
                <span class="shipping-money">+<?= $arDelivery["PRICE_FORMATED"]; ?></span>
                <?endif;?>
            </div>
        </div>
    <?}
    ?>
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
<?
}
?>