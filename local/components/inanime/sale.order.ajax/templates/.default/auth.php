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

<div class="page page_register">
    <div class="form_order">
        <div class="user-info">
<div class="title"><span>Данные для заказа</span></div>
            <div class="choise-user">
                <div class="radioWrap">
                    <input type="radio" name="choise_user" id="choise_user_new" checked="checked">
                    <label for="choise_user_new">
                        Я &mdash; новый пользователь на сайте
                    </label>
                </div>

                <div class="radioWrap">
                    <input type="radio" name="choise_user" id="choise_user_old">
                    <label for="choise_user_old">
                        У меня уже есть аккаунт тут
                    </label>
                </div>

                <? if ($_SESSION["USER_VALUES"]["order"]["ALLOW_AUTO_REGISTER"] == "Y") { ?>
                    <a href="javascript:void(0);" id="orderWithReg">Заказ с регистрацией</a>
                <? } else { ?>
                    <a href="javascript:void(0);" id="orderWithoutReg">Заказ без регистрации</a>
                <? } ?>
            </div>

            <div class="user-info__form">
                <div class="overlay">
                    <span class="icon icon_preloader"></span>
                </div>

                <div class="part part_user-new" id="part_user-new">
                    <form method="post" action="" name="order_reg_form" class="form">
                        <?= bitrix_sessid_post() ?>
                        <?
                        foreach ($arResult["POST"] as $key => $value) {
                            ?>
                            <input type="hidden" name="<?= $key ?>" value="<?= $value ?>" />
                            <?
                        }
                        ?>
                        <div class="col col_left">

                            <div class="form__el">
                                <div class="labelWrap">
                                    <label for="NEW_LAST_NAME">Фамилия</label>
                                </div>
                                <div class="inputWrap">
                                    <input type="text" name="NEW_LAST_NAME" id="NEW_LAST_NAME" tabindex="1" class="form__text" value="<?= $arResult["AUTH"]["NEW_LAST_NAME"] ?>" />
                                    <? if (strlen($arResult["ERROR"]["NEW_LAST_NAME"])) { ?>
                                        <div class="form__error" title="<?= $arResult["ERROR"]["NEW_LAST_NAME"]; ?>"><i class="fa fa-exclamation-triangle"></i> <span class="no-js-text"><?= $arResult["ERROR"]["NEW_LAST_NAME"]; ?></span></div>
                                    <? } ?>
                                </div>
                            </div>

                            <div class="form__el">
                                <div class="labelWrap">
                                    <label for="NEW_NAME">Имя</label>
                                </div>
                                <div class="inputWrap">
                                    <input type="text" name="NEW_NAME" id="NEW_NAME" tabindex="2" class="form__text" value="<?= $arResult["AUTH"]["NEW_NAME"] ?>" />                                
                                    <? if (strlen($arResult["ERROR"]["NEW_NAME"])) { ?>
                                        <div class="form__error" title="<?= $arResult["ERROR"]["NEW_NAME"]; ?>"><i class="fa fa-exclamation-triangle"></i> <span class="no-js-text"><?= $arResult["ERROR"]["NEW_NAME"]; ?></span></div>
                                    <? } ?>
                                </div>
                            </div>

                            <div class="form__el">
                                <div class="labelWrap">
                                    <label for="NEW_SECOND_NAME">Отчество</label>
                                </div>
                                <div class="inputWrap">
                                    <input type="text" name="NEW_SECOND_NAME" id="NEW_SECOND_NAME" tabindex="3" class="form__text" value="<?= $arResult["AUTH"]["NEW_SECOND_NAME"] ?>" />
                                    <? if (strlen($arResult["ERROR"]["NEW_SECOND_NAME"])) { ?>
                                        <div class="form__error" title="<?= $arResult["ERROR"]["NEW_SECOND_NAME"]; ?>"><i class="fa fa-exclamation-triangle"></i> <span class="no-js-text"><?= $arResult["ERROR"]["NEW_SECOND_NAME"]; ?></span></div>
                                    <? } ?>
                                </div>
                            </div>
                            <div class="form__el">
                                <div class="labelWrap">
                                    <label for="NEW_PHONE">Телефон</label>
                                </div>
                                <div class="inputWrap">
                                    <input type="text" name="NEW_PHONE" id="NEW_PHONE" tabindex="4" class="form__text" value="<?= $arResult["AUTH"]["NEW_PHONE"] ?>" />
                                    <? if (strlen($arResult["ERROR"]["NEW_PHONE"])) { ?>
                                        <div class="form__error" title="<?= $arResult["ERROR"]["NEW_PHONE"]; ?>"><i class="fa fa-exclamation-triangle"></i> <span class="no-js-text"><?= $arResult["ERROR"]["NEW_PHONE"]; ?></span></div>
                                    <? } ?>
                                </div>
                            </div>
                            <div class="form__el">
                                <div class="labelWrap">
                                    <label for="NEW_EMAIL">Электронная почта</label>
                                </div>
                                <div class="inputWrap">
                                    <input type="text" name="NEW_EMAIL" id="NEW_EMAIL" tabindex="5" class="form__text" value="<?= $arResult["AUTH"]["NEW_EMAIL"] ?>" />
                                    <? if (strlen($arResult["ERROR"]["NEW_EMAIL"])) { ?>
                                        <div class="form__error" title="<?= $arResult["ERROR"]["NEW_EMAIL"]; ?>"><i class="fa fa-exclamation-triangle"></i> <span class="no-js-text"><?= $arResult["ERROR"]["NEW_EMAIL"]; ?></span></div>
                                    <? } ?>
                                </div>
                            </div>

                        </div>

                        <div class="col col_left second-col<? if (!$USER->IsAuthorized()) echo ' full-length'; ?>">
                            <div class="inner-col">
                                <div class="form__el">
                                    <div class="labelWrap">
                                        <label for="NEW_LOGIN">Логин</label>
                                    </div>
                                    <div class="inputWrap">
                                        <input type="text" name="NEW_LOGIN" id="NEW_LOGIN" tabindex="6" class="form__text" value="<?= $arResult["AUTH"]["NEW_LOGIN"] ?>" />
                                        <? if (strlen($arResult["ERROR"]["NEW_LOGIN"])) { ?>
                                            <div class="form__error" title="<?= $arResult["ERROR"]["NEW_LOGIN"]; ?>"><i class="fa fa-exclamation-triangle"></i> <span class="no-js-text"><?= $arResult["ERROR"]["NEW_LOGIN"]; ?></span></div>
                                        <? } ?>
                                    </div>
                                </div>

                                <div class="form__el">
                                    <div class="labelWrap">
                                        <label for="NEW_PASSWORD">Пароль</label>
                                    </div>
                                    <div class="inputWrap inputWrap_password">
                                        <input type="password" name="NEW_PASSWORD" id="NEW_PASSWORD" tabindex="7" class="form__text" value="" />
                                        <a href="javascript:void(0);" class="showPassword"></a>
                                        <? if (strlen($arResult["ERROR"]["NEW_PASSWORD"])) { ?>
                                            <div class="form__error" title="<?= $arResult["ERROR"]["NEW_PASSWORD"]; ?>"><i class="fa fa-exclamation-triangle"></i> <span class="no-js-text"><?= $arResult["ERROR"]["NEW_PASSWORD"]; ?></span></div>
                                        <? } ?>
                                    </div>
                                </div>

                                <div class="form__el">
                                    <div class="labelWrap">
                                        <label for="NEW_PASSWORD_CONFIRM">Подтверждение пароля</label>
                                    </div>
                                    <div class="inputWrap inputWrap_password">
                                        <input type="password" name="NEW_PASSWORD_CONFIRM" id="NEW_PASSWORD_CONFIRM" tabindex="8" class="form__text" value="" />
                                        <a href="javascript:void(0);" class="showPassword"></a>
                                        <? if (strlen($arResult["ERROR"]["NEW_PASSWORD_CONFIRM"])) { ?>
                                            <div class="form__error" title="<?= $arResult["ERROR"]["NEW_PASSWORD_CONFIRM"]; ?>"><i class="fa fa-exclamation-triangle"></i> <span class="no-js-text"><?= $arResult["ERROR"]["NEW_PASSWORD_CONFIRM"]; ?></span></div>
                                        <? } ?>
                                    </div>
                                </div>

                                <?
                                if ($arResult["AUTH"]["captcha_registration"] == "Y") { //CAPTCHA
                                    ?>
                                    <div class="form__el form__el_captcha">
                                        <div class="labelWrap">
                                            <label for="captcha">Код проверки</label>
                                        </div>
                                        <div class="w group">
                                            <div class="captchaWrap">
                                                <input type="hidden" name="captcha_sid" value="<?= $arResult["AUTH"]["capCode"] ?>">
                                                <img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["AUTH"]["capCode"] ?>" width="147" height="48" alt="CAPTCHA" class="captcha-image">
                                            </div>
                                            <div class="inputWrap">
                                                <input type="text" name="captcha_word" maxlength="10" id="captcha" tabindex="9" class="form__text form__text_captcha text-placeholder">
                                                <? if (strlen($arResult["ERROR"]["CAPTCHA_ERROR"])) { ?>
                                                    <div class="form__error" title="<?= strip_tags($arResult["ERROR"]["CAPTCHA_ERROR"]); ?>"><i class="fa fa-exclamation-triangle"></i> <span class="no-js-text"><?= strip_tags($arResult["ERROR"]["CAPTCHA_ERROR"]); ?></span></div>
                                                <? } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?
                                }
                                ?>
                            </div>
                            <div class="form__el form__el_accept">
                                <div class="checkboxWrap">
                                    <label for="ACCEPT_NEW">
                                        <input type="checkbox" name="ACCEPT_NEW" id="ACCEPT_NEW" tabindex="10" value="Y">
                                        <span class="multiline">Я прочитал и согласен с Условиями обработки моих персональных данных и ознакомлен с Политикой конфиденциальности компании.</span>
                                    </label>
                                </div>
                                <? if (strlen($arResult["ERROR"]["ACCEPT_NEW"])) { ?>
                                    <div class="form__error" title="<?= strip_tags($arResult["ERROR"]["ACCEPT_NEW"]); ?>"><i class="fa fa-exclamation-triangle"></i> <span class="no-js-text"><?= strip_tags($arResult["ERROR"]["ACCEPT_NEW"]); ?></span></div>
                                <? } ?>
                            </div>
                        </div>

                        <div class="submitWrap">
                            <input type="hidden" name="do_register" value="Y">
                            <button type="submit" name="submit" value="submit" class="button button_submit-order"><span>Продолжить оформление заказа <i class="fa fa-arrow-right"></i></span></button>
                        </div>

                    </form>
                </div>

                <div class="part part_user-old" id="part_user-old" style="display: none;">
                    <form method="post" action="" name="order_auth_form">
                        <?= bitrix_sessid_post() ?>
                        <?
                        foreach ($arResult["POST"] as $key => $value) {
                            ?>
                            <input type="hidden" name="<?= $key ?>" value="<?= $value ?>" />
                            <?
                        }
                        ?>


                        <div class="col col_left">
                            <div class="form__el">
                                <div class="labelWrap">
                                    <label for="USER_LOGIN">Логин</label>
                                </div>
                                <div class="inputWrap">
                                    <input type="text" name="USER_LOGIN" id="USER_LOGIN" maxlength="50" value="" class="form__text" />
                                </div>
                            </div>
                        </div>
                        <div class="col col_right">
                            <div class="form__el">
                                <div class="labelWrap">
                                    <label for="USER_PASSWORD">Пароль</label>
                                </div>
                                <div class="inputWrap">
                                    <input type="password" name="USER_PASSWORD" id="USER_PASSWORD" maxlength="50" class="form__text" />
                                    <a href="/auth/?forgot_password=yes" class="forgot">забыли пароль?</a>
                                </div>
                            </div>
                        </div>
                        <div class="submitWrap">
                            <button type="submit" name="submit" value="submit" class="button button_submit-order"><span>Войти и завершить оформление <i class="fa fa-arrow-right"></i></span></button>
                            <input type="hidden" name="do_authorize" value="Y" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>