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

        <div class="top-pager-container">
            <div class="tag-list-container clearfix">
                <div class="dropdown-wrap">
                    <div class="dropdown select-container tag">
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <li>
                                <span onclick="iaReviewList.ddSelectFilter(this);">
                                    &nbsp
                                    <span class="sort-value hidden"></span>
                                </span>
                            </li>
                            <?
                            $currFlter;
                            if($GLOBALS['arrFilter'] && !empty($GLOBALS['arrFilter']['?TAGS'])) $currFlter = $GLOBALS['arrFilter']['?TAGS'];
                            $res = CIBlockElement::GetList(Array(), Array("ACTIVE"=>"Y", "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, false, Array("NAME", "TAGS"));
                            while ($el = $res->Fetch())
                            {
                                $arrTags = explode(', ',$el["TAGS"]);
                                for ($i =0; $i < sizeof($arrTags); $i++)
                                {
                                    if(empty($arrTags[$i]) || trim($arrTags[$i])=='') continue;
                                    echo '<li>';
                                        echo '<span onclick="iaReviewList.ddSelectFilter(this);">';
                                            echo $arrTags[$i];
                                            echo '<span class="sort-value hidden">'.$arrTags[$i].'</span>';
                                        echo '</span>';
                                    echo '</li>';
                                }
                            }
                            ?>
                        </ul>
                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <span class="glyphicon glyphicon-chevron-down"></span>
                            <span class="text">
                                <?=$currFlter?>
                                <span class="sort-value hidden"><?=$currFlter?></span>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="dropdown-wrap">
                    <div class="dropdown select-container order">
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                            <li>
                                <span onclick="iaReviewList.ddSelectFilter(this);">
                                    Дата публикации <span class="glyphicon glyphicon-triangle-top"></span>
                                    <span class="sort-value hidden"><?=strtolower($arParams['SORT_BY1'])?>;asc</span>
                                </span>
                            </li>
                            <li>
                                <span onclick="iaReviewList.ddSelectFilter(this);">
                                    Дата публикации <span class="glyphicon glyphicon-triangle-bottom"></span>
                                    <span class="sort-value hidden"><?=strtolower($arParams['SORT_BY1'])?>;desc</span>
                                </span>
                            </li>
                        </ul>
                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <span class="glyphicon glyphicon-chevron-down"></span>
                            <span class="text">
                                Дата публикации<span class="glyphicon glyphicon-triangle-<?=(strtolower($arParams['SORT_ORDER1'])=='asc')?'top':'bottom'?>"></span>
                                <span class="sort-value hidden"><?=strtolower($arParams['SORT_BY1'])?>;<?=strtolower($arParams['SORT_ORDER1'])?></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="pager-container hidden-xs">
                <?if($arParams["DISPLAY_TOP_PAGER"]):?>
                    <?=$arResult["NAV_STRING"]?><br />
                <?endif;?>
            </div>
        </div>
<?if(count($arResult["ITEMS"])>0):?>
    <div class="reviews-list-container">
		<?foreach($arResult["ITEMS"] as $arItem):?>
            <?
            //$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            //$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
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

                <?
                if($arItem["TAGS"]){
                    $arrTags = explode(',',$arItem["TAGS"]);
                    ?>
                    <div class="tags-container">
                        <div class="tags-list">
                            <ul>
                                <?
                                foreach($arrTags as $tag)
                                {?>
                                    <li><a href="/search/index.php?tags=<?=urlencode($tag)?>"><i class="fa fa-hashtag" aria-hidden="true"></i><?=$tag?></a></li>
                                <?}?>
                            </ul>
                        </div>
                    </div>
                <?}?>
            </div>
		<?endforeach;?>
	</div>
<?endif?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
    <?=$arResult["NAV_STRING"]?>
<?endif;?>
    </div>
</div>
<?
    $arJSParams = array('ajaxURL'=>$templateFolder.'/ajax.php');
$arJSParams['elementsPerPage'] = $arParams["NEWS_COUNT"];
?>
<script>
    var iaReviewList = new InanimeReviewList(<? echo CUtil::PhpToJSObject($arJSParams, false, true);?>);
    $(document).ready(function(){
        $('.ia-reviews-list .pagination-container .pagination a').click(function(event){
            iaReviewList.pagSelectFilter(event);
        });
    });
</script>