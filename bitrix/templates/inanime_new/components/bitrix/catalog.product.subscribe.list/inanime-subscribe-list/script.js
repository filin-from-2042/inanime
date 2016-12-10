window.InAnimeSubscribeList = function(arParams)
{
    this.productType = 0;
    this.showQuantity = true;
    this.showAbsent = true;
    this.secondPict = false;
    this.showOldPrice = false;
    this.showPercent = false;
    this.showSkuProps = false;

    this.product = {
        checkQuantity: false,
        maxQuantity: 0,
        stepQuantity: 1,
        isDblQuantity: false,
        canBuy: true,
        canSubscription: true,
        name: '',
        pict: {},
        id: 0,
        addUrl: '',
        buyUrl: '',
        listSubscribeId: {}
    };

    this.defaultPict = {
        pict: null,
        secondPict: null
    };

    this.checkQuantity = false;
    this.maxQuantity = 0;
    this.stepQuantity = 1;
    this.isDblQuantity = false;
    this.canBuy = true;
    this.canSubscription = true;
    this.precision = 6;
    this.precisionFactor = Math.pow(10,this.precision);

    this.offers = [];
    this.offerNum = 0;
    this.treeProps = [];
    this.obTreeRows = [];
    this.showCount = [];
    this.showStart = [];
    this.selectedValues = {};

    this.obProduct = null;
    this.obQuantity = null;
    this.obQuantityUp = null;
    this.obQuantityDown = null;
    this.obPict = null;
    this.obSecondPict = null;
    this.obPrice = null;
    this.obTree = null;
    this.obBuyBtn = null;
    this.deleteSubscribeBtn = null;
    this.obDscPerc = null;
    this.obSecondDscPerc = null;
    this.obSkuProps = null;
    this.obMeasure = null;

    this.obPopupWin = null;
    this.basketUrl = '';
    this.basketParams = {};

    this.treeRowShowSize = 5;
    this.treeEnableArrow = { display: '', cursor: 'pointer', opacity: 1 };
    this.treeDisableArrow = { display: '', cursor: 'default', opacity:0.2 };

    this.lastElement = false;
    this.containerHeight = 0;

    this.errorCode = 0;

    this.ajaxUrl = '/bitrix/components/bitrix/catalog.product.subscribe.list/ajax.php';

    if ('object' === typeof arParams)
    {
        this.productType = parseInt(arParams.PRODUCT_TYPE, 10);
        this.showQuantity = arParams.SHOW_QUANTITY;
        this.showAbsent = arParams.SHOW_ABSENT;
        this.secondPict = !!arParams.SECOND_PICT;
        this.showOldPrice = !!arParams.SHOW_OLD_PRICE;
        this.showPercent = !!arParams.SHOW_DISCOUNT_PERCENT;
        this.showSkuProps = !!arParams.SHOW_SKU_PROPS;

        this.product.listSubscribeId = arParams.PRODUCT.LIST_SUBSCRIBE_ID;

        switch (this.productType)
        {
            case 1://product
            case 2://set
                if (!!arParams.PRODUCT && 'object' === typeof(arParams.PRODUCT))
                {
                    if (this.showQuantity)
                    {
                        this.product.checkQuantity = arParams.PRODUCT.CHECK_QUANTITY;
                        this.product.isDblQuantity = arParams.PRODUCT.QUANTITY_FLOAT;
                        if (this.product.checkQuantity)
                        {
                            this.product.maxQuantity = (this.product.isDblQuantity ? parseFloat(arParams.PRODUCT.MAX_QUANTITY) : parseInt(arParams.PRODUCT.MAX_QUANTITY, 10));
                        }
                        this.product.stepQuantity = (this.product.isDblQuantity ? parseFloat(arParams.PRODUCT.STEP_QUANTITY) : parseInt(arParams.PRODUCT.STEP_QUANTITY, 10));

                        this.checkQuantity = this.product.checkQuantity;
                        this.isDblQuantity = this.product.isDblQuantity;
                        this.maxQuantity = this.product.maxQuantity;
                        this.stepQuantity = this.product.stepQuantity;
                        if (this.isDblQuantity)
                        {
                            this.stepQuantity = Math.round(this.stepQuantity*this.precisionFactor)/this.precisionFactor;
                        }
                    }
                    this.product.canBuy = arParams.PRODUCT.CAN_BUY;
                    this.product.canSubscription = arParams.PRODUCT.SUBSCRIPTION;

                    this.canBuy = this.product.canBuy;
                    this.canSubscription = this.product.canSubscription;

                    this.product.name = arParams.PRODUCT.NAME;
                    this.product.pict = arParams.PRODUCT.PICT;
                    this.product.id = arParams.PRODUCT.ID;
                    if (!!arParams.PRODUCT.ADD_URL)
                    {
                        this.product.addUrl = arParams.PRODUCT.ADD_URL;
                    }
                    if (!!arParams.PRODUCT.BUY_URL)
                    {
                        this.product.buyUrl = arParams.PRODUCT.BUY_URL;
                    }
                }
                else
                {
                    this.errorCode = -1;
                }
                break;
            case 3://sku
                if (!!arParams.PRODUCT && 'object' === typeof(arParams.PRODUCT))
                {
                    this.product.name = arParams.PRODUCT.NAME;
                    this.product.id = arParams.PRODUCT.ID;
                }
                break;
            default:
                this.errorCode = -1;
        }
        this.lastElement = (!!arParams.LAST_ELEMENT && 'Y' === arParams.LAST_ELEMENT);
    }

};

