<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
?>
<?
function ia_getHtmlDataColumn($arrData)
{
    $data = '<div class="store-address-container grey-container">
                        <div class="icon-wrap block-wrap">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                        </div>
                        <div class="text-wrap block-wrap">
                            <span class="brown-dotted-text">'.$arrData["Name"].'</span>
                        </div>
                        <div class="button-wrap block-wrap">
                            <button type="submit" class="btn btn-default ia-btn yellow-btn option-btn" onclick="$(this).closest(\'.store-address-container\').find(\'.full-content\').toggle()"><span class="glyphicon glyphicon-option-horizontal"></span></button>
                        </div>
                        <div class="full-content">
                            <div class="address gray-text">
                                '.$arrData["Address"].'
                            </div>
                            '.((isset($arrData["Phone"]))?'<div class="phone gray-text">'.$arrData["Phone"].'</div>':'').
                        ((isset($arrData["WorkTime"]))?'<div class="work-hours gray-text">Режим работы:'.$arrData["WorkTime"]. '</div>':'').'
                        </div>
                    </div>';
    return $data;
}
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

            <?
            $currCityStores = array();
            $rsProps= CCatalogStore::GetList(array('SORT'=>'ASC'),array('SITE_ID'=>SITE_ID));
            while ($arProp = $rsProps->Fetch())
            {
                $arUF = $GLOBALS["USER_FIELD_MANAGER"]->GetUserFields("CAT_STORE", $arProp['ID']);
                if(intval($arUF['UF_LOCATION_ID']["VALUE"])===intval($_SESSION['USER_VALUES']['CURRENT_LOCATION_DATA']['ID']))
                    $currCityStores[$arProp['ID']] = $arProp;
            }

            $arPlacemarks = array();
            $gpsN = '';
            $gpsS = '';

            $storesData = array();
            if(count($currCityStores)>0)
            {
                foreach($currCityStores as $storeID=>$store)
                {
                    $storesData[$storeID]['Name'] = $store["ADDRESS"];
                    $storesData[$storeID]['Address'] = $store["TITLE"];
                    $storesData[$storeID]['WorkTime'] = $store["SCHEDULE"];
                    $storesData[$storeID]['GPS_N'] = $store["GPS_N"];
                    $storesData[$storeID]['GPS_S'] = $store["GPS_S"];
                }
            }
            else
            {
                $currZipCode;
                $db_zip = CSaleLocation::GetLocationZIP(intval($_SESSION['USER_VALUES']['CURRENT_LOCATION_DATA']['ID']));
                if($zipProp = $db_zip->Fetch())
                {
                    $currZipCode = $zipProp['ZIP'];
                }
                if($currZipCode)
                {
                    $xml = simplexml_load_file("http://int.cdek.ru/pvzlist.php?citypostcode=".$currZipCode);
                    if($xml->count()>0){
                        $storesData = array();
                        $counter = 0;
                        foreach($xml->Pvz  as $key => $pvz)
                        {
                            foreach($pvz->attributes() as $name => $value)
                            {
                                if($name=='coordX') $storesData[$counter]['GPS_S'] = (string)$value;
                                else if($name=='coordY') $storesData[$counter]['GPS_N'] = (string)$value;
                                else $storesData[$counter][$name]=(string)$value;
                            }
                            $counter++;
                        }
                    }
                }
            }

            $counter=1;
            $firstColumnData='';
            $secondColumnData='';
            foreach($storesData as $store)
            {
                $data = ia_getHtmlDataColumn($store);

                if($counter%2==0)
                {
                    $secondColumnData.=$data;
                }else{
                    $firstColumnData.=$data;
                }
                $counter++;
                if($store["GPS_S"]!=0 && $store["GPS_N"]!=0)
                {
                    $gpsN=substr(doubleval($store["GPS_N"]),0,15);
                    $gpsS=substr(doubleval($store["GPS_S"]),0,15);
                    $arPlacemarks[]=array("LON"=>$gpsS,"LAT"=>$gpsN,"TEXT"=>$store["Address"]);
                }
            }
            ?>

    <div class="row">
        <div class="col-sm-10 col-md-12 col-lg-12 map-column hidden-xs">
            <div class="map-container">
                <?$APPLICATION->IncludeComponent("bitrix:map.yandex.view", "", array(
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
                        "MAP_ID" => "yam_1"
                    ),
                    $component,
                    array("HIDE_ICONS" => "Y")
                );?>
            </div>
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
            <? $APPLICATION->IncludeComponent("bitrix:map.yandex.view", "", array(
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
                    "MAP_ID" => "yam_2"
                ),
                $component,
                array("HIDE_ICONS" => "Y")
            );?>
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

        <?/*$APPLICATION->IncludeComponent(
            "bitrix:catalog.store.list",
            "template1",
            array(
                "PHONE" => "Y",
                "SCHEDULE" => "Y",
                "PATH_TO_ELEMENT" => "store/#store_id#",
                "MAP_TYPE" => "0",
                "SET_TITLE" => "Y",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "36000000",
                "COMPONENT_TEMPLATE" => "template1"
            ),
            false
        );*/?>

        <hr class="general-content-bottom-line">
    </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>