<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
?>
<div class="subscribe-form"  id="subscribe-form">
<?
$frame = $this->createFrame("subscribe-form", false)->begin();
?>
	<form action="<?=$arResult["FORM_ACTION"]?>" class="col-md-24 col-lg-24 clearfix" id="quisck-subscribe-form">

	<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
        <input type="hidden" name="sf_RUB_ID[]" id="sf_RUB_ID_<?=$RubValue["ID"]?>" checked value="<?=$RubValue["ID"]?>" />
	<?endforeach;?>
    <div class="col-md-12 col-lg-12 column-text">
        <div class="text">Подписаться на новости</div><span class="divider hidden-xs">&#124;</span>
        <div class="text hidden-xs">Узнай первым о последних новостях и акциях</div>
    </div>
    <div class="col-md-12 col-lg-12 column-text">
        <div class="input-container email">
            <input class="form-control" type="text" name="sf_EMAIL" size="20" placeholder="Электронная почта" value="<?=$arResult["EMAIL"]?>" title="<?=GetMessage("subscr_form_email_title")?>" />
            <i class="fa fa-envelope-o mail" aria-hidden="true"></i>
        </div>
        <div class="input-container">
            <button class="btn btn-default ia-btn text-btn yellow-btn" type="submit" name="OK" value="Отправить"><i class="fa fa-paper-plane-o" aria-hidden="true"></i>Отправить</button>
        </div>
    </div>
        <script>
            $(document).ready(function(){
                $('#quisck-subscribe-form').submit(function(event)
                {
                    var ajaxURL = '<?=$templateFolder.'/ajax.php';?>';
                    var modal = $('#quick-subscribe-modal');
                    event.preventDefault();

                    $.ajax({
                        type: "POST",
                        url: ajaxURL,
                        data: {
                            subscribeMail:$(this).find('input[name="sf_EMAIL"]').val(),
                            sessid: BX.bitrix_sessid()
                        },
                        success: function(data){
                            modal.find('.text-data').text(data);
                            modal.modal();
                        },
                        error: function( xhr, textStatus ) {
                            
                        }
                    });
                });
            });
        </script>
	</form>
    <div class="modal fade ia-modal" id="quick-subscribe-modal" tabindex="-1" role="dialog" aria-labelledby="modalQuickSubscribeAdded">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="clearfix ">
                <i class="fa fa-times" aria-hidden="true"></i>
              </span>
                    </button>
                    <div class="modal-title" style="color:rgb(99,186,196);text-align: center;font-size:15px"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;<span class="text-data"></span></div>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
<?
$frame->beginStub();
?>
	<form action="<?=$arResult["FORM_ACTION"]?>">

		<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
			<label for="sf_RUB_ID_<?=$itemValue["ID"]?>">
                <input type="hidden" name="sf_RUB_ID[]" id="sf_RUB_ID_<?=$RubValue["ID"]?>" checked value="<?=$RubValue["ID"]?>" />
			</label><br />
		<?endforeach;?>

		<table border="0" cellspacing="0" cellpadding="2" align="center">
			<tr>
				<td><input type="text" name="sf_EMAIL" size="20" value="" title="<?=GetMessage("subscr_form_email_title")?>" /></td>
			</tr>
			<tr>
				<td align="right"><input type="submit" name="OK" value="<?=GetMessage("subscr_form_button")?>" /></td>
			</tr>
		</table>
	</form>
<?
$frame->end();
?>
</div>
