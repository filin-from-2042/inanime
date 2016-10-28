<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

?>
<?if (!empty($arResult)):?>
<ul>
<?foreach($arResult as $arItem):?>
    <li><i class="fa fa-chevron-right" aria-hidden="true"></i><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
<?endforeach?>
</ul>
<?endif?>