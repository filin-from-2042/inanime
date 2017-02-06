<?
/**
 * @global CMain $APPLICATION
 * @param array $arParams
 * @param array $arResult
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<?//var_dump($arResult['dump'] )?>
<form method="post" name="form1" class="form form_userinfo" action="<?= $arResult["FORM_TARGET"] ?>" enctype="multipart/form-data">
    <?= $arResult["BX_SESSION_CHECK"] ?>
    <div class="row">

        <?= ShowError($arResult["strProfileError"]); ?>
        <?
        if ($arResult['DATA_SAVED'] == 'Y') {
            header("Location: /personal/profile/");
            exit();
            //echo ShowNote(GetMessage('PROFILE_DATA_SAVED'));
        }
        if ($arResult['SUBSCRIBE_MESSAGE'] == 'Y') {
            echo ShowNote($arResult['SUBSCRIBE_MESSAGE_TEXT']);
        }
        ?>
        <?= $arResult["BX_SESSION_CHECK"] ?>
        <input type="hidden" name="lang" value="<?= LANG ?>" />
        <input type="hidden" name="ID" value=<?= $arResult["ID"] ?> />
        <input type="hidden" name="profile-edit" value="Y" />
        <input type="hidden" class="form__text" name="LOGIN" value="<? echo $arResult["arUser"]["LOGIN"] ?>">

        <div class="col-sm-24 col-md-10 col-lg-10 fio-column">
            <div class="input-container">
                <input type="text" name="NAME" value="<?= $arResult["arUser"]["NAME"] ?>" placeholder="Ваше имя" class="form-control name-input">
            </div>
            <div class="input-container">
                <input type="text" name="LAST_NAME" value="<?= $arResult["arUser"]["LAST_NAME"] ?>" placeholder="Фамилия" class="form-control second-name-input">
            </div>
            <div class="input-container">
                <input type="text" name="PERSONAL_BIRTHDAY" value="<?= $arResult["arUser"]["PERSONAL_BIRTHDAY"]; ?>" placeholder="Дата рождения" class="form-control birthday-input">
            </div>
            <div class="radio-container">
                <input type="hidden" name="PERSONAL_GENDER" class="ia-radio-value" value="<?=$arResult["arUser"]["PERSONAL_GENDER"]?>">
                <div class="radio-button-container">
                    <div class="ia-radio-button small<?= $arResult["arUser"]["PERSONAL_GENDER"] == "M" ? ' active' : "" ?>">
                        <span class="value hidden">M</span>
                        <div class="radio-dot"></div>
                    </div>
                    <div class="button-title">Мужской пол</div>
                </div>
                <div class="radio-button-container">
                    <div class="ia-radio-button small<?= $arResult["arUser"]["PERSONAL_GENDER"] == "F" ? ' active' : "" ?>">
                        <span class="value hidden">F</span>
                        <div class="radio-dot"></div>
                    </div>
                    <div class="button-title">Женский пол</div>
                </div>
                <script>
                    $(document).ready(function ()
                    {
                        $('.ia-radio-button,.radio-button-container .button-title').click(inanime_new.radioClick);
                    });
                </script>
            </div>
            <div class="confirm-container">
                <div class="ia-checkbox">
                    <input type="checkbox" id="subscribe-check" <?=($arResult['SUBSCRIBED']=='Y'?'checked="checked"':'')?> name="subscribe-add">
                    <label for="subscribe-check">Я хочу получать новости компании, а также информацию об акциях и новых предложениях</label>
                </div>
            </div>
            <div class="save-button-container hidden-sm hidden-xs">
                <button type="submit" class="btn btn-default ia-btn text-btn yellow-btn" name="save" value="save">Сохранить</button>
            </div>
        </div>
        <div class="col-sm-24 col-md-14 col-lg-14 address-column">
            <?
            $rsUser = CUser::GetList(($by="ID"), ($order="desc"), array("ID"=>$USER->GetID()),array("SELECT"=>array("UF_CUSTOMER_PROFILE")));
            $userData = $rsUser->Fetch();
            $currentProfileID = intval($userData['UF_CUSTOMER_PROFILE']);

            $profiles = CSaleOrderUserProps::GetList(
                array("DATE_UPDATE" => "DESC"),
                array("USER_ID" => $USER->GetID())
            );
            $profilesCount = $profiles->SelectedRowsCount();

            if($profilesCount==1)
            {
                $profile = $profiles->Fetch();
                $profileID = $profile['ID'];
                $profileVals = CSaleOrderUserPropsValue::GetList(array("ID" => "ASC"), Array("USER_PROPS_ID"=>$profileID));

                $location = 0;
                $address = '';
                $phone = '';
                while ($profileVal = $profileVals->Fetch())
                {
                    if($profileVal['PROP_CODE']=='LOCATION') $location = $profileVal["VALUE"];
                    if($profileVal['PROP_CODE']=='ADDRESS') $address = $profileVal["VALUE"];
                    if($profileVal['PROP_CODE']=='PHONE') $phone = $profileVal["VALUE"];
                }
                $arLocs = CSaleLocation::GetByID($location, LANGUAGE_ID);
                $fullAddress = 'г. '.$arLocs["CITY_NAME"].', '.$address;
                ?>
                <div class="input-container full-address-container">
                    <input type="hidden" name="customer-profile-radio"  <?=($currentProfileID)?' value="'.$currentProfileID.'"':''?> />
                    <input type="text" name="full-address" value="<?=$fullAddress?>" placeholder="Город" class="form-control city-input" disabled="disabled">
                    <button class="ia-close-btn" type="submit" name="remove-profile" value="<?=$profileID?>" onclick="event.stopPropagation()">
                        <span aria-hidden="true" class="clearfix"><i aria-hidden="true" class="fa fa-times"></i></span>
                    </button>
                </div>

            <?}
            elseif($profilesCount > 1)
            {?>
                <div class="address-radio-container radio-container">
                    <input type="hidden" name="customer-profile-radio" class="ia-radio-value" <?=($currentProfileID)?' value="'.$currentProfileID.'"':''?> />
                    <?
                    while ($profile = $profiles->Fetch())
                    {?>
                        <div class="radio-button-container">
                            <?
                            $profileID = $profile['ID'];
                            $profileVals = CSaleOrderUserPropsValue::GetList(array("ID" => "ASC"), Array("USER_PROPS_ID"=>$profileID));
                            ?>
                            <div class="ia-radio-button small<?=($profileID==$currentProfileID)?' active':''?>">
                                <span class="value hidden"><?=$profileID?></span>
                               <div class="radio-dot"></div>
                            </div>
                            <?
                            $location = 0;
                            $address = '';
                            $phone = '';
                            while ($profileVal = $profileVals->Fetch())
                            {
                                if($profileVal['PROP_CODE']=='LOCATION') $location = $profileVal["VALUE"];
                                if($profileVal['PROP_CODE']=='ADDRESS') $address = $profileVal["VALUE"];
                                if($profileVal['PROP_CODE']=='PHONE') $phone = $profileVal["VALUE"];
                            }
                            $arLocs = CSaleLocation::GetByID($location, LANGUAGE_ID);
                            $fullAddress = 'г. '.$arLocs["CITY_NAME"].', '.$address;
                            ?>
                            <div class="button-title">
                                <div class="input-container">
                                    <input type="text" name="full-address" value="<?=$fullAddress?>" placeholder="Дом" class="form-control full-address-input" disabled="disabled">
                                    <button class="ia-close-btn" type="submit" name="remove-profile" value="<?=$profileID?>" onclick="event.stopPropagation()">
                                        <span aria-hidden="true" class="clearfix"><i aria-hidden="true" class="fa fa-times"></i></span>
                                    </button>
                                </div>
                                <div class="input-container">
                                    <input type="text" name="phone" value="<?=$phone?>" placeholder="Номер телефона" class="form-control phone-input" disabled="disabled">
                                </div>
                            </div>
                        </div>
                    <?
                    }?>
                </div>
            <?}?>

                <hr>
                <div class="address-container sample">
                    <div class="input-container">

                        <?$APPLICATION->IncludeComponent("bitrix:sale.location.selector.search", "template1", Array(
                                "COMPONENT_TEMPLATE" => ".default",
                                "ID" => $location,	// ID местоположения
                                "CODE" => "",	// Символьный код местоположения
                                "INPUT_NAME" => "LOCATION",	// Имя поля ввода
                                "PROVIDE_LINK_BY" => "id",	// Сохранять связь через
                                "JSCONTROL_GLOBAL_ID" => "",
                                "JS_CALLBACK" => "",	// Javascript-функция обратного вызова
                                "FILTER_BY_SITE" => "Y",	// Фильтровать по сайту
                                "SHOW_DEFAULT_LOCATIONS" => "Y",	// Отображать местоположения по-умолчанию
                                "CACHE_TYPE" => "A",	// Тип кеширования
                                "CACHE_TIME" => "36000000",	// Время кеширования (сек.)
                                "FILTER_SITE_ID" => SITE_ID,	// Cайт
                                "INITIALIZE_BY_GLOBAL_EVENT" => "",	// Инициализировать компонент только при наступлении указанного javascript-события на объекте window.document
                                "SUPPRESS_ERRORS" => "N",	// Не показывать ошибки, если они возникли при загрузке компонента
                            ),
                            false
                        );?>
                    </div>
                    <div class="street-data-fields">
                        <div class="input-container street-container">
                            <input type="text" name="street" value="" placeholder="Улица" class="form-control street-input">
                        </div>
                        <div class="input-container">
                            <input type="text" name="house-number" value="" placeholder="Дом" class="form-control house-number-input">
                        </div>
                        <div class="input-container">
                            <input type="text" name="apartment" value="" placeholder="Квартира" class="form-control apartment-input">
                        </div>
                        <div class="input-container new-phone-container">
                            <input type="text" name="new-phone" value="" placeholder="Номер телефона" class="form-control phone-input">
                        </div>
                    </div>
                    <div class="add-address-button-container">
                        <button type="submit" value="new-address" name="new-address" class="btn btn-default ia-btn text-btn blue-btn">Добавить</button>
                    </div>
                </div>
                <div class="add-button-container">
                    <div class="add-button address"><span class="yellow-text-underline"><i class="fa fa-home" aria-hidden="true"></i>Добавить ещё один адрес</span></div>
                </div>

                <div class="save-button-container visible-sm visible-xs">
                    <button type="submit" class="btn btn-default ia-btn text-btn yellow-btn" name="save" value="save">Сохранить</button>
                </div>
            </div>
        </div>

    <script>
        var profileData = new profilePersonalData();
    </script>
</form>