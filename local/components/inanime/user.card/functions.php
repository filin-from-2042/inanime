<?
function GetUserCardByName($name, $iblock, $active = true) {
	$arSelect = Array("ID", "IBLOCK_ID", "NAME","PROPERTY_*");
	$strName = str_replace(array(' ','-',':'),'',$name);
	if (!$active) {
		$arFilter = Array("IBLOCK_ID"=>$iblock, "NAME" => $strName);
	} else {
		$arFilter = Array("IBLOCK_ID"=>$iblock, "ACTIVE"=>"Y", "NAME" => $strName);
	}
	$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nTopCount"=>1), $arSelect);
	if($ob = $res->GetNextElement()){ 
		$arFields = $ob->GetFields();
		$arFields['PROPS'] = $ob->GetProperties();
		$print_array = str_split($arFields['NAME'], 4);
		$print_value = '';
		for ($i = 0; $i <= count($print_array); $i++) {
		    $print_value .= $print_array[$i].' ';
		}
		$arReturn = array(
			'ID' => $arFields['ID'],
			'NAME' => $arFields['NAME'],
			'PRINT_NAME' => $print_value,
			'GROUP' => $arFields['PROPS']['USER_GROUP']['VALUE'],
			'TYPE' => $arFields['PROPS']['TYPE']['VALUE'],
			'PASS' => $arFields['PROPS']['PASS']['VALUE']
		);
		return $arReturn;
	} else {
		return false;
	}
}
function SetUserData($id,$card_name,$group_current,$group_delete,$group_add) {
	$groups = array_diff($group_current, $group_delete);
	$groups[] = $group_add;
	$user = new CUser;
	$fields = Array(
		"UF_USER_CARD"  => $card_name,
		"GROUP_ID" => $groups
	);
	$test = $user->Update($id, $fields);
}