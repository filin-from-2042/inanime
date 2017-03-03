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
    $APPLICATION->SetAdditionalCSS('https://fonts.googleapis.com/css?family=Oswald:400,300');
    ?>
    <?$APPLICATION->AddHeadScript("https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js");?>
    <?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/bootstrap.min.js");?>
    <?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/script.js");?>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
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
                    <div class="col-md-24 col-lg-17">
                        <?$APPLICATION->IncludeFile(
                            $APPLICATION->GetTemplatePath("include_areas/contacts.php"),
                            Array(),
                            Array("MODE"=>"html")
                        );?>

                    </div>
                    <div class="hidden-md col-lg-7">
                        <?$APPLICATION->IncludeFile(
                            $APPLICATION->GetTemplatePath("include_areas/socials-buttons.php"),
                            Array(),
                            Array("MODE"=>"html")
                        );?>
                    </div>
                </div>
                <!-- search -->
                <div class="row search-row">
                    <div class="col-md-8 col-lg-6 location-column">
                        <?
                        $locationID = 0;
                        $locationName = '';
                        if(isset($_SESSION['USER_VALUES']) && isset($_SESSION['USER_VALUES']['CURRENT_LOCATION_DATA']))
                        {
                            $locationID = $_SESSION['USER_VALUES']['CURRENT_LOCATION_DATA']['ID'];
                            $locationName = $_SESSION['USER_VALUES']['CURRENT_LOCATION_DATA']['NAME'];
                        }else{
                            if(CModule::IncludeModule("sale"))
                            {
                                $countryID=1;
                                $db_varsCountry = CSaleLocation::GetList(
                                    array(
                                        "SORT" => "ASC",
                                        "COUNTRY_NAME_LANG" => "ASC",
                                        "CITY_NAME_LANG" => "ASC"
                                    ),
                                    array("LID" => LANGUAGE_ID),
                                    false,
                                    false,
                                    array()
                                );
                                if ($varsCountry = $db_varsCountry->GetNext()){
                                    $countryID=$varsCountry['COUNTRY_ID'];
                                }

                                $cityObj = new CCity();
                                $arThisCity = $cityObj ->GetFullInfo();

                                $db_vars = CSaleLocation::GetList(
                                    array(
                                        "SORT" => "ASC",
                                        "COUNTRY_NAME_LANG" => "ASC",
                                        "CITY_NAME_LANG" => "ASC"
                                    ),
                                    array("CITY_NAME" => $arThisCity['CITY_NAME']['VALUE'], "LID" => LANGUAGE_ID, 'COUNTRY_ID'=>$countryID),
                                    false,
                                    false,
                                    array()
                                );
                                if ($vars = $db_vars->GetNext()){
                                    $locationID = $vars['ID'];
                                    $locationName = $vars['CITY_NAME'];
                                    $_SESSION['USER_VALUES']['CURRENT_LOCATION_DATA']['ID'] = $locationID;
                                    $_SESSION['USER_VALUES']['CURRENT_LOCATION_DATA']['NAME'] = $locationName;
                                }
                            }
                        }?>

                        <?$APPLICATION->IncludeComponent(
                            "bitrix:sale.location.selector.search",
                            "header-location",
                            array(
                                "COMPONENT_TEMPLATE" => "header-location",
                                "ID" => $locationID,
                                "CODE" => "",
                                "INPUT_NAME" => "globalInputAddressLocationSelector",
                                "PROVIDE_LINK_BY" => "id",
                                "JS_CONTROL_GLOBAL_ID" => "globalAddressLocationSelector",
                                "JS_CALLBACK" => "iaHelper_headerLocationCallback",
                                "AJAX_MODE" => "Y",
                                "FILTER_BY_SITE" => "Y",
                                "SHOW_DEFAULT_LOCATIONS" => "Y",
                                "CACHE_TYPE" => "A",
                                "CACHE_TIME" => "36000000",
                                "FILTER_SITE_ID" => "current",
                                "INITIALIZE_BY_GLOBAL_EVENT" => "",
                                "SUPPRESS_ERRORS" => "N",
                                "COMPOSITE_FRAME_MODE" => "A",
                                "COMPOSITE_FRAME_TYPE" => "AUTO"
                            ),
                            false
                        );?>
                    </div>
                    <div class="col-md-16 col-lg-18 search-column">

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
                    <?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "inanime-template", Array(
                        "PATH_TO_BASKET" => SITE_DIR."personal/cart/",	// Страница корзины
                            "PATH_TO_PERSONAL" => SITE_DIR."personal",	// Страница персонального раздела
                            "SHOW_PERSONAL_LINK" => "N",	// Отображать персональный раздел
                            "SHOW_NUM_PRODUCTS" => "Y",	// Показывать количество товаров
                            "SHOW_TOTAL_PRICE" => "Y",	// Показывать общую сумму по товарам
                            "SHOW_PRODUCTS" => "N",	// Показывать список товаров
                            "POSITION_FIXED" => "N",	// Отображать корзину поверх шаблона
                            "SHOW_AUTHOR" => "Y",	// Добавить возможность авторизации
                            "PATH_TO_REGISTER" => SITE_DIR."login/",	// Страница регистрации
                            "PATH_TO_PROFILE" => SITE_DIR."personal",	// Страница профиля
                            "COMPONENT_TEMPLATE" => ".default_old",
                            "PATH_TO_ORDER" => SITE_DIR."personal/order/make/",	// Страница оформления заказа
                            "SHOW_EMPTY_VALUES" => "Y",	// Выводить нулевые значения в пустой корзине
                            "HIDE_ON_BASKET_PAGES" => "Y",	// Не показывать на страницах корзины и оформления заказа
                            "COMPOSITE_FRAME_MODE" => "A",	// Голосование шаблона компонента по умолчанию
                            "COMPOSITE_FRAME_TYPE" => "AUTO",	// Содержимое компонента
                        ),
                        false                    );?>
                </div>
            </div>
            <!-- tablet -->
            <div class="container visible-sm col-sm-15">
                <div class="row contacts-container">
                    <div class="col-sm-24">
                        <?$APPLICATION->IncludeFile(
                            $APPLICATION->GetTemplatePath("include_areas/contacts.php"),
                            Array(),
                            Array("MODE"=>"html")
                        );?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-11 container buttons-container">
                        <div class="row account-buttons-container" style="min-height:22px">
                        </div>
                        <div class="row search-button-container">
                            <div class="col-sm-12">
                                <?$APPLICATION->IncludeComponent(
                                    "bitrix:sale.location.selector.search",
                                    "header-location",
                                    array(
                                        "COMPONENT_TEMPLATE" => "header-location",
                                        "ID" => $locationID,
                                        "CODE" => "",
                                        "INPUT_NAME" => "globalInputAddressLocationSelectorTablet",
                                        "PROVIDE_LINK_BY" => "id",
                                        "JS_CONTROL_GLOBAL_ID" => "globalAddressLocationSelectorTablet",
                                        "JS_CALLBACK" => "iaHelper_headerLocationCallback",
                                        "AJAX_MODE" => "Y",
                                        "FILTER_BY_SITE" => "Y",
                                        "SHOW_DEFAULT_LOCATIONS" => "Y",
                                        "CACHE_TYPE" => "A",
                                        "CACHE_TIME" => "36000000",
                                        "FILTER_SITE_ID" => "current",
                                        "INITIALIZE_BY_GLOBAL_EVENT" => "",
                                        "SUPPRESS_ERRORS" => "N",
                                        "COMPOSITE_FRAME_MODE" => "A",
                                        "COMPOSITE_FRAME_TYPE" => "AUTO"
                                    ),
                                    false
                                );?>
                            </div>
                            <div class="col-sm-12 search-button-column">

                                <?$APPLICATION->IncludeComponent(
                                    "bitrix:search.form",
                                    "flat1",
                                    array(
                                        "PAGE" => "/search/",
                                        "COMPONENT_TEMPLATE" => "flat1",
                                        "USE_SUGGEST" => "Y",
                                        "COMPOSITE_FRAME_MODE" => "A",
                                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                                        'TABLET'=>'Y'
                                    ),
                                    false
                                );?>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-13 basket-top-container">
                        <?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "inanime-template", Array(
                                "PATH_TO_BASKET" => SITE_DIR."personal/cart/",	// Страница корзины
                                "PATH_TO_PERSONAL" => SITE_DIR."personal/",	// Страница персонального раздела
                                "SHOW_PERSONAL_LINK" => "N",	// Отображать персональный раздел
                                "SHOW_NUM_PRODUCTS" => "Y",	// Показывать количество товаров
                                "SHOW_TOTAL_PRICE" => "Y",	// Показывать общую сумму по товарам
                                "SHOW_PRODUCTS" => "N",	// Показывать список товаров
                                "POSITION_FIXED" => "N",	// Отображать корзину поверх шаблона
                                "SHOW_AUTHOR" => "Y",	// Добавить возможность авторизации
                                "PATH_TO_REGISTER" => SITE_DIR."login/",	// Страница регистрации
                                "PATH_TO_PROFILE" => SITE_DIR."personal/",	// Страница профиля
                                "COMPONENT_TEMPLATE" => ".default_old",
                                "PATH_TO_ORDER" => SITE_DIR."personal/order/make/",	// Страница оформления заказа
                                "SHOW_EMPTY_VALUES" => "Y",	// Выводить нулевые значения в пустой корзине
                                "HIDE_ON_BASKET_PAGES" => "Y",	// Не показывать на страницах корзины и оформления заказа
                                "COMPOSITE_FRAME_MODE" => "A",	// Голосование шаблона компонента по умолчанию
                                "COMPOSITE_FRAME_TYPE" => "AUTO",	// Содержимое компонента
                            ),
                            false
                        );?>
                    </div>
                </div>
            </div>
            <!-- mobile -->
            <div class="col-xs-12 hidden-sm hidden-md hidden-lg mobile-buttons-column">
                <?
                $cntBasketItems = CSaleBasket::GetList(
                    array(),
                    array(
                        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                        "LID" => SITE_ID,
                        "ORDER_ID" => "NULL"
                    ),
                    array()
                );
                if($cntBasketItems>0)
                {
                ?>
                    <a class="btn btn-default ia-btn yellow-btn image-btn mobile-link-button-cart" href="/personal/cart" role="button"><img src="<?=SITE_TEMPLATE_PATH."/images/commerce.png"?>" class="img-commerce"></a>
                    <a class="btn btn-default ia-btn blue-btn image-btn mobile-link-button-favorite" href="/personal/cart?tab=put-aside" role="button"><img src="<?=SITE_TEMPLATE_PATH."/images/favorite.png"?>" class="img-favorite"></span></a>
                <?}else{?>
                    <span class="btn btn-default ia-btn yellow-btn image-btn mobile-link-button-cart" role="button"><img src="<?=SITE_TEMPLATE_PATH."/images/commerce.png"?>" class="img-commerce"></span>
                    <span class="btn btn-default ia-btn blue-btn image-btn mobile-link-button-favorite"  role="button"><img src="<?=SITE_TEMPLATE_PATH."/images/favorite.png"?>" class="img-favorite"></span>

                <?}?>

                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-navigation-bar" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

        </div>
    </div>
    <div class="modal fade ia-modal" id="autorization-modal" tabindex="-1" role="dialog" aria-labelledby="modalAutorization">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="clearfix ">
                <i class="fa fa-times" aria-hidden="true"></i>
              </span>
                    </button>

                    <h4 class="modal-title">Авторизация</h4>
                </div>
                <div class="modal-body">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:system.auth.form",
                        "template1",
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
    <div class="modal fade ia-modal" id="registration-modal" tabindex="-1" role="dialog" aria-labelledby="modalRegistration">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="clearfix ">
                <i class="fa fa-times" aria-hidden="true"></i>
              </span>
                    </button>

                    <h4 class="modal-title">Регистрация</h4>
                </div>
                <div class="modal-body">
                    <?$APPLICATION->IncludeComponent("bitrix:main.register","template1",Array(
                            "USER_PROPERTY_NAME" => "",
                            "SEF_MODE" => "Y",
                            "SHOW_FIELDS" => Array(),
                            "REQUIRED_FIELDS" => Array(),
                            "AUTH" => "Y",
                            "USE_BACKURL" => "Y",
                            "SUCCESS_PAGE" => "",
                            "SET_TITLE" => "Y",
                            "USER_PROPERTY" => Array(),
                            "SEF_FOLDER" => "/",
                            "VARIABLE_ALIASES" => Array()
                        )
                    );?>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade ia-modal" id="password-reset-modal" tabindex="-1" role="dialog" aria-labelledby="modalPasswordReset">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="clearfix ">
                <i class="fa fa-times" aria-hidden="true"></i>
              </span>
                    </button>

                    <h4 class="modal-title">Восстановление пароля</h4>
                </div>
                <div class="modal-body">
                    <?$APPLICATION->IncludeComponent( "bitrix:system.auth.forgotpasswd",
                        "template1",
//                        "",
                        Array('USE_CAPTCHA'=>'N')
                    );?>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade ia-modal" id="item-added-modal" tabindex="-1" role="dialog" aria-labelledby="modalItemAdded">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="clearfix ">
                <i class="fa fa-times" aria-hidden="true"></i>
              </span>
                    </button>
                    <div class="modal-title" style="color:rgb(99,186,196);text-align: center;font-size:15px"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;Товар успешно добавлен</div>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
</header>
    <?$APPLICATION->IncludeComponent("inanime:menu",
        "horizontal_multilevel1",
        Array(
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
<div class="container visible-xs">
    <hr class="mobile-top-nav-line">
</div>
<div class="content">