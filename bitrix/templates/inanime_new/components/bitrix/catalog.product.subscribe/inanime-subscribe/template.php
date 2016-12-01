<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

use Bitrix\Main\Localization\Loc;

$strMainId = $this->getEditAreaId($arResult['PRODUCT_ID']);
$jsObject = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainId);
$paramsForJs = array(
	'buttonId' => $arResult['BUTTON_ID'],
	'jsObject' => $jsObject,
	'alreadySubscribed' => $arResult['ALREADY_SUBSCRIBED'],
	'productId' => $arResult['PRODUCT_ID'],
	'buttonClass' => htmlspecialcharsbx($arResult['BUTTON_CLASS']),
	'urlListSubscriptions' => '/',
);

$templateData = $paramsForJs;
?>

<button type="button" class="<?=htmlspecialcharsbx($arResult['BUTTON_CLASS'])?>"
        id="<?=htmlspecialcharsbx($arResult['BUTTON_ID'])?>"
        data-item="<?=htmlspecialcharsbx($arResult['PRODUCT_ID'])?>" style="<?=($arResult['DEFAULT_DISPLAY']?'':'display: none;')?>">
    <span class="icon-btn" style="width:43px;box-sizing: border-box"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></span>
    <span class="text-btn"><?=Loc::getMessage('CPST_SUBSCRIBE_BUTTON_NAME')?></span>
    <input type="hidden" id="<?=htmlspecialcharsbx($arResult['BUTTON_ID'])?>_hidden">
</button>

<script type="text/javascript">
	var <?=$jsObject?> = new JCCatalogProductSubscribe(<?=CUtil::phpToJSObject($paramsForJs, false, true)?>);

	BX.message({
		CPST_SUBSCRIBE_POPUP_TITLE: '<?=GetMessageJS('CPST_SUBSCRIBE_POPUP_TITLE');?>',
		CPST_SUBSCRIBE_BUTTON_NAME: '<span class="icon-btn" style="width:43px;box-sizing: border-box"><i class="fa fa-2x fa-exclamation-circle" aria-hidden="true"></i></span><span class="text-btn"><?=Loc::getMessage('CPST_SUBSCRIBE_BUTTON_NAME')?></span>',
		CPST_SUBSCRIBE_BUTTON_CLOSE: '<?=GetMessageJS('CPST_SUBSCRIBE_BUTTON_CLOSE');?>',
		CPST_SUBSCRIBE_MANY_CONTACT_NOTIFY: '<?=GetMessageJS('CPST_SUBSCRIBE_MANY_CONTACT_NOTIFY');?>',
		CPST_SUBSCRIBE_LABLE_CONTACT_INPUT: '<?=GetMessageJS('CPST_SUBSCRIBE_LABLE_CONTACT_INPUT');?>',
		CPST_SUBSCRIBE_VALIDATE_UNKNOW_ERROR: '<?=GetMessageJS('CPST_SUBSCRIBE_VALIDATE_UNKNOW_ERROR');?>',
		CPST_SUBSCRIBE_VALIDATE_ERROR_EMPTY_FIELD: '<?=GetMessageJS('CPST_SUBSCRIBE_VALIDATE_ERROR_EMPTY_FIELD');?>',
		CPST_SUBSCRIBE_VALIDATE_ERROR: '<?=GetMessageJS('CPST_SUBSCRIBE_VALIDATE_ERROR');?>',
		CPST_SUBSCRIBE_CAPTCHA_TITLE: '<?=GetMessageJS('CPST_SUBSCRIBE_CAPTCHA_TITLE');?>',
		CPST_STATUS_SUCCESS: '<?=GetMessageJS('CPST_STATUS_SUCCESS');?>',
		CPST_STATUS_ERROR: '<?=GetMessageJS('CPST_STATUS_ERROR');?>',
		CPST_ENTER_WORD_PICTURE: '<?=GetMessageJS('CPST_ENTER_WORD_PICTURE');?>',
		CPST_TITLE_ALREADY_SUBSCRIBED: '<span class="icon-btn" style="width:43px;box-sizing: border-box"><i class="fa fa-2x fa-exclamation-circle" aria-hidden="true"></i></span><span class="text-btn already-subscribe"><?=Loc::getMessage('CPST_TITLE_ALREADY_SUBSCRIBED')?></span>',
		CPST_POPUP_SUBSCRIBED_TITLE: '<?=GetMessageJS('CPST_POPUP_SUBSCRIBED_TITLE');?>',
		CPST_POPUP_SUBSCRIBED_TEXT: '<?=GetMessageJS('CPST_POPUP_SUBSCRIBED_TEXT');?>'
	});
</script>