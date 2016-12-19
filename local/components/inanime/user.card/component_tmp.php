<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)die();

/* --------------------- ИНИЦИАЛИЗАЦИЯ --------------------- */
// подключаем служебные модули функции и прочее
CModule::IncludeModule('iblock');
include('functions.php');
// получаем пользователя
// у пользователя должно быть свойство "UF_USER_CARD" содержащее номер карты (элемент инфоблока)
global $USER;
// инфоблок, в котором лежат карты
// у элементов должны быть свойства "USER_GROUP" содержащее номер группы, в которую нужно добавить пользователя и "TYPE" - список (Золотая/Серебрянная)
$user_cards_iblock = 11;
// группы к которым идет привязка с помощью дисконтных карт
// второй массив - размер скидки предоставляемые группой
// все группы и скидки необходимо создать заранее
$discount_groups = array(22,23,24,25,26,27,28,29,30,31);
$discount_groups_count = array(22=>5,23=>7,24=>10,25=>12,26=>15,27=>20,28=>25,29=>30,30=>35,31=>40);

/* -------------------------- ОБРАБОТКА ФОРМ --------------------- */ 
if (!empty($_REQUEST['ADD_CARD'])) {
	$arResult['MESSAGE'] = $arResult['ERROR'] = false;
	if (empty($_REQUEST['NEW_CARD_NAME']) || empty($_REQUEST['NEW_CARD_PASS'])) {
		if (empty($_REQUEST['NEW_CARD_NAME'])) {
			$arResult['ERROR'] = 'Не указан номер карты.';
		} else {
			$arResult['ERROR'] = 'Не указан пароль карты.';
		}
	} else {
		$card = GetUserCardByName($_REQUEST['NEW_CARD_NAME'],$user_cards_iblock);
		if ($card == false) {
			$arResult['ERROR'] = 'Указанная карта не существует, либо уже используется.';
		} else {
			if ($card['PASS'] !== $_REQUEST['NEW_CARD_PASS']) {
				$arResult['ERROR'] = 'Неверно указан пароль карты';
			} else {
				SetUserData($USER->GetID(),$_REQUEST['NEW_CARD_NAME'],$USER->GetUserGroupArray(),$discount_groups,$card['GROUP']);
				$el = new CIBlockElement;
				$res = $el->Update($card['ID'], Array("ACTIVE" => "N"));
				$arResult['MESSAGE'] = 'Карта применена. Выйдите из учетной записи и авторизуйтесь снова для применения скидки.';
			}
		}
	}
} elseif(!empty($_REQUEST['CHANGE_CARD'])) {
	$arResult['MESSAGE'] = $arResult['ERROR'] = false;
	if (empty($_REQUEST['NEW_CARD_NAME']) || empty($_REQUEST['NEW_CARD_PASS'])) {
		if (empty($_REQUEST['NEW_CARD_NAME'])) {
			$arResult['ERROR'] = 'Не указан номер карты.';
		} else {
			$arResult['ERROR'] = 'Не указан пароль карты.';
		}
	} else {
		$card = GetUserCardByName($_REQUEST['NEW_CARD_NAME'],$user_cards_iblock);
		if ($card == false) {
			$arResult['ERROR'] = 'Указанная карта не существует, либо уже используется.';
		} else {
			if ($card['ID'] == $_REQUEST['CURRENT_CARD_ID']) {
				$arResult['ERROR'] = 'Новая карта совпадает с текущей';
			} else {
				if ($card['PASS'] !== $_REQUEST['NEW_CARD_PASS']) {
					$arResult['ERROR'] = 'Неверно указан пароль карты';
				} else {
					SetUserData($USER->GetID(),$_REQUEST['NEW_CARD_NAME'],$USER->GetUserGroupArray(),$discount_groups,$card['GROUP']);
					$elOld = new CIBlockElement;
					$elOld->Update($_REQUEST['CURRENT_CARD_ID'], Array("ACTIVE" => "Y"));
					$elNew = new CIBlockElement;
					$elNew->Update($card['ID'], Array("ACTIVE" => "N"));
					$arResult['MESSAGE'] = 'Новая карта успешно применена. Выйдите из учетной записи и авторизуйтесь снова для применения скидки.';
				}
			}
		}
	}
}

