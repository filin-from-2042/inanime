<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?
if(count($arResult["SECTIONS"]) > 0)
{?>
    <ul class="catalog-top-hierarhy clearfix">

        <?
        foreach($arResult["SECTIONS"] as $arSection)
        {
            if($arSection['DEPT_LEVEL'] > 1) continue;

            echo '<li class="'.($arSection['ID']==$arParams["SECTION_ID"]?'active':'').'"><a href="'.$arSection["SECTION_PAGE_URL"].'">'.$arSection['NAME'].'</a></li>';
        }
        ?>
    </ul>
<?}?>