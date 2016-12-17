<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<div class="menu-container">
    <div class="fox-icon"></div>
    <ul>
        <?
        $counter = 0;
        foreach ($arResult as $itemIndex => $arItem)
        {
            $counter++;
            ?>
            <li<?if ($arItem["SELECTED"]==1) echo ' class="active"';?>><a href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a></li>
        <? } ?>
    </ul>
</div>