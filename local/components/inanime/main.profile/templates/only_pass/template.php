<?
/**
 * @global CMain $APPLICATION
 * @param array $arParams
 * @param array $arResult
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<h2>Изменение пароля</h2>
<?= ShowError($arResult["strProfileError"]); ?>
<?
if ($arResult['DATA_SAVED'] == 'Y') {
    echo ShowNote('Данные сохранены');
}
?>
<form method="post" name="form1" class="form form_userinfo form_userinfo_password" action="<?= $arResult["FORM_TARGET"] ?>" enctype="multipart/form-data">
    <?= $arResult["BX_SESSION_CHECK"] ?>
    <input type="hidden" name="lang" value="<?= LANG ?>" />
    <input type="hidden" name="ID" value=<?= $arResult["ID"] ?> />
    <input type="hidden" name="change-password" value="Y" />

    <div class="row">
        <div class="col-sm-24 col-md-12 col-lg-12 password-column">
            <div class="input-container">
                <input type="password" name="OLD_PASSWORD"  tabindex="1" value="" placeholder="Введите текущий пароль" class="form-control name-input">
            </div>
            <div class="input-container">
                <input type="text" name="NEW_PASSWORD" tabindex="2" value="" placeholder="Введите новый пароль" class="form-control second-name-input">
            </div>
            <div class="input-container">
                <input type="text" name="NEW_PASSWORD_CONFIRM" tabindex="3" value="" placeholder="Повторите новый пароль" class="form-control birthday-input">
            </div>
            <div class="change-button-container">
                <button type="submit" class="btn btn-default ia-btn text-btn yellow-btn" name="save" value="save">Изменить</button>
            </div>
        </div>
<!--        <div class="col-sm-24 col-md-12 col-lg-12 confirm-column">-->
<!--            <div class="confirm-container">-->
<!--                <div class="ia-checkbox">-->
<!--                    <input type="checkbox" id="subscribe-check" checked="checked" name="subscribe-add">-->
<!--                    <label for="subscribe-check">Я хочу получать новости компании, а также информацию об акциях и новых предложениях</label>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
    </div>
</form>