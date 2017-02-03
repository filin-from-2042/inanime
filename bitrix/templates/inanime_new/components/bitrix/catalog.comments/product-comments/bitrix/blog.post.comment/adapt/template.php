<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @global CMain $APPLICATION */
CJSCore::Init(array("image"));?>
<script>
BX.ready( function(){
	if(BX.viewImageBind)
	{
		BX.viewImageBind(
			'blg-comment-<?=$arParams["ID"]?>',
			false,
			{tag:'IMG', attr: 'data-bx-image'}
		);
	}
    // при скрытии окна очистка полей
    $('#add-comment').on('hidden.bs.modal', function (e) {
        $('#form_c_del .advantage-textarea').text('');
        $('#form_c_del .advantage-textarea').val('');
        $('#form_c_del .minus-textarea').text('');
        $('#form_c_del .minus-textarea').val('');
        $('#form_c_del .comment-textarea').text('');
        $('#form_c_del .comment-textarea').val('');

        $('#form_c_del .plus-minus-row').show();
    });
});
</script>
<div class="blog-comments comment-container" id="comments">
<a name="comments"></a>
<?

$prevTab = 0;
function ShowComment($comment, $tabCount=0, $tabSize=2.5, $canModerate=false, $User=Array(), $use_captcha=false, $bCanUserComment=false, $errorComment=false, $arParams = array())
{
    $comment["urlToAuthor"] = "";
    $comment["urlToBlog"] = "";

    if($comment["SHOW_AS_HIDDEN"] == "Y" || $comment["PUBLISH_STATUS"] == BLOG_PUBLISH_STATUS_PUBLISH || $comment["SHOW_SCREENNED"] == "Y" || $comment["ID"] == "preview")
    {
        global $prevTab;
        $tabCount = IntVal($tabCount);
        if($tabCount <= 5)
            $paddingSize = 2.5 * $tabCount;
        elseif($tabCount > 5 && $tabCount <= 10)
            $paddingSize = 2.5 * 5 + ($tabCount - 5) * 1.5;
        elseif($tabCount > 10)
            $paddingSize = 2.5 * 5 + 1.5 * 5 + ($tabCount-10) * 1;

        if(($tabCount+1) <= 5)
            $paddingSizeNew = 2.5 * ($tabCount+1);
        elseif(($tabCount+1) > 5 && ($tabCount+1) <= 10)
            $paddingSizeNew = 2.5 * 5 + (($tabCount+1) - 5) * 1.5;
        elseif(($tabCount+1) > 10)
            $paddingSizeNew = 2.5 * 5 + 1.5 * 5 + (($tabCount+1)-10) * 1;
        $paddingSizeNew -= $paddingSize;

        if($prevTab > $tabCount)
            $prevTab = $tabCount;
        if($prevTab <= 5)
            $prevPaddingSize = 2.5 * $prevTab;
        elseif($prevTab > 5 && $prevTab <= 10)
            $prevPaddingSize = 2.5 * 5 + ($prevTab - 5) * 1.5;
        elseif($prevTab > 10)
            $prevPaddingSize = 2.5 * 5 + 1.5 * 5 + ($prevTab-10) * 1;

        $prevTab = $tabCount;
        ?>

        <a name="<?=$comment["ID"]?>"></a>
        <div class="blog-comment" style="margin-left:<?=$paddingSize?>em;">
        <div id="blg-comment-<?=$comment["ID"]?>" class="comment-main grey-container">
        <?
        if($comment["PUBLISH_STATUS"] == BLOG_PUBLISH_STATUS_PUBLISH || $comment["SHOW_SCREENNED"] == "Y" || $comment["ID"] == "preview")
        {
        $aditStyle = "";
        if($arParams["is_ajax_post"] == "Y" || $comment["NEW"] == "Y")
            $aditStyle .= " blog-comment-new";
        if($comment["AuthorIsAdmin"] == "Y")
            $aditStyle = " blog-comment-admin";
        if(IntVal($comment["AUTHOR_ID"]) > 0)
            $aditStyle .= " blog-comment-user-".IntVal($comment["AUTHOR_ID"]);
        if($comment["AuthorIsPostAuthor"] == "Y")
            $aditStyle .= " blog-comment-author";
        if($comment["PUBLISH_STATUS"] != BLOG_PUBLISH_STATUS_PUBLISH && $comment["ID"] != "preview")
            $aditStyle .= " blog-comment-hidden";
        if($comment["ID"] == "preview")
            $aditStyle .= " blog-comment-preview";
        ?>
        <div class="blog-comment-cont<?=$aditStyle?>">
        <div class="blog-comment-cont-white">
        <div class="blog-comment-info nickname-rate-container">
            <?if ($arParams["SHOW_RATING"] == "Y"):?>
                <div class="blog-post-rating rating_vote_graphic">
                    <?
                    $GLOBALS['APPLICATION']->IncludeComponent(
                        "bitrix:rating.vote", $arParams["RATING_TYPE"],
                        Array(
                            "ENTITY_TYPE_ID" => "BLOG_COMMENT",
                            "ENTITY_ID" => $comment["ID"],
                            "OWNER_ID" => $comment["arUser"]["ID"],
                            "USER_VOTE" => $arParams["RATING"][$comment["ID"]]["USER_VOTE"],
                            "USER_HAS_VOTED" => $arParams["RATING"][$comment["ID"]]["USER_HAS_VOTED"],
                            "TOTAL_VOTES" => $arParams["RATING"][$comment["ID"]]["TOTAL_VOTES"],
                            "TOTAL_POSITIVE_VOTES" => $arParams["RATING"][$comment["ID"]]["TOTAL_POSITIVE_VOTES"],
                            "TOTAL_NEGATIVE_VOTES" => $arParams["RATING"][$comment["ID"]]["TOTAL_NEGATIVE_VOTES"],
                            "TOTAL_VALUE" => $arParams["RATING"][$comment["ID"]]["TOTAL_VALUE"],
                            "PATH_TO_USER_PROFILE" => $arParams["~PATH_TO_USER"],
                            "AJAX_MODE" => "Y"
                        ),
                        $arParams["component"],
                        array("HIDE_ICONS" => "Y")
                    );?>
                </div>
            <?endif;?>
            <?
            if (COption::GetOptionString("blog", "allow_alias", "Y") == "Y" && (strlen($comment["urlToBlog"]) > 0 || strlen($comment["urlToAuthor"]) > 0) && array_key_exists("ALIAS", $comment["BlogUser"]) && strlen($comment["BlogUser"]["ALIAS"]) > 0)
                $arTmpUser = array(
                    "NAME" => "",
                    "LAST_NAME" => "",
                    "SECOND_NAME" => "",
                    "LOGIN" => "",
                    "NAME_LIST_FORMATTED" => $comment["BlogUser"]["~ALIAS"],
                );
            elseif (strlen($comment["urlToBlog"]) > 0 || strlen($comment["urlToAuthor"]) > 0)
                $arTmpUser = array(
                    "NAME" => $comment["arUser"]["~NAME"],
                    "LAST_NAME" => $comment["arUser"]["~LAST_NAME"],
                    "SECOND_NAME" => $comment["arUser"]["~SECOND_NAME"],
                    "LOGIN" => $comment["arUser"]["~LOGIN"],
                    "NAME_LIST_FORMATTED" => "",
                );

            $commentData = json_decode($comment["TextFormated"], true);
            // признак комментария к отзыву
            $parentMark = isset($comment["PARENT_ID"]) && $comment["PARENT_ID"];
            if($parentMark)
            {
                // данные создателя родительского комментария
                $parentCommentData = CBlogComment::GetByID($comment["PARENT_ID"]);
                $rsUser = CUser::GetByID($parentCommentData['AUTHOR_ID']);
                $parentUserData = $rsUser->Fetch();
                $parentUserName = $parentUserData['NAME'].' '.$parentUserData['LAST_NAME'];
            }

            if(strlen($comment["urlToBlog"])>0)
            {
                if($parentMark)
                {?>
                    <div class="answer-icon"></div>
                <?}
                ?>
                <div class="blog-author nicname">
                    <?

                    $GLOBALS["APPLICATION"]->IncludeComponent("bitrix:main.user.link",
                        '',
                        array(
                            "ID" => $comment["arUser"]["ID"],
                            "HTML_ID" => "blog_post_comment_".$comment["arUser"]["ID"],
                            "NAME" => $arTmpUser["NAME"],
                            "LAST_NAME" => $arTmpUser["LAST_NAME"],
                            "SECOND_NAME" => $arTmpUser["SECOND_NAME"],
                            "LOGIN" => $arTmpUser["LOGIN"],
                            "NAME_LIST_FORMATTED" => $arTmpUser["NAME_LIST_FORMATTED"],
                            "USE_THUMBNAIL_LIST" => "N",
                            "PROFILE_URL" => $comment["urlToAuthor"],
                            "PROFILE_URL_LIST" => $comment["urlToBlog"],
                            "PATH_TO_SONET_MESSAGES_CHAT" => $arParams["~PATH_TO_MESSAGES_CHAT"],
                            "PATH_TO_VIDEO_CALL" => $arParams["~PATH_TO_VIDEO_CALL"],
                            "DATE_TIME_FORMAT" => $arParams["DATE_TIME_FORMAT"],
                            "SHOW_YEAR" => $arParams["SHOW_YEAR"],
                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                            "NAME_TEMPLATE" => $arParams["NAME_TEMPLATE"],
                            "SHOW_LOGIN" => $arParams["SHOW_LOGIN"],
                            "PATH_TO_CONPANY_DEPARTMENT" => $arParams["~PATH_TO_CONPANY_DEPARTMENT"],
                            "PATH_TO_SONET_USER_PROFILE" => ($arParams["USE_SOCNET"] == "Y" ? $comment["urlToAuthor"] : $arParams["~PATH_TO_SONET_USER_PROFILE"]),
                            "INLINE" => "Y",
                            "SEO_USER" => $arParams["SEO_USER"],
                        ),
                        false,
                        array("HIDE_ICONS" => "Y")
                    );
                    ?>
                </div>
                <?
                if($parentMark)
                {
                    ?>
                    <div class="comment-parent-nicname"><?=$parentUserName?></div>
                <?}?>
            <?
            }
            elseif(strlen($comment["urlToAuthor"])>0)
            {
                if($parentMark)
                {?>
                    <div class="answer-icon"></div>
                <?}
                ?><div class="blog-author nicname">
                <?if($arParams["SEO_USER"] == "Y"):?>
                <noindex>
            <?endif;?>
                <?
                $GLOBALS["APPLICATION"]->IncludeComponent("bitrix:main.user.link",
                    '',
                    array(
                        "ID" => $comment["arUser"]["ID"],
                        "HTML_ID" => "blog_post_comment_".$comment["arUser"]["ID"],
                        "NAME" => $arTmpUser["NAME"],
                        "LAST_NAME" => $arTmpUser["LAST_NAME"],
                        "SECOND_NAME" => $arTmpUser["SECOND_NAME"],
                        "LOGIN" => $arTmpUser["LOGIN"],
                        "NAME_LIST_FORMATTED" => $arTmpUser["NAME_LIST_FORMATTED"],
                        "USE_THUMBNAIL_LIST" => "N",
                        "PROFILE_URL" => $comment["urlToAuthor"],
                        "PATH_TO_SONET_MESSAGES_CHAT" => $arParams["~PATH_TO_MESSAGES_CHAT"],
                        "PATH_TO_VIDEO_CALL" => $arParams["~PATH_TO_VIDEO_CALL"],
                        "DATE_TIME_FORMAT" => $arParams["DATE_TIME_FORMAT"],
                        "SHOW_YEAR" => $arParams["SHOW_YEAR"],
                        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                        "CACHE_TIME" => $arParams["CACHE_TIME"],
                        "NAME_TEMPLATE" => $arParams["NAME_TEMPLATE"],
                        "SHOW_LOGIN" => $arParams["SHOW_LOGIN"],
                        "PATH_TO_CONPANY_DEPARTMENT" => $arParams["~PATH_TO_CONPANY_DEPARTMENT"],
                        "PATH_TO_SONET_USER_PROFILE" => ($arParams["USE_SOCNET"] == "Y" ? $comment["urlToAuthor"] : $arParams["~PATH_TO_SONET_USER_PROFILE"]),
                        "INLINE" => "Y",
                        "SEO_USER" => $arParams["SEO_USER"],
                    ),
                    false,
                    array("HIDE_ICONS" => "Y")
                );
                ?>
                <?if($arParams["SEO_USER"] == "Y"):?>
                </noindex>
            <?endif;?>
                </div>

                <?
                if($parentMark)
                {
                    ?>
                    <div class="comment-parent-nicname"><?=$parentUserName?></div>
                <?}?>
            <?
            }
            else
            {
                if($parentMark)
                {?>
                    <div class="answer-icon"></div>
                <?}?>
                <div class="blog-author nicname"><?=$comment["AuthorName"]?></div>
                <?
                if($parentMark)
                {?>
                    <div class="comment-parent-nicname"><?=$parentUserName?></div>
                <?}?>
            <?
            }

            if(strlen($comment["urlToDelete"])>0 && strlen($comment["AuthorEmail"])>0)
            {
                ?>
                (<a href="mailto:<?=$comment["AuthorEmail"]?>"><?=$comment["AuthorEmail"]?></a>)
            <?
            }

            ?>
        </div>
        <div class="blog-clear-float"></div>
        <div class="blog-comment-content">
            <?if(strlen($comment["TitleFormated"])>0)
            {
                ?>
                <b><?=$comment["TitleFormated"]?></b><br />
            <?
            }
            ?>
            <?
            if($commentData)
            {
                ?>
                <div class="part-title">Плюсы:</div>
                <div class="part-text gray-text"><?=$commentData['plus']?></div>
                <div class="part-title">Минусы:</div>
                <div class="part-text gray-text"><?=$commentData['minus']?></div>
                <div class="part-title">Комментарий:</div>
                <div class="part-text gray-text"><?=$commentData['comment']?></div>
            <?
            } else echo '<span class="gray-text">'.$comment["TextFormated"].'</span>';

            if(!empty($arParams["arImages"][$comment["ID"]]))
            {
                ?>
                <div class="feed-com-files">
                    <div class="feed-com-files-title"><?=GetMessage("BLOG_PHOTO")?></div>
                    <div class="feed-com-files-cont">
                        <?
                        foreach($arParams["arImages"][$comment["ID"]] as $val)
                        {
                            ?><span class="feed-com-files-photo"><img src="<?=$val["small"]?>" alt="" border="0" data-bx-image="<?=$val["full"]?>"></span><?
                        }
                        ?>
                    </div>
                </div>
            <?
            }

            if($comment["COMMENT_PROPERTIES"]["SHOW"] == "Y")
            {
                $eventHandlerID = AddEventHandler('main', 'system.field.view.file', Array('CBlogTools', 'blogUFfileShow'));
                ?><div><?
                foreach ($comment["COMMENT_PROPERTIES"]["DATA"] as $FIELD_NAME => $arPostField)
                {
                    if(!empty($arPostField["VALUE"]))
                    {
                        $GLOBALS["APPLICATION"]->IncludeComponent(
                            "bitrix:system.field.view",
                            $arPostField["USER_TYPE"]["USER_TYPE_ID"],
                            array("arUserField" => $arPostField), null, array("HIDE_ICONS"=>"Y"));
                    }
                }
                ?></div><?
                if ($eventHandlerID !== false && ( intval($eventHandlerID) > 0 ))
                    RemoveEventHandler('main', 'system.field.view.file', $eventHandlerID);
            }
            ?>
            <div class="blog-comment-meta date-answer-container">
                <?
                if($bCanUserComment===true)
                {
                    ?>
                    <div class="blog-comment-date text gray-text"><?=$comment["DateFormated"]?></div>
                    <span class="blog-vert-separator divider">|</span>
                    <span class="blog-comment-answer"><a class="answer-button" href="javascript:void(0)" onclick="return showComment('<?=$comment["ID"]?>')"><?=GetMessage("B_B_MS_REPLY")?></a></span>

                <?
                }

                if($comment["CAN_EDIT"] == "Y")
                {
                    ?>
                    <script>
                        top.text<?=$comment["ID"]?> = text<?=$comment["ID"]?> = '<?=CUtil::JSEscape($comment["~POST_TEXT"])?>';
                        top.title<?=$comment["ID"]?> = title<?=$comment["ID"]?> = '<?=CUtil::JSEscape($comment["TITLE"])?>';
                    </script>
                    <span class="blog-vert-separator divider">|</span>
                    <span class="blog-comment-edit"><a href="javascript:void(0)" onclick="return editComment('<?=$comment["ID"]?>')"><?=GetMessage("BPC_MES_EDIT")?></a></span>
                <?
                }
                if(strlen($comment["urlToShow"])>0)
                {
                    ?>
                    <span class="blog-vert-separator divider">|</span>
                    <span class="blog-comment-show">
								<?if($arParams["AJAX_POST"] == "Y"):?>
                        <a href="javascript:void(0)" onclick="return hideShowComment('<?=$comment["urlToShow"]."&".bitrix_sessid_get()?>', '<?=$comment["ID"]?>');" title="<?=GetMessage("BPC_MES_SHOW")?>">
                            <?else:?>
                            <a href="<?=$comment["urlToShow"]."&".bitrix_sessid_get()?>" title="<?=GetMessage("BPC_MES_SHOW")?>">
                                <?endif;?>
                                <?=GetMessage("BPC_MES_SHOW")?></a></span>
                <?
                }
                if(strlen($comment["urlToHide"])>0)
                {
                    ?>
                    <span class="blog-vert-separator divider">|</span>
                    <span class="blog-comment-show">
								<?if($arParams["AJAX_POST"] == "Y"):?>
                        <a href="javascript:void(0)" onclick="return hideShowComment('<?=$comment["urlToHide"]."&".bitrix_sessid_get()?>&IBLOCK_ID=<?=$_REQUEST["IBLOCK_ID"]?>&ELEMENT_ID=<?=$_REQUEST["ELEMENT_ID"]?>', '<?=$comment["ID"]?>');" title="<?=GetMessage("BPC_MES_HIDE")?>">
                            <?else:?>
                            <a href="<?=$comment["urlToHide"]."&".bitrix_sessid_get()?>&IBLOCK_ID=<?=$_REQUEST["IBLOCK_ID"]?>&ELEMENT_ID=<?=$_REQUEST["ELEMENT_ID"]?>" title="<?=GetMessage("BPC_MES_HIDE")?>">
                                <?endif;?>
                                <?=GetMessage("BPC_MES_HIDE")?></a></span>
                <?
                }
                if(strlen($comment["urlToDelete"])>0)
                {
                    ?>
                    <span class="blog-vert-separator divider">|</span>
                    <span class="blog-comment-delete">
								<?if($arParams["AJAX_POST"] == "Y"):?>
                        <a href="javascript:void(0)" onclick="if(confirm('<?=GetMessage("BPC_MES_DELETE_POST_CONFIRM")?>')) deleteComment('<?=$comment["urlToDelete"]."&".bitrix_sessid_get()?>&IBLOCK_ID=<?=$_REQUEST["IBLOCK_ID"]?>&ELEMENT_ID=<?=$_REQUEST["ELEMENT_ID"]?>', '<?=$comment["ID"]?>');" title="<?=GetMessage("BPC_MES_DELETE")?>">
                            <?else:?>
                            <a href="javascript:if(confirm('<?=GetMessage("BPC_MES_DELETE_POST_CONFIRM")?>')) window.location='<?=$comment["urlToDelete"]."&".bitrix_sessid_get()?>&IBLOCK_ID=<?=$_REQUEST["IBLOCK_ID"]?>&ELEMENT_ID=<?=$_REQUEST["ELEMENT_ID"]?>'" title="<?=GetMessage("BPC_MES_DELETE")?>">
                                <?endif;?>
                                <?=GetMessage("BPC_MES_DELETE")?></a></span>
                <?
                }
                if(strlen($comment["urlToSpam"])>0)
                {
                    ?>
                    <span class="blog-vert-separator divider">|</span>
                    <span class="blog-comment-delete blog-comment-spam"><a href="<?=$comment["urlToSpam"]?>" title="<?=GetMessage("BPC_MES_SPAM_TITLE")?>"><?=GetMessage("BPC_MES_SPAM")?></a></span>
                <?
                }
                if ($arParams["SHOW_RATING"] == "Y")
                {
                    ?>
                    <span class="rating_vote_text">
                        <span class="blog-vert-separator divider">|</span>
                        <?$GLOBALS["APPLICATION"]->IncludeComponent(
                            "bitrix:rating.vote", $arParams["RATING_TYPE"],
                            Array(
                                "ENTITY_TYPE_ID" => "BLOG_COMMENT",
                                "ENTITY_ID" => $comment["ID"],
                                "OWNER_ID" => $comment["arUser"]["ID"],
                                "USER_VOTE" => $arParams["RATING"][$comment["ID"]]["USER_VOTE"],
                                "USER_HAS_VOTED" => $arParams["RATING"][$comment["ID"]]["USER_HAS_VOTED"],
                                "TOTAL_VOTES" => $arParams["RATING"][$comment["ID"]]["TOTAL_VOTES"],
                                "TOTAL_POSITIVE_VOTES" => $arParams["RATING"][$comment["ID"]]["TOTAL_POSITIVE_VOTES"],
                                "TOTAL_NEGATIVE_VOTES" => $arParams["RATING"][$comment["ID"]]["TOTAL_NEGATIVE_VOTES"],
                                "TOTAL_VALUE" => $arParams["RATING"][$comment["ID"]]["TOTAL_VALUE"],
                                "PATH_TO_USER_PROFILE" => $arParams["~PATH_TO_USER"],
                            ),
                            $arParams["component"],
                            array("HIDE_ICONS" => "Y")
                        );?>
							</span>
                <?
                }
                ?>
            </div>

        </div>
        </div>
        </div>
        <div class="blog-clear-float"></div>

        <?
        if(strlen($errorComment) <= 0 && (strlen($_POST["preview"]) > 0 && $_POST["show_preview"] != "N") && (IntVal($_POST["parentId"]) > 0 || IntVal($_POST["edit_id"]) > 0)
            && ( (IntVal($_POST["parentId"])==$comment["ID"] && IntVal($_POST["edit_id"]) <= 0)
                || (IntVal($_POST["edit_id"]) > 0 && IntVal($_POST["edit_id"]) == $comment["ID"] && $comment["CAN_EDIT"] == "Y")))
        {
            $level = 0;
            $commentPreview = Array(
                "ID" => "preview",
                "TitleFormated" => htmlspecialcharsEx($_POST["subject"]),
                "TextFormated" => $_POST["commentFormated"],
                "AuthorName" => $User["NAME"],
                "DATE_CREATE" => GetMessage("B_B_MS_PREVIEW_TITLE"),
            );
            ShowComment($commentPreview, (IntVal($_POST["edit_id"]) == $comment["ID"] && $comment["CAN_EDIT"] == "Y") ? $level : ($level+1), 2.5, false, Array(), false, false, false, $arParams);
        }

        if(strlen($errorComment)>0 && $bCanUserComment===true
            && (IntVal($_POST["parentId"])==$comment["ID"] || IntVal($_POST["edit_id"]) == $comment["ID"]))
        {
            ?>
            <div class="blog-errors blog-note-box blog-note-error">
                <div class="blog-error-text">
                    <?=$errorComment?>
                </div>
            </div>
        <?
        }
        ?>
        </div>
        <div id="err_comment_<?=$comment['ID']?>"></div>
        <div id="form_comment_<?=$comment['ID']?>"></div>
        <div id="new_comment_cont_<?=$comment['ID']?>" style="margin-left:<?=$paddingSizeNew?>em;"></div>
        <div id="new_comment_<?=$comment['ID']?>" style="display:none;"></div>

        <?
        if((strlen($errorComment) > 0 || strlen($_POST["preview"]) > 0)
            && (IntVal($_POST["parentId"])==$comment["ID"] || IntVal($_POST["edit_id"]) == $comment["ID"])
            && $bCanUserComment===true)
        {
            ?>
            <script>
                top.text<?=$comment["ID"]?> = text<?=$comment["ID"]?> = '<?=CUtil::JSEscape($_POST["comment"])?>';
                top.title<?=$comment["ID"]?> = title<?=$comment["ID"]?> = '<?=CUtil::JSEscape($_POST["subject"])?>';
                <?
                if(IntVal($_POST["edit_id"]) == $comment["ID"])
                {
                    ?>editComment('<?=$comment["ID"]?>');<?
						}
						else
						{
							?>showComment('<?=$comment["ID"]?>', 'Y', '<?=CUtil::JSEscape($_POST["user_name"])?>', '<?=CUtil::JSEscape($_POST["user_email"])?>', 'Y');<?
						}
						?>
            </script>
        <?
        }
        }
        elseif($comment["SHOW_AS_HIDDEN"] == "Y")
            echo "<b>".GetMessage("BPC_HIDDEN_COMMENT")."</b>";
        ?>
        </div>
    <?
    }
}

