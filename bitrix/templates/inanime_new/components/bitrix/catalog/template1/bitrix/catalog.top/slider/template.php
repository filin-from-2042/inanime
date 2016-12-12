<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
CJSCore::Init(array("fx"));
$randID = rand();
$itemsCount = count($arResult["ITEMS"]);
?>
<div class="bx_slider_section" id="bx_catalog_slider_<?=$randID?>">
	<div class="bx_slider_container" style="width:300%;" id="bx_catalog_slider_cont_<?=$randID?>">
<?foreach($arResult["ITEMS"] as $key=>$arItem):
    $dpURL=getDetailPageUrl($arItem["ID"]);
                if ($dpURL) $detailPageURL=$dpURL; else $detailPageURL=$arItem["DETAIL_PAGE_URL"];
    ?>
		<div class="bx_slider_block bx_index_number_<?=$key+1?> <?if ($key==0):?>active<?endif?>" style="width:33.333%;">
			<div class="bx_slider_photo_container">
				<div class="bx_slider_photo_background"></div>
				<a href="<?=$detailPageURL?>" class="bx_slider_photo_element" style="background: #fff url('<?=$arItem["DETAIL_PICTURE"]["SRC"]?>') no-repeat center;">
				</a>
			</div>
			<div class="bx_slider_content_container">
				<h1 class="bx_slider_title"><a href="<?=$detailPageURL?>"><?=$arItem["NAME"]?></a></h1>
				<div class="bx_slider_content_description" style="padding-top: 10px;"><?=$arItem["PREVIEW_TEXT"] ? $arItem["PREVIEW_TEXT"] : $arItem["DETAILTEXT"]?></div>
				<div class="bx_slider_price_container">
					<div class="bx_slider_price_leftblock">
					<?if(is_array($arItem["OFFERS"]) && !empty($arItem["OFFERS"])):?>
						<div class="bx_slider_current_price"><? echo GetMessage('CATALOG_FROM'); ?> <?=$arItem["PRINT_MIN_OFFER_PRICE"]?></div>
					<?else:?>
						<?
						if (isset($arItem['MIN_PRICE']) && !empty($arItem['MIN_PRICE']))
						{
							if ($arItem['MIN_PRICE']["DISCOUNT_VALUE"] < $arItem['MIN_PRICE']["VALUE"]):?>
								<div class="bx_slider_current_price"><?=$arItem['MIN_PRICE']["PRINT_DISCOUNT_VALUE"]?></div>
								<div class="bx_slider_old_price"><?=$arItem['MIN_PRICE']["PRINT_VALUE"]?></div>
							<?else:?>
								<div class="bx_slider_current_price bx_no_oldprice"><?=$arItem['MIN_PRICE']["PRINT_VALUE"]?></div>
							<?endif;
						}
						else
						{
							foreach($arItem["PRICES"] as $priceCode=>$arPrices):?>
							<?if ($arPrices["DISCOUNT_VALUE"] < $arPrices["VALUE"]):?>
								<div class="bx_slider_current_price"><?=$arPrices["PRINT_DISCOUNT_VALUE"]?></div>
								<div class="bx_slider_old_price"><?=$arPrices["PRINT_VALUE"]?></div>
							<?else:?>
								<div class="bx_slider_current_price bx_no_oldprice"><?=$arPrices["PRINT_VALUE"]?></div>
							<?endif?>
							<?endforeach;
						}
					endif?>
						<a href="<?=$detailPageURL?>" class="bt_blue big shadow cart"><span></span><strong><?=GetMessage("CATALOG_MORE")?></strong></a>
					</div>
					<div class="bx_slider_price_rightblock"></div>
				</div>
			</div>
		</div>
<?endforeach?>
	</div>
	<?if ($itemsCount > 1):?>
	<div class="bx_slider_controls">
		<div class="bx_slider_arrow_left" onclick="catalogSlideLeft(this, '<?=$randID?>')"></div>
		<div class="bx_slider_arrow_right" onclick="catalogSlideRight(this, '<?=$randID?>')"></div>
	</div>
	<ul class="bx_slider_pagination" id="bx_catalog_dots_<?=$randID?>">
		<?for($i=1; $i<=$itemsCount; $i++){?>
		<li class="<?if ($i==1):?>active<?endif?> bx_dot_index_number_<?=$i?>"><a href="javascript:void(0)" onclick="catalogSlideJump(this, '<?=$randID?>', '<?=$i?>')"></a></li>
		<?}?>
	</ul>
	<?endif?>
</div>

<script>
	BX.ready(function(){
		var blocksList = BX.findChildren(BX("bx_catalog_slider_cont_<?=$randID?>"), {className:"bx_slider_block"}, true);
		var lis_col = blocksList.length;

		var bx_slider_container_width = (100 * lis_col);
		var bx_slider_block_width = (100 / lis_col);
		var contObj = BX("bx_catalog_slider_cont_<?=$randID?>");
		contObj.style.width = bx_slider_container_width+"%";
		for(block in blocksList)
		{
			blocksList[block].style.width = bx_slider_block_width+"%";
			blocksList[block].style.opacity = 0;
		}
		BX.firstChild(contObj).style.opacity = 1;
	});

	if (!window.catalogSliderMove)
		window.catalogSliderMove = [{'<?=$randID?>' : 0}];
	else
		window.catalogSliderMove.push({'<?=$randID?>' : 0});
</script>