<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Создание дисконтных карт");
global $USER;
if (!$USER->IsAdmin()) LocalRedirect("/");
CModule::IncludeModule("iblock");

$arGroups = $arTypes = array();
// выбираем группы пользователей
$filter = Array("STRING_ID" => "USER_DISCOUNT_%","ACTIVE" => "Y");
$rsGroups = CGroup::GetList(($by = "c_sort"), ($order = "asc"), $filter);
while ($ar_result = $rsGroups->fetch()) $arGroups[] = $ar_result;
// выбираем типы карт
$db_enum_list = CIBlockProperty::GetPropertyEnum(110, Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>11));
while($ar_enum_list = $db_enum_list->GetNext()) $arTypes[] = $ar_enum_list;
// ОБРАБОТЧИК
if (@$_REQUEST["submit"] == "Y") {

//	$iblockID = intval($_REQUEST['IBLOCK_ID']);
	$iblockID = 27;
    $allchars = '0123456789';
    $arInfo = $arError = '';
    for ($n = 1; $n <= $_REQUEST['CARDS_QUANTITY']; $n++) {
        $code = $pass = '';
        for ($i = 0; $i < 16; $i++) {
            $code.=substr($allchars, round((rand(0, 10) * 0.1) * (strlen($allchars) - 1)), 1);
        }
        for ($i = 0; $i < 4; $i++) {
            $pass.=substr($allchars, round((rand(0, 10) * 0.1) * (strlen($allchars) - 1)), 1);
        }
        $el = new CIBlockElement;
        $property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$iblockID, "VALUE"=>$_REQUEST['USER_GROUP']));
        if($enum_fields = $property_enums->GetNext()) $group = $enum_fields['ID'];

        $arLoadProductArray = Array(
            "MODIFIED_BY" => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_SECTION_ID" => false, // элемент лежит в корне раздела
            "IBLOCK_ID" => $iblockID,
            "PROPERTY_VALUES" => array(
                "PASS" => $pass,
                "TYPE" => array("VALUE"=>intval($_REQUEST['TYPE'])),
                "USER_GROUP" => array("VALUE"=>intval($_REQUEST['USER_GROUP']))
            ),
            "NAME" => $code,
            "ACTIVE" => "Y"
        );
        $PRODUCT_ID = $el->Add($arLoadProductArray);
        if ($PRODUCT_ID <= 0) {
            $ex = $APPLICATION->GetException();
            $errorMessage = $ex->GetString();
            $arError[] = $errorMessage;
        } else {
            $arInfo[] = 'Карта успешно создана! Номер карты - <strong>'.$code.'</strong>, пароль - <strong>'.$pass.'</strong>';
        }
    }
    if (count($arInfo) > 0) {
        $arInfo[] = '<p>Подробнее вы можете посмотреть в <a style="text-decoration:underline" href="/bitrix/admin/iblock_list_admin.php?IBLOCK_ID='.$iblockID.'&type=references&lang=ru&find_section_section=0">административной части</a></p>';
    }
}
?>

<?if (!empty($arInfo)):?>
    <div class="notetext">
        <?=implode('<br>', $arInfo)?>
    </div><br>
<?endif?>
<?if (!empty($arError)):?>
    <div class="errortext">
        <?=implode('<br>', $arError)?>
    </div><br>
<?endif?>
<div class="container">
    <form action="" method="POST" class="form form_register form_user-callback form_generate_cards">

        <? if (count($arGroups) > 0) { ?>
            <div class="form__el">
                <div class="labelWrap">
                    <label for="USER_GROUP">Группа пользователей</label>
                </div>
                <div class="selectWrap">
                    <select required="required" name="USER_GROUP"  id="USER_GROUP" class="form__select" style="width: 100%">
                        <? foreach ($arGroups as $group) { ?>
                            <option value="<?= $group["ID"]; ?>"><?= $group["NAME"]; ?></option>
                        <? } ?>
                    </select>
                </div>
            </div>
        <? } ?>
        <div class="form__el">
            <div class="labelWrap">
                <label for="TYPE">Тип создаваемой карты</label>
            </div>
            <div class="selectWrap">
                <select required="required" name="TYPE"  id="TYPE" class="form__select" style="width: 100%">
                    <? foreach ($arTypes as $type) { ?>
                        <option value="<?= $type["ID"]; ?>"><?= $type["VALUE"]; ?></option>
                    <? } ?>
                </select>
            </div>
        </div>

        <div class="form__el">
            <div class="labelWrap">
                <label for="CARDS_QUANTITY">Количество дисконтных карт</label>
            </div>
            <div class="inputWrap">
                <input min="1" type="number" class="form__text" name="CARDS_QUANTITY" id="CARDS_QUANTITY" value="<?= (int) $_REQUEST["CARDS_QUANTITY"]; ?>" autocomplete="off" />
            </div>
        </div>

        <div class="submitWrap">
            <button type="submit" name="submit" value="Y" class="button button_submit-register"><span>Генерировать дисконтные карты</span></button>
        </div>
    </form>
