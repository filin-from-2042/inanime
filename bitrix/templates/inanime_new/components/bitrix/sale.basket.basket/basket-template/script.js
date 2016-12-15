
InAnimeBasket = function (params)
{
    console.log(params);
    this.deleteActions = params.deleteActions;
    this.delayActions = params.delayActions;
    this.currentPage = params.currentPage;
    var that = this;
    $('.ia-basket #my-basket .content-row input[type="checkbox"]').click(that.setAvailabilityActionButtons);
    $('.table-action-column .action-button-container button.put-aside-action').click(function(event){that.delayButtonAction(event, that)});
    $('.table-action-column .action-button-container button.remove-action').click(function(event){that.deleteButtonAction(event, that)});
    $(document).ready(function ()
    {
        $('.ia-counter-container .button').click(inanime_new.counterButtonClick);
    });
};

// включение/отключение кнопок с дейсвиями
InAnimeBasket.prototype.setAvailabilityActionButtons = function()
{
    var anythingChecked = false;
    $('.content-row input[type="checkbox"]').each(function(){
        if(this.checked) anythingChecked= true;
    });
    var actionButtons = $('.table-action-column .action-button-container button');
    if(!anythingChecked) actionButtons.attr('disabled',true);
    else actionButtons.attr('disabled',false);
};

InAnimeBasket.prototype.delayButtonAction = function(event, that)
{
    event.preventDefault();
    var elementID;
    var newCartData;
    $('.content-row input[type="checkbox"]').each(function(){

        if(this.checked){
            elementID = this.id.split('_')[1];
            $.ajax({
                type: "POST",
                async:false,
                url: that.delayActions[elementID],
                success: function(data){
                    newCartData = data;
                },
                error: function( xhr, textStatus ) {
                    alert( [ xhr.status, textStatus ] );
                }
            });

        }
    });
    $('.ia-basket #my-basket .my-basket-tab-container').replaceWith($(newCartData).find('.my-basket-tab-container'));
};

InAnimeBasket.prototype.deleteButtonAction = function(event, that)
{
    event.preventDefault();
    var elementID;
    var newCartData;
    $('.content-row input[type="checkbox"]').each(function(){
        if(this.checked){
            elementID = this.id.split('_')[1];
            $.ajax({
                type: "POST",
                async:false,
//                url: '/personal/cart/?basketAction='+that.deleteActions[elementID],
                url: that.deleteActions[elementID],
                success: function(data){
                    newCartData = data;
                },
                error: function( xhr, textStatus ) {
                    alert( [ xhr.status, textStatus ] );
                }
            });

        }
    });
    $('.ia-basket #my-basket .my-basket-tab-container').replaceWith($(newCartData).find('.my-basket-tab-container'));
};

InAnimeBasket.prototype.enterCoupon = function()
{
    var newCoupon = BX('coupon');
    if (!!newCoupon && !!newCoupon.value)
        this.recalcBasketAjax({'coupon' : newCoupon.value});
};

InAnimeBasket.prototype.recalcBasketAjax = function(params)
{
    BX.showWait();

    var property_values = {},
        action_var = BX('action_var').value,
//        items = BX('basket_items'),
        items = $('.table-content-container .content-row'),
        delayedItems = BX('delayed_items'),
        postData,
        i;

    postData = {
        'sessid': BX.bitrix_sessid(),
        'site_id': BX.message('SITE_ID'),
        'props': property_values,
        'action_var': action_var,
        'select_props': BX('column_headers').value,
        'offers_props': BX('offers_props').value,
        'quantity_float': BX('quantity_float').value,
        'count_discount_4_all_quantity': BX('count_discount_4_all_quantity').value,
        'price_vat_show_value': BX('price_vat_show_value').value,
        'hide_coupon': BX('hide_coupon').value,
        'use_prepayment': BX('use_prepayment').value
    };
    postData[action_var] = 'recalculate';
    if (!!params && typeof params === 'object')
    {
        for (i in params)
        {
            if (params.hasOwnProperty(i))
                postData[i] = params[i];
        }
    }
/*
    if (!!items && items.rows.length > 0)
    {
        for (i = 1; items.rows.length > i; i++)
            postData['QUANTITY_' + items.rows[i].id] = BX('QUANTITY_' + items.rows[i].id).value;
    }
    */
    if (!!items && items.length > 0)
    {
        items.each(function(){
            postData['QUANTITY_' + this.id] = $(this).find('.column-count input.counter-value').val();
        });
    }

    if (!!delayedItems && delayedItems.rows.length > 0)
    {
        for (i = 1; delayedItems.rows.length > i; i++)
            postData['DELAY_' + delayedItems.rows[i].id] = 'Y';
    }

    var that = this ;
    BX.ajax({
        url: '/bitrix/components/bitrix/sale.basket.basket/ajax.php',
        method: 'POST',
        data: postData,
        dataType: 'json',
        onsuccess: function(result)
        {
            console.log(result);
            BX.closeWait();

            if(params.coupon)
            {
                //hello, gifts!
                if(!!result && !!result.BASKET_DATA && !!result.BASKET_DATA.NEED_TO_RELOAD_FOR_GETTING_GIFTS)
                {
                    BX.reload();
                }
            }

            that.updateBasketTable(null, result);

        }
    });
};

