function inanime_new() {
    this.init_custom_vertical_carousel = function (element_id, elements_count) {
        var carouselData =
        {
            cc_count: elements_count, // ���������� �����������
            cc_position: 0, // ������� ����� �����
            carouselEl: document.getElementById(element_id),
            cc_height: document.getElementById(element_id).querySelector('img').height,
            paddingBottom: parseInt($(document.getElementById(element_id).querySelector('li')).css('paddingBottom'))
        }
        inanime_new[element_id] = carouselData;
        carouselData.carouselEl.querySelector('.prev').onclick = function () {
            var prevPosition = inanime_new[element_id].cc_position;
            var height = inanime_new[element_id].cc_height;
            var paddingBottom = inanime_new[element_id].paddingBottom;
            var count = inanime_new[element_id].cc_count;

            inanime_new[element_id].cc_position = Math.min(prevPosition + (height + paddingBottom) * count, 0);
            $('#' + element_id + ' ul').animate(
                {
                    marginTop: inanime_new[element_id].cc_position + 'px'
                }
            );
        };

        carouselData.carouselEl.querySelector('.next').onclick = function () {
            var listElems = inanime_new[element_id].carouselEl.querySelectorAll('li');
            var prevPosition = inanime_new[element_id].cc_position;
            var height = inanime_new[element_id].cc_height;
            var paddingBottom = inanime_new[element_id].paddingBottom;
            var count = inanime_new[element_id].cc_count;

            inanime_new[element_id].cc_position = Math.max(prevPosition - (height + paddingBottom) * count, -(height + paddingBottom) * (listElems.length - count));
            $('#' + element_id + ' ul').animate(
                {
                    marginTop: inanime_new[element_id].cc_position + 'px'
                }
            );
        };
    };
    this.init_custom_horizontal_carousel = function (element_id, elements_count) {
        var carouselData =
        {
            cc_count: elements_count,
            cc_position: 0,
            carouselEl: document.getElementById(element_id),
            cc_width: $(document.getElementById(element_id).querySelector('li')).width(),
            paddingRight: parseInt($(document.getElementById(element_id).querySelector('li')).css('paddingRight'))
        };

        inanime_new[element_id] = carouselData;
        $('#'+element_id).css('width',(carouselData.cc_width+carouselData.paddingRight)*elements_count );
        carouselData.carouselEl.querySelector('.prev').onclick = function () {
            var prevPosition = inanime_new[element_id].cc_position;
            var width = inanime_new[element_id].cc_width;
            var paddingRight = inanime_new[element_id].paddingRight;
            var count = inanime_new[element_id].cc_count;

            inanime_new[element_id].cc_position = Math.min(prevPosition + (width + paddingRight) * count, 0);
            $('#' + element_id + ' ul').animate(
                {
                    marginLeft: inanime_new[element_id].cc_position + 'px'
                }
            );
        };

        carouselData.carouselEl.querySelector('.next').onclick = function () {
            var listElems = inanime_new[element_id].carouselEl.querySelectorAll('li');
            var prevPosition = inanime_new[element_id].cc_position;
            var width = inanime_new[element_id].cc_width;
            var paddingRight = inanime_new[element_id].paddingRight;
            var count = inanime_new[element_id].cc_count;

            inanime_new[element_id].cc_position = Math.max(prevPosition - (width + paddingRight) * count, -(width + paddingRight) * (listElems.length - count));
            $('#' + element_id + ' ul').animate(
                {
                    marginLeft: inanime_new[element_id].cc_position + 'px'
                }
            );
        };
    };
    // инициализация карусели дял карточки товара
    this.init_product_horizontal_carousel = function(element_id, elements_count)
    {
        var carouselData =
        {
            cc_count: elements_count,
            cc_position: 0,
            carouselEl: document.getElementById(element_id),
            cc_width: $(document.getElementById(element_id).querySelector('li')).width(),
            paddingRight: parseInt($(document.getElementById(element_id).querySelector('li')).css('paddingRight'))
        };

        inanime_new[element_id] = carouselData;
        $('#'+element_id).css('width',(carouselData.cc_width+carouselData.paddingRight)*elements_count );
        $('#'+element_id).closest('.photo-carousel-container').find('.prev').click(function () {
            var prevPosition = inanime_new[element_id].cc_position;
            var width = inanime_new[element_id].cc_width;
            var paddingRight = inanime_new[element_id].paddingRight;
            var count = inanime_new[element_id].cc_count;

            inanime_new[element_id].cc_position = Math.min(prevPosition + (width + paddingRight) * count, 0);
            $('#' + element_id + ' ul').animate(
                {
                    marginLeft: inanime_new[element_id].cc_position + 'px'
                }
            );
        });

        $('#'+element_id).closest('.photo-carousel-container').find('.next').click(function () {
            var listElems = inanime_new[element_id].carouselEl.querySelectorAll('li');
            var prevPosition = inanime_new[element_id].cc_position;
            var width = inanime_new[element_id].cc_width;
            var paddingRight = inanime_new[element_id].paddingRight;
            var count = inanime_new[element_id].cc_count;

            inanime_new[element_id].cc_position = Math.max(prevPosition - (width + paddingRight) * count, -(width + paddingRight) * (listElems.length - count));
            $('#' + element_id + ' ul').animate(
                {
                    marginLeft: inanime_new[element_id].cc_position + 'px'
                }
            );
        });

    };

    /* Переменная-флаг для отслеживания того, происходит ли в данный момент ajax-запрос. В самом начале даем ей значение false, т.е. запрос не в процессе выполнения */
    this.inProgress = false;
    this.getSectionPage = function( filterData, sortData, pageNumber, withReplace = false)
    {
        var postData = {
                        "PAGEN_1" : pageNumber,
                        "section_id": String(window.currCatalogSectionID),
                        "sort_data": JSON.stringify(sortData),
                        "filter_data": JSON.stringify(filterData),
                        "page_element_count": String(window.currCatalogPageElementCount),
                        "price_code" : '["BASE"]'
                    };
        $.ajax({
            url: '/ajax/catalog_pager.php',
            method: 'POST',
            data: postData,
            beforeSend: function() {
                window.inanime_new.inProgress = true;
                $(".items-section").append('<div id="overlay-load"></div>');
            }
        }).done(function(data){

            $('.items-section #overlay-load').remove();
            window.scrollLoadMaxPages = $(data).find('#maxPages').text();
            var parsedData = $(data).find('.product-item-preview');
            // манипуляции с классом new для добавления lazyload новым элементам
            parsedData.find('img.lazy').addClass('new');
            if(withReplace) $(".items-section .items-container .product-item-preview").remove();
            $(".items-section .items-container").append(parsedData);

            $(".items-section .items-container .product-item-preview img.lazy.new").lazyload({effect : "fadeIn"});
            $(".items-section .items-container .product-item-preview img.lazy.new").removeClass('new');
            window.inanime_new.inProgress = false;
        });
    };
    this.ddSetSelectedText = function (element)
    {
        var inputElment = $(element).closest(".dropdown").find(".btn.dropdown-toggle input[type='hidden']");
        inputElment.val($(element).find(".sort-value.hidden").text());

        $(element).closest(".dropdown").find(".btn.dropdown-toggle")
                                        .text('').append('<span class="glyphicon glyphicon-chevron-down"></span><span class="text">'+element.innerHTML+'</span>')
                                                .append(inputElment);
        this.changeViewHandler();
    };

    this.gatherInputsValues = function (values, elements)
    {
        if(elements)
        {
            for(var i = 0; i < elements.length; i++)
            {
                var el = elements[i];
                if (el.disabled || !el.type)
                    continue;

                switch(el.type.toLowerCase())
                {
                    case 'text':
                    case 'textarea':
                    case 'password':
                    case 'hidden':
                    case 'select-one':
                        if(el.value.length)
                            values[values.length] = {name : el.name, value : el.value};
                        break;
                    case 'radio':
                    case 'checkbox':
                        if(el.checked)
                            values[values.length] = {name : el.name, value : el.value};
                        break;
                    case 'select-multiple':
                        for (var j = 0; j < el.options.length; j++)
                        {
                            if (el.options[j].selected)
                                values[values.length] = {name : el.name, value : el.options[j].value};
                        }
                        break;
                    default:
                        break;
                }
            }
        }
    };

    this.changeViewHandler = function(scrollChange = false)
    {
        if(!!this.timer)
        {
            clearTimeout(this.timer);
        }

        this.timer = setTimeout(function(){
            var filterData = [];
            var form = document.getElementById('inanime-filter-form');
            window.inanime_new.gatherInputsValues(filterData, BX.findChildren(form, {'tag': new RegExp('^(input|select)$', 'i')}, true));

            filterData[filterData.length] = {name:"discount", value: $(".btn.btn-primary.type-btn.discount").attr("aria-pressed") || "false"};
            filterData[filterData.length] = {name:"week-goods", value: $(".btn.btn-primary.type-btn.week-goods").attr("aria-pressed") || "false"};
            filterData[filterData.length] = {name:"topsale", value: $(".btn.btn-primary.type-btn.topsale").attr("aria-pressed") || "false"};

            var sortVal =  $('.sort-container .select-container .btn .sort-value.hidden').text();
            var splittedVal = sortVal.split(';');
            var sortData = {sortField:splittedVal[0], sortType:splittedVal[1]};
            if(scrollChange)
            {
                window.inanime_new.getSectionPage(filterData, sortData, window.scrollLoadStartFrom, false );
                window.scrollLoadStartFrom ++;
            }
            else{
                window.inanime_new.getSectionPage(filterData, sortData, 1, true );
                window.scrollLoadStartFrom =2;
            }
        },  500);
    };

    this.addToCart=function(productID, quantity, productName, price, delay=false )
    {
        $.ajax({
            type: "POST",
            url: "/ajax/basket.php",
            data: {
                priceCode : '["BASE"]',
                productID : productID,
                quantity: quantity,
                price : price,
                name : productName,
                delay:(delay) ? 'Y' : 'N'
            },
            //dataType: 'json',
            success: function(data){
                $('header .content-container.cart-container').contents().remove();
                $('header .content-container.cart-container').append($(data).find('.content-container.cart-container').contents());
            },
            error: function( xhr, textStatus ) {
                alert( [ xhr.status, textStatus ] );
            }
        });
    };

    // обработчик нажания на кнопку в поле кол-во
    this.counterButtonClick = function ()
    {
        button = $(this)
        var counterContainer = button.closest('.ia-counter-container');
        var input = counterContainer.find('input.counter-value');
        if(button.hasClass('increase'))
        {
            input.val(parseInt(input.val())+1);
        }
        else if (button.hasClass('decrease'))
        {
            if (input.val() > 1) input.val(parseInt(input.val()) - 1);
        }
    };

    // обработчик клика по радио кнопке
    this.radioClick = function (event)
    {
        if ($(this).hasClass('ia-radio-button')) var radioButton = $(this);
        else var radioButton = $(this).closest('.radio-button-container').find('.ia-radio-button');
        radioButton.closest('.radio-container').find('input.ia-radio-value').val(radioButton.find('span.value.hidden').text());
        radioButton.closest('.radio-container').find('.ia-radio-button').removeClass('active');
        radioButton.addClass('active');
    };
}
window.inanime_new = new inanime_new();