</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
<?
/*
?>
<div class="page page_card">
    <? if ($_POST["submit"] == "Y") { ?>
        <?
        $createdCards = array();
        $group_code = $_POST["GROUP_ID"];
        $quantity = $_POST["CARDS_QUANTITY"];
        if ($quantity > 0 && strlen($group_code) > 0) {
            for ($i = 1; $i <= $quantity; $i++) {
                $CARD_ID = getUniqueCardID();
                $CARD_CODE = getUniqueCardID(4);
                $el = new CIBlockElement;

                $PROP = array();
                $PROP["DISCOUNT_GROUP"] = $group_code;
                $PROP["CARD_CODE"] = $CARD_ID;
                $PROP["CARD_CODE_CONFIRM"] = $CARD_CODE;

                $arLoadProductArray = Array(
                    "MODIFIED_BY" => $USER->GetID(), // элемент изменен текущим пользователем
                    "IBLOCK_SECTION_ID" => false, // элемент лежит в корне раздела
                    "IBLOCK_ID" => 5,
                    "PROPERTY_VALUES" => $PROP,
                    "NAME" => "Карта №" . $CARD_ID,
                    "ACTIVE" => "Y"
                );
                $PRODUCT_ID = $el->Add($arLoadProductArray);

                if ($PRODUCT_ID <= 0) {
                    $ex = $APPLICATION->GetException();
                    $errorMessage = $ex->GetString();
                    echo $errorMessage;
                } else {
                    $createdCards[] = array("ID"=>$CARD_ID,"CODE"=>$CARD_CODE);
                }
            }
            if (count($createdCards) > 0) {
                echo 'Успешно созданные карты (группа '.$group_code.'):<br />';
                foreach ($createdCards as $card)
                    echo "Номер карты: ".$card["ID"].'. Код: '.$card["CODE"].'<br />';
            }
        }
        ?>
    <? } else { ?>
        <?
        $filter = Array
            (
            "STRING_ID" => "USER_DISCOUNT_%",
            "ACTIVE" => "Y",
        );
        $rsGroups = CGroup::GetList(($by = "c_sort"), ($order = "asc"), $filter); // выбираем группы
        $arGroups = array();
        while ($ar_result = $rsGroups->fetch())
            $arGroups[] = $ar_result;
        ?>
        <form action="" method="POST" class="form form_register form_user-callback form_generate_cards">
            <? if (count($arGroups) > 0) { ?>

                <div class="form__el">
                    <div class="labelWrap">
                        <label for="GROUP_ID">Группа пользователей</label>
                    </div>
                    <div class="selectWrap">
                        <select name="GROUP_ID"  id="GROUP_ID" class="form__select" style="width: 100%">
                            <option value="0">не выбрано</option>
                            <? foreach ($arGroups as $group) { ?>
                                <option value="<?= $group["STRING_ID"]; ?>"><?= $group["NAME"]; ?> (<?= $group["STRING_ID"]; ?>)</option>
                            <? } ?>
                        </select>
                    </div>
                </div>
            <? } ?>

            <div class="form__el">
                <div class="labelWrap">
                    <label for="CARDS_QUANTITY">Количество дисконтных карт</label>
                </div>
                <div class="inputWrap">
                    <input type="text" class="form__text" name="CARDS_QUANTITY" id="CARDS_QUANTITY" value="<?= (int) $_REQUEST["CARDS_QUANTITY"]; ?>" autocomplete="off" />
                </div>
            </div>

            <div class="submitWrap">
                <button type="submit" name="submit" value="Y" class="button button_submit-register"><span>Генерировать дисконтные карты</span></button>
            </div>
        </form>

    <? } ?>
</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>