InAnimeBasket.prototype.deleteCoupon = function(element)
{

    var target = $(element),
        value=target.attr('data-coupon');

    if (!!target.length && value)
    {
        //value = target.getAttribute('data-coupon');
        if (!!value && value.length > 0)
        {
            this.recalcBasketAjax({'delete_coupon' : value});
        }
    }
};


/**
 * @param basketItemId
 * @param {{BASKET_ID : string, BASKET_DATA : { GRID : { ROWS : {} }}, COLUMNS: {}, PARAMS: {}, DELETE_ORIGINAL : string }} res
 */
InAnimeBasket.prototype.updateBasketTable = function(basketItemId, res)
{
    var table = $('.table-content-container'),
        rows,
        newBasketItemId,
        arItem,
        lastRow,
        newRow,
        arColumns,
        bShowDeleteColumn = false,
        bShowDelayColumn = false,
        bShowPropsColumn = false,
        bShowPriceType = false,
        bUseFloatQuantity,
        origBasketItem,
        oCellMargin,
        i,
        oCellName,
        imageURL,
        cellNameHTML,
        oCellItem,
        cellItemHTML,
        bSkip,
        j,
        val,
        propId,
        arProp,
        bIsImageProperty,
        full,
        arVal,
        valId,
        arSkuValue,
        selected,
        valueId,
        k,
        arItemProp,
        oCellQuantity,
        oCellQuantityHTML,
        ratio,
        max,
        isUpdateQuantity,
        oldQuantity,
        oCellPrice,
        fullPrice,
        id,
        oCellDiscount,
        oCellWeight,
        oCellCustom,
        customColumnVal;

    if (!table || typeof res !== 'object')
    {
        return;
    }

    rows = table.find('.content-row');
    lastRow = rows[rows.length - 1];
    bUseFloatQuantity = (res.PARAMS.QUANTITY_FLOAT === 'Y');

    // update product params after recalculation
    if (!!res.BASKET_DATA)
    {
        for (id in res.BASKET_DATA.GRID.ROWS)
        {
            if (res.BASKET_DATA.GRID.ROWS.hasOwnProperty(id))
            {
                var item = res.BASKET_DATA.GRID.ROWS[id];
                var currRow = rows.filter('#'+id);

                var oldPriceEl = currRow.find('.column-cost .price.old');
                if (oldPriceEl.length)
                {
                    oldPriceEl.empty();
                    if(item.FULL_PRICE != item.PRICE)
                        oldPriceEl.append(item.FULL_PRICE_FORMATED);
                }

                var priceEl = currRow.find('.column-cost .price.yellow-text');
                if (priceEl.length)
                {
                    priceEl.empty();
                    if(item.PRICE)
                        priceEl.append(item.PRICE_FORMATED);
                }

                var sumEl = currRow.find('.column-all-cost .column-data');
                if (sumEl.length)
                {
                    sumEl.empty();
                    if(item.SUM)
                        sumEl.append(item.SUM);
                }

                var quantityEl = currRow.find('.column-count .counter-value');
                if (quantityEl.length)
                    quantityEl.val(item.QUANTITY);
            }
        }
    }

    // update coupon info
    if (!!res.BASKET_DATA)
        this.couponListUpdate(res.BASKET_DATA);

    // update warnings if any
 /*   if (res.hasOwnProperty('WARNING_MESSAGE'))
    {
        var warningText = '';

        for (i = res['WARNING_MESSAGE'].length - 1; i >= 0; i--)
            warningText += res['WARNING_MESSAGE'][i] + '<br/>';

        BX('warning_message').innerHTML = warningText;
    }
*/
    // update total basket values
    if (!!res.BASKET_DATA)
    {
        var allSumEL = $('.basket-action-column .total-container .total-value');
        if (allSumEL.length)
        {
            allSumEL.empty();
            if(res['BASKET_DATA']['allSum_FORMATED'])
                allSumEL.append(res['BASKET_DATA']['allSum_FORMATED']);
        }
    }
};

/**
 * @param {COUPON_LIST : []} res
 */