window.InAnimeSubscribeList.prototype.deleteSubscribe = function()
{
    var itemId, offerIndex;
    switch(this.productType)
    {
        case 1:
        case 2:
            itemId = this.product.id;
            break;
        case 3:
            var i, j, boolSearch;
            if(!this.offers.length)
            {
                itemId = this.product.id;
                break;
            }
            for(i = 0; i < this.offers.length; i++)
            {
                boolSearch = true;
                for(j in this.selectedValues)
                {
                    if(this.selectedValues[j] !== this.offers[i].TREE[j])
                    {
                        boolSearch = false;
                        break;
                    }
                }
                if(boolSearch)
                {
                    offerIndex = i;
                    itemId = this.offers[i].ID;
                    break;
                }
            }
            break;
    }

    if(!itemId || !this.product.listSubscribeId.hasOwnProperty(itemId))
        return;

    BX.ajax({
        method: 'POST',
        dataType: 'json',
        url: this.ajaxUrl,
        data: {
            sessid: BX.bitrix_sessid(),
            deleteSubscribe: 'Y',
            itemId: itemId,
            listSubscribeId: this.product.listSubscribeId[itemId]
        },
        onsuccess: BX.delegate(function (result) {
            if(result.success)
            {
                this.showWindowWithAnswer({status: 'success'});
                location.reload();
            }
            else
            {
                this.showWindowWithAnswer({status: 'error', message: result.message});
            }
        }, this)
    });
};



window.InAnimeSubscribeList.prototype.showWindowWithAnswer = function(answer)
{
    answer = answer || {};
    if (!answer.message) {
        if (answer.status == 'success') {
            answer.message = BX.message('CPSL_STATUS_SUCCESS');
        } else {
            answer.message = BX.message('CPSL_STATUS_ERROR');
        }
    }
    var messageBox = BX.create('div', {
        props: {
            className: 'bx-catalog-subscribe-alert'
        },
        children: [
            BX.create('span', {
                props: {
                    className: 'bx-catalog-subscribe-aligner'
                }
            }),
            BX.create('span', {
                props: {
                    className: 'bx-catalog-subscribe-alert-text'
                },
                text: answer.message
            }),
            BX.create('div', {
                props: {
                    className: 'bx-catalog-subscribe-alert-footer'
                }
            })
        ]
    });
    var currentPopup = BX.PopupWindowManager.getCurrentPopup();
    if(currentPopup) {
        currentPopup.destroy();
    }
    var idTimeout = setTimeout(function () {
        var w = BX.PopupWindowManager.getCurrentPopup();
        if (!w || w.uniquePopupId != 'bx-catalog-subscribe-status-action') {
            return;
        }
        w.close();
        w.destroy();
    }, 3500);
    var popupConfirm = BX.PopupWindowManager.create('bx-catalog-subscribe-status-action', null, {
        content: messageBox,
        onPopupClose: function () {
            this.destroy();
            clearTimeout(idTimeout);
        },
        autoHide: true,
        zIndex: 2000,
        className: 'bx-catalog-subscribe-alert-popup'
    });
    popupConfirm.show();
    BX('bx-catalog-subscribe-status-action').onmouseover = function (e) {
        clearTimeout(idTimeout);
    };
    BX('bx-catalog-subscribe-status-action').onmouseout = function (e) {
        idTimeout = setTimeout(function () {
            var w = BX.PopupWindowManager.getCurrentPopup();
            if (!w || w.uniquePopupId != 'bx-catalog-subscribe-status-action') {
                return;
            }
            w.close();
            w.destroy();
        }, 3500);
    };
};