/* -------------------------- ПОЛУЧАЕМ ДАННЫЕ --------------------- */ 
// проверка на существование карты и её валидность
$arResult['HAVE_CARD'] = 'N';
// получаем данные пользователя
$arRes = CUser::GetList($by,$desc,array("ID" => $USER->GetID()),array("FIELDS"=>array("ID","NAME"),"SELECT"=>array("UF_USER_CARD")));
$arResult['USER'] = $arRes->fetch();
$arResult['USER_GROUPS'] = $USER->GetUserGroupArray();

if (!empty($arResult['USER']['UF_USER_CARD']) && strlen($arResult['USER']['UF_USER_CARD']) > 0) {
	$arResult['HAVE_CARD'] = 'Y';
	$arResult['CARD'] = GetUserCardByName($arResult['USER']['UF_USER_CARD'],$user_cards_iblock, false);
}

/* ------------------------------ ВЫВОД -------------------------- */
?>
<div class="card-block r">
	<form action="<?=$APPLICATION->GetCurPage()?>" method="POST" class="form_userinfo">
		<?if ($arResult['HAVE_CARD'] == 'Y'):?>
			<div class="dcard<?if($arResult['CARD']['TYPE'] == 'Золотая'):?> golden<?endif?>">
				<div class="title">Дисконтная карта <?=$discount_groups_count[$arResult['CARD']['GROUP']]?>%</div>
				<div class="n"><?=$arResult['CARD']['PRINT_NAME']?></div>
			</div>
			<?if ($arResult['MESSAGE'] || $arResult['ERROR']):?>
				<?if (!empty($arResult['ERROR'])):?>
					<div class="errortext"><?=$arResult['ERROR']?></div>
				<?elseif (!empty($arResult['MESSAGE'])):?>
					<div class="notetext"><?=$arResult['MESSAGE']?></div>
				<?endif?>
				<br>
			<?endif?>
			<input type="hidden" name="CURRENT_CARD_ID" value="<?=$arResult['CARD']['ID']?>">
			<input type="hidden" name="CURRENT_CARD_NAME" value="<?=$arResult['CARD']['NAME']?>">
			<input type="text" name="NEW_CARD_NAME" id="card_name" class="form__text"> <br>
			<button class="button_submit-change-userinfo" value="Сохранить" name="CHANGE_CARD" type="submit">
				<i class="fa fa-refresh"></i>
				<span>Изменить карту</span>
			</button>
		<?else:?>
			<div class="dcard">
				<div class="title">Дисконтная карта</div>
			</div>
			<?if ($arResult['MESSAGE'] || $arResult['ERROR']):?>
				<?if (!empty($arResult['ERROR'])):?>
					<div class="errortext"><?=$arResult['ERROR']?></div>
				<?elseif (!empty($arResult['MESSAGE'])):?>
					<div class="notetext"><?=$arResult['MESSAGE']?></div>
				<?endif?>
				<br>
			<?endif?>
			<small>Номер карты</small><br>
			<input type="text" name="NEW_CARD_NAME" id="card_name" class="form__text"> <br>
			<small>Пароль</small><br>
			<input type="text" name="NEW_CARD_PASS" id="card_name" class="form__text"> <br>
			<button class="button_submit-change-userinfo" value="Сохранить" name="ADD_CARD" type="submit">
				<i class="fa fa-refresh"></i>
				<span>Указать карту</span>
			</button>
		<?endif;?>
	</form>
</div>
<?
/* -------------------------- ОТЛАДКА ---------------------------- */
// вывод отладочной информации для администратора сайта
//if ($USER->IsAdmin()) {echo '<pre>'; print_r($arResult); echo '</pre>'; }
//if ($USER->IsAdmin()) {echo '<pre>'; print_r($_REQUEST); echo '</pre>'; }