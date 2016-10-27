<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/".SITE_TEMPLATE_ID."/header.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width">
    <link rel="shortcut icon" type="image/x-icon" href="<?=SITE_DIR?>favicon.ico" />

    <?
    $APPLICATION->ShowHead();
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/bootstrap.min.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/font-awesome.min.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/styles.css');
    $APPLICATION->SetAdditionalCSS('http://fonts.googleapis.com/css?family=Oswald:400,300');
    ?>
    <?$APPLICATION->AddHeadScript("https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js");?>
    <?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/bootstrap.min.js");?>
    <title><?$APPLICATION->ShowTitle()?></title>
</head>
<body>
<div id="panel"><?$APPLICATION->ShowPanel();?></div>
<header>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-9 col-md-7 col-lg-7">
                <a href="/" class="logo-link">
                    <?$APPLICATION->IncludeFile(
                        $APPLICATION->GetTemplatePath("include_areas/company_logo.php"),
                        Array(),
                        Array("MODE"=>"html")
                    );?>
                </a>
            </div>
            <!-- desktop -->
            <div class="hidden-xs hidden-sm col-md-10 col-lg-11 container">
                <!-- contacts -->
                <div class="row">
                    <div class="col-md-17 col-lg-17">
                        <?$APPLICATION->IncludeFile(
                            $APPLICATION->GetTemplatePath("include_areas/contacts.php"),
                            Array(),
                            Array("MODE"=>"html")
                        );?>

                    </div>
                    <div class="col-md-7 col-lg-7">
                        <div class="social-container">
                            <a href="http://www.facebook.com" class="social-link facebook"></a>
                            <a href="http://www.twitter.com" class="social-link twitter"></a>
                            <a href="http://vimeo.com" class="social-link vimeo"></a>
                        </div>
                    </div>
                </div>
                <!-- search -->
                <div class="row search-row">
                    <div class="col-md-6 col-lg-6">
                        <button type="button" class="btn btn-default ia-btn text-btn yellow-btn">Город</button>
                    </div>
                    <div class="col-md-18 col-lg-18">

                        <?$APPLICATION->IncludeComponent(
                            "bitrix:search.form",
                            "flat1",
                            array(
                                "PAGE" => "/search/",
                                "COMPONENT_TEMPLATE" => "flat1",
                                "USE_SUGGEST" => "Y",
                                "COMPOSITE_FRAME_MODE" => "A",
                                "COMPOSITE_FRAME_TYPE" => "AUTO"
                            ),
                            false
                        );?>

                    </div>
                </div>
            </div>
            <div class="hidden-xs hidden-sm col-md-7 col-lg-6 container user-controls-container">
                <div class="row">
                    <button type="button" class="btn btn-default ia-btn text-btn yellow-btn">Регистрация</button>
                    <button type="button" class="btn btn-default ia-btn text-btn blue-btn">Личный кабинет</button>
                </div>
                <div class="row">
                    <div class="content-container cart-container">
                        <a class="btn btn-default ia-btn yellow-btn image-btn" href="/personal/cart" role="button"><span class="icon cart-icon"></span></a>
                        <div class="text-container">
                                <span>
                                    <span>2 товара</span><i class="fa fa-long-arrow-right" aria-hidden="true"></i><span class="yellow-text">5.130 &#8381;</span>
                                </span>
                        </div>
                        <a class="btn btn-default ia-btn blue-btn image-btn" href="/personal/cart" role="button"><span class="icon favorite-icon"></span></a>
                    </div>
                </div>
            </div>
            <!-- tablet -->
            <div class="container hidden-xs col-sm-15 hidden-md hidden-lg">
                <div class="row contacts-container">
                    <div class="col-sm-24">
                        <span class="contacts"><span>(495) 832-93-29,</span><span>(8442) 234-53-63</span></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-11 container buttons-container">
                        <div class="row account-buttons-container">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-default ia-btn text-btn yellow-btn">Регистрация</button>
                            </div>
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-default ia-btn text-btn blue-btn">Личный кабинет</button>
                            </div>
                        </div>
                        <div class="row search-button-container">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-default ia-btn text-btn yellow-btn"><i class="fa fa-map-marker" aria-hidden="true"></i> Город <i class="fa fa-caret-down" aria-hidden="true"></i></button>
                            </div>
                            <div class="col-sm-12">
                                <div class="search-input-container">
                                    <input type="text" name="q" value="" placeholder="Поиск" class="form-control search-input"/>
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-13">
                        <div class="content-container cart-container">
                            <a class="btn btn-default ia-btn yellow-btn image-btn" href="#" role="button"><span class="icon cart-icon"></span></a>
                            <div class="text-container">
                                <span>
                                    <span>2 товара</span><i class="fa fa-long-arrow-right" aria-hidden="true"></i><span class="yellow-text">5.130 &#8381;</span>
                                </span>
                            </div>
                            <a class="btn btn-default ia-btn blue-btn image-btn" href="#" role="button"><span class="icon favorite-icon"></span></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- mobile -->
            <div class="col-xs-12 hidden-sm hidden-md hidden-lg">
                <a class="btn btn-default ia-btn yellow-btn image-btn" href="#" role="button"><img src="<?=SITE_TEMPLATE_PATH."/images/commerce.png"?>" class="img-commerce"></a>
                <a class="btn btn-default ia-btn blue-btn image-btn" href="#" role="button"><img src="<?=SITE_TEMPLATE_PATH."/images/favorite.png"?>" class="img-favorite"></span></a>

                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-navigation-bar" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

        </div>
    </div>
</header>
    <?$APPLICATION->IncludeComponent("bitrix:menu", "horizontal_multilevel1", Array(
	"ROOT_MENU_TYPE" => "left",	// Тип меню для первого уровня
		"MENU_CACHE_TYPE" => "A",	// Тип кеширования
		"MENU_CACHE_TIME" => "36000000",	// Время кеширования (сек.)
		"MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
		"MENU_THEME" => "site",
		"CACHE_SELECTED_ITEMS" => "N",
		"MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
		"MAX_LEVEL" => "3",	// Уровень вложенности меню
		"CHILD_MENU_TYPE" => "left",	// Тип меню для остальных уровней
		"USE_EXT" => "Y",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
		"DELAY" => "N",	// Откладывать выполнение шаблона меню
		"ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
		"COMPONENT_TEMPLATE" => "horizontal_multilevel",
		"COMPOSITE_FRAME_MODE" => "A",	// Голосование шаблона компонента по умолчанию
		"COMPOSITE_FRAME_TYPE" => "AUTO",	// Содержимое компонента
	),
	false
);?>

<div class="workarea">