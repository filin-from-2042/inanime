<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
function getNumEnding($number, $endingArray) {
    $number = $number % 100;
    if ($number>=11 && $number<=19) {
        $ending=$endingArray[2];
    } else {
        $i = $number % 10;
        switch ($i) {
            case (1): $ending = $endingArray[0]; break;
            case (2):
            case (3):
            case (4): $ending = $endingArray[1]; break;
            default: $ending=$endingArray[2];
        }
    }
    return $ending;
}
$arEnding = Array(
    "ий",
    "ия",
    "иев"
);
?>

<h1 class="ia-page-title"><?=$arResult['NAME'];?></h1>
<div class="content">
    <div class="container reviews-list ia-reviews-list">
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
    <?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<?if(count($arResult["ITEMS"])>0):?>
    <div class="reviews-list-container">
		<?foreach($arResult["ITEMS"] as $arItem):?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <div class="review-container">
                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
                    <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>">
                    <div class="review-info">
                        <div class="date text">
                            <i class="fa fa-clock-o" aria-hidden="true"></i>
                            <span><?=$arItem['DISPLAY_ACTIVE_FROM']?></span>
                        </div>
                        <div class="comments text">
                            <i class="fa fa-comment-o" aria-hidden="true"></i>
                            <span>
                               <?
                                $arSelect = Array("PROPERTY_FORUM_MESSAGE_CNT");
                                $arFilter = Array("ID"=>IntVal($arItem["ID"]));
                                //$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
                                $res = CIBlockElement::GetProperty(IntVal($arItem["IBLOCK_ID"]),IntVal($arItem["ID"]), array("sort" => "asc"), Array("CODE"=>"FORUM_MESSAGE_CNT"));
                                if ($ob = $res->GetNext())
                                {
                                    if($ob["VALUE"]) echo $ob["VALUE"]." комментар".getNumEnding(IntVal($ob["VALUE"]), $arEnding);
                                    else {echo '0 комментариев';}
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                    <div class="review-title">
                        <?=$arItem["NAME"]?>
                    </div>
                    <div class="review-anonce">
                        <?=$arItem["PREVIEW_TEXT"]?>
                    </div>
                </a>
            </div>
		<?endforeach;?>
	</div>
<?endif?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
    <br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
    </div>
</div>