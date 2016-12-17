<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props_format.php");
?>
<div class="section">
<h4><?=GetMessage("SOA_TEMPL_PROP_INFO")?></h4>
	<?
	$bHideProps = true;

	if (is_array($arResult["ORDER_PROP"]["USER_PROFILES"]) && !empty($arResult["ORDER_PROP"]["USER_PROFILES"])):?>
			<div class="bx_block r1x3">
				<?=GetMessage("SOA_TEMPL_EXISTING_PROFILE")?>
			</div>
			<div class="bx_block r3x1">
					<?
					if (count($arResult["ORDER_PROP"]["USER_PROFILES"]) == 1)
					{
						foreach($arResult["ORDER_PROP"]["USER_PROFILES"] as $arUserProfiles)
						{
							echo "<strong>".$arUserProfiles["NAME"]."</strong>";
							?>
							<input type="hidden" name="PROFILE_ID" id="ID_PROFILE_ID" value="<?=$arUserProfiles["ID"]?>" />
							<?
						}
					}
					else
					{
						?>
						<select name="PROFILE_ID" id="ID_PROFILE_ID" onChange="SetContact(this.value)">
							<?
							foreach($arResult["ORDER_PROP"]["USER_PROFILES"] as $arUserProfiles)
							{
								?>
								<option value="<?= $arUserProfiles["ID"] ?>"<?if ($arUserProfiles["CHECKED"]=="Y") echo " selected";?>><?=$arUserProfiles["NAME"]?></option>
								<?
							}
							?>
						</select>
						<?
					}
					?>
				<div style="clear: both;"></div>
			</div>
    <?
	else:
		$bHideProps = false;
	endif;
	?>
</div>

<div class="row">
    <div class="col-md-offset-3 col-lg-offset-3 col-sm-12 col-md-8 col-lg-8 fio-column">
        <div class="input-container">
<?
foreach ($arResult["ORDER_PROP"]["USER_PROPS_Y"] as $arProperties)
{
    if($arProperties['IS_PROFILE_NAME']=='Y')
    {?>
        <div class="input-container">
            <input type="text" name="<?=$arProperties["FIELD_NAME"]?>" value="<?=$arProperties["VALUE"]?>" placeholder="" class="form-control first-name-input">
        </div>
    <?}
    if($arProperties['IS_EMAIL']=='Y')
    {?>
        <div class="input-container">
            <input type="text" name="<?=$arProperties["FIELD_NAME"]?>" value="<?=$arProperties["VALUE"]?>" placeholder="" class="form-control email-input">
        </div>
    <?}
    if($arProperties['IS_PHONE']=='Y')
    {?>
        <div class="input-container">
            <input type="text" name="<?=$arProperties["FIELD_NAME"]?>" value="<?=$arProperties["VALUE"]?>" placeholder="" class="form-control phone-input">
        </div>
    <?}

}

//var_dump($arResult["ORDER_PROP"]["USER_PROPS_N"]);
//var_dump($arResult["ORDER_PROP"]["USER_PROPS_Y"]);
?>

        </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12 address-column">
        <div class="radio-values-container">
            <div class="radio-container">
                <input type="hidden" name="adress-radio" class="ia-radio-value">
                <div class="radio-button-container">
                    <div class="ia-radio-button small">
                        <span class="value hidden">address1</span>
                        <div class="radio-dot"></div>
                    </div>
                    <div class="button-title">Сохраненный адрес 1</div>
                </div>
                <div class="radio-button-container">
                    <div class="ia-radio-button small">
                        <span class="value hidden">address2</span>
                        <div class="radio-dot"></div>
                    </div>
                    <div class="button-title">Сохраненный адресс 2</div>
                </div>
            </div>
            <div class="values-container">
                <div class="address-container address1 selected">
                    <div class="input-container">
                        <input type="text" name="city" value="" placeholder="Город1" class="form-control city-input">
                    </div>
                    <div class="street-data-fields">
                        <div class="input-container">
                            <input type="text" name="street" value="" placeholder="Улица1" class="form-control street-input">
                        </div>
                        <div class="input-container">
                            <input type="text" name="house-number" value="" placeholder="Дом1" class="form-control house-number-input">
                        </div>
                        <div class="input-container apartment-container">
                            <input type="text" name="apartment" value="" placeholder="Квартира1" class="form-control apartment-input">
                        </div>
                    </div>
                </div>
                <div class="address-container address2">
                    <div class="input-container">
                        <input type="text" name="city" value="" placeholder="Город2" class="form-control city-input" disabled="disabled">
                    </div>
                    <div class="street-data-fields">
                        <div class="input-container">
                            <input type="text" name="street" value="" placeholder="Улица2" class="form-control street-input" disabled="disabled">
                        </div>
                        <div class="input-container">
                            <input type="text" name="house-number" value="" placeholder="Дом2" class="form-control house-number-input" disabled="disabled">
                        </div>
                        <div class="input-container apartment-container">
                            <input type="text" name="apartment" value="" placeholder="Квартира2" class="form-control apartment-input" disabled="disabled">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?if(!CSaleLocation::isLocationProEnabled()):?>
	<div style="display:none;">

		<?$APPLICATION->IncludeComponent(
			"bitrix:sale.ajax.locations",
			$arParams["TEMPLATE_LOCATION"],
			array(
				"AJAX_CALL" => "N",
				"COUNTRY_INPUT_NAME" => "COUNTRY_tmp",
				"REGION_INPUT_NAME" => "REGION_tmp",
				"CITY_INPUT_NAME" => "tmp",
				"CITY_OUT_LOCATION" => "Y",
				"LOCATION_VALUE" => "",
				"ONCITYCHANGE" => "submitForm()",
			),
			null,
			array('HIDE_ICONS' => 'Y')
		);?>

	</div>
<?endif?>