function RecursiveComments($sArray, $key, $level=0, $first=false, $canModerate=false, $User, $use_captcha, $bCanUserComment, $errorComment, $arSumComments, $arParams)

{
    if(!empty($sArray[$key]))
    {
        foreach($sArray[$key] as $comment)
        {
            if(!empty($arSumComments[$comment["ID"]]))
            {
                $comment["CAN_EDIT"] = $arSumComments[$comment["ID"]]["CAN_EDIT"];
                $comment["SHOW_AS_HIDDEN"] = $arSumComments[$comment["ID"]]["SHOW_AS_HIDDEN"];
                $comment["SHOW_SCREENNED"] = $arSumComments[$comment["ID"]]["SHOW_SCREENNED"];
                $comment["NEW"] = $arSumComments[$comment["ID"]]["NEW"];
            }
            ShowComment($comment, $level, 2.5, $canModerate, $User, $use_captcha, $bCanUserComment, $errorComment, $arParams);
            if(!empty($sArray[$comment["ID"]]))
            {
                foreach($sArray[$comment["ID"]] as $key1)
                {
                    if(!empty($arSumComments[$key1["ID"]]))
                    {
                        $key1["CAN_EDIT"] = $arSumComments[$key1["ID"]]["CAN_EDIT"];
                        $key1["SHOW_AS_HIDDEN"] = $arSumComments[$key1["ID"]]["SHOW_AS_HIDDEN"];
                        $key1["SHOW_SCREENNED"] = $arSumComments[$key1["ID"]]["SHOW_SCREENNED"];
                        $key1["NEW"] = $arSumComments[$key1["ID"]]["NEW"];
                    }
                    ShowComment($key1, ($level+1), 2.5, $canModerate, $User, $use_captcha, $bCanUserComment, $errorComment, $arParams);

                    if(!empty($sArray[$key1["ID"]]))
                    {
                        RecursiveComments($sArray, $key1["ID"], ($level+2), false, $canModerate, $User, $use_captcha, $bCanUserComment, $errorComment, $arSumComments, $arParams);
                    }
                }
            }
            if($first)
                $level=0;
        }
    }
}


