<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Мы предлагаем широкий ассортимент аниме");
$APPLICATION->SetPageProperty("keywords", "аниме одежда, аниме берелоки, кулоны");
$APPLICATION->SetTitle("Интернет-магазин \"АНИМЕ аксессуаров\"");
?>

    <div class="row main-carousel">
        <div class="col-xs-24 col-sm-18 col-md-18 col-lg-18">
            <?if (IsModuleInstalled("advertising")):?>
                <? $APPLICATION->IncludeComponent(
                    "bitrix:advertising.banner",
                    "bootstrap",
                    array(
                        "COMPONENT_TEMPLATE" => "bootstrap",
                        "TYPE" => "MAIN",
                        "NOINDEX" => "Y",
                        "QUANTITY" => "3",
                        "BS_EFFECT" => "fade",
                        "BS_CYCLING" => "N",
                        "BS_WRAP" => "Y",
                        "BS_PAUSE" => "Y",
                        "BS_KEYBOARD" => "Y",
                        "BS_ARROW_NAV" => "Y",
                        "BS_BULLET_NAV" => "Y",
                        "BS_HIDE_FOR_TABLETS" => "N",
                        "BS_HIDE_FOR_PHONES" => "N",
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "36000000",
                        "DEFAULT_TEMPLATE" => "-",
                        "COMPOSITE_FRAME_MODE" => "A",
                        "COMPOSITE_FRAME_TYPE" => "AUTO"
                    ),
                    false
                );?>
            <?endif?>
        </div>
        <div class="hidden-xs col-sm-6 col-md-6 col-lg-6">
            <script>
                $(document).ready(function(){
                    inanime_new.init_custom_vertical_carousel('carousel-custom-vertical',2);
                });
            </script>
            <? $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/jquery.bxslider.css');?>
            <? $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.bxslider.min.js');?>
            <?
            CModule::IncludeModule("iblock");
            CModule::IncludeModule("catalog");
            $arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_SHOW_MAIN_SLIDER", "PROPERTY_MAIN_SLIDER_PHOTO", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PREVIEW_TEXT", "PROPERTY_IS_NEWPRODUCT", "DETAIL_PAGE_URL", "PROPERTY_DISCOUNT");
            $arFilter = Array("IBLOCK_ID" => array(18, 19, 23), "ACTIVE" => "Y", "!PROPERTY_SHOW_MAIN_SLIDER" => false);
            $res = CIBlockElement::GetList(Array("ACTIVE_FROM" => "DESC", "SORT" => "ASC"), $arFilter, false, Array("nPageSize" => 4), $arSelect);
            $arSlides = array();
            while ($ob = $res->GetNextElement()) {
                $arFields = $ob->GetFields();

                $arFields["file"] = CFile::ResizeImageGet(($arFields["PROPERTY_MAIN_SLIDER_PHOTO_VALUE"])?$arFields["PROPERTY_MAIN_SLIDER_PHOTO_VALUE"] : (($arFields["DETAIL_PICTURE"]) ? $arFields["DETAIL_PICTURE"] : $arFields["PREVIEW_PICTURE"]), array('width' => '1800', 'height' => '1000'), BX_RESIZE_IMAGE_PROPORTIONAL, true);

                $arSlides[] = $arFields;
            }

            if (count($arSlides) > 0) {
                ?>
                <div id="carousel-custom-vertical" class="carousel-custom">
                    <div class="prev button"><i class="fa fa-chevron-circle-up" aria-hidden="true"></i></div>
                    <ul>
                    <?
                    $counter = 0;
                    foreach ($arSlides as $slide) {
                        $counter++;
                        $previewText = (strlen($slide["PREVIEW_TEXT"]) > 83) ? substr($slide["PREVIEW_TEXT"], 0, 80) . '...' : $slide["PREVIEW_TEXT"];
                        $nameText = (strlen($slide["NAME"]) > 43) ? substr($slide["NAME"], 0, 40) . '...' : $slide["NAME"];
                        ?>
                        <li>
                            <? if ($slide["file"]["src"]) { ?>
                                <img src="<?= $slide["file"]["src"]; ?>">
                            <? } ?>
                            <a href="<?= $slide["DETAIL_PAGE_URL"]; ?>" class="text"><?=($previewText)?htmlspecialchars($previewText):htmlspecialchars($nameText)?></a>
                        </li>
                    <? } ?>
                    </ul>
                    <div class="next button"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i></div>
                </div>
            <? } ?>
        </div>
    </div>
    <div class="row articles-links">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <a class="article-container grey-container discounts" href="/articles/56063/">
                <div class="table-wrap">
                    <span class="text">Постоянным клиентам скидки до 40%</span>
                </div>
            </a>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <a class="article-container grey-container choise"  href="/articles/56064/">
                <div class="table-wrap">
                    <span class="text">Огромный выбор</span>
                </div>
            </a>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <a class="article-container grey-container shipping" href="/articles/56065/">
                <div class="table-wrap">
                    <span class="text">Бесплатная доставка от 3000 рублей</span>
                </div>
            </a>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <a class="article-container grey-container order" href="/articles/56066/">
                <div class="table-wrap">
                    <span class="text">Товар под заказ</span>
                </div>
            </a>
        </div>
    </div>

