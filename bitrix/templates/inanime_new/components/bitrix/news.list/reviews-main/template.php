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
<div class="reviews-list-container">
    <?foreach($arResult["ITEMS"] as $arItem):?>

    <div class="review-container">
        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
            <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>" title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>">
            <div class="review-info">
                <div class="date text">
                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                    <span><?=$arItem["DISPLAY_ACTIVE_FROM"]?></span>
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
                <?echo $arItem["NAME"]?>
            </div>
            <div class="review-anonce hidden-xs">
                <?echo $arItem["PREVIEW_TEXT"];?>
            </div>
        </a>
    </div>
    <?endforeach?>
</div>