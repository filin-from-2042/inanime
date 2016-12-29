<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
if (!function_exists("showFilePropertyField")) {

    function showFilePropertyField($name, $property_fields, $values, $max_file_size_show = 50000) {
        $res = "";

        if (!is_array($values) || empty($values))
            $values = array(
                "n0" => 0,
            );

        if ($property_fields["MULTIPLE"] == "N") {
            $res = "<label for=\"\"><input type=\"file\" size=\"" . $max_file_size_show . "\" value=\"" . $property_fields["VALUE"] . "\" name=\"" . $name . "[0]\" id=\"" . $name . "[0]\"></label>";
        } else {
            $res = '
			<script type="text/javascript">
				function addControl(item)
				{
					var current_name = item.id.split("[")[0],
						current_id = item.id.split("[")[1].replace("[", "").replace("]", ""),
						next_id = parseInt(current_id) + 1;

					var newInput = document.createElement("input");
					newInput.type = "file";
					newInput.name = current_name + "[" + next_id + "]";
					newInput.id = current_name + "[" + next_id + "]";
					newInput.onchange = function() { addControl(this); };

					var br = document.createElement("br");
					var br2 = document.createElement("br");

					BX(item.id).parentNode.appendChild(br);
					BX(item.id).parentNode.appendChild(br2);
					BX(item.id).parentNode.appendChild(newInput);
				}
			</script>
			';

            $res .= "<label for=\"\"><input type=\"file\" size=\"" . $max_file_size_show . "\" value=\"" . $property_fields["VALUE"] . "\" name=\"" . $name . "[0]\" id=\"" . $name . "[0]\"></label>";
            $res .= "<br/><br/>";
            $res .= "<label for=\"\"><input type=\"file\" size=\"" . $max_file_size_show . "\" value=\"" . $property_fields["VALUE"] . "\" name=\"" . $name . "[1]\" id=\"" . $name . "[1]\" onChange=\"javascript:addControl(this);\"></label>";
        }

        return $res;
    }

}

