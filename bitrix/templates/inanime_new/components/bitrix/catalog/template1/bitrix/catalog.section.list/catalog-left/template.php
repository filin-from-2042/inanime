<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
    <ul class="catalog-left-hierarhy">
        <?
        foreach($arResult["SECTIONS"] as $arSection)
        {
            if($arSection['DEPT_LEVEL'] > 1) continue;
            $arFileData = null;
            $res = CIBlockSection::GetList(array("SORT"=>"ASC"), array('IBLOCK_ID' => $arSection['IBLOCK_ID'],"ID"=>$arSection['ID']),false, array("UF_*"));
            $uf_value =null;
            if($uf_value = $res->GetNext())
            {
                if($uf_value["UF_MENU_ICON"])
                    $arFileData = CFile::GetFileArray($uf_value["UF_MENU_ICON"]);
            }
            echo '<li><a href="'.$arSection["SECTION_PAGE_URL"].'">'.(($arFileData && $arFileData["SRC"])?'<img src="'.$arFileData["SRC"].'">':'').$arSection['NAME'].'</a></li>';
        }
        ?>
    </ul>