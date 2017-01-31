<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<script>
    /*
     function ChangeGenerate(val)
     {
     if (val)
     {
     document.getElementById("sof_choose_login").style.display = 'none';
     }
     else
     {
     document.getElementById("sof_choose_login").style.display = 'block';
     document.getElementById("NEW_GENERATE_N").checked = true;
     }
     
     try {
     document.order_reg_form.NEW_LOGIN.focus();
     } catch (e) {
     }
     }
     */
</script>

<div class="order-drawing-up auth-forms">
    <div class="order-drawing-up-header">
        <?$APPLICATION->IncludeComponent(
            "bitrix:breadcrumb",
            "catalog-chain",
            Array(
                "START_FROM" => "0",
                "PATH" => "",
                "SITE_ID" => SITE_ID
            )
        );
        ?>
        <h1 class="ia-page-title">Оформление заказа</h1>
    </div>
    <div class="container">

        <div class="radio-tab-control-wrap">
            <div class="row">

                <div class="col-sm-6 col-md-6 col-lg-6">
                    <?/*?>
                    <div class="radio-controls-container">
                        <div class="radio-control active" data-contentid="user-new-data">
                            <input type="radio" name="choise_user" id="choise_user_new" checked="checked">
                            <label for="choise_user_new">
                                Я &mdash; новый пользователь на сайте
                            </label>
                        </div>
                        <div class="radio-control"  data-contentid="user-old-data">
                            <input type="radio" name="choise_user" id="choise_user_old">
                            <label for="choise_user_old">
                                У меня уже есть аккаунт тут
                            </label>
                        </div>
                    </div>
                    <?*/?>
                    <div class="radio-container">
                        <div class="radio-button-container user-type">
                            <div class="ia-radio-button small active" data-contentid="user-new-data">
                                <span class="value hidden">choise_user</span>
                                <div class="radio-dot"></div>
                            </div>
                            <div class="button-title">Я &mdash; новый пользователь</div>
                        </div>

                        <div class="radio-button-container user-type">
                            <div class="ia-radio-button small" data-contentid="user-old-data">
                                <span class="value hidden">choise_user_old</span>
                                <div class="radio-dot"></div>
                            </div>
                            <div class="button-title">У меня уже есть аккаунт</div>
                        </div>
                        <input type="hidden" name="choise_user" value="choise_user" />
                    </div>

                    <? if ($_SESSION["USER_VALUES"]["order"]["ALLOW_AUTO_REGISTER"] == "Y") { ?>
                        <a href="javascript:void(0);" class="yellow-text-underline"  id="orderWithReg">Заказ с регистрацией</a>
                    <? } else { ?>
                        <a href="javascript:void(0);" class="yellow-text-underline"  id="orderWithoutReg">Заказ без регистрации</a>
                    <? } ?>

                    <script>
                        $(document).ready(function ()
                        {
                            $('.radio-button-container.user-type .ia-radio-button,.radio-button-container.user-type .button-title').click(inAnimeOrderAjax.changeUserType);
                        });
                    </script>
                </div>
                <div class="col-sm-18 col-md-18 col-lg-18">
                    <div class="radio-tabs-container ">
                        <div class="radio-content active" id="user-new-data">

                            <form method="post" action="" name="order_reg_form" class="form">
                                <?= bitrix_sessid_post() ?>
                                <?
                                foreach ($arResult["POST"] as $key => $value) {
                                    ?>
                                    <input type="hidden" name="<?= $key ?>" value="<?= $value ?>" />
                                <?
                                }
                                ?>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="input-container">
                                            <input type="text" placeholder="Фамилия" name="NEW_LAST_NAME" id="NEW_LAST_NAME" tabindex="1" class="form-control" value="<?= $arResult["AUTH"]["NEW_LAST_NAME"] ?>" />
                                        </div>
                                        <div class="input-container">
                                            <input type="text" placeholder="Имя" name="NEW_NAME" id="NEW_NAME" tabindex="2" class="form-control" value="<?= $arResult["AUTH"]["NEW_NAME"] ?>" /></div>
                                        <div class="input-container">
                                            <input type="text" placeholder="Отчество" name="NEW_SECOND_NAME" id="NEW_SECOND_NAME" tabindex="3" class="form-control" value="<?= $arResult["AUTH"]["NEW_SECOND_NAME"] ?>" /></div>
                                        <div class="input-container">
                                            <input type="text" placeholder="Телефон" name="NEW_PHONE" id="NEW_PHONE" tabindex="4" class="form-control" value="<?= $arResult["AUTH"]["NEW_PHONE"] ?>" /></div>
                                        <div class="input-container">
                                            <input type="text" name="NEW_EMAIL" placeholder="Email" id="NEW_EMAIL" tabindex="5" class="form-control" value="<?= $arResult["AUTH"]["NEW_EMAIL"] ?>" /></div>


                                        <? $arAllPropCode = array("ZIP", "COMPANY", "CONTACT_PERSON", "FIO", "EMAIL", "PHONE", "COMPANY_ADR", "FAX", "INN", "KPP", "ADDRESS"); ?>
                                        <? $arErrors = $arResult["ERROR"]; ?>
                                        <? foreach ($arErrors as $key => $error): ?>
                                            <? if(array_search($key, $arAllPropCode)): ?>
                                                <? unset($arErrors[$key]); ?>
                                            <? endif; ?>
                                        <? endforeach; ?>
                                        <? if(count($arErrors)): ?>
                                            <? $error = reset($arErrors); ?>
                                            <div class="error-container">
                                                <span class="no-js-text "><?= $error; ?></span>
                                            </div>
                                        <? endif; ?>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="input-container">
                                            <input type="text" placeholder="Логин" name="NEW_LOGIN" id="NEW_LOGIN" tabindex="6" class="form-control" value="<?= $arResult["AUTH"]["NEW_LOGIN"] ?>" />
                                        </div>
                                        <div class="input-container">
                                            <input type="password" placeholder="Пароль" name="NEW_PASSWORD" id="NEW_PASSWORD" tabindex="7" class="form-control" value="" />
                                        </div>
                                        <div class="input-container">
                                            <input type="password" placeholder="Подтверждение пароля" name="NEW_PASSWORD_CONFIRM" id="NEW_PASSWORD_CONFIRM" tabindex="8" class="form-control" value="" />
                                        </div>
                                        <div class="input-container">
                                            <div class="captchaWrap">
                                                <input type="hidden" name="captcha_sid" value="<?= $arResult["AUTH"]["capCode"] ?>">
                                                <img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["AUTH"]["capCode"] ?>" width="147" height="48" alt="CAPTCHA" class="captcha-image">
                                            </div>
                                            <div class="inputWrap">
                                                <input type="text" placeholder="Код проверки" name="captcha_word" maxlength="10" id="captcha" tabindex="9" class="form__text form__text_captcha text-placeholder form-control">
                                            </div>
                                        </div>

                                        <div class="input-container">
                                            <div class="ia-checkbox">
                                                <input type="checkbox" id="ACCEPT_NEW" <?=($arResult['SUBSCRIBED']=='Y'?'checked="checked"':'')?> name="ACCEPT_NEW" tabindex="10" value="Y">
                                                <label for="ACCEPT_NEW">Я прочитал и согласен с Условиями обработки моих персональных данных и ознакомлен с Политикой конфиденциальности компании.</label>
                                            </div>
                                        </div>

                                        <div class="button-container">
                                            <input type="hidden" name="do_register" value="Y">
                                            <button type="submit" name="submit" value="submit" class="btn btn-default ia-btn text-btn yellow-btn" ><span>Продолжить оформление заказа <i class="fa fa-arrow-right"></i></span></button>
                                        </div>

                                    </div>
                                </div>
                            </form>

                        </div>
                        <div class="radio-content" id="user-old-data">

                            <?$APPLICATION->IncludeComponent(
                                "bitrix:system.auth.form",
                                "template2",
                                Array(
                                    "REGISTER_URL" => "/auth/",
                                    "REGISTER_URL" => "/auth/",
                                    "PROFILE_URL" => "/personal/profile/"
                                )
                            );?>


                        </div>
                    </div>
                </div>

            </div>
        </div>
        <script>
            $(document).ready(function()
            {/*
                $('.radio-tab-control-wrap .radio-control').click(function(event)
                {
                    var $this = $(this);
                    if(!$this.hasClass('active'))
                    {
                        var contentID = $(this).attr('data-contentid');
                        $this.closest('.radio-tab-control-wrap').find('.radio-control.active').removeClass('active');
                        $this.addClass('active');
                        $this.closest('.radio-tab-control-wrap').find('.radio-tabs-container .active').removeClass('active');
                        $this.closest('.radio-tab-control-wrap').find('.radio-tabs-container #'+contentID).addClass('active');
                    }
                });*/
            });
        </script>

    </div>
</div>