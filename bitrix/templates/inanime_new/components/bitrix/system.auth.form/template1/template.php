
<?
if (strlen($_POST['ajax_key']) && $_POST['ajax_key']==md5('ajax_'.LICENSE_KEY) && $_POST['typeAction']=='authentification') {
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
    require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
    die();
}
?>

<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

    <div class="bx-system-auth-form">

<?
if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'])
    ShowMessage($arResult['ERROR_MESSAGE']);
?>
<?if($arResult["FORM_TYPE"] == "login"){?>
    <form name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>" id="login-area">

        <input type="hidden" name="typeAction" value="authentification" />

        <?if($arResult["BACKURL"] <> ''):?>
            <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
        <?endif?>
        <?foreach ($arResult["POST"] as $key => $value):?>
            <input type="hidden" name="<?=$key?>" value="<?=$value?>" />
        <?endforeach?>
        <input type="hidden" name="AUTH_FORM" value="Y" />
        <input type="hidden" name="TYPE" value="AUTH" />

        <div class="icon-input-container">
            <div class="icon-input-wrap">
                <input type="text" name="USER_LOGIN" value="<?=$arResult["USER_LOGIN"]?>" placeholder="Электронная почта" class="form-control email-input">
                <i class="fa fa-envelope-o" aria-hidden="true"></i>
            </div>
        </div>
        <div class="icon-input-container">
            <div class="icon-input-wrap">
                <input type="password" name="USER_PASSWORD" value="" placeholder="Пароль" class="form-control password-input">
                <i class="fa fa-lock" aria-hidden="true"></i>
            </div>
        </div>
        <div class="reset-link-container">
            <span class="yellow-text-underline reset-link">Забыли пароль?</span>
        </div>
    </form>
        <div class="row registration-social">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 registration-column">
                <div class="autorization-btn-container" >
                    <span class="autorization-btn brown-dotted-text">Регистрация</span>
                </div>
            </div>
            <?if($arResult["AUTH_SERVICES"]):?>
                <?
                $APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "flat1",
                    array(
                        "AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
                        "SUFFIX"=>"form",
                    ),
                    $component,
                    array("HIDE_ICONS"=>"Y")
                );
                ?>
            <?endif?>

        </div>
        <div class="button-container">
            <button class="btn btn-default ia-btn text-btn blue-btn" type="submit" name="Login" value="<?=GetMessage("AUTH_LOGIN_BUTTON")?>" onclick="$('#login-area').submit()">
                <span>Войти</span>
            </button>
        </div>
        <script type="text/javascript">
            $(document).on('ready', function(){
                $('#login-area .registration-social').click(function(){ $("#autorization-modal").modal('hide');});

                $('#login-area .autorization-btn').click(function(){
                    $("#autorization-modal").modal('hide');
                    $("#registration-modal").modal('show');
                });

                $('#login-area .reset-link').click(function(){
                    $("#autorization-modal").modal('hide');
                    $("#password-reset-modal").modal('show');
                });

                $('#login-area').submit(function(){
                    var $this = $(this);
                    var $form = {
                        action: $this.attr('action'),
                        post: {'ajax_key':'<?= md5('ajax_'.LICENSE_KEY)?>'}
                    };
                    $.each($('input', $this), function(){
                        if ($(this).attr('name').length) {
                            $form.post[$(this).attr('name')] = $(this).val();
                        }
                    });
                    $.post($form.action, $form.post, function(data){
                        $('input', $this).removeAttr('disabled');
                        if (data.type == 'error') {
                            alert(data.message);
                        } else {
                            window.location = window.location;
                        }
                    }, 'json');
                    return false;
                });
            })
        </script>
<?}else if($arResult["FORM_TYPE"] == "otp"){?>
<?}else{?>
<?}?>
</div>