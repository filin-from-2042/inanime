<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();?>
<div class="bx_small_cart">
    <span class="icon_cart"></span>

    <?if ($arParams['SHOW_AUTHOR'] == 'Y'):?>
        <div class="user-buttons">
        <?if ($USER->IsAuthorized()):
            $name = trim($USER->GetFullName());
            if (! $name)
                $name = trim($USER->GetLogin());
            if (strlen($name) > 15)
                $name = substr($name, 0, 12).'...';
            ?>
            <div class="col-sm-12 col-md-14 col-lg-12 left-button-container col-xs-12 registration-container">
                <a type="button" class="link_profile btn btn-default ia-btn text-btn blue-btn" href="<?=$arParams['PATH_TO_PROFILE']?>"><?=$name?></a>
            </div>
            <div class="col-sm-12 col-md-10 col-lg-11 right-button-container  col-xs-12 profile">
                <a type="button"  class="link_profile btn btn-default ia-btn text-btn yellow-btn" href="?logout=yes"><?=GetMessage('TSB1_LOGOUT')?></a>
            </div>
        <?else:?>
            <div class="col-sm-12 col-md-10 col-lg-11 left-button-container col-xs-12 registration-container">
                <a type="button" class="link_profile btn btn-default ia-btn text-btn yellow-btn" href="<?=$arParams['PATH_TO_REGISTER']?>?register=yes"><?=GetMessage('TSB1_REGISTER')?></a>
            </div>
            <div class="col-sm-12 col-md-14 col-lg-12 right-button-container col-xs-12 profile">
                <a type="button" class="link_profile btn btn-default ia-btn text-btn blue-btn" href="<?=$arParams['PATH_TO_REGISTER']?>?login=yes" onclick="$('#autorization-modal').modal();return false;"><?=GetMessage('TSB1_LOGIN')?></a>
            </div>
        <?endif?>
        </div>
    <?endif?>

    <div class="content-container cart-container hidden-xs">
        <a class="btn btn-default ia-btn yellow-btn image-btn" href="/personal/cart" role="button"><span class="icon cart-icon"></span></a>
        <div class="text-container">
            <span>
                <span><?=$arResult['NUM_PRODUCTS'].' '.$arResult['PRODUCT(S)']?></span><i class="fa fa-long-arrow-right" aria-hidden="true"></i><span class="yellow-text"><?=$arResult['TOTAL_PRICE']?></span>
            </span>
        </div>
        <a class="btn btn-default ia-btn blue-btn image-btn" href="/personal/cart" role="button"><span class="icon favorite-icon"></span></a>
    </div>

</div>
