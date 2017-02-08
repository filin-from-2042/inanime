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

            <?
$rsProps= CCatalogStore::GetList(array('SORT'=>'ASC'),array('SITE_ID'=>SITE_ID));
while ($arProp = $rsProps->Fetch())
{
    //var_dump($arProp);
}
            //var_dump($_SESSION['USER_VALUES']['CURRENT_LOCATION_DATA']['ID']);
            ?>

        <?$APPLICATION->IncludeComponent(
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
        );?>

        <hr class="general-content-bottom-line">
    </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>