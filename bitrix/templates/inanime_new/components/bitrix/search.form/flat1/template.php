<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(true);?>

<form action="<?=$arResult["FORM_ACTION"]?>" name="searchForm">
    <div class="search-input-container">
        <?if($arParams["USE_SUGGEST"] === "Y"):?><?$APPLICATION->IncludeComponent(
                    "bitrix:search.suggest.input",
                    "flat1",
                    array(
                        "NAME" => "q",
                        "VALUE" => "",
                        "INPUT_SIZE" => 15,
                        "DROPDOWN_SIZE" => 10,
                        'TABLET'=>(array_key_exists('TABLET',$arParams) && $arParams['TABLET']=='Y')?'Y':'N'
                    ),
                    $component, array("HIDE_ICONS" => "Y")
        );?><?else:?>
            <input type="text" name="q" value="" size="15" maxlength="50" placeholder="Поиск" class="form-control search-input" />
        <?endif;?>
        <i class="fa fa-search" aria-hidden="true" onclick="$(this).closest('form').submit()"></i>
    </div>
</form>
