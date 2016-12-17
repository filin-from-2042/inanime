<div class="menu-container">
    <div class="fox-icon"></div>
    <ul>
        <li<?=(strpos($_SERVER['REQUEST_URI'],'personal/profile')>0 && strpos($_SERVER['REQUEST_URI'],'personal/profile/password')==0)?' class="active"':''?>>
            <a href="/personal/profile">Персональные данные</a>
        </li>
        <li<?=(strpos($_SERVER['REQUEST_URI'],'personal/profile/password')>0)?' class="active"':''?>>
            <a href="/personal/profile/password">Настройки профиля</a>
        </li>
        <li<?=(strpos($_SERVER['REQUEST_URI'],'personal/order')>0)?' class="active"':''?>><a href="/personal/order">Просмотр моих заказов</a></li>
        <li<?=(strpos($_SERVER['REQUEST_URI'],'personal/discount')>0)?' class="active"':''?>><a href="/personal/discount">Активация дисконтной карты</a></li>
        <li<?=(strpos($_SERVER['REQUEST_URI'],'personal/questions')>0)?' class="active"':''?>><a href="/personal/questions">Мои вопросы и ответы</a></li>
    </ul>
</div>