<div class="row sections-carousel">
    <div class="fox-icon top"></div>
<!--    <div class="fox-icon bottom"></div>-->
    <h2>Новиники</h2>
    <div class="col-xs-24 col-sm-24 col-md-18 col-md-offset-1 col-lg-offset-0 col-lg-18 column first-section">
        <div class="carousel-container col-sm-24">
            <?$APPLICATION->IncludeComponent("bitrix:catalog.section", "list1", Array(
                "IBLOCK_TYPE_ID" => "catalog",
                    "IBLOCK_ID" => "19",	// Инфоблок
                    "BASKET_URL" => "/personal/cart/",	// URL, ведущий на страницу с корзиной покупателя
                    "COMPONENT_TEMPLATE" => "list",
                    "IBLOCK_TYPE" => "catalog",	// Тип инфоблока
                    "SECTION_ID" => "769",	// ID раздела
                    "SECTION_CODE" => "",	// Код раздела
                    "SECTION_USER_FIELDS" => array(	// Свойства раздела
                        0 => "",
                        1 => "",
                    ),
                    "ELEMENT_SORT_FIELD" => "sort",	// По какому полю сортируем элементы
                    "ELEMENT_SORT_ORDER" => "desc",	// Порядок сортировки элементов
                    "ELEMENT_SORT_FIELD2" => "id",	// Поле для второй сортировки элементов
                    "ELEMENT_SORT_ORDER2" => "desc",	// Порядок второй сортировки элементов
                    "FILTER_NAME" => "arrFilter",	// Имя массива со значениями фильтра для фильтрации элементов
                    "INCLUDE_SUBSECTIONS" => "Y",	// Показывать элементы подразделов раздела
                    "SHOW_ALL_WO_SECTION" => "Y",	// Показывать все элементы, если не указан раздел
                    "HIDE_NOT_AVAILABLE" => "N",	// Товары, не доступные для покупки
                    "PAGE_ELEMENT_COUNT" => "12",	// Количество элементов на странице
                    "LINE_ELEMENT_COUNT" => "3",	// Количество элементов выводимых в одной строке таблицы
                    "PROPERTY_CODE" => array(	// Свойства
                        0 => "",
                        1 => "",
                    ),
                    "OFFERS_FIELD_CODE" => array(	// Поля предложений
                        0 => "",
                        1 => "",
                    ),
                    "OFFERS_PROPERTY_CODE" => array(	// Свойства предложений
                        0 => "COLOR_REF",
                        1 => "SIZES_SHOES",
                        2 => "SIZES_CLOTHES",
                        3 => "",
                    ),
                    "OFFERS_SORT_FIELD" => "sort",	// По какому полю сортируем предложения товара
                    "OFFERS_SORT_ORDER" => "desc",	// Порядок сортировки предложений товара
                    "OFFERS_SORT_FIELD2" => "id",	// Поле для второй сортировки предложений товара
                    "OFFERS_SORT_ORDER2" => "desc",	// Порядок второй сортировки предложений товара
                    "OFFERS_LIMIT" => "5",	// Максимальное количество предложений для показа (0 - все)
                    "TEMPLATE_THEME" => "site",
                    "PRODUCT_DISPLAY_MODE" => "Y",
                    "ADD_PICT_PROP" => "-",
                    "LABEL_PROP" => "-",
                    "OFFER_ADD_PICT_PROP" => "-",
                    "OFFER_TREE_PROPS" => array(
                        0 => "COLOR_REF",
                    ),
                    "PRODUCT_SUBSCRIPTION" => "N",
                    "SHOW_DISCOUNT_PERCENT" => "N",
                    "SHOW_OLD_PRICE" => "Y",
                    "SHOW_CLOSE_POPUP" => "N",
                    "MESS_BTN_BUY" => "Купить",
                    "MESS_BTN_ADD_TO_BASKET" => "В корзину",
                    "MESS_BTN_SUBSCRIBE" => "Подписаться",
                    "MESS_BTN_DETAIL" => "Подробнее",
                    "MESS_NOT_AVAILABLE" => "Нет в наличии",
                    "SECTION_URL" => "",	// URL, ведущий на страницу с содержимым раздела
                    "DETAIL_URL" => "",	// URL, ведущий на страницу с содержимым элемента раздела
                    "SECTION_ID_VARIABLE" => "SECTION_ID",	// Название переменной, в которой передается код группы
                    "SEF_MODE" => "N",	// Включить поддержку ЧПУ
                    "AJAX_MODE" => "N",	// Включить режим AJAX
                    "AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
                    "AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
                    "AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
                    "AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
                    "CACHE_TYPE" => "A",	// Тип кеширования
                    "CACHE_TIME" => "36000000",	// Время кеширования (сек.)
                    "CACHE_GROUPS" => "Y",	// Учитывать права доступа
                    "SET_TITLE" => "Y",	// Устанавливать заголовок страницы
                    "SET_BROWSER_TITLE" => "Y",	// Устанавливать заголовок окна браузера
                    "BROWSER_TITLE" => "-",	// Установить заголовок окна браузера из свойства
                    "SET_META_KEYWORDS" => "Y",	// Устанавливать ключевые слова страницы
                    "META_KEYWORDS" => "-",	// Установить ключевые слова страницы из свойства
                    "SET_META_DESCRIPTION" => "Y",	// Устанавливать описание страницы
                    "META_DESCRIPTION" => "-",	// Установить описание страницы из свойства
                    "SET_LAST_MODIFIED" => "N",	// Устанавливать в заголовках ответа время модификации страницы
                    "USE_MAIN_ELEMENT_SECTION" => "N",	// Использовать основной раздел для показа элемента
                    "ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
                    "CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
                    "ACTION_VARIABLE" => "action",	// Название переменной, в которой передается действие
                    "PRODUCT_ID_VARIABLE" => "id",	// Название переменной, в которой передается код товара для покупки
                    "PRICE_CODE" => array(	// Тип цены
                        0 => "BASE",
                    ),
                    "USE_PRICE_COUNT" => "N",	// Использовать вывод цен с диапазонами
                    "SHOW_PRICE_COUNT" => "1",	// Выводить цены для количества
                    "PRICE_VAT_INCLUDE" => "Y",	// Включать НДС в цену
                    "CONVERT_CURRENCY" => "N",	// Показывать цены в одной валюте
                    "USE_PRODUCT_QUANTITY" => "N",	// Разрешить указание количества товара
                    "PRODUCT_QUANTITY_VARIABLE" => "",	// Название переменной, в которой передается количество товара
                    "ADD_PROPERTIES_TO_BASKET" => "Y",	// Добавлять в корзину свойства товаров и предложений
                    "PRODUCT_PROPS_VARIABLE" => "prop",	// Название переменной, в которой передаются характеристики товара
                    "PARTIAL_PRODUCT_PROPERTIES" => "N",	// Разрешить добавлять в корзину товары, у которых заполнены не все характеристики
                    "PRODUCT_PROPERTIES" => "",	// Характеристики товара
                    "OFFERS_CART_PROPERTIES" => array(	// Свойства предложений, добавляемые в корзину
                        0 => "COLOR_REF",
                    ),
                    "ADD_TO_BASKET_ACTION" => "ADD",
                    "PAGER_TEMPLATE" => "round",	// Шаблон постраничной навигации
                    "DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
                    "DISPLAY_BOTTOM_PAGER" => "Y",	// Выводить под списком
                    "PAGER_TITLE" => "Товары",	// Название категорий
                    "PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
                    "PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
                    "PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
                    "PAGER_BASE_LINK_ENABLE" => "N",	// Включить обработку ссылок
                    "SET_STATUS_404" => "N",	// Устанавливать статус 404
                    "SHOW_404" => "N",	// Показ специальной страницы
                    "MESSAGE_404" => "",	// Сообщение для показа (по умолчанию из компонента)
                    "BACKGROUND_IMAGE" => "-",	// Установить фоновую картинку для шаблона из свойства
                    "DISABLE_INIT_JS_IN_COMPONENT" => "N",	// Не подключать js-библиотеки в компоненте
                    "COMPOSITE_FRAME_MODE" => "A",	// Голосование шаблона компонента по умолчанию
                    "COMPOSITE_FRAME_TYPE" => "AUTO",	// Содержимое компонента
                ),
                false
            );?>
        </div>
        <div class="carousel-container col-sm-24">
            <?$APPLICATION->IncludeComponent("bitrix:catalog.section", "list1", Array(
                    "IBLOCK_TYPE_ID" => "catalog",
                    "IBLOCK_ID" => "19",	// Инфоблок
                    "BASKET_URL" => "/personal/cart/",	// URL, ведущий на страницу с корзиной покупателя
                    "COMPONENT_TEMPLATE" => "list",
                    "IBLOCK_TYPE" => "catalog",	// Тип инфоблока
                    "SECTION_ID" => "759",	// ID раздела
                    "SECTION_CODE" => "",	// Код раздела
                    "SECTION_USER_FIELDS" => array(	// Свойства раздела
                        0 => "",
                        1 => "",
                    ),
                    "ELEMENT_SORT_FIELD" => "sort",	// По какому полю сортируем элементы
                    "ELEMENT_SORT_ORDER" => "desc",	// Порядок сортировки элементов
                    "ELEMENT_SORT_FIELD2" => "id",	// Поле для второй сортировки элементов
                    "ELEMENT_SORT_ORDER2" => "desc",	// Порядок второй сортировки элементов
                    "FILTER_NAME" => "arrFilter",	// Имя массива со значениями фильтра для фильтрации элементов
                    "INCLUDE_SUBSECTIONS" => "Y",	// Показывать элементы подразделов раздела
                    "SHOW_ALL_WO_SECTION" => "Y",	// Показывать все элементы, если не указан раздел
                    "HIDE_NOT_AVAILABLE" => "N",	// Товары, не доступные для покупки
                    "PAGE_ELEMENT_COUNT" => "12",	// Количество элементов на странице
                    "LINE_ELEMENT_COUNT" => "3",	// Количество элементов выводимых в одной строке таблицы
                    "PROPERTY_CODE" => array(	// Свойства
                        0 => "",
                        1 => "",
                    ),
                    "OFFERS_FIELD_CODE" => array(	// Поля предложений
                        0 => "",
                        1 => "",
                    ),
                    "OFFERS_PROPERTY_CODE" => array(	// Свойства предложений
                        0 => "COLOR_REF",
                        1 => "SIZES_SHOES",
                        2 => "SIZES_CLOTHES",
                        3 => "",
                    ),
                    "OFFERS_SORT_FIELD" => "sort",	// По какому полю сортируем предложения товара
                    "OFFERS_SORT_ORDER" => "desc",	// Порядок сортировки предложений товара
                    "OFFERS_SORT_FIELD2" => "id",	// Поле для второй сортировки предложений товара
                    "OFFERS_SORT_ORDER2" => "desc",	// Порядок второй сортировки предложений товара
                    "OFFERS_LIMIT" => "5",	// Максимальное количество предложений для показа (0 - все)
                    "TEMPLATE_THEME" => "site",
                    "PRODUCT_DISPLAY_MODE" => "Y",
                    "ADD_PICT_PROP" => "-",
                    "LABEL_PROP" => "-",
                    "OFFER_ADD_PICT_PROP" => "-",
                    "OFFER_TREE_PROPS" => array(
                        0 => "COLOR_REF",
                    ),
                    "PRODUCT_SUBSCRIPTION" => "N",
                    "SHOW_DISCOUNT_PERCENT" => "N",
                    "SHOW_OLD_PRICE" => "Y",
                    "SHOW_CLOSE_POPUP" => "N",
                    "MESS_BTN_BUY" => "Купить",
                    "MESS_BTN_ADD_TO_BASKET" => "В корзину",
                    "MESS_BTN_SUBSCRIBE" => "Подписаться",
                    "MESS_BTN_DETAIL" => "Подробнее",
                    "MESS_NOT_AVAILABLE" => "Нет в наличии",
                    "SECTION_URL" => "",	// URL, ведущий на страницу с содержимым раздела
                    "DETAIL_URL" => "",	// URL, ведущий на страницу с содержимым элемента раздела
                    "SECTION_ID_VARIABLE" => "SECTION_ID",	// Название переменной, в которой передается код группы
                    "SEF_MODE" => "N",	// Включить поддержку ЧПУ
                    "AJAX_MODE" => "N",	// Включить режим AJAX
                    "AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
                    "AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
                    "AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
                    "AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
                    "CACHE_TYPE" => "A",	// Тип кеширования
                    "CACHE_TIME" => "36000000",	// Время кеширования (сек.)
                    "CACHE_GROUPS" => "Y",	// Учитывать права доступа
                    "SET_TITLE" => "Y",	// Устанавливать заголовок страницы
                    "SET_BROWSER_TITLE" => "Y",	// Устанавливать заголовок окна браузера
                    "BROWSER_TITLE" => "-",	// Установить заголовок окна браузера из свойства
                    "SET_META_KEYWORDS" => "Y",	// Устанавливать ключевые слова страницы
                    "META_KEYWORDS" => "-",	// Установить ключевые слова страницы из свойства
                    "SET_META_DESCRIPTION" => "Y",	// Устанавливать описание страницы
                    "META_DESCRIPTION" => "-",	// Установить описание страницы из свойства
                    "SET_LAST_MODIFIED" => "N",	// Устанавливать в заголовках ответа время модификации страницы
                    "USE_MAIN_ELEMENT_SECTION" => "N",	// Использовать основной раздел для показа элемента
                    "ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
                    "CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
                    "ACTION_VARIABLE" => "action",	// Название переменной, в которой передается действие
                    "PRODUCT_ID_VARIABLE" => "id",	// Название переменной, в которой передается код товара для покупки
                    "PRICE_CODE" => array(	// Тип цены
                        0 => "BASE",
                    ),
                    "USE_PRICE_COUNT" => "N",	// Использовать вывод цен с диапазонами
                    "SHOW_PRICE_COUNT" => "1",	// Выводить цены для количества
                    "PRICE_VAT_INCLUDE" => "Y",	// Включать НДС в цену
                    "CONVERT_CURRENCY" => "N",	// Показывать цены в одной валюте
                    "USE_PRODUCT_QUANTITY" => "N",	// Разрешить указание количества товара
                    "PRODUCT_QUANTITY_VARIABLE" => "",	// Название переменной, в которой передается количество товара
                    "ADD_PROPERTIES_TO_BASKET" => "Y",	// Добавлять в корзину свойства товаров и предложений
                    "PRODUCT_PROPS_VARIABLE" => "prop",	// Название переменной, в которой передаются характеристики товара
                    "PARTIAL_PRODUCT_PROPERTIES" => "N",	// Разрешить добавлять в корзину товары, у которых заполнены не все характеристики
                    "PRODUCT_PROPERTIES" => "",	// Характеристики товара
                    "OFFERS_CART_PROPERTIES" => array(	// Свойства предложений, добавляемые в корзину
                        0 => "COLOR_REF",
                    ),
                    "ADD_TO_BASKET_ACTION" => "ADD",
                    "PAGER_TEMPLATE" => "round",	// Шаблон постраничной навигации
                    "DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
                    "DISPLAY_BOTTOM_PAGER" => "Y",	// Выводить под списком
                    "PAGER_TITLE" => "Товары",	// Название категорий
                    "PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
                    "PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
                    "PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
                    "PAGER_BASE_LINK_ENABLE" => "N",	// Включить обработку ссылок
                    "SET_STATUS_404" => "N",	// Устанавливать статус 404
                    "SHOW_404" => "N",	// Показ специальной страницы
                    "MESSAGE_404" => "",	// Сообщение для показа (по умолчанию из компонента)
                    "BACKGROUND_IMAGE" => "-",	// Установить фоновую картинку для шаблона из свойства
                    "DISABLE_INIT_JS_IN_COMPONENT" => "N",	// Не подключать js-библиотеки в компоненте
                    "COMPOSITE_FRAME_MODE" => "A",	// Голосование шаблона компонента по умолчанию
                    "COMPOSITE_FRAME_TYPE" => "AUTO",	// Содержимое компонента
                ),
                false
            );?>
        </div>
    </div>
    <div class="col-xs-24 col-sm-24 col-md-18 col-md-offset-3 col-lg-offset-0 col-lg-6 column">
        <div class="col-xs-24 col-sm-12 col-md-12 col-lg-24">
            <div class="carousel-container ">
                <?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section", 
	"list2", 
	array(
		"IBLOCK_TYPE_ID" => "catalog",
		"IBLOCK_ID" => "19",
		"BASKET_URL" => "/personal/cart/",
		"COMPONENT_TEMPLATE" => "list2",
		"IBLOCK_TYPE" => "catalog",
		"SECTION_ID" => "743",
		"SECTION_CODE" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_ORDER" => "desc",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER2" => "desc",
		"FILTER_NAME" => "arrFilter",
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "Y",
		"HIDE_NOT_AVAILABLE" => "N",
		"PAGE_ELEMENT_COUNT" => "12",
		"LINE_ELEMENT_COUNT" => "3",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_PROPERTY_CODE" => array(
			0 => "COLOR_REF",
			1 => "SIZES_SHOES",
			2 => "SIZES_CLOTHES",
			3 => "",
		),
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "desc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "desc",
		"OFFERS_LIMIT" => "5",
		"TEMPLATE_THEME" => "site",
		"PRODUCT_DISPLAY_MODE" => "Y",
		"ADD_PICT_PROP" => "-",
		"LABEL_PROP" => "-",
		"OFFER_ADD_PICT_PROP" => "-",
		"OFFER_TREE_PROPS" => array(
			0 => "COLOR_REF",
		),
		"PRODUCT_SUBSCRIPTION" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_OLD_PRICE" => "Y",
		"SHOW_CLOSE_POPUP" => "N",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SEF_MODE" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "Y",
		"SET_BROWSER_TITLE" => "Y",
		"BROWSER_TITLE" => "-",
		"SET_META_KEYWORDS" => "Y",
		"META_KEYWORDS" => "-",
		"SET_META_DESCRIPTION" => "Y",
		"META_DESCRIPTION" => "-",
		"SET_LAST_MODIFIED" => "N",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"CACHE_FILTER" => "N",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRODUCT_PROPERTIES" => array(
		),
		"OFFERS_CART_PROPERTIES" => array(
			0 => "COLOR_REF",
		),
		"ADD_TO_BASKET_ACTION" => "ADD",
		"PAGER_TEMPLATE" => "round",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Товары",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"BACKGROUND_IMAGE" => "-",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
            </div>
        </div>
        <div class="col-xs-24 col-sm-12 col-md-12 col-lg-24">
            <div class="carousel-container">
                <?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section", 
	"list2", 
	array(
		"IBLOCK_TYPE_ID" => "catalog",
		"IBLOCK_ID" => "19",
		"BASKET_URL" => "/personal/cart/",
		"COMPONENT_TEMPLATE" => "list2",
		"IBLOCK_TYPE" => "catalog",
		"SECTION_ID" => "790",
		"SECTION_CODE" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_ORDER" => "desc",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER2" => "desc",
		"FILTER_NAME" => "arrFilter",
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "Y",
		"HIDE_NOT_AVAILABLE" => "N",
		"PAGE_ELEMENT_COUNT" => "12",
		"LINE_ELEMENT_COUNT" => "3",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_PROPERTY_CODE" => array(
			0 => "COLOR_REF",
			1 => "SIZES_SHOES",
			2 => "SIZES_CLOTHES",
			3 => "",
		),
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "desc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "desc",
		"OFFERS_LIMIT" => "5",
		"TEMPLATE_THEME" => "site",
		"PRODUCT_DISPLAY_MODE" => "Y",
		"ADD_PICT_PROP" => "-",
		"LABEL_PROP" => "-",
		"OFFER_ADD_PICT_PROP" => "-",
		"OFFER_TREE_PROPS" => array(
			0 => "COLOR_REF",
		),
		"PRODUCT_SUBSCRIPTION" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_OLD_PRICE" => "Y",
		"SHOW_CLOSE_POPUP" => "N",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SEF_MODE" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "Y",
		"SET_BROWSER_TITLE" => "Y",
		"BROWSER_TITLE" => "-",
		"SET_META_KEYWORDS" => "Y",
		"META_KEYWORDS" => "-",
		"SET_META_DESCRIPTION" => "Y",
		"META_DESCRIPTION" => "-",
		"SET_LAST_MODIFIED" => "N",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"CACHE_FILTER" => "N",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRODUCT_PROPERTIES" => array(
		),
		"OFFERS_CART_PROPERTIES" => array(
			0 => "COLOR_REF",
		),
		"ADD_TO_BASKET_ACTION" => "ADD",
		"PAGER_TEMPLATE" => "round",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Товары",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"BACKGROUND_IMAGE" => "-",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
            </div>
        </div>
    </div>
</div>
    <div id="fox-icon-bottom" class="fox-icon bottom"></div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>