if($arResult["is_ajax_post"] != "Y")
	include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/script.php");
else
{
	$APPLICATION->RestartBuffer();
	?><script>window.BX = top.BX;
		<?/*if($arResult["use_captcha"]===true)
		{
			?>
				var cc;
				if(document.cookie.indexOf('<?echo session_name()?>'+'=') == -1)
					cc = Math.random();
				else
					cc ='<?=$arResult["CaptchaCode"]?>';

				BX('captcha').src='/bitrix/tools/captcha.php?captcha_code='+cc;
				BX('captcha_code').value = cc;
				BX('captcha_word').value = "";
			<?
		}*/
	?>
	if(!top.arImages)
		top.arImages = [];
	if(!top.arImagesId)
		top.arImagesId = [];
	<?
	foreach($arResult["Images"] as $aImg)
	{
		?>
		top.arImages.push('<?=CUtil::JSEscape($aImg["SRC"])?>');
		top.arImagesId.push('<?=$aImg["ID"]?>');
		<?
	}
	?>
	</script><?
	if(strlen($arResult["COMMENT_ERROR"])>0)
	{
		?>
		<script>top.commentEr = 'Y';</script>
		<div class="blog-errors blog-note-box blog-note-error">
			<div class="blog-error-text">
				<?=$arResult["COMMENT_ERROR"]?>
			</div>
		</div>
		<?
	}
}

if(strlen($arResult["MESSAGE"])>0)
{
	?>
	<div class="blog-textinfo blog-note-box">
		<div class="blog-textinfo-text">
			<?=$arResult["MESSAGE"]?>
		</div>
	</div>
	<?
}
if(strlen($arResult["ERROR_MESSAGE"])>0)
{
	?>
	<div class="blog-errors blog-note-box blog-note-error">
		<div class="blog-error-text" id="blg-com-err">
			<?=$arResult["ERROR_MESSAGE"]?>
		</div>
	</div>
	<?
}
if(strlen($arResult["FATAL_MESSAGE"])>0)
{
	?>
	<div class="blog-errors blog-note-box blog-note-error">
		<div class="blog-error-text">
			<?=$arResult["FATAL_MESSAGE"]?>
		</div>
	</div>
	<?
}
else
{
	if($arResult["imageUploadFrame"] == "Y")
	{
		?>
		<script>
			<?if(!empty($arResult["Image"])):?>
				top.bxBlogImageId = top.arImagesId.push('<?=$arResult["Image"]["ID"]?>');
				top.arImages.push('<?=CUtil::JSEscape($arResult["Image"]["SRC"])?>');
				top.bxBlogImageIdWidth = '<?=CUtil::JSEscape($arResult["Image"]["WIDTH"])?>';
			<?elseif(strlen($arResult["ERROR_MESSAGE"]) > 0):?>
				top.bxBlogImageError = '<?=CUtil::JSEscape($arResult["ERROR_MESSAGE"])?>';
			<?endif;?>
		</script>
		<?
		die();
	}
	else
	{

        $arParams["RATING"] = $arResult["RATING"];
        $arParams["component"] = $component;
        $arParams["arImages"] = $arResult["arImages"];
        if($arResult["is_ajax_post"] == "Y")
            $arParams["is_ajax_post"] = "Y";

        if($arResult["is_ajax_post"] != "Y" && $arResult["NEED_NAV"] == "Y")
        {
            for($i = 1; $i <= $arResult["PAGE_COUNT"]; $i++)
            {
                $tmp = $arResult["CommentsResult"];
                $tmp[0] = $arResult["PagesComment"][$i];
                ?>
                <div id="blog-comment-page-<?=$i?>"<?if($arResult["PAGE"] != $i) echo "style=\"display:none;\""?>><?RecursiveComments($tmp, $arResult["firstLevel"], 0, true, $arResult["canModerate"], $arResult["User"], $arResult["use_captcha"], $arResult["CanUserComment"], $arResult["COMMENT_ERROR"], $arResult["Comments"], $arParams);?></div>
                <?
            }
        }else{
            RecursiveComments($arResult["CommentsResult"], $arResult["firstLevel"], 0, true, $arResult["canModerate"], $arResult["User"], $arResult["use_captcha"], $arResult["CanUserComment"], $arResult["COMMENT_ERROR"], $arResult["Comments"], $arParams);
        }
        if($arResult["is_ajax_post"] != "Y")
        {
            if($arResult["NEED_NAV"] == "Y")
            {
            ?>
                <div class="blog-comment-nav">
                    <?=GetMessage("BPC_PAGE")?>&nbsp;<?
                    for($i = 1; $i <= $arResult["PAGE_COUNT"]; $i++)
                    {
                        $style = "blog-comment-nav-item";
                        if($i == $arResult["PAGE"])
                            $style .= " blog-comment-nav-item-sel";
                        ?><a class="<?=$style?>" href="<?=$arResult["NEW_PAGES"][$i]?>" onclick="return bcNav('<?=$i?>', this)" id="blog-comment-nav-b<?=$i?>"><?=$i?></a>&nbsp;&nbsp;<?
                    }
                    ?>
                </div>
            <?
            }
        }
		if($arResult["is_ajax_post"] != "Y" && $arResult["CanUserComment"])
		{
			/*$ajaxPath = POST_FORM_ACTION_URI;
			$parent = $component->GetParent();
			if (isset($parent) && is_object($parent))
			{
				$ajaxPath = $parent->GetTemplate()->GetFolder().'/ajax.php';
			}*/
			$ajaxPath = $templateFolder.'/ajax.php';
			?>
            <div id="form_comment_" style="display:none;">
                <div id="form_c_del" style="display:none;">

                        <div class="modal fade ia-modal" id="add-comment" tabindex="-1" role="dialog" aria-labelledby="addComment">

                            <div class="blog-comment-form">
                                <form method="POST" name="form_comment" id="form_comment" action="<?=$ajaxPath; ?>">
                                    <input type="hidden" name="parentId" id="parentId" value="">
                                    <input type="hidden" name="edit_id" id="edit_id" value="">
                                    <input type="hidden" name="act" id="act" value="add">
                                    <input type="hidden" name="post" value="Y">
                                    <?
                                    if(isset($_REQUEST["IBLOCK_ID"]))
                                    {
                                        ?><input type="hidden" name="IBLOCK_ID" value="<?=(int)$_REQUEST["IBLOCK_ID"]; ?>"><?
                                    }
                                    if(isset($_REQUEST["ELEMENT_ID"]))
                                    {
                                        ?><input type="hidden" name="ELEMENT_ID" value="<?=(int)$_REQUEST["ELEMENT_ID"]; ?>"><?
                                    }
                                    if(isset($_REQUEST["SITE_ID"]))
                                    {
                                        ?><input type="hidden" name="SITE_ID" value="<?=htmlspecialcharsbx($_REQUEST["SITE_ID"]); ?>"><?
                                    }

                                    echo makeInputsFromParams($arParams["PARENT_PARAMS"]);
                                    echo bitrix_sessid_post();?>

                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true" class="clearfix ">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                          </span>
                                                </button>

                                                <h4 class="modal-title">Оставить отзыв</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form>
                                                    <div class="row plus-minus-row">
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-ls-12 advantages-column">
                                                            <div class="text-title"><i class="fa fa-plus" aria-hidden="true"></i>Плюсы</div>
                                                            <div class="icon-input-container">
                                                                <div class="icon-input-wrap">
                                                                    <textarea name="advantage-text" class="form-control advantage-textarea"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-ls-12 minus-column">
                                                            <div class="text-title"><i class="fa fa-minus" aria-hidden="true"></i>Минусы</div>
                                                            <div class="icon-input-container">
                                                                <div class="icon-input-wrap">
                                                                    <textarea name="minus-text" class="form-control minus-textarea"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row comment-row">
                                                        <div class="col-xs-24 col-sm-24 col-md-24 col-lg-24">
                                                            <div class="text-title"><i class="fa fa-commenting" aria-hidden="true"></i>Комментарий</div>
                                                            <div class="icon-input-container">
                                                                <div class="icon-input-wrap">
                                                                    <textarea name="minus-text" class="form-control comment-textarea"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="status-container">
                                                        <div class="success">Ответ на Ваш вопрос можно будет прочитать в личном кабинете</div>
                                                    </div>
                                                    <div class="button-container">

                                                        <input class="btn btn-default ia-btn text-btn blue-btn" tabindex="10" value="<?=GetMessage("B_B_MS_SEND")?>" type="button" name="sub-post" id="post-button" onclick="submitComment()">

                                                    </div>
                                                    <input type="hidden" name="comment" value="">
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="blog_upload_cid" id="upload-cid" value="">
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

			<?
			/*if($arResult["use_captcha"]===true)
			{
				?>
				<div id="captcha_del">
					<img src="/bitrix/tools/captcha.php?captcha_code=<?=$arResult["CaptchaCode"]?>" width="180" height="40" id="captcha" style="display:none;">
					<script>
						document.getElementById('captcha_code').value = '<?=$arResult["CaptchaCode"]?>';
					</script>
				</div>
				<?
			}*/
		}
		?>
		<?
		if($arResult["is_ajax_post"] != "Y")
		{
			if($arResult["CanUserComment"])
			{
				$postTitle = "";
				if($arParams["NOT_USE_COMMENT_TITLE"] != "Y")
					$postTitle = "RE: ".CUtil::JSEscape($arResult["Post"]["TITLE"]);
				?>

                <div id="form_comment_0">
                    <div id="err_comment_0"></div>
                    <div id="form_comment_0"></div>
                    <div id="new_comment_cont_0"></div>
                    <div id="new_comment_0" style="display:none;"></div>
                </div>
                <?
                if((strlen($arResult["COMMENT_ERROR"])>0 || strlen($_POST["preview"]) > 0)
                && IntVal($_POST["parentId"]) == 0 && strlen($_POST["parentId"]) < 2 && IntVal($_POST["edit_id"]) <= 0)
                {
                ?>
                    <script>
                        top.text0 = text0 = '<?=CUtil::JSEscape($_POST["comment"])?>';
                        top.title0 = title0 = '<?=CUtil::JSEscape($_POST["subject"])?>';
                        showComment('0', 'Y', '<?=CUtil::JSEscape($_POST["user_name"])?>', '<?=CUtil::JSEscape($_POST["user_email"])?>', 'Y');
                    </script>
                    <?
                }?>

                <div class="blog-add-comment button-container">
                    <button class="btn btn-default ia-btn text-btn blue-btn" type="submit" name="OK" value="Оставить отзыв" onclick="return showComment('0')">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                        <span><?=GetMessage("B_B_MS_ADD_COMMENT")?></span>
                    </button>
                </div>
				<a name="0"></a>
				<?
				if(strlen($arResult["COMMENT_ERROR"]) > 0 && strlen($_POST["parentId"]) < 2
					&& IntVal($_POST["parentId"])==0 && IntVal($_POST["edit_id"]) <= 0)
				{
					?>
					<div class="blog-errors blog-note-box blog-note-error">
						<div class="blog-error-text"><?=$arResult["COMMENT_ERROR"]?></div>
					</div>
					<?
				}
			}

			if($arResult["NEED_NAV"] == "Y")
			{
				?>
				<div class="blog-comment-nav">
					<?=GetMessage("BPC_PAGE")?>&nbsp;<?
					for($i = 1; $i <= $arResult["PAGE_COUNT"]; $i++)
					{
						$style = "blog-comment-nav-item";
						if($i == $arResult["PAGE"])
							$style .= " blog-comment-nav-item-sel";
						?><a class="<?=$style?>" href="<?=$arResult["NEW_PAGES"][$i]?>" onclick="return bcNav('<?=$i?>', this)" id="blog-comment-nav-t<?=$i?>"><?=$i?></a>&nbsp;&nbsp;<?
					}
				?>
				</div>
				<?
			}
		}
	}
}
?>
</div>
<?
if($arResult["is_ajax_post"] == "Y")
	die();

function makeInputsFromParams($arParams, $name="PARAMS")
{
	$result = "";

	if(is_array($arParams))
	{
		foreach ($arParams as $key => $value)
		{
			if(substr($key, 0, 1) != "~")
			{
				$inputName = $name.'['.$key.']';

				if(is_array($value))
					$result .= makeInputsFromParams($value, $inputName);
				else
					$result .= '<input type="hidden" name="'.$inputName.'" value="'.$value.'">'.PHP_EOL;
			}
		}
	}

	return $result;
}
?>