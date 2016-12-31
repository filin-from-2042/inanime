<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
?>
    <div class="container contacts-data">
        <div class="section-personal-header answers-questions">
            <?$APPLICATION->IncludeComponent(
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
            $APPLICATION->AddChainItem('Контакты');
            ?>
            <h1 class="ia-page-title">Контакты</h1>
        </div>
        <div class="row">
            <div class="col-sm-10 col-md-12 col-lg-12 map-column hidden-xs">
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3316.2330102240135!2d38.2903800931116!3d54.00400297630973!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x41369c06ea285fa9%3A0xc8c21f4c63c8b7e0!2z0YPQuy4g0JzQsNGP0LrQvtCy0YHQutC-0LPQviwgMTgsINCd0L7QstC-0LzQvtGB0LrQvtCy0YHQuiwg0KLRg9C70YzRgdC60LDRjyDQvtCx0LsuLCAzMDE2NjQ!5e0!3m2!1sru!2sru!4v1483171421644"
                            width="400" height="300" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
            <div class="col-sm-14 col-md-12 col-lg-9 data-column">
                <div class="data-container">
                    <div class="address-container data-wrap">
                        <div class="title">
                            Юридический адрес
                        </div>
                        <div class="data gray-text">
                             Россия, город Новомосковск, улица Маяковского, д.18
                        </div>
                    </div>
                    <div class="phone-container data-wrap">
                        <div class="title">Телефоны</div>
                        <div class="value gray-text">+7 923 642 8222</div>
                    </div>
                    <div class="mail-container data-wrap">
                        <div class="title">Электронная почта</div>
                        <div class="value"><a href="mailto:support@inanime.ru">support@inanime.ru</a></div>
                    </div>
                    <div class="social-container data-wrap">
                        <a href="http://www.facebook.com" class="social-link facebook"></a>
                        <a href="http://www.twitter.com" class="social-link twitter"></a>
                        <a href="http://vimeo.com" class="social-link vimeo"></a>
                    </div>
                </div>
            </div>
            <div class="col-xs-24 hidden-sm hidden-md hidden-lg map-column">
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3316.2330102240135!2d38.2903800931116!3d54.00400297630973!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x41369c06ea285fa9%3A0xc8c21f4c63c8b7e0!2z0YPQuy4g0JzQsNGP0LrQvtCy0YHQutC-0LPQviwgMTgsINCd0L7QstC-0LzQvtGB0LrQvtCy0YHQuiwg0KLRg9C70YzRgdC60LDRjyDQvtCx0LsuLCAzMDE2NjQ!5e0!3m2!1sru!2sru!4v1483171421644"
                            width="400" height="300" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
        </div>
        <div class="addresses-list-container">
            <div class="fox-icon bottom hidden visible-md visible-lg"></div>
            <hr>
        </div>
        <?/*?>
        <div class="addresses-list-container">
            <hr>
            <h2>Наши магазины</h2>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 column">
                    <div class="store-address-container grey-container">
                        <div class="icon-wrap block-wrap">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                        </div>
                        <div class="text-wrap block-wrap">
                            <span class="brown-dotted-text">Тушинская/Волоколамское шоссе, вл. 89, стр. 2</span>
                        </div>
                        <div class="button-wrap block-wrap">
                            <button type="submit" class="btn btn-default ia-btn yellow-btn option-btn"><span class="glyphicon glyphicon-option-horizontal"></span></button>
                        </div>
                        <div class="full-content">
                            <div class="address gray-text">
                                Бульвар Дм. Донского
                            </div>
                            <div class="phone gray-text">8-800-250-25-25</div>
                            <div class="work-hours gray-text">Режим работы: 9:00-22:00, ежедневно</div>
                        </div>
                    </div>
                    <div class="store-address-container grey-container">
                        <div class="icon-wrap block-wrap">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                        </div>
                        <div class="text-wrap block-wrap">
                            <span class="brown-dotted-text">Тушинская/Волоколамское шоссе, вл. 89, стр. 2</span>
                        </div>
                        <div class="button-wrap block-wrap">
                            <button type="submit" class="btn btn-default ia-btn yellow-btn option-btn"><span class="glyphicon glyphicon-option-horizontal"></span></button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12 column">
                    <div class="store-address-container grey-container">
                        <div class="icon-wrap block-wrap">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                        </div>
                        <div class="text-wrap block-wrap">
                            <span class="brown-dotted-text">Тушинская/Волоколамское шоссе, вл. 89, стр. 2</span>
                        </div>
                        <div class="button-wrap block-wrap">
                            <button type="submit" class="btn btn-default ia-btn yellow-btn option-btn"><span class="glyphicon glyphicon-option-horizontal"></span></button>
                        </div>
                    </div>
                    <div class="store-address-container grey-container">
                        <div class="icon-wrap block-wrap">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                        </div>
                        <div class="text-wrap block-wrap">
                            <span class="brown-dotted-text">Тушинская/Волоколамское шоссе, вл. 89, стр. 2</span>
                        </div>
                        <div class="button-wrap block-wrap">
                            <button type="submit" class="btn btn-default ia-btn yellow-btn option-btn"><span class="glyphicon glyphicon-option-horizontal"></span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?*/?>
    </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>