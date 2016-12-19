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
    <form action="<?=$APPLICATION->GetCurPage()?>" method="POST" class="form_userinfo">
    <?if ($arResult['HAVE_CARD'] == 'Y'):?>
        <div class="row">
            <div class="col-sm-24 col-md-13 col-lg-13 cards-inputs-column">
                <div class="input-container">
                    <input type="text" name="number" value="" placeholder="Номер карты" class="form-control number-input">
                </div>
                <div class="input-container">
                    <input type="text" name="password" value="" placeholder="Введите пароль" class="form-control password-input">
                </div>
                <div class="activate-button-container">
                    <button type="submit" class="btn btn-default ia-btn text-btn yellow-btn">Изменить</button>
                </div>
            </div>
            <div class="col-sm-24 col-md-11 col-lg-11 card-column">
                <div class="card-container">
                    <img width="355" height="215" src="/images/inanime-discount-card.png"/>
                </div>
            </div>
        </div>
    <?else:?>
        <div class="row">
            <div class="col-sm-24 col-md-13 col-lg-13 cards-inputs-column">
                <div class="input-container">
                    <input type="text" name="number" value="" placeholder="Номер карты" class="form-control number-input">
                </div>
                <div class="input-container">
                    <input type="text" name="password" value="" placeholder="Введите пароль" class="form-control password-input">
                </div>
                <div class="activate-button-container">
                    <button type="submit" class="btn btn-default ia-btn text-btn yellow-btn">Активировать</button>
                </div>
            </div>
            <div class="col-sm-24 col-md-11 col-lg-11 card-column">
                <div class="card-container">
                    <img width="355" height="215" src="/images/inanime-discount-card.png"/>
                </div>
            </div>
        </div>
    <?endif;?>
    </form>


    <style type="text/css">
        /***************************** PROFILE DISCOUNT CARD **************************/
        .section-personal.discount .fields-column {
            padding-top:0;
        }
        .section-personal.discount .cards-inputs-column {
            padding-top:13px;
        }
        .section-personal.discount .cards-inputs-column {
            padding-left:5px;
        }
        .section-personal.discount .cards-inputs-column .form-control{
            width:435px;
        }
        .section-personal.discount .cards-inputs-column .activate-button-container button {
            padding: 12px 22px;
            font-size: 14px;
        }
        .section-personal.discount .cards-inputs-column .activate-button-container {
            margin-top: 30px;
        }
        .section-personal.discount .card-column {
            padding-left:0;
        }
        .section-personal.discount .card-column .card-container    {
            border-radius: 10px;
        }
        /* Mobile */
        @media (max-width: 760px) {
            /****************************** PERSONAL - ACTIVATE DISCOUNT ******************/
            .section-personal.discount .cards-inputs-column .form-control {
                width: 290px;
            }
            .section-personal.discount .cards-inputs-column {
                padding-left: 14px;
            }
            .section-personal.discount .cards-inputs-column .activate-button-container {
                text-align: center;
            }
            .section-personal.discount .card-column .card-container {
                margin-top: 33px;
                text-align:center;
            }
            .section-personal.discount .card-column .card-container img {
                width: 290px;
                height: 177px;
            }
        }
        /* Tablet */
        @media (min-width: 760px) and (max-width:992px) {
            /******************************** PERSONAL - DISCOUNT ACTIVATE ****************/
            .section-personal.discount .cards-inputs-column {
                padding-left: 13px;
            }
            .section-personal.discount .cards-inputs-column {
                padding-top: 0px;
            }
            .section-personal.discount .card-column .card-container {
                margin-top: 33px;
                margin-left: 13px;
            }
        }

        @media (min-width: 992px) and (max-width: 1230px){
            /***************************** PERSONAL - ACTIVATE DISCOUNT ****************/
            .section-personal.discount .cards-inputs-column .form-control {
                width: 355px;
            }
        }
    </style>
<?
/* -------------------------- ОТЛАДКА ---------------------------- */
// вывод отладочной информации для администратора сайта
//if ($USER->IsAdmin()) {echo '<pre>'; print_r($arResult); echo '</pre>'; }
//if ($USER->IsAdmin()) {echo '<pre>'; print_r($_REQUEST); echo '</pre>'; }