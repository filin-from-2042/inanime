<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Доставка");
?>
<div class="container">
    <div class="section-header">
        <?$APPLICATION->IncludeComponent(
            "bitrix:breadcrumb",
            "catalog-chain",
            Array(
                "START_FROM" => "0",
                "PATH" => "",
                "SITE_ID" => SITE_ID
            )
        );
        ?>
        <?
        $APPLICATION->AddChainItem('Доставка');
        ?>
        <h1 class="ia-page-title">Доставка</h1>
    </div>
    <h2 style="margin: 14px 0px 10px; padding: 0px; border: 0px; outline: 0px; font-size: 18px; vertical-align: middle; background: rgb(255, 255, 255); font-family: sans-serif, Arial, Verdana, &quot;Trebuchet MS&quot;; color: rgb(51, 51, 51); text-transform: uppercase; line-height: 1.2em;"><span style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37); font-weight: normal;">ДОСТАВКА ПОЧТОЙ РОССИИ </span></h2>

    <p style="margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 13px; vertical-align: middle; background: rgb(255, 255, 255); color: rgb(51, 51, 51); font-family: sans-serif, Arial, Verdana, &quot;Trebuchet MS&quot;; line-height: 20.8px;">Для этого Вам необходимо выбрать вид оплаты:</p>
    <span style="color: rgb(51, 51, 51); font-family: sans-serif, Arial, Verdana, &quot;Trebuchet MS&quot;; font-size: 13px;">
      <ul>
        <li>посылка с объявленной ценностью для жителей регионов России;</li>
      </ul>
    </span>
    <p style="margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 13px; vertical-align: middle; background: rgb(255, 255, 255); color: rgb(51, 51, 51); font-family: sans-serif, Arial, Verdana, &quot;Trebuchet MS&quot;; line-height: 20.8px;">Далее требуется указать ФИО (не забывайте отчество), точный адрес (не забывайте индекс) и контактные данные (желательно указывать более одного телефона). Мы связываемся с Вами по телефону или по электронной почте, сообщаем, что посылка отправлена, и Вы ждете извещения на свой физический почтовый ящик. Как правило, доставка почтой занимает до 4-х недель и более, в зависимости от региона страны.  </p>

    <p style="margin: 5px 0px 0px; padding: 0px; border: 0px; outline: 0px; font-size: 13px; vertical-align: middle; background: rgb(255, 255, 255); color: rgb(51, 51, 51); font-family: sans-serif, Arial, Verdana, &quot;Trebuchet MS&quot;; line-height: 20.8px;"><i>Также необходимо напомнить, что отправленные нами грузы страхуются.</i></p>

    <p style="margin: 5px 0px 0px; padding: 0px; border: 0px; outline: 0px; font-size: 13px; vertical-align: middle; background: rgb(255, 255, 255); color: rgb(51, 51, 51); font-family: sans-serif, Arial, Verdana, &quot;Trebuchet MS&quot;; line-height: 20.8px;">Стоимость доставки рассчитывается автоматически при оформлении заказа. Свой выбор Вам необходимо подтвердить нажатием кнопки &quot;Продолжить&quot; в нижнем правом углу.</p>

    <p style="margin: 5px 0px 0px; padding: 0px; border: 0px; outline: 0px; font-size: 13px; vertical-align: middle; background: rgb(255, 255, 255); color: rgb(51, 51, 51); font-family: sans-serif, Arial, Verdana, &quot;Trebuchet MS&quot;; line-height: 20.8px;">Если Вы живете в труднодоступном районе (где Почта России предлагает только авиадоставку), то тариф будет рассчитан нашими сотрудниками вручную после оформления заказа.</p>

    <p style="margin: 5px 0px 0px; padding: 0px; border: 0px; outline: 0px; font-size: 13px; vertical-align: middle; background: rgb(255, 255, 255); color: rgb(51, 51, 51); font-family: sans-serif, Arial, Verdana, &quot;Trebuchet MS&quot;; line-height: 20.8px;"><strong style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Внимание!</strong> Стоимость доставки может отличаться от <strong style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">тарифов сайта Почты России</strong>!</p>

    <h2 style="margin: 14px 0px 10px; padding: 0px; border: 0px; outline: 0px; font-size: 18px; vertical-align: middle; background: rgb(255, 255, 255); font-family: sans-serif, Arial, Verdana, &quot;Trebuchet MS&quot;; color: rgb(51, 51, 51); text-transform: uppercase; line-height: 1.2em; font-weight: normal;">СЛУЖБА ДОСТАВКИ &quot;СДЭК&quot;</h2>

    <p style="margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 13px; vertical-align: middle; background: rgb(255, 255, 255); color: rgb(51, 51, 51); font-family: sans-serif, Arial, Verdana, &quot;Trebuchet MS&quot;; line-height: 20.8px;">Компания СДЭК имеет собственную развитую сеть пунктов выдачи заказов...  
      <br />

      <br />
    Стоимость доставки рассчитывается сайтом при оформлении заказа.  
      <br />
    Более подробную информацию можно получить у менеджера, который свяжется с Вами для согласования доставки данным способом.  
      <br />

      <br />
    <strong style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent; color: rgb(37, 37, 37);">Стоимость доставки СДЭК</strong> складывается из следующих факторов:   
      <br />
    </p>

    <ul>
      <li>стоимость доставки согласно тарифной сетке, </li>

      <li>размер страховки (все посылки обязательно страхуются),  </li>

      <li>комиссия за перевод денежных средств, которая закладывается в случае, если посылка отправляется с наложенным платежом. </li>
    </ul>
    Мы <strong style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; color: rgb(37, 37, 37);">предлагаем</strong> клиентам <strong style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; color: rgb(37, 37, 37);">систему скидок на доставку</strong> заказов весом не более 3 кг <strong style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; color: rgb(37, 37, 37);">в пункты самовывоза</strong>: <span style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; color: rgb(37, 37, 37);"> 
      <br />
    Сроки доставки СДЭК можно посмотреть.</span><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
</div>