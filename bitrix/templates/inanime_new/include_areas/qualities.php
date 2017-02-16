<div class="row articles-links hidden-xs hidden-sm">
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <a class="article-container grey-container discounts" href="/articles/56063/">
            <div class="table-wrap">
                <span class="text">Постоянным клиентам скидки до 40%</span>
            </div>
        </a>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <a class="article-container grey-container choise"  href="/articles/56064/">
            <div class="table-wrap">
                <span class="text">Огромный выбор</span>
            </div>
        </a>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <a class="article-container grey-container shipping" href="/articles/56065/">
            <div class="table-wrap">
                <span class="text">Бесплатная доставка от 3000 рублей</span>
            </div>
        </a>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <a class="article-container grey-container order" href="/articles/56066/">
            <div class="table-wrap">
                <span class="text">Товар под заказ</span>
            </div>
        </a>
    </div>
</div>
<div class="row articles-links hidden-md hidden-lg tablet">
        <a class="article-container grey-container discounts" href="/articles/56063/">
            <div class="table-wrap">
                <span class="text">Постоянным клиентам скидки до 40%</span>
            </div>
        </a>
        <a class="article-container grey-container choise"  href="/articles/56064/">
            <div class="table-wrap">
                <span class="text">Огромный выбор</span>
            </div>
        </a>
        <a class="article-container grey-container shipping" href="/articles/56065/">
            <div class="table-wrap">
                <span class="text">Бесплатная доставка от 3000 рублей</span>
            </div>
        </a>
        <a class="article-container grey-container order" href="/articles/56066/">
            <div class="table-wrap">
                <span class="text">Товар под заказ</span>
            </div>
        </a>
    <script>
        $(document).ready(function(){
            $('.row.articles-links.tablet .article-container').click(function(event)
            {
                event.preventDefault();
                var $this = $(this);
                if($this.hasClass('opened')) location.href = $this.attr('href');
                else
                {
                    $('.row.articles-links.tablet .article-container').removeClass('opened');
                    $this.addClass('opened');
                }
            });
        });
    </script>
</div>