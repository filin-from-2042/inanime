<div class="menu-container">
    <div class="fox-icon"></div>
    <?
    $matches = array();
    preg_match('/^([a-z0-9-\/]+)\/+\??.*$/', $_SERVER['REQUEST_URI'], $matches);
    ?>
    <ul>
        <li<?=($matches[1]==='/personal')?' class="active"':''?>>
            <a href="/personal">Личный кабинет</a>
        </li>
        <li<?=($matches[1]==='/personal/profile')?' class="active"':''?>>
            <a href="/personal/profile">Персональные данные</a>
        </li>
        <li<?=($matches[1]==='/personal/profile/password')?' class="active"':''?>>
            <a href="/personal/profile/password">Настройки профиля</a>
        </li>
        <li<?=($matches[1]==='/personal/order')?' class="active"':''?>><a href="/personal/order">Просмотр моих заказов</a></li>
        <li<?=($matches[1]=='/personal/discount')?' class="active"':''?>><a href="/personal/discount">Активация дисконтной карты</a></li>
        <li<?=($matches[1]==='/personal/questions')?' class="active"':''?>><a href="/personal/questions">Мои вопросы и ответы</a></li>
    </ul>
</div>