<?php

if (!defined('B_PROLOG_INCLUDED') || (B_PROLOG_INCLUDED !== true)) {
    die();
}


if(!$arResult["NavShowAlways"])
{
if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
return;
}

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
?>

<?if($arResult["bDescPageNumbering"] === true):?>


<div class="pagination-container hidden-xs">
    <nav aria-label="Inanime news list">
        <ul class="ia-pagination pagination">

    <?if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
        <?if($arResult["bSavePage"]):?>
            <a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>"><?=GetMessage("nav_begin")?></a>
            |
            <a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"><?=GetMessage("nav_prev")?></a>
            |
        <?else:?>
            <a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=GetMessage("nav_begin")?></a>
            |
            <?if ($arResult["NavPageCount"] == ($arResult["NavPageNomer"]+1) ):?>
                <a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=GetMessage("nav_prev")?></a>
                |
            <?else:?>
                <a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"><?=GetMessage("nav_prev")?></a>
                |
            <?endif?>
        <?endif?>
    <?else:?>
        <?=GetMessage("nav_begin")?>&nbsp;|&nbsp;<?=GetMessage("nav_prev")?>&nbsp;|
    <?endif?>

    <?while($arResult["nStartPage"] >= $arResult["nEndPage"]):?>
        <?$NavRecordGroupPrint = $arResult["NavPageCount"] - $arResult["nStartPage"] + 1;?>

        <?if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
            <b><?=$NavRecordGroupPrint?></b>
        <?elseif($arResult["nStartPage"] == $arResult["NavPageCount"] && $arResult["bSavePage"] == false):?>
            <a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$NavRecordGroupPrint?></a>
        <?else:?>
            <a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$NavRecordGroupPrint?></a>
        <?endif?>

        <?$arResult["nStartPage"]--?>
    <?endwhile?>

    |

    <?if ($arResult["NavPageNomer"] > 1):?>
        <a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"><?=GetMessage("nav_next")?></a>
        |
        <a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1"><?=GetMessage("nav_end")?></a>
    <?else:?>
        <?=GetMessage("nav_next")?>&nbsp;|&nbsp;<?=GetMessage("nav_end")?>
    <?endif?>

<?else:?>


<div class="pagination-container">
    <nav aria-label="Inanime news list">
        <ul class="ia-pagination pagination">

            <li>
                <?if ($arResult["NavPageNomer"] > 1):?>

                    <?if($arResult["bSavePage"]):?>
                        <a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>" aria-label="Previous">
                            <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        </a>
                    <?else:?>
                        <?if ($arResult["NavPageNomer"] > 2):?>
                            <a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>" aria-label="Previous">
                                <i class="fa fa-chevron-left" aria-hidden="true"></i>
                            </a>
                        <?else:?>
                            <a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" aria-label="Previous">
                                <i class="fa fa-chevron-left" aria-hidden="true"></i>
                            </a>
                        <?endif?>
                    <?endif?>

                <?else:?>
                <?endif?>
            </li>

    <?while($arResult["nStartPage"] <= $arResult["nEndPage"]):?>

            <?if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
                <li class="active"><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$arResult["nStartPage"]?></a>
            <?elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):?>
                <li><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$arResult["nStartPage"]?></a>
            <?else:?>
                <li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a>
            <?endif?>
            <?$arResult["nStartPage"]++?>
        </li>
    <?endwhile?>

    <li>
        <?if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
            <a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"  aria-label="Next">
                <i class="fa fa-chevron-right" aria-hidden="true"></i>
            </a>
        <?else:?>

        <?endif?>
    </li>

<?endif?>


    <?if ($arResult["bShowAll"]):?>
        <noindex>
            <?if ($arResult["NavShowAll"]):?>
                |&nbsp;<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=0" rel="nofollow"><?=GetMessage("nav_paged")?></a>
            <?else:?>
                |&nbsp;<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=1" rel="nofollow"><?=GetMessage("nav_all")?></a>
            <?endif?>
        </noindex>
    <?endif?>


        </ul>
    </nav>
</div>