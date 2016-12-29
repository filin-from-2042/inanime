<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<script type="text/javascript">
    function changePaySystem(param)
    {
        if (BX("account_only") && BX("account_only").value == 'Y') // PAY_CURRENT_ACCOUNT checkbox should act as radio
        {
            if (param == 'account')
            {
                if (BX("PAY_CURRENT_ACCOUNT"))
                {
                    BX("PAY_CURRENT_ACCOUNT").checked = true;
                    BX("PAY_CURRENT_ACCOUNT").setAttribute("checked", "checked");
                    BX.addClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');

                    // deselect all other
                    var el = document.getElementsByName("PAY_SYSTEM_ID");
                    for (var i = 0; i < el.length; i++)
                        el[i].checked = false;
                }
            }
            else
            {
                BX("PAY_CURRENT_ACCOUNT").checked = false;
                BX("PAY_CURRENT_ACCOUNT").removeAttribute("checked");
                BX.removeClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');
            }
        }
        else if (BX("account_only") && BX("account_only").value == 'N')
        {
            if (param == 'account')
            {
                if (BX("PAY_CURRENT_ACCOUNT"))
                {
                    BX("PAY_CURRENT_ACCOUNT").checked = !BX("PAY_CURRENT_ACCOUNT").checked;

                    if (BX("PAY_CURRENT_ACCOUNT").checked)
                    {
                        BX("PAY_CURRENT_ACCOUNT").setAttribute("checked", "checked");
                        BX.addClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');
                    }
                    else
                    {
                        BX("PAY_CURRENT_ACCOUNT").removeAttribute("checked");
                        BX.removeClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');
                    }
                }
            }
        }

        submitForm();
    }
</script>
<?
if ($arResult["PAY_FROM_ACCOUNT"] == "Y" && false) {
    $accountOnly = ($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y") ? "Y" : "N";
    ?>
    <input type="hidden" id="account_only" value="<?= $accountOnly ?>" />
    <div class="bx_block w100 vertical">
        <div class="bx_element">
            <input type="hidden" name="PAY_CURRENT_ACCOUNT" value="N">
            <label for="PAY_CURRENT_ACCOUNT" id="PAY_CURRENT_ACCOUNT_LABEL" onclick="changePaySystem('account');" class="<? if ($arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"] == "Y") echo "selected" ?>">
                <input type="checkbox" name="PAY_CURRENT_ACCOUNT" id="PAY_CURRENT_ACCOUNT" value="Y"<? if ($arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"] == "Y") echo " checked=\"checked\""; ?>>
                <div class="bx_logotype">
                    <span style="background-image:url(<?= $templateFolder ?>/images/logo-default-ps.gif);"></span>
                </div>
                <div class="bx_description">
                    <strong><?= GetMessage("SOA_TEMPL_PAY_ACCOUNT") ?></strong>
                    <p>
                    <div><?= GetMessage("SOA_TEMPL_PAY_ACCOUNT1") . " <b>" . $arResult["CURRENT_BUDGET_FORMATED"] ?></b></div>
                    <? if ($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y"): ?>
                        <div><?= GetMessage("SOA_TEMPL_PAY_ACCOUNT3") ?></div>
                    <? else: ?>
                        <div><?= GetMessage("SOA_TEMPL_PAY_ACCOUNT2") ?></div>
                    <? endif; ?>
                    </p>
                </div>
            </label>
            <div class="clear"></div>
        </div>
    </div>
<?
}
?>
<h3>Способ оплаты</h3>
<div class="radio-container">
    <input type="hidden" name="PAY_SYSTEM_ID" class="ia-radio-value">
    <?
    uasort($arResult["PAY_SYSTEM"], "cmpBySort"); // resort arrays according to SORT value

    foreach ($arResult["PAY_SYSTEM"] as $arPaySystem)
    {?>

        <div class="radio-button-container">
            <div class="ia-radio-button small<?=($arPaySystem["CHECKED"] == "Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"] == "Y"))?' active':''?>">
                <span class="value hidden"><?= $arPaySystem["ID"] ?></span>
                <div class="radio-dot"></div>
            </div>
            <div class="button-title">
            <?if($arPaySystem['PSA_LOGOTIP'] && $arPaySystem['PSA_LOGOTIP']['SRC']):?>
            <img src="<?=$arPaySystem['PSA_LOGOTIP']['SRC']?>" class="payment-icon">
            <?endif;?>
            <?= $arPaySystem["PSA_NAME"]; ?></div>
        </div>
    <?
    }
    ?>
</div>
<script>
    $(document).ready(function(){
        $('body').on('click', '.payment-column .ia-radio-button,.payment-column .radio-button-container .button-title', function(){

            if ($(this).hasClass('ia-radio-button')) var radioButton = $(this);
            else var radioButton = $(this).closest('.radio-button-container').find('.ia-radio-button');
            radioButton.closest('.radio-container').find('input.ia-radio-value').val(radioButton.find('span.value.hidden').text());
            radioButton.closest('.radio-container').find('.ia-radio-button').removeClass('active');
            radioButton.addClass('active');

            changePaySystem();
        });
    });
</script>