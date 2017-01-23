<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="section-personal-header ia-top-breadcrumb-title-container">
    <?/*$APPLICATION->IncludeComponent(
        "bitrix:breadcrumb",
        "catalog-chain",
        Array(
            "START_FROM" => "0",
            "PATH" => "",
            "SITE_ID" => SITE_ID
        )
    );
    ?>
    <?
    $APPLICATION->AddChainItem('Смена пароля');
   */ ?>
    <div class="row"><div class="col-md-24 col-lg-24"><div class="bx-breadcrumb">
                <div class="bx-breadcrumb-item" id="bx_breadcrumb_0" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb" itemref="bx_breadcrumb_1">

                    <a href="/" title="Главная" itemprop="url">
                        <span itemprop="title">Главная</span>
                    </a>
                    </div>
                <div class="bx-breadcrumb-item">
                    /
                    <span>Смена пароля</span>
                </div><div style="clear:both"></div>
            </div>
        </div>
    </div>
    <h1 class="ia-page-title">Смена пароля</h1>
</div>

<div class="bx-auth container changepasswd-container">

<?
ShowMessage($arParams["~AUTH_RESULT"]);
?>
<form method="post" action="<?=$arResult["AUTH_FORM"]?>" name="bform">
	<?if (strlen($arResult["BACKURL"]) > 0): ?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
	<? endif ?>
	<input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="CHANGE_PWD">
    <div class="row">
        <input type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["LAST_LOGIN"]?>" class="bx-auth-input form-control" placeholder="<?=GetMessage("AUTH_LOGIN")?>"/>
    </div>
    <div class="row">
        <input type="text" name="USER_CHECKWORD" maxlength="50" value="<?=$arResult["USER_CHECKWORD"]?>" placeholder="<?=GetMessage("AUTH_CHECKWORD")?>" class="bx-auth-input form-control " />
    </div>
    <div class="row">
        <input type="password" name="USER_PASSWORD" maxlength="50" value="<?=$arResult["USER_PASSWORD"]?>" placeholder="<?=GetMessage("AUTH_NEW_PASSWORD_REQ")?>" class="bx-auth-input form-control " autocomplete="off" />
    </div>
    <div class="row">
        <input type="password" name="USER_CONFIRM_PASSWORD" maxlength="50" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" placeholder="<?=GetMessage("AUTH_NEW_PASSWORD_CONFIRM")?>" class="bx-auth-input form-control" autocomplete="off" /></td>
    </div>
    <div class="row">
        <input type="submit" name="change_pwd" class="btn btn-default ia-btn text-btn blue-btn submit-btn" value="<?=GetMessage("AUTH_CHANGE")?>" />
    </div>
<p class="gray-text"><?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
<p class="gray-text"><span class="starrequired">*</span><?=GetMessage("AUTH_REQ")?></p>

</form>

<script type="text/javascript">
document.bform.USER_LOGIN.focus();
</script>
</div>