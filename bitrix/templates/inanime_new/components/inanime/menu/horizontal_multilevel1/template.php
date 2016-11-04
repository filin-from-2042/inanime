<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>

    <nav class="navigation mobile-collapsed" id="top-navigation-bar">
        <div class="container">
            <!-- general menu -->
            <ul class="first-level">

            <?
            $previousLevel = 0;
            $columnItems = 0;
            $columnsCount=0;
            foreach($arResult as $arItem):?>

                <?// первый уровень меню?>
                <?if($arItem["DEPTH_LEVEL"]=='1'):?>

                    <?if($previousLevel=='2'):?>
                        </ul></li>
                    <?elseif( $previousLevel=='3'):?>
                        </ul>
                        <?=str_repeat("</div>", $columnsCount);?>
                        </li></ul></li>
                        <?$columnItems=0?>
                        <?$columnsCount=0?>
                    <?endif?>

                    <?if($arItem["IS_PARENT"]):?>
                    <li class="dropdown">
                        <i class="fa fa-bars visible-sm" aria-hidden="true" data-toggle="dropdown"></i>
                        <a href="<?=$arItem["LINK"]?>" ><?=$arItem["TEXT"]?></a>
                        <i class="fa fa-bars hidden-sm hidden-xs" aria-hidden="true" data-toggle="dropdown"></i>
                        <ul class="second-level dropdown-menu">
                    <?else:?>
                        <li>
                            <a href="<?=$arItem["LINK"]?>" ><?=$arItem["TEXT"]?></a>
                        </li>
                    <?endif?>
                <?endif?>

                <?// второй уровень меню?>
                <?if($arItem["DEPTH_LEVEL"]=='2'):?>
                    <?
                    $arFileData = null;
                    if($arItem["PARAMS"]["UF_MENU_ICON"])
                        $arFileData = CFile::GetFileArray($arItem["PARAMS"]["UF_MENU_ICON"]);
                    ?>
                    <?if($previousLevel=='3'):?>
                        </ul>
                        <?=str_repeat("</div>", $columnsCount);?>
                        </li>
                        <?$columnItems=0?>
                        <?$columnsCount=0?>
                    <?endif?>

                    <?if($arItem["IS_PARENT"]):?>
                        <li class="dropdown"><a href="<?=$arItem["LINK"]?>" >
                                <?if($arFileData && $arFileData["SRC"]):?><img src="<?=$arFileData["SRC"]?>"><?endif?><?=$arItem["TEXT"]?></a>
                        <div class="third-level-container">
                            <ul>
                        <?$columnsCount=1?>
                    <?else:?>
                        <li><?CFile::ShowImage($arItem["PARAMS"]["UF_MENU_ICON"], 20, 20, '', true)?><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
                    <?endif?>
                <?endif?>

                <?// третий уровень меню?>
                <?if($arItem["DEPTH_LEVEL"]=='3'):?>
                    <?if($columnItems < 10):?>
                        <li><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
                        <?$columnItems++?>
                    <?else:?>
                        </ul>
                        <ul>
                            <li><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
                        <?$columnItems=1?>
                        <?$columnsCount++?>
                    <?endif?>

                <?endif?>

                <?$previousLevel=$arItem["DEPTH_LEVEL"]?>
            <?endforeach?>

            </ul>
                            <div class="container visible-xs">
                                <div class="row contacts-container">
                                    <div class="col-xs-24">
                                        <?$APPLICATION->IncludeFile(
                                            $APPLICATION->GetTemplatePath("include_areas/contacts.php"),
                                            Array(),
                                            Array("MODE"=>"html")
                                        );?>
                                    </div>
                                </div>
                                <div class="row location-container">
                                    <div class="col-xs-12">
                                        <span class="geo-title">Мой город:</span>
                                    </div>
                                    <div class="col-xs-12">
                                        <button type="button" class="btn btn-default ia-btn text-btn yellow-btn"><i class="fa fa-map-marker" aria-hidden="true"></i> Город <i class="fa fa-caret-down" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <div class="row user-buttons-container">
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
                                <div class="row search-container">
                                    <div class="col-xs-24">
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
        </div>
    </nav>
<?endif?>