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
<div class="container ia-reviews-single">
    <?if($arParams["DISPLAY_PICTURE"]!="N"):?>
        <?if (is_array($arResult["DETAIL_PICTURE"])):?>
            <img src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" class="review-full-image hidden-xs" />
        <?endif;?>
    <?endif?>
    <?if($arParams["DISPLAY_NAME"]!="N" && $arResult["NAME"]):?>
        <h1><?=$arResult["NAME"]?></h1>
    <?endif;?>
    <?
    foreach($arResult["FIELDS"] as $code=>$value)
    {?>
    <?if($code == "TAGS" && $value):?>
        <div class="tags-container visible-xs">
            <div class="tags-list">
                <ul>
                    <?
                    $arTags = explode(',',$value);
                    foreach($arTags as $tag)
                    {?>
                        <li><i class="fa fa-hashtag" aria-hidden="true"></i><?=$tag?></li>
                    <?}
                    ?>
                </ul>
            </div>
        </div>
    <?endif;?>
    <?}?>
    <div class="review-info-container">
        <div class="review-info">
            <div class="date text">
                <i class="fa fa-clock-o" aria-hidden="true"></i>
                <span><?echo $arResult["DISPLAY_ACTIVE_FROM"]?></span>
            </div>
            <div class="comments text">
                <i class="fa fa-comment-o" aria-hidden="true"></i>
                <span>
                     <?
                     $arSelect = Array("PROPERTY_FORUM_MESSAGE_CNT");
                     $arFilter = Array("ID"=>IntVal($arResult["ID"]));
                     //$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
                     $res = CIBlockElement::GetProperty(IntVal($arResult["IBLOCK_ID"]),IntVal($arResult["ID"]), array("sort" => "asc"), Array("CODE"=>"FORUM_MESSAGE_CNT"));
                     if ($ob = $res->GetNext())
                     {
                         if($ob["VALUE"]) echo $ob["VALUE"]." комментар".getNumEnding(IntVal($ob["VALUE"]), $arEnding);
                         else {echo '0 комментариев';}
                     }
                     ?>
                </span>
            </div>
        </div>
    </div>
    <?
    foreach($arResult["FIELDS"] as $code=>$value)
    {?>
        <?if($code == "TAGS" && $value):?>
        <div class="tags-container hidden-xs">
            <div class="tags-list">
                <ul>
                <?
                    $arTags = explode(',',$value);
                    foreach($arTags as $tag)
                    {?>
                        <li><a href="/search/index.php?tags=<?=urlencode($tag)?>"><i class="fa fa-hashtag" aria-hidden="true"></i><?=$tag?></a></li>
                    <?}
                ?>
                </ul>
            </div>
        </div>
        <?endif;?>
    <?}

    ?>
    <?if($arParams["DISPLAY_PICTURE"]!="N"):?>
        <?if (is_array($arResult["DETAIL_PICTURE"])):?>
            <img src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" class="review-full-image visible-xs" />
        <?endif;?>
    <?endif?>
    <div class="text-container">

    <?if(strlen($arResult["DETAIL_TEXT"])>0):?>
        <?echo $arResult["DETAIL_TEXT"];?>
    <?else:?>
		<?echo $arResult["PREVIEW_TEXT"];?>
	<?endif?>
    </div>

</div>