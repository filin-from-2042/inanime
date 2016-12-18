<?
/**
 * @global CMain $APPLICATION
 * @param array $arParams
 * @param array $arResult
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>

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
        ?>
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
            <div class="save-button-container hidden-sm hidden-xs">
                <button type="submit" class="btn btn-default ia-btn text-btn yellow-btn" name="save" value="save">Сохранить</button>
            </div>
        </div>
<!--        <div class="col-sm-24 col-md-14 col-lg-14 address-column">-->
<!--            <div class="address-radio-container radio-container">-->
<!--                <input type="hidden" name="address-radio" class="ia-radio-value">-->
<!--                <div class="radio-button-container">-->
<!--                    <div class="ia-radio-button small">-->
<!--                        <span class="value hidden">male</span>-->
<!--                        <div class="radio-dot"></div>-->
<!--                    </div>-->
<!--                    <div class="button-title">-->
<!--                        <div class="input-container">-->
<!--                            <input type="text" name="full-address" value="" placeholder="Дом" class="form-control full-address-input" disabled="disabled">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="radio-button-container">-->
<!--                    <div class="ia-radio-button small">-->
<!--                        <span class="value hidden">male</span>-->
<!--                        <div class="radio-dot"></div>-->
<!--                    </div>-->
<!--                    <div class="button-title">-->
<!--                        <div class="input-container">-->
<!--                            <input type="text" name="full-address" value="" placeholder="Дом" class="form-control full-address-input" disabled="disabled">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="add-button-container">-->
<!--                    <div class="add-button address"><span class="yellow-text-underline"><i class="fa fa-home" aria-hidden="true"></i>Добавить ещё один адрес</span></div>-->
<!--                </div>-->
<!--                <hr>-->
<!--            </div>-->
<!--            <div class="address-container">-->
<!--                <div class="input-container">-->
<!--                    <input type="text" name="city" value="" placeholder="Город" class="form-control city-input">-->
<!--                </div>-->
<!--                <div class="street-data-fields">-->
<!--                    <div class="input-container">-->
<!--                        <input type="text" name="street" value="" placeholder="Улица" class="form-control street-input">-->
<!--                    </div>-->
<!--                    <div class="input-container">-->
<!--                        <input type="text" name="house-number" value="" placeholder="Дом" class="form-control house-number-input">-->
<!--                    </div>-->
<!--                    <div class="input-container">-->
<!--                        <input type="text" name="apartment" value="" placeholder="Квартира" class="form-control apartment-input">-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="add-address-button-container hidden-sm hidden-xs">-->
<!--                    <button type="submit" class="btn btn-default ia-btn text-btn blue-btn">Добавить</button>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="phone-container">-->
<!--                <div class="input-container">-->
<!--                    <input type="text" name="birthday" value="" placeholder="Номер телефона" class="form-control phone-input">-->
<!--                </div>-->
<!--                <div class="add-button-container">-->
<!--                    <div class="add-button phone"><span class="yellow-text-underline"><i class="fa fa-mobile" aria-hidden="true"></i>Добавить номер телефона</span></div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="save-button-container visible-sm">-->
<!--                <button type="button" class="btn btn-default ia-btn text-btn yellow-btn">Сохранить</button>-->
<!--            </div>-->
<!--        </div>-->
    </div>
</form>