if (!function_exists("PrintPropsForm")) {

    function PrintPropsForm($arSource = array(), $locationTemplate = ".default", $IDs = null, $arErrors) {
	global $USER;
	$rsUser = CUser::GetByID($USER->GetID());
$arUser = $rsUser->Fetch();
        if (!empty($arSource)) {
            ?>
            <div>
                <?
                foreach ($arSource as $arProperties) {

                    if ($arProperties["TYPE"] == "LOCATION" || (is_array($IDs) && !in_array($arProperties["CODE"], $IDs)))
                        continue;
		    if (strlen($arProperties["VALUE"])==0) {
		    if ($arProperties["CODE"]=="INN") $arProperties["VALUE"]=$arUser["UF_USER_INN"]; else
			if ($arProperties["CODE"]=="KPP") $arProperties["VALUE"]=$arUser["UF_USER_KPP"]; else
			    if ($arProperties["CODE"]=="FAX") $arProperties["VALUE"]=$arUser["WORK_FAX"]; else
				if ($arProperties["CODE"]=="COMPANY_ADR") $arProperties["VALUE"]=$arUser["UF_J_ADDRESS"]; else
				    if ($arProperties["CODE"]=="PHONE") $arProperties["VALUE"]=$arUser["WORK_PHONE"];
		    }


                    if ($arProperties["CODE"] == "ZIP") {
                        ?>
                        <input type="hidden" maxlength="250" size="<?= $arProperties["SIZE1"] ?>" value="<?= $arProperties["VALUE"] ?>" name="<?= $arProperties["FIELD_NAME"] ?>" id="<?= $arProperties["FIELD_NAME"] ?>">
                        <?
                        continue;
                    }
                    if ($arProperties["TYPE"] == "CHECKBOX") {
                        ?>
                        <input type="hidden" name="<?= $arProperties["FIELD_NAME"] ?>" value="">

                        <div class="bx_block r1x3 pt8">
                            <?= $arProperties["NAME"] ?>
                            <? if ($arProperties["REQUIED_FORMATED"] == "Y"): ?>
                                <span class="bx_sof_req">*</span>
                            <? endif; ?>
                        </div>

                        <div class="bx_block r1x3 pt8">
                            <input type="checkbox" name="<?= $arProperties["FIELD_NAME"] ?>" id="<?= $arProperties["FIELD_NAME"] ?>" value="Y"<? if ($arProperties["CHECKED"] == "Y") echo " checked"; ?>>

                            <?
                            if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
                                ?>
                                <div class="bx_description">
                                    <?= $arProperties["DESCRIPTION"] ?>
                                </div>
                                <?
                            endif;
                            ?>
                        </div>

                        <div style="clear: both;"></div>
                        <?
                    }
                    elseif ($arProperties["TYPE"] == "TEXT") {
                        ?>
                        <div class="form__el">
                            <div class="labelWrap">
                                <label for="<?= $arProperties["FIELD_NAME"] ?>"><?= $arProperties["NAME"] ?></label>
                            </div>

                            <div class="inputWrap">
                                <input type="text" class="form__text" name="<?= $arProperties["FIELD_NAME"] ?>" id="<?= $arProperties["FIELD_NAME"] ?>" value="<?= $arProperties["VALUE"] ?>" />
                                <? if (strlen($arErrors[$arProperties["CODE"]]) > 0) { ?>
                                    <div class="form__error" title='<?= $arErrors[$arProperties["CODE"]]; ?>'><i class="fa fa-exclamation-triangle"></i> <span class="no-js-text"><?= $arErrors[$arProperties["CODE"]]; ?></span></div>
                                <? } ?>
                            </div>

                        </div>
                        <?
                    } elseif ($arProperties["TYPE"] == "SELECT") {
                        ?>
                        <br/>
                        <div class="bx_block r1x3 pt8">
                            <?= $arProperties["NAME"] ?>
                            <? if ($arProperties["REQUIED_FORMATED"] == "Y"): ?>
                                <span class="bx_sof_req">*</span>
                            <? endif; ?>
                        </div>

                        <div class="bx_block r3x1">
                            <select name="<?= $arProperties["FIELD_NAME"] ?>" id="<?= $arProperties["FIELD_NAME"] ?>" size="<?= $arProperties["SIZE1"] ?>">
                                <?
                                foreach ($arProperties["VARIANTS"] as $arVariants):
                                    ?>
                                    <option value="<?= $arVariants["VALUE"] ?>"<? if ($arVariants["SELECTED"] == "Y") echo " selected"; ?>><?= $arVariants["NAME"] ?></option>
                                    <?
                                endforeach;
                                ?>
                            </select>

                            <?
                            if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
                                ?>
                                <div class="bx_description">
                                    <?= $arProperties["DESCRIPTION"] ?>
                                </div>
                                <?
                            endif;
                            ?>
                        </div>
                        <div style="clear: both;"></div>
                        <?
                    }
                    elseif ($arProperties["TYPE"] == "MULTISELECT") {
                        ?>
                        <br/>
                        <div class="bx_block r1x3 pt8">
                            <?= $arProperties["NAME"] ?>
                            <? if ($arProperties["REQUIED_FORMATED"] == "Y"): ?>
                                <span class="bx_sof_req">*</span>
                            <? endif; ?>
                        </div>

                        <div class="bx_block r3x1">
                            <select multiple name="<?= $arProperties["FIELD_NAME"] ?>" id="<?= $arProperties["FIELD_NAME"] ?>" size="<?= $arProperties["SIZE1"] ?>">
                                <?
                                foreach ($arProperties["VARIANTS"] as $arVariants):
                                    ?>
                                    <option value="<?= $arVariants["VALUE"] ?>"<? if ($arVariants["SELECTED"] == "Y") echo " selected"; ?>><?= $arVariants["NAME"] ?></option>
                                    <?
                                endforeach;
                                ?>
                            </select>

                            <?
                            if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
                                ?>
                                <div class="bx_description">
                                    <?= $arProperties["DESCRIPTION"] ?>
                                </div>
                                <?
                            endif;
                            ?>
                        </div>
                        <div style="clear: both;"></div>
                        <?
                    }
                    elseif ($arProperties["TYPE"] == "TEXTAREA") {
                        ?>
                        <div class="form__el form__el_address">
                            <div class="labelWrap">
                                <label for="<?= $arProperties["FIELD_NAME"] ?>"><?= $arProperties["NAME"] ?> <span class="city"></span></label>
                            </div>
                            <div style="position:relative">
                                <div class="textareaWrap">
                                    <textarea name="<?= $arProperties["FIELD_NAME"] ?>" id="<?= $arProperties["FIELD_NAME"] ?>" id="address_new" cols="30" rows="10" class="form__textarea"><?= $arProperties["VALUE"] ?></textarea>
                                    <? if (strlen($arErrors[$arProperties["CODE"]]) > 0) { ?>
                                    <div class="form__error" title='<?= $arErrors[$arProperties["CODE"]]; ?>'><i class="fa fa-exclamation-triangle"></i> <span class="no-js-text"><?= $arErrors[$arProperties["CODE"]]; ?></span></div>
                                <? } ?>
                                </div>
                                
                            </div>
                        </div>
                        <?
                    } elseif ($arProperties["TYPE"] == "LOCATION") {
                        $value = 0;
                        if (is_array($arProperties["VARIANTS"]) && count($arProperties["VARIANTS"]) > 0) {
                            foreach ($arProperties["VARIANTS"] as $arVariant) {
                                if ($arVariant["SELECTED"] == "Y") {
                                    $value = $arVariant["ID"];
                                    break;
                                }
                            }
                        }
                        ?>
                        <div class="bx_block r1x3 pt8">
                            <?= $arProperties["NAME"] ?>
                            <? if ($arProperties["REQUIED_FORMATED"] == "Y"): ?>
                                <span class="bx_sof_req">*</span>
                            <? endif; ?>
                        </div>

                        <div class="bx_block r3x1">
                            <?
                            $GLOBALS["APPLICATION"]->IncludeComponent(
                                    "bitrix:sale.ajax.locations", $locationTemplate, array(
                                "AJAX_CALL" => "N",
                                "COUNTRY_INPUT_NAME" => "COUNTRY",
                                "REGION_INPUT_NAME" => "REGION",
                                "CITY_INPUT_NAME" => $arProperties["FIELD_NAME"],
                                "CITY_OUT_LOCATION" => "Y",
                                "LOCATION_VALUE" => $value,
                                "ORDER_PROPS_ID" => $arProperties["ID"],
                                "ONCITYCHANGE" => ($arProperties["IS_LOCATION"] == "Y" || $arProperties["IS_LOCATION4TAX"] == "Y") ? "submitForm()" : "",
                                "SIZE1" => $arProperties["SIZE1"],
                                    ), null, array('HIDE_ICONS' => 'Y')
                            );
                            ?>

                            <?
                            if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
                                ?>
                                <div class="bx_description">
                                    <?= $arProperties["DESCRIPTION"] ?>
                                </div>
                                <?
                            endif;
                            ?>
                        </div>
                        <div style="clear: both;"></div>
                        <?
                    }
                    elseif ($arProperties["TYPE"] == "RADIO") {
                        ?>
                        <div class="bx_block r1x3 pt8">
                            <?= $arProperties["NAME"] ?>
                            <? if ($arProperties["REQUIED_FORMATED"] == "Y"): ?>
                                <span class="bx_sof_req">*</span>
                            <? endif; ?>
                        </div>

                        <div class="bx_block r3x1">
                            <?
                            if (is_array($arProperties["VARIANTS"])) {
                                foreach ($arProperties["VARIANTS"] as $arVariants):
                                    ?>
                                    <input
                                        type="radio"
                                        name="<?= $arProperties["FIELD_NAME"] ?>"
                                        id="<?= $arProperties["FIELD_NAME"] ?>_<?= $arVariants["VALUE"] ?>"
                                        value="<?= $arVariants["VALUE"] ?>" <? if ($arVariants["CHECKED"] == "Y") echo " checked"; ?> />

                                    <label for="<?= $arProperties["FIELD_NAME"] ?>_<?= $arVariants["VALUE"] ?>"><?= $arVariants["NAME"] ?></label></br>
                                    <?
                                endforeach;
                            }
                            ?>

                            <?
                            if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
                                ?>
                                <div class="bx_description">
                                    <?= $arProperties["DESCRIPTION"] ?>
                                </div>
                                <?
                            endif;
                            ?>
                        </div>
                        <div style="clear: both;"></div>
                        <?
                    }
                    elseif ($arProperties["TYPE"] == "FILE") {
                        ?>
                        <br/>
                        <div class="bx_block r1x3 pt8">
                            <?= $arProperties["NAME"] ?>
                            <? if ($arProperties["REQUIED_FORMATED"] == "Y"): ?>
                                <span class="bx_sof_req">*</span>
                            <? endif; ?>
                        </div>

                        <div class="bx_block r3x1">
                            <?= showFilePropertyField("ORDER_PROP_" . $arProperties["ID"], $arProperties, $arProperties["VALUE"], $arProperties["SIZE1"]) ?>

                            <?
                            if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
                                ?>
                                <div class="bx_description">
                                    <?= $arProperties["DESCRIPTION"] ?>
                                </div>
                                <?
                            endif;
                            ?>
                        </div>

                        <div style="clear: both;"></div><br/>
                        <?
                    }
                }
                ?>
            </div>
            <?
        }
    }

}
?>