InAnimeBasket.prototype.couponListUpdate = function(res)
{
    var couponBlock,
        couponClass,
        fieldCoupon,
        couponsCollection,
        couponFound,
        i,
        j,
        key;

    if (!!res && typeof res !== 'object')
    {
        return;
    }

    couponBlock = $('.coupon-container')[0];
    if (!!couponBlock)
    {
        if (!!res.COUPON_LIST && BX.type.isArray(res.COUPON_LIST))
        {
            fieldCoupon = $(couponBlock).find('#coupon')[0];
            if (!!fieldCoupon)
            {
                fieldCoupon.value = '';
            }
            couponsCollection = $(couponBlock).find('input[name="OLD_COUPON[]"]').get();

            if (!!couponsCollection)
            {

                if (BX.type.isElementNode(couponsCollection))
                {
                    couponsCollection = [couponsCollection];
                }

                for (i = 0; i < res.COUPON_LIST.length; i++)
                {
                    couponFound = false;
                    key = -1;
                    for (j = 0; j < couponsCollection.length; j++)
                    {
                        if (couponsCollection[j].value === res.COUPON_LIST[i].COUPON)
                        {
                            couponFound = true;
                            key = j;
                            couponsCollection[j].couponUpdate = true;
                            break;
                        }
                    }
                    if (couponFound)
                    {
                        couponClass = 'disabled';
                        if (res.COUPON_LIST[i].JS_STATUS === 'BAD')
                            couponClass = 'bad';
                        else if (res.COUPON_LIST[i].JS_STATUS === 'APPLYED')
                            couponClass = 'good';

                        BX.adjust(couponsCollection[key], {props: {className: 'hidden '+couponClass}});
                        BX.adjust(couponsCollection[key].previousSibling, {props: {className: 'number grey-container gray-text '+couponClass}});
//                        BX.adjust(couponsCollection[key].previousSibling, {props: {className: 'number grey-container gray-text '+couponClass}});
//                        BX.adjust(couponsCollection[key].nextSibling.nextSibling, {html: res.COUPON_LIST[i].JS_CHECK_CODE});
                    }
                    else
                    {
                        this.couponCreate(couponBlock, res.COUPON_LIST[i]);
                    }
                }
                for (j = 0; j < couponsCollection.length; j++)
                {
                    if (typeof (couponsCollection[j].couponUpdate) === 'undefined' || !couponsCollection[j].couponUpdate)
                    {
                        BX.remove(couponsCollection[j].parentNode);
                        couponsCollection[j] = null;
                    }
                    else
                    {
                        couponsCollection[j].couponUpdate = null;
                    }
                }
            }
            else
            {
                for (i = 0; i < res.COUPON_LIST.length; i++)
                {
                    this.couponCreate(couponBlock, res.COUPON_LIST[i]);
                }
            }
        }
    }
    couponBlock = null;
};

/**
 * @param couponBlock
 * @param {COUPON: string, JS_STATUS: string} oneCoupon - new coupon.
 */
InAnimeBasket.prototype.couponCreate = function(couponBlock, oneCoupon)
{
    var couponClass = 'disabled';

    if (!BX.type.isElementNode(couponBlock))
        return;
    if (oneCoupon.JS_STATUS === 'BAD')
        couponClass = 'bad';
    else if (oneCoupon.JS_STATUS === 'APPLYED')
        couponClass = 'good';

    couponBlock.appendChild(BX.create(
        'div',
        {
            props: {
                className: 'discount-container'
            },
            children: [
                BX.create(
                    'div',
                    {
                        props: {
                            className: 'number grey-container gray-text '+couponClass
                        },
                        html: oneCoupon.COUPON
                    }
                ),
                BX.create(
                    'input',
                    {
                        props: {
                            className: 'hidden '+couponClass,
                            type: 'text',
                            value: oneCoupon.COUPON,
                            name: 'OLD_COUPON[]'
                        },
                        attrs: {
                            disabled: true,
                            readonly: true
                        }
                    }
                ),
                BX.create(
                    'button',
                    {
                        props: {
                            className: 'ia-close-btn',
                            type:'button'
                        },
                        attrs: {
                            'data-dismiss' :"modal",
                            'data-coupon': oneCoupon.COUPON,
                            'aria-label':'Close',
                            onclick:"iaBasket.deleteCoupon(this)"
                        },
                        children:[
                            BX.create(
                                'span',
                                {
                                    props: {
                                        className: 'clearfix'
                                    },
                                    attrs: {
                                        'aria-hidden':'true'
                                    },
                                    children:[
                                        BX.create(
                                            'i',
                                            {
                                                props: {
                                                    className: 'fa fa-times'
                                                },
                                                attrs: {
                                                    'aria-hidden':'true'
                                                }
                                            }
                                        )
                                    ]
                                }
                            )
                        ]
                    }
                )
            ]
        }
    ));
};

InAnimeBasket.prototype.checkOut = function()
{
    if (!!BX('coupon'))
        BX('coupon').disabled = true;
    BX("basket_form").submit();
    return true;
};

InAnimeBasket.prototype.clearAll = function()
{
    console.log(this.currentPage);

    BX.ajax({
        url: this.currentPage,
        method: 'POST',
        data: {
            action : 'clearAll'
        },
        onsuccess: function(result)
        {
           window.location.reload();
        }
    });

    return false;
};