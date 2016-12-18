<?
/**
 * @global CMain $APPLICATION
 * @param array $arParams
 * @param array $arResult
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>

<div class="cabinet-password">
    <div class="l">
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

            <div class="form__el">
                <div class="label">Старый пароль</div>
                <div class="w">
                    <div class="inputWrap inputWrap_password">
                        <input type="password" name="OLD_PASSWORD" maxlength="50" class="form__text" tabindex="1" value="" autocomplete="off" />
                        <a href="javascript:void(0);" class="showPassword"></a>
                    </div>
                </div>
            </div>

            <div class="form__el">
                <div class="label">Новый пароль</div>
                <div class="w">
                    <div class="inputWrap inputWrap_password">
                        <input type="password" name="NEW_PASSWORD" maxlength="50" class="form__text" tabindex="2" value="" autocomplete="off" />
                        <a href="javascript:void(0);" class="showPassword"></a>
                    </div>
                </div>
            </div>
            <div class="form__el form__el_submit">
                <div class="w">
                    <button type="submit" name="save" value="<?= (($arResult["ID"] > 0) ? GetMessage("MAIN_SAVE") : GetMessage("MAIN_ADD")) ?>" class="button button_submit-register"><span>Сохранить пароль</span></button>
                </div>
            </div>

        </form>
    </div>
    <div class="r">
        <div class="banner banner_password">
            <?
            $APPLICATION->IncludeComponent("bitrix:advertising.banner", "", Array(
                "TYPE" => "password_page",
                "CACHE_TYPE" => "A",
                "NOINDEX" => "Y",
                "CACHE_TIME" => "3600"
                    )
            );
            ?>        
        </div>
    </div>
</div>
