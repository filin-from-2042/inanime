<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Мои вопросы и ответы");
?>
    <div class="section-personal-header answers-questions">
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
        $APPLICATION->AddChainItem('Мои вопросы и ответы');
        ?>
        <h1 class="ia-page-title">Мои вопросы и ответы</h1>
    </div>
    <div class="section-personal answers-questions">
        <div class="container">
            <div class="row">
                <div class="col-xs-24 col-sm-8 col-md-6 col-lg-6 menu-column">
                    <?
                    $APPLICATION->IncludeFile("/include/personal_left_menu.php", Array(), Array(
                        "MODE" => "html", // будет редактировать в веб-редакторе
                        "NAME" => "Редактирование включаемой области раздела", // текст всплывающей подсказки на иконке
                    ));
                    ?>
                </div>

                <div class="col-xs-24 col-sm-16 col-md-18 col-lg-18 fields-column answers-questions-column">
                    <div id="questions">
                        <?
                        CModule::IncludeModule('iblock');
                        $userID = $USER->GetID();
                        $arFilter = Array("IBLOCK_ID"=>25, "ACTIVE"=>"Y", 'CREATED_USER_ID'=>$userID);
                        $res = CIBlockElement::GetList(Array('created'=>'DESC'), $arFilter,false,false,array());
                        while($ob = $res->GetNextElement())
                        {
                            $props = $ob->GetProperties();
                            ?>
                            <div class="question-answer-section-container">
                                <div class="question-container">
                                    <div class="question-title">
                                        <span class="question-text"><?=$props['question']['VALUE']['TEXT']?> ?</span>
                                    </div>
                                    <div class="product-link-container">
                                        <?
                                        $arFilterProd = Array("IBLOCK_ID"=>19, "ID"=>intval($props['question_product_id']['VALUE']));
                                        $resProd = CIBlockElement::GetList(Array('created'=>'DESC'), $arFilterProd,false,false,array());
                                        while($obProd = $resProd->GetNextElement())
                                        {
                                            $fieldsProd = $obProd->GetFields();
                                            echo '<span class="gray-text text">Вопрос к товару</span><a href="'.$fieldsProd["DETAIL_PAGE_URL"].'" class="product-link light-blue-text-underline">'.$fieldsProd['NAME'].'</a>';
                                        }
                                        ?>
                                    </div>
                                    <div class="question-answer gray-text">
                                        <?=($props['answer'] && $props['answer']['VALUE']) ? $props['answer']['VALUE']['TEXT'] : ''?></div>
                                </div>
                            </div>
                            <?
                        }
                        ?>
                        <script>
                            $(document).ready(function()
                            {
                                $('.question-answer-section-container .question-title').click(function()
                                {

                                    $(this).closest('.question-container').find('.question-answer').toggle();
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>