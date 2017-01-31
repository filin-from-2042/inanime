
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

    <div class="bx-system-auth-form">

<?
if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'])
    ShowMessage($arResult['ERROR_MESSAGE']);
?>
<?if($arResult["FORM_TYPE"] == "login"){?>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <form name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?$arResult["AUTH_URL"]?>" id="login-area2">

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
                <div class="button-container">
                    <button class="btn btn-default ia-btn text-btn blue-btn" type="submit" name="Login" value="<?=GetMessage("AUTH_LOGIN_BUTTON")?>" onclick="$('#login-area2').submit()">
                        <span>Войти</span>
                    </button>
                </div>
            </form>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="row registration-social">
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
        </div>
    </div>
    <script>
        $(document).on('ready', function(){
            $('#login-area2 .reset-link').click(function(){
                $("#password-reset-modal").modal('show');
            });
        });
    </script>
<?}else if($arResult["FORM_TYPE"] == "otp"){?>
<?}else{?>
<?}?>
</div>