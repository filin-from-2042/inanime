<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

ShowMessage($arParams["~AUTH_RESULT"]);

?>

<form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>" id="forgotpwd">
    <?
    if (strlen($arResult["BACKURL"]) > 0)
    {
        ?>
        <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
    <?
    }
    ?>
    <input type="hidden" name="AUTH_FORM" value="Y">
    <input type="hidden" name="TYPE" value="SEND_PWD">

    <div class="reset-title-container">
        <span class="reset-title">
            Укажите адрес электронной почты, и мы вышлем Вам ссылку для восстановления пароля
        </span>
    </div>
    <div class="icon-input-container">
        <div class="icon-input-wrap">
            <input type="text" name="USER_EMAIL" maxlength="255" value="" placeholder="Электронная почта" class="form-control email-input">
            <i class="fa fa-envelope-o" aria-hidden="true"></i>
        </div>
    </div>
    <div class="row registration-social">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 autorization-column">
            <div class="autorization-btn-container">
                <span class="autorization-btn brown-dotted-text">Авторизация</span>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 registration-column">
            <div class="registration-btn-container">
                <span class="registration-btn brown-dotted-text">Регистрация</span>
            </div>
        </div>
    </div>
    <div class="button-container">
        <button class="btn btn-default ia-btn text-btn blue-btn" type="submit" name="send_account_info" value="Восстановить">
            <span>Восстановить</span>
        </button>
    </div>

    <script type="text/javascript">
        $(document).on('ready', function(){

            $('#forgotpwd .autorization-btn').click(function(){
                $("#password-reset-modal").modal('hide');
                $("#autorization-modal").modal('show');
            });

            $('#forgotpwd .registration-btn').click(function(){
                $("#password-reset-modal").modal('hide');
                $("#registration-modal").modal('show');
            });
/*
            $('#forgotpwd').submit(function(){
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
            });*/
        });
    </script>

</form>