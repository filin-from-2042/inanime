<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оплата");
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
        $APPLICATION->AddChainItem('Оплата');
        ?>
        <h1 class="ia-page-title">Оплата</h1>
    </div>
<h2 style="margin: 14px 0px 10px; padding: 0px; border: 0px; outline: 0px; font-size: 18px; vertical-align: middle; background: rgb(255, 255, 255); font-family: sans-serif, Arial, Verdana, &quot;Trebuchet MS&quot;; color: rgb(51, 51, 51); text-transform: uppercase; line-height: 1.2em;">
  <br />
</h2>

<p></p>
 
<div><span style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent; color: rgb(37, 37, 37);">
    <table border="0" cellpadding="0" cellspacing="10" width="100%" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 12px; vertical-align: middle; background: rgb(255, 255, 255); border-spacing: 0px; color: rgb(114, 114, 114); font-family: Arial, sans-serif;">
      <tbody style="margin: 0px; padding: 0px; border: 0px; outline: 0px; background: transparent;">
        <tr style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: top; background: transparent;"><td style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; width: 480px;">
            <div style="margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: medium; vertical-align: middle; background: transparent; font-weight: bold;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 16px; vertical-align: middle; background: transparent;color:#333" >Платежные системы</span></div>
          
            <div style="margin: 0px; padding: 0px 0px 0px 5px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;">Прием платежей в пользу клиентов ROBOKASSA<span style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;">®</span>;
                <br />
              Пополнение кошельков из других платежных систем;</span>
              <div style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;">
                <ul style="margin: 5px 0px 10px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; list-style: none;">
                  <li style="margin: 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">QIWI</b> – <a href="http://www.qiwi.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.qiwi.ru</a></span></li>
                
                  <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Webmoney</b> - <a href="http://webmoney.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">webmoney.ru</a></span></li>
                
                  <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Яндекс.Деньги</b> - <a href="http://money.yandex.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">money.yandex.ru</a></span></li>
                
                  <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Rapida Online</b> - <a href="http://www.rapidaonline.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.rapidaonline.ru</a></span></li>
                
                  <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">MoneyMail</b> - <a href="https://www.moneymail.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.moneymail.ru</a></span></li>
                
                  <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Деньги@mail.ru</b> - <a href="https://money.mail.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">money.mail.ru</a></span></li>
                
                  <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Единый кошелек</b> - <a href="http://w1.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">w1.ru</a></span></li>
                
                  <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">EasyPay</b> - <a href="http://easypay.by/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">easypay.by</a></span></li>
                
                  <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">LiqPay</b> - <a href="http://www.liqpay.com/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.liqpay.com</a></span></li>
                
                  <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Handy Bank</b> - <a href="http://www.handybank.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.handybank.ru</a></span></li>
                
                  <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Кошелек Элекснет</b> - <a href="http://1.elecsnet.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">1.elecsnet.ru</a></span></li>
                </ul>
              </div>
            </div>
          </td><td style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; width: 480px;">
            <div style="margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: medium; vertical-align: middle; background: transparent; font-weight: bold;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 16px; vertical-align: middle; background: transparent;color:#333;">Банки</span></div>
          
            <ul style="margin: 5px 0px 10px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; list-style: none;">
              <li style="margin: 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">ОКЕАН БАНК</b> – <a href="http://www.oceanbank.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.oceanbank.ru</a>
                  <br />
                Стратегический партнер, расчетный банк сервиса ROBOKASSA<span style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;">®</span>. </span>
                <br />
               </li>
            
              <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Альфа-Банк</b> – <a href="http://alfabank.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">alfabank.ru</a>
                  <br />
                Оплата товаров наших партнеров через систему
                  <br />
                &quot;Альфа-Клик&quot;.</span></li>
            
              <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Промсвязьбанк</b> – <a href="http://www.psbank.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.psbank.ru</a>
                  <br />
                Оплата товаров наших партнеров через
                  <br />
                Интернет-банк &quot;PSB-Retail&quot;.</span></li>
            
              <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Банк ВТБ24</b> – <a href="http://www.vtb24.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.vtb24.ru</a>
                  <br />
                Прием платежей через банкоматы и систему &quot;Телебанк&quot;.</span></li>
            
              <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Банк Петрокоммерц</b> – <a href="http://www.pkb.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.pkb.ru</a>
                  <br />
                Прием платежей в банкоматах Банка.</span></li>
            
              <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Межтопэнергобанк</b> – <a href="http://www.mteb.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.mteb.ru</a>
                  <br />
                Оплата товаров наших партнеров через систему
                  <br />
                &quot;mteb@online&quot;.</span></li>
            
              <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Татфондбанк</b> – <a href="http://www.tfb.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.tfb.ru</a>
                  <br />
                Оплата товаров наших партнеров через систему
                  <br />
                &quot;Онлайн Партнер&quot;.</span></li>
            
              <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Банк АВБ</b> – <a href="http://www.avtovazbank.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.avtovazbank.ru</a>
                  <br />
                Оплата товаров наших партнеров.</span></li>
            
              <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Банк Интеза</b> – <a href="http://www.bancaintesa.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.bancaintesa.ru</a>
                  <br />
                Оплата товаров наших партнеров через
                  <br />
                электронный банк my.bancaintesa.</span></li>
            
              <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">АКБ «ГОРОД»</b> – <a href="http://www.bankgorod.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.bankgorod.ru</a>
                  <br />
                Оплата товаров наших партнеров через систему
                  <br />
                «GBANK RETAIL».</span></li>
            
              <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Банк Русский Стандарт</b> – <a href="http://www.rsb.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.rsb.ru</a>
                  <br />
                Оплата товаров наших партнеров через
                  <br />
                Интернет-банк.</span></li>
            </ul>
          </td></tr>
      
        <tr style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: top; background: transparent;"><td style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;">
            <div style="margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: medium; vertical-align: middle; background: transparent; font-weight: bold;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 16px; vertical-align: middle; background: transparent;color:#333;">Сети терминалов моментальной оплаты</span></div>
          
            <div style="margin: 0px; padding: 0px 0px 0px 5px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;">Прием платежей в пользу клиентов ROBOKASSA<span style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;">®</span>.</span></div>
          
            <ul style="margin: 5px 0px 10px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; list-style: none;">
              <li style="margin: 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">QIWI</b> – <a href="http://www.qiwi.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.qiwi.ru</a></span></li>
            
              <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Элекснет</b> - <a href="http://www.elecsnet.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.elecsnet.ru</a></span></li>
            
              <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Кассира.нет</b> - <a href="http://www.pscb.ru/terminals.aspx?pageuid=terminals" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.pscb.ru/terminals.aspx?pageuid=terminals</a></span></li>
            
              <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Мобил Элемент</b> - <a href="http://www.mobilelement.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.mobilelement.ru</a></span></li>
            
              <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Абсолют Плат</b> - <a href="http://www.absolutplat.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.absolutplat.ru</a></span></li>
            
              <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Pinpay</b> - <a href="http://www.pinpay.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.pinpay.ru</a></span></li>
            </ul>
          </td><td style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;">
            <div style="margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: medium; vertical-align: middle; background: transparent; font-weight: bold;"> </div>
          
            <ul style="margin: 5px 0px 10px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; list-style: none;"></ul>
          </td></tr>
      
        <tr style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: top; background: transparent;"><td style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;">
            <div style="margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: medium; vertical-align: middle; background: transparent; font-weight: bold;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 16px; vertical-align: middle; background: transparent;color:#333;">А также</span></div>
          
            <ul style="margin: 5px 0px 10px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; list-style: none;">
              <li style="margin: 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Связной</b> – <a href="http://www.svyaznoy.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.svyaznoy.ru</a>
                  <br />
                Прием платежей в пользу клиентов ROBOKASSA<span style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;">®</span>.</span></li>
            
              <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Евросеть</b> – <a href="http://euroset.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">euroset.ru</a>
                  <br />
                Прием платежей в пользу клиентов ROBOKASSA<span style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;">®</span>.</span></li>
            
              <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; white-space: nowrap;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Телекоммуникационная компания Караван</b> - <a href="http://www.caravan.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.caravan.ru</a>
                  <br />
                Размещение серверов и защита от DDOS.</span></li>
            
              <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Киберплат</b> - <a href="http://www.cyberplat.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.cyberplat.ru</a>
                  <br />
                Предоставление шлюзов для оплаты товаров и услуг.</span></li>
            
              <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">Моё дело</b> – <a href="http://www.moedelo.org/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.moedelo.org</a>
                  <br />
                Интернет-бухгалтерия: справочная, учётная системы, отправка отчётности через интернет и консультации экспертов.</span></li>
            
              <li style="margin: 5px 0px 0px; padding: 0px 0px 0px 21px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><span id="ctl00_ctl00_CPHMainContent_CPHMainContent_lbContent" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"><b style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(37, 37, 37);">ComfortWay</b> – <a href="http://www.comfortway.ru/" target="_blank" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent; color: rgb(13, 63, 150); text-decoration: none;">www.comfortway.ru</a>
                  <br />
                Интернет, звонки и SMS в роуминге, прием платежей.</span></li>
            </ul>
          </td><td style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"> </td></tr>
      </tbody>
    </table>
  </span></div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>