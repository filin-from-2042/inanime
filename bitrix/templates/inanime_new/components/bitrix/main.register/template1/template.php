<?
if (strlen($_POST['ajax_key']) && $_POST['ajax_key']==md5('ajax_'.LICENSE_KEY) && $_POST['typeAction']=='registration') {
    $APPLICATION->RestartBuffer();
    if (!defined('PUBLIC_AJAX_MODE')) {
        define('PUBLIC_AJAX_MODE', true);
    }
    header('Content-type: application/json');

    if ($arResult['ERROR']) {
        echo json_encode(array(
            'type' => 'error',
            'message' => strip_tags($arResult['ERROR_MESSAGE']['MESSAGE']),
        ));
    } else {
        echo json_encode(array('type' => 'ok'));
    }

    //var_dump($arResult['ERROR']);
    require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
    die();
}
?>
<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
    die();
?>
<?
if (count($arResult["ERRORS"]) > 0):
    foreach ($arResult["ERRORS"] as $key => $error)
        if (intval($key) == 0 && $key !== 0)
            $arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;".GetMessage("REGISTER_FIELD_".$key)."&quot;", $error);

    ShowError(implode("<br />", $arResult["ERRORS"]));

elseif($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):
?>
    <p><?echo GetMessage("REGISTER_EMAIL_WILL_BE_SENT")?></p>
<?endif?>

<?//var_dump($arResult["SHOW_FIELDS"])?>
<form  method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform" enctype="multipart/form-data" id="registr-area">
    <?
    if($arResult["BACKURL"] <> ''):
        ?>
        <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
    <?
    endif;
    ?>
    <input type="hidden" name="typeAction" value="registration" />

    <div class="icon-input-container">
        <div class="icon-input-wrap">
            <input type="text" name="REGISTER[LOGIN]" value="" placeholder="Имя" class="form-control username-input">
            <i class="fa fa-user" aria-hidden="true"></i>
        </div>
    </div>
    <div class="icon-input-container">
        <div class="icon-input-wrap">
            <input type="text" name="REGISTER[EMAIL]" value="" placeholder="Электронная почта" class="form-control email-input">
            <i class="fa fa-envelope-o" aria-hidden="true"></i>
        </div>
    </div>
    <div class="icon-input-container">
        <div class="icon-input-wrap">
            <input type="password" size="30" name="REGISTER[PASSWORD]" value="" placeholder="Пароль" class="form-control password-input">
            <i class="fa fa-lock" aria-hidden="true"></i>
        </div>
    </div>
    <div class="icon-input-container">
        <div class="icon-input-wrap">
            <input type="password" size="30" name="REGISTER[CONFIRM_PASSWORD]" value="" placeholder="Повторите пароль" class="form-control password-repeat-input">
            <i class="fa fa-lock" aria-hidden="true"></i>
        </div>
    </div>
    <?
    /* CAPTCHA */
    if ($arResult["USE_CAPTCHA"] == "Y")
    {
    ?>
    <div class="row captcha-input-image">
        <div class="col-xs-11 col-sm-11 col-md-11 col-lg-11 captcha-input-column">
            <div class="input-container">
                <input type="text" name="captcha_word" maxlength="50" value="" placeholder="Введите символы" class="form-control captcha-symbols-input">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 captcha-image-column">
            <div class="captcha-container">
                <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
                <img class="captcha" src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
            </div>
        </div>
    </div>
    <?
    }
    /* !CAPTCHA */
    ?>

    <div class="row autorization-social">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 autorization-column">
            <div class="autorization-btn-container">
                <span class="autorization-btn brown-dotted-text" >Авторизация</span>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 social-column">
            <div class="social-container">
                <?
                if(!$USER->IsAuthorized() && CModule::IncludeModule("socialservices"))
                {
                    $oAuthManager = new CSocServAuthManager();
                    $arServices = $oAuthManager->GetActiveAuthServices($arResult);

                    if(!empty($arServices))
                    {
                        $arResult["AUTH_SERVICES"] = $arServices;
                        if(isset($_REQUEST["auth_service_id"]) && $_REQUEST["auth_service_id"] <> '' && isset($arResult["AUTH_SERVICES"][$_REQUEST["auth_service_id"]]))
                        {
                            $arResult["CURRENT_SERVICE"] = $_REQUEST["auth_service_id"];
                            if(isset($_REQUEST["auth_service_error"]) && $_REQUEST["auth_service_error"] <> '')
                            {
                                $arResult['ERROR_MESSAGE'] = $oAuthManager->GetError($arResult["CURRENT_SERVICE"], $_REQUEST["auth_service_error"]);
                            }
                            elseif(!$oAuthManager->Authorize($_REQUEST["auth_service_id"]))
                            {
                                $ex = $APPLICATION->GetException();
                                if ($ex)
                                $arResult['ERROR_MESSAGE'] = $ex->GetString();
                            }
                        }
                    }
                }?>

                    <?$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "flat1",
                        array(
                            "AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
                            "SUFFIX"=>"form",
                        ),
                        $component,
                        array("HIDE_ICONS"=>"Y")
                    );
                    ?>
            </div>
        </div>
    </div>
    <div class="status-container">
        <div class="success">Ответ на Ваш вопрос можно будет прочитать в личном кабинете</div>
    </div>
    <div class="button-container">
        <button class="btn btn-default ia-btn text-btn blue-btn" type="submit" name="register_submit_button" value="Зарегистрироваться">
            <span>Зарегистрироваться</span>
        </button>
    </div>
    <script type="text/javascript">
        $(document).on('ready', function(){
            $('#registr-area .autorization-btn').click(function(){
                $("#registration-modal").modal('hide');
                $("#autorization-modal").modal('show');
            });
            //$('#login-area .registration-social').click(function(){ $(this).closest('.ia-modal').modal('toggle')});
            $('#registr-area').submit(function(){
                var $this = $(this);
                var $form = {
                    action: $this.attr('action'),
                    post: {'ajax_key':'<?= md5('ajax_'.LICENSE_KEY)?>',register_submit_button:'register_submit_button'}
                };
                $.each($('input', $this), function(){
                    if ($(this).attr('name').length) {
                        $form.post[$(this).attr('name')] = $(this).val();
                    }
                });
                $.post($form.action, $form.post, function(data){
                    $('input', $this).removeAttr('disabled');

                    console.log(data);
                    if (data.type == 'error') {
                        alert(data.message);
                    } else {
                        window.location = window.location;
                    }
                }, 'json');
                return false;
            });
        });
    </script>
</form>