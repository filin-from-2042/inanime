<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
if (count($arResult["PERSON_TYPE"]) > 1) {
    ?>
   <? 
  /* $typeSelected=false;
   foreach ($arResult["PERSON_TYPE"] as $v) {
        if ($v["CHECKED"] == "Y" && !$v["NOT_CONFIRMED"]) {$typeSelected=true;continue;}
    }
    var_dump($typeSelected);
    if (!$typeSelected) $arResult["PERSON_TYPE"][$currentType]["CHECKED"]="Y";*/
    ?>
        
    <? foreach ($arResult["PERSON_TYPE"] as $v) { ?>
        <input type="radio" id="PERSON_TYPE_<?= $v["ID"] ?>" name="PERSON_TYPE" style="display:none;" value="<?= $v["ID"] ?>"<? if ($v["CHECKED"] == "Y") echo " checked=\"checked\""; ?> onClick="submitForm()" />
    <? } ?>
    <input type="hidden" name="PERSON_TYPE_OLD" value="<?= $arResult["USER_VALS"]["PERSON_TYPE_ID"] ?>" />
    <?
}
else {
    if (IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"]) > 0) {
        //for IE 8, problems with input hidden after ajax
        ?>
        <span style="display:none;">
            <input type="text" name="PERSON_TYPE" value="<?= IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"]) ?>" />
            <input type="text" name="PERSON_TYPE_OLD" value="<?= IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"]) ?>" />
        </span>
        <?
    } else {
        foreach ($arResult["PERSON_TYPE"] as $v) {
            ?>
            <input type="hidden" id="PERSON_TYPE" name="PERSON_TYPE" value="<?= $v["ID"] ?>" />
            <input type="hidden" name="PERSON_TYPE_OLD" value="<?= $v["ID"] ?>" />
            <?
        }
    }
}
?>