<?
/**
 * @global CMain $APPLICATION
 * @param array $arParams
 * @param array $arResult
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>


<div class="l">
    
    <?= ShowError($arResult["strProfileError"]); ?>
    <?
    if ($arResult['DATA_SAVED'] == 'Y') {
        header("Location: /personal/profile/");
        exit();
        //echo ShowNote(GetMessage('PROFILE_DATA_SAVED'));
    }
    ?>
    <form method="post" name="form1" class="form form_userinfo" action="<?= $arResult["FORM_TARGET"] ?>" enctype="multipart/form-data">
        <?= $arResult["BX_SESSION_CHECK"] ?>
        <input type="hidden" name="lang" value="<?= LANG ?>" />
        <input type="hidden" name="ID" value=<?= $arResult["ID"] ?> />
        <input type="hidden" name="profile-edit" value="Y" />
<?if ($arResult["arUser"]["UF_USER_TYPE"]==USER_TYPE_COMPANY) {?>
	       <div class="form__el">
            <div class="label">Название компании</div>
            <div class="w">
                <div class="inputWrap">
                    <input type="text" class="form__text" name="WORK_COMPANY" value="<?= $arResult["arUser"]["WORK_COMPANY"] ?>">
                </div>
            </div>
        </div>
<?} else {?>
        <div class="form__el">
            <div class="label">Фамилия</div>
            <div class="w">
                <div class="inputWrap">
                    <input type="text" class="form__text" name="LAST_NAME" value="<?= $arResult["arUser"]["LAST_NAME"] ?>">
                </div>
            </div>
        </div>

        <div class="form__el">
            <div class="label">Имя</div>
            <div class="w">
                <div class="inputWrap">
                    <input type="text" class="form__text" name="NAME" value="<?= $arResult["arUser"]["NAME"] ?>">
                </div>
            </div>
        </div>
        <div class="form__el">
            <div class="label">Отчество</div>
            <div class="w">
                <div class="inputWrap">
                    <input type="text" class="form__text" name="SECOND_NAME" value="<?= $arResult["arUser"]["SECOND_NAME"] ?>">
                </div>
            </div>
        </div>
	<?}?>
        <div class="form__el">
            <div class="label">Псевдоним</div>
            <div class="w">
                <div class="inputWrap">
                    <input type="text" class="form__text" name="LOGIN" value="<? echo $arResult["arUser"]["LOGIN"] ?>">
                </div>
            </div>
        </div>
        <div class="form__el">
            <div class="label">Электронная почта</div>
            <div class="w">
                <div class="inputWrap">
                    <input type="text" class="form__text" disabled="disabled" name="EMAIL" value="<? echo $arResult["arUser"]["EMAIL"] ?>">
                </div>
            </div>
        </div>

        <? /* ?>
          <div class="form__el">
          <div class="label">Пароль</div>
          <div class="w">
          <div class="inputWrap inputWrap_password">
          <input type="password" name="NEW_PASSWORD" maxlength="50" class="form__text" value="" autocomplete="off" />
          <a href="javascript:void(0);" class="showPassword"></a>
          </div>
          </div>
          </div>

          <div class="form__el">
          <div class="label">Подтверждение пароля</div>
          <div class="w">
          <div class="inputWrap inputWrap_password">
          <input type="password" name="NEW_PASSWORD_CONFIRM" maxlength="50" class="form__text" value="" autocomplete="off" />
          <a href="javascript:void(0);" class="showPassword"></a>
          </div>
          </div>
          </div>
          <? */ ?>
        <?if ($arResult["arUser"]["UF_USER_TYPE"]==USER_TYPE_PERSON) {?>
        <div class="form__el form__el_sex">
            <div class="label">Пол</div>
            <div class="w">

                <div class="radioWrap">
                    <input type="radio" name="PERSONAL_GENDER" value="M"<?= $arResult["arUser"]["PERSONAL_GENDER"] == "M" ? " checked=\"checked\"" : "" ?>  id="sex_1">
                    <label for="sex_1">мужской</label>
                </div>
                <div class="radioWrap">
                    <input type="radio" name="PERSONAL_GENDER" value="F"<?= $arResult["arUser"]["PERSONAL_GENDER"] == "F" ? " checked=\"checked\"" : "" ?>  id="sex_2">
                    <label for="sex_2">женский</label>
                </div>
            </div>
        </div>		
        <div class="form__el form__el_birthday">
            <div class="label">Дата рождения</div>
            <div class="w">
                <div class="inputWrap">
                    <span class="icon icon_calendar"></span>
                    <input type="text" class="form__text form__text_date" name="PERSONAL_BIRTHDAY" value="<?= $arResult["arUser"]["PERSONAL_BIRTHDAY"]; ?>">
                </div>
            </div>
        </div>	
        <?} elseif ($arResult["arUser"]["UF_USER_TYPE"]==USER_TYPE_COMPANY) {?>
	<div class="form__el">
            <div class="label">Юридический адрес</div>
            <div class="w">
                <div class="inputWrap">
                    <input type="text" class="form__text" name="UF_J_ADDRESS" value="<? echo $arResult["arUser"]["UF_J_ADDRESS"] ?>">
                </div>

            </div>
        </div>
	<div class="form__el">
            <div class="label">Телефон компании</div>
            <div class="w">
                <div class="inputWrap">
                    <input type="text" class="form__text" name="WORK_PHONE" value="<? echo $arResult["arUser"]["WORK_PHONE"] ?>">
                </div>

            </div>
        </div>
	<div class="form__el">
            <div class="label">Факс компании</div>
            <div class="w">
                <div class="inputWrap">
                    <input type="text" class="form__text" name="WORK_FAX" value="<? echo $arResult["arUser"]["WORK_FAX"] ?>">
                </div>

            </div>
        </div>
	 <div class="form__el">
            <div class="label">ИНН</div>
            <div class="w">
                <div class="inputWrap">
                    <input type="text" class="form__text" name="UF_USER_INN" value="<? echo $arResult["arUser"]["UF_USER_INN"] ?>">
                </div>

            </div>
        </div>
	<div class="form__el">
            <div class="label">КПП</div>
            <div class="w">
                <div class="inputWrap">
                    <input type="text" class="form__text" name="UF_USER_KPP" value="<? echo $arResult["arUser"]["UF_USER_KPP"] ?>">
                </div>

            </div>
        </div>
	<?}?>
    <? /*
        <div id="userCard" class="form__el">
            <div class="label">Номер дисконтной карты</div>
            <div class="w">
                <div class="inputWrap">
                    <input type="text" class="form__text" name="UF_USER_CARD" value="<? echo $arResult["arUser"]["UF_USER_CARD"] ?>">
                </div>

            </div>
        </div>
        
          <div class="form__el">
            <div class="label">Код подтверждения карты</div>
            <div class="w">

                <div class="inputWrap">
                    <input type="text" class="form__text" name="CARD_CONFIRM" value="">
                </div>
            </div>
        </div>
    */ ?>
        <div class="form__el form__el_submit">
            <div class="w">
                <button type="submit" name="save" value="<?= (($arResult["ID"] > 0) ? GetMessage("MAIN_SAVE") : GetMessage("MAIN_ADD")) ?>" class="button_submit-change-userinfo"><i class="fa fa-refresh"></i> <span>Сохранить</span></button>
            </div>
        </div>
    </form>
</div>
