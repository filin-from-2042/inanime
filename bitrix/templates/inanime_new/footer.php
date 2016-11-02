<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
            </div>
        </div>
        <footer>
            <div class="container gray-text general-container">
                <div class="row">
                    <div class="hidden-xs hidden-sm col-md-6 col-lg-6 container about">
                        <div class="row">
                            <?$APPLICATION->IncludeFile(
                                $APPLICATION->GetTemplatePath("include_areas/logo_footer.php"),
                                Array(),
                                Array("MODE"=>"html")
                            );?>
                            <p>
                                <?$APPLICATION->IncludeFile(
                                    $APPLICATION->GetTemplatePath("include_areas/text_footer.php"),
                                    Array(),
                                    Array("MODE"=>"text")
                                );?>
                            </p>
                        </div>
                    </div>
                    <div class="hidden-xs col-sm-5 col-md-5 col-lg-5 menu">
                        <?$APPLICATION->IncludeComponent("inanime:menu", "ia_bottom_vertical", Array(
                            "ROOT_MENU_TYPE" => "bottom",
                            "MENU_CACHE_TYPE" => "A",
                            "MENU_CACHE_TIME" => "36000000",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "MENU_THEME" => "site",
                            "CACHE_SELECTED_ITEMS" => "N",
                            "MENU_CACHE_GET_VARS" => "",
                            "MAX_LEVEL" => "3",
                            "CHILD_MENU_TYPE" => "left",
                            "USE_EXT" => "Y",
                            "DELAY" => "N",
                            "ALLOW_MULTI_SELECT" => "N",
                            "COMPONENT_TEMPLATE" => "catalog_vertical",
                            "COMPOSITE_FRAME_MODE" => "A",	// Голосование шаблона компонента по умолчанию
                            "COMPOSITE_FRAME_TYPE" => "AUTO",	// Содержимое компонента
                            ),
                            false
                            );?>

                    </div>
                    <div class="col-xs-24 col-sm-9 col-md-6 col-lg-6 contacts ">
                        <h4>Контакты</h4>
                        <div>
                            <p>
                                <?$APPLICATION->IncludeFile(
                                    $APPLICATION->GetTemplatePath("include_areas/location_text_footer.php"),
                                    Array(),
                                    Array("MODE"=>"text")
                                );?>
                            </p>
                        </div>
                        <div>
                            <p>
                                <?$APPLICATION->IncludeFile(
                                    $APPLICATION->GetTemplatePath("include_areas/contacts_footer.php"),
                                    Array(),
                                    Array("MODE"=>"text")
                                );?>
                            </p>
                        </div>
                        <div class="social-container visible-xs-block">
                            <a href="http://www.facebook.com" class="social-link facebook"></a>
                            <a href="http://www.twitter.com" class="social-link twitter"></a>
                            <a href="http://vimeo.com" class="social-link vimeo"></a>
                        </div>
                    </div>
                    <div class="hidden-xs col-sm-10 col-md-6 col-lg-6 join">
                        <h4>Присоединяйтесь</h4>
                        <p>
                            <?$APPLICATION->IncludeFile(
                                $APPLICATION->GetTemplatePath("include_areas/join_text_footer.php"),
                                Array(),
                                Array("MODE"=>"text")
                            );?>
                        </p>
                        <div class="social-container">
                            <a href="http://www.facebook.com" class="social-link facebook"></a>
                            <a href="http://www.twitter.com" class="social-link twitter"></a>
                            <a href="http://vimeo.com" class="social-link vimeo"></a>
                        </div>
                    </div>

                </div>
                <hr>

            </div>
        </footer>
<!-- Yandex.Metrika counter --> <script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter40606800 = new Ya.Metrika({ id:40606800, clickmap:true, trackLinks:true, accurateTrackBounce:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/40606800" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->
    </body>
</html>