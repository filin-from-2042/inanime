<?
$arPlacemarks = array();
$gpsN = '';
$gpsS = '';
?>
<?if(is_array($arResult["STORES"]) && !empty($arResult["STORES"])):?>
            <?$counter=1;?>
            <?$firstColumnData='';?>
            <?$secondColumnData='';?>
            <?foreach($arResult["STORES"] as $pid=>$arProperty):?>
                <?
                $data = '<div class="store-address-container grey-container">
                        <div class="icon-wrap block-wrap">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                        </div>
                        <div class="text-wrap block-wrap">
                            <span class="brown-dotted-text">'.$arProperty["ADDRESS"].'</span>
                        </div>
                        <div class="button-wrap block-wrap">
                            <button type="submit" class="btn btn-default ia-btn yellow-btn option-btn" onclick="$(this).closest(\'.store-address-container\').find(\'.full-content\').toggle()"><span class="glyphicon glyphicon-option-horizontal"></span></button>
                        </div>
                        <div class="full-content">
                            <div class="address gray-text">
                                '.$arProperty["TITLE"].'
                            </div>
                            '.((isset($arProperty["PHONE"]))?'<div class="phone gray-text">'.$arProperty["PHONE"].'</div>':'').
                    ((isset($arProperty["PHONE"]))?'<div class="work-hours gray-text">Режим работы:'.$arProperty["SCHEDULE"]. '</div>':'').'
                        </div>
                    </div>';

                if($counter%2==0)
                {
                    $secondColumnData.=$data;
                }else{
                    $firstColumnData.=$data;
                }
                ?>
                <?$counter++;?>
                <?
                if($arProperty["GPS_S"]!=0 && $arProperty["GPS_N"]!=0)
                {
                    $gpsN=substr(doubleval($arProperty["GPS_N"]),0,15);
                    $gpsS=substr(doubleval($arProperty["GPS_S"]),0,15);
                    $arPlacemarks[]=array("LON"=>$gpsS,"LAT"=>$gpsN,"TEXT"=>$arProperty["TITLE"]);
                }
                ?>
            <?endforeach;?>
<?endif;?>
<div class="row">

    <div class="col-sm-10 col-md-12 col-lg-12 map-column hidden-xs">
        <?
        if ($arResult['VIEW_MAP'])
        {?>
            <div class="map-container">
               <? if($arResult["MAP"]==0)
                {
                    $APPLICATION->IncludeComponent("bitrix:map.yandex.view", ".default", array(
                            "INIT_MAP_TYPE" => "MAP",
                            "MAP_DATA" => serialize(array("yandex_lat"=>$gpsN,"yandex_lon"=>$gpsS,"yandex_scale"=>14,"PLACEMARKS" => $arPlacemarks)),
    //                        "MAP_WIDTH" => "400",
    //                        "MAP_HEIGHT" => "300",
                        "MAP_WIDTH" => "100%",
                        "MAP_HEIGHT" => "360",
                            "CONTROLS" => array(
                                0 => "ZOOM",
                            ),
                            "OPTIONS" => array(
                                0 => "ENABLE_SCROLL_ZOOM",
                                1 => "ENABLE_DBLCLICK_ZOOM",
                                2 => "ENABLE_DRAGGING",
                            ),
                            "MAP_ID" => ""
                        ),
                        $component,
                        array("HIDE_ICONS" => "Y")
                    );
                }
                else
                {
                    $APPLICATION->IncludeComponent("bitrix:map.google.view", ".default", array(
                            "INIT_MAP_TYPE" => "MAP",
                            "MAP_DATA" => serialize(array("google_lat"=>$gpsN,"google_lon"=>$gpsS,"google_scale"=>10,"PLACEMARKS" => $arPlacemarks)),
    //                        "MAP_WIDTH" => "400",
    //                        "MAP_HEIGHT" => "300",
                            "MAP_WIDTH" => "100%",
                            "MAP_HEIGHT" => "360",
                            "CONTROLS" => array(
                                0 => "ZOOM",
                            ),
                            "OPTIONS" => array(
                                0 => "ENABLE_SCROLL_ZOOM",
                                1 => "ENABLE_DBLCLICK_ZOOM",
                                2 => "ENABLE_DRAGGING",
                            ),
                            "MAP_ID" => ""
                        ),
                        $component,
                        array("HIDE_ICONS" => "Y")
                    );
                }?>
            </div>
        <?}
        ?>
    </div>

    <div class="col-sm-14 col-md-12 col-lg-9 data-column">
        <div class="data-container">
            <?$APPLICATION->IncludeFile(
                $APPLICATION->GetTemplatePath("include_areas/contacts_page_data.php"),
                Array(),
                Array("MODE"=>"html")
            );?>

            <?$APPLICATION->IncludeFile(
                $APPLICATION->GetTemplatePath("include_areas/socials-buttons.php"),
                Array(),
                Array("MODE"=>"html")
            );?>
        </div>
    </div>
    <div class="col-xs-24 hidden-sm hidden-md hidden-lg map-column">
        <?
        if ($arResult['VIEW_MAP'])
        {?>
            <div class="map-container">

                <? if($arResult["MAP"]==0)
                {
                    $APPLICATION->IncludeComponent("bitrix:map.yandex.view", ".default", array(
                            "INIT_MAP_TYPE" => "MAP",
                            "MAP_DATA" => serialize(array("yandex_lat"=>$gpsN,"yandex_lon"=>$gpsS,"yandex_scale"=>14,"PLACEMARKS" => $arPlacemarks)),
//                            "MAP_WIDTH" => "400",
//                            "MAP_HEIGHT" => "300",
                            "MAP_WIDTH" => "100%",
                            "MAP_HEIGHT" => "265",
                            "CONTROLS" => array(
                                0 => "ZOOM",
                            ),
                            "OPTIONS" => array(
                                0 => "ENABLE_SCROLL_ZOOM",
                                1 => "ENABLE_DBLCLICK_ZOOM",
                                2 => "ENABLE_DRAGGING",
                            ),
                            "MAP_ID" => ""
                        ),
                        $component,
                        array("HIDE_ICONS" => "Y")
                    );
                }
                else
                {
                    $APPLICATION->IncludeComponent("bitrix:map.google.view", ".default", array(
                            "INIT_MAP_TYPE" => "MAP",
                            "MAP_DATA" => serialize(array("google_lat"=>$gpsN,"google_lon"=>$gpsS,"google_scale"=>15,"PLACEMARKS" => $arPlacemarks)),
//                            "MAP_WIDTH" => "400",
//                            "MAP_HEIGHT" => "300",
                            "MAP_WIDTH" => "100%",
                            "MAP_HEIGHT" => "265",
                            "CONTROLS" => array(
                                0 => "ZOOM",
                            ),
                            "OPTIONS" => array(
                                0 => "ENABLE_SCROLL_ZOOM",
                                1 => "ENABLE_DBLCLICK_ZOOM",
                                2 => "ENABLE_DRAGGING",
                            ),
                            "MAP_ID" => ""
                        ),
                        $component,
                        array("HIDE_ICONS" => "Y")
                    );
                }?>
            </div>
        <?}
        ?>
    </div>
</div>
<?if($firstColumnData):?>
    <div class="addresses-list-container">
        <div class="fox-icon bottom hidden visible-md visible-lg"></div>
        <hr>
        <h2>Наши магазины</h2>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 column"><?=$firstColumnData?></div>
            <div class="col-sm-12 col-md-12 col-lg-12 column"><?=$secondColumnData?></div>
        </div>
    </div>
<?endif;?>