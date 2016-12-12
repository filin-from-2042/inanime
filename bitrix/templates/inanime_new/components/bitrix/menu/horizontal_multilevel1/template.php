<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>


    <nav class="navigation mobile-collapsed" id="top-navigation-bar">
        <div class="container">
            <ul class="first-level">

            <?
            $previousLevel = 0;
            $columnItems = 0;
            $columnsCount=0;
            foreach($arResult as $arItem):?>

                <?// первый уровень меню?>
                <?if($arItem["DEPTH_LEVEL"]=='1'):?>

                    <?if($previousLevel=='2'):?>
                        </ul></li>
                    <?elseif( $previousLevel=='3'):?>
                        </ul>
                        <?=str_repeat("</div>", $columnsCount);?>
                        </li></ul></li>
                        <?$columnItems=0?>
                        <?$columnsCount=0?>
                    <?endif?>

                    <?if($arItem["IS_PARENT"]):?>
                    <li class="dropdown">
                        <i class="fa fa-bars visible-sm" aria-hidden="true" data-toggle="dropdown"></i>
                        <a href="<?=$arItem["LINK"]?>" ><?=$arItem["TEXT"]?></a>
                        <i class="fa fa-bars hidden-sm hidden-xs" aria-hidden="true" data-toggle="dropdown"></i>
                        <ul class="second-level dropdown-menu">
                    <?else:?>
                        <li>
                            <a href="<?=$arItem["LINK"]?>" ><?=$arItem["TEXT"]?></a>
                        </li>
                    <?endif?>
                <?endif?>

                <?// второй уровень меню?>
                <?if($arItem["DEPTH_LEVEL"]=='2'):?>

                    <?if($previousLevel=='3'):?>
                        </ul>
                        <?=str_repeat("</div>", $columnsCount);?>
                        </li>
                        <?$columnItems=0?>
                        <?$columnsCount=0?>
                    <?endif?>

                    <?if($arItem["IS_PARENT"]):?>
                        <li class="dropdown"><a href="<?=$arItem["LINK"]?>" ><i class="fa fa-male" aria-hidden="true"></i><?=$arItem["TEXT"]?></a>
                        <div class="third-level-container">
                            <ul>
                        <?$columnsCount=1?>
                    <?else:?>
                        <li><a href="<?=$arItem["LINK"]?>"><i class="fa fa-female" aria-hidden="true"></i><?=$arItem["TEXT"]?></a></li>
                    <?endif?>
                <?endif?>

                <?// третий уровень меню?>
                <?if($arItem["DEPTH_LEVEL"]=='3'):?>
                    <?if($columnItems < 10):?>
                        <li><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
                        <?$columnItems++?>
                    <?else:?>
                        </ul>
                        <ul>
                            <li><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
                        <?$columnItems=1?>
                        <?$columnsCount++?>
                    <?endif?>

                <?endif?>

                <?$previousLevel=$arItem["DEPTH_LEVEL"]?>
            <?endforeach?>

            </ul>
        </div>
    </nav>
<?endif?>