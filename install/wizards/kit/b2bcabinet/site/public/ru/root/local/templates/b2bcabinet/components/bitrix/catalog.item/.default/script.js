(function (window){
	'use strict';

    if (window.JCCatalogItem)
        return;

    var BasketButton = function(params) {
        BasketButton.superclass.constructor.apply(this, arguments);
        this.buttonNode = BX.create('span', {
            props: { className: 'btn btn-default btn-buy btn-sm', id: this.id },
            style: typeof params.style === 'object' ? params.style : {},
            text: params.text,
            events: this.contextEvents
        });

        if (BX.browser.IsIE()) {
            this.buttonNode.setAttribute("hideFocus", "hidefocus");
        }
    };
    BX.extend(BasketButton, BX.PopupWindowButton);

    window.JCCatalogItem = function(arParams) {
        this.productType = 0;
        this.showQuantity = true;
        this.showAbsent = true;
        this.secondPict = false;
        this.showOldPrice = false;
        this.showMaxQuantity = 'N';
        this.relativeQuantityFactor = 5;
        this.showPercent = false;
        this.showSkuProps = false;
        this.basketAction = 'ADD';
        this.showClosePopup = false;
        this.useCompare = false;
        this.showSubscription = false;
        this.visual = {
            ID: '',
            PICT_ID: '',
            SECOND_PICT_ID: '',
            PICT_SLIDER_ID: '',
            QUANTITY_ID: '',
            QUANTITY_UP_ID: '',
            QUANTITY_DOWN_ID: '',
            PRICE_ID: '',
            PRICE_OLD_ID: '',
            DSC_PERC: '',
            SECOND_DSC_PERC: '',
            DISPLAY_PROP_DIV: '',
            BASKET_PROP_DIV: '',
            SUBSCRIBE_ID: ''
        };
        this.product = {
            checkQuantity: false,
            maxQuantity: 0,
            stepQuantity: 1,
            isDblQuantity: false,
            canBuy: true,
            name: '',
            pict: {},
            id: 0,
            addUrl: '',
            buyUrl: ''
        };

        this.basketMode = '';
        this.basketData = {
            useProps: false,
            emptyProps: false,
            quantity: 'quantity',
            props: 'prop',
            basketUrl: '',
            sku_props: '',
            sku_props_var: 'basket_props',
            add_url: '',
            buy_url: ''
        };

        this.compareData = {
            compareUrl: '',
            compareDeleteUrl: '',
            comparePath: ''
        };

        this.defaultPict = {
            pict: null,
            secondPict: null
        };

        this.defaultSliderOptions = {
            interval: 3000,
            wrap: true
        };
        this.slider = {
            options: {},
            items: [],
            active: null,
            sliding: null,
            paused: null,
            interval: null,
            progress: null
        };
        this.touch = null;

        this.quantityDelay = null;
        this.quantityTimer = null;

        this.checkQuantity = false;
        this.maxQuantity = 0;
        this.minQuantity = 0;
        this.stepQuantity = 1;
        this.isDblQuantity = false;
        this.canBuy = true;
        this.precision = 6;
        this.precisionFactor = Math.pow(10, this.precision);
        this.bigData = false;
        this.fullDisplayMode = false;
        this.viewMode = '';
        this.templateTheme = '';

        this.currentPriceMode = '';
        this.currentPrices = [];
        this.currentPriceSelected = 0;
        this.currentQuantityRanges = [];
        this.currentQuantityRangeSelected = 0;

        this.offers = [];
        this.offerNum = 0;
        this.treeProps = [];
        this.selectedValues = {};

        this.obProduct = null;
        this.blockNodes = {};
        this.obQuantity = null;
        this.obQuantityUp = null;
        this.obQuantityDown = null;
        this.obQuantityLimit = {};
        this.obPict = null;
        this.obSecondPict = null;
        this.obPictSlider = null;
        this.obPictSliderIndicator = null;
        this.obPrice = null;
        this.obTree = null;
        this.obBuyBtn = null;
        this.obBasketActions = null;
        this.obNotAvail = null;
        this.obSubscribe = null;
        this.obDscPerc = null;
        this.obSecondDscPerc = null;
        this.obSkuProps = null;
        this.obMeasure = null;
        this.obCompare = null;

        this.obPopupWin = null;
        this.basketUrl = '';
        this.basketParams = {};
        this.isTouchDevice = BX.hasClass(document.documentElement, 'bx-touch');
        this.hoverTimer = null;
        this.hoverStateChangeForbidden = false;
        this.mouseX = null;
        this.mouseY = null;

        this.useEnhancedEcommerce = false;
        this.dataLayerName = 'dataLayer';
        this.brandProperty = false;

        this.errorCode = 0;

        if (typeof arParams === 'object') {
            if (arParams.PRODUCT_TYPE) {
                this.productType = parseInt(arParams.PRODUCT_TYPE, 10);
            }

            this.showQuantity = arParams.SHOW_QUANTITY;
            this.showAbsent = arParams.SHOW_ABSENT;
            this.secondPict = arParams.SECOND_PICT;
            this.showOldPrice = arParams.SHOW_OLD_PRICE;
            this.showMaxQuantity = arParams.SHOW_MAX_QUANTITY;
            this.relativeQuantityFactor = parseInt(arParams.RELATIVE_QUANTITY_FACTOR);
            this.showPercent = arParams.SHOW_DISCOUNT_PERCENT;
            this.showSkuProps = arParams.SHOW_SKU_PROPS;
            this.showSubscription = arParams.USE_SUBSCRIBE;

            if (arParams.ADD_TO_BASKET_ACTION) {
                this.basketAction = arParams.ADD_TO_BASKET_ACTION;
            }

            this.showClosePopup = arParams.SHOW_CLOSE_POPUP;
            this.useCompare = arParams.DISPLAY_COMPARE;
            this.fullDisplayMode = arParams.PRODUCT_DISPLAY_MODE === 'Y';
            this.bigData = arParams.BIG_DATA;
            this.viewMode = arParams.VIEW_MODE || '';
            this.templateTheme = arParams.TEMPLATE_THEME || '';
            this.useEnhancedEcommerce = arParams.USE_ENHANCED_ECOMMERCE === 'Y';
            this.dataLayerName = arParams.DATA_LAYER_NAME;
            this.brandProperty = arParams.BRAND_PROPERTY;

            this.visual = arParams.VISUAL;

            switch (this.productType) {
                case 0: // no catalog
                case 1: // product
                case 2: // set
                    if (arParams.PRODUCT && typeof arParams.PRODUCT === 'object') {
                        this.currentPriceMode = arParams.PRODUCT.ITEM_PRICE_MODE;
                        this.currentPrices = arParams.PRODUCT.ITEM_PRICES;
                        this.currentPriceSelected = arParams.PRODUCT.ITEM_PRICE_SELECTED;
                        this.currentQuantityRanges = arParams.PRODUCT.ITEM_QUANTITY_RANGES;
                        this.currentQuantityRangeSelected = arParams.PRODUCT.ITEM_QUANTITY_RANGE_SELECTED;

                        if (this.showQuantity) {
                            this.product.checkQuantity = arParams.PRODUCT.CHECK_QUANTITY;
                            this.product.isDblQuantity = arParams.PRODUCT.QUANTITY_FLOAT;

                            if (this.product.checkQuantity) {
                                this.product.maxQuantity = (this.product.isDblQuantity ? parseFloat(arParams.PRODUCT.MAX_QUANTITY) : parseInt(arParams.PRODUCT.MAX_QUANTITY, 10));
                            }

                            this.product.stepQuantity = (this.product.isDblQuantity ? parseFloat(arParams.PRODUCT.STEP_QUANTITY) : parseInt(arParams.PRODUCT.STEP_QUANTITY, 10));

                            this.checkQuantity = this.product.checkQuantity;
                            this.isDblQuantity = this.product.isDblQuantity;
                            this.stepQuantity = this.product.stepQuantity;
                            this.maxQuantity = this.product.maxQuantity;
                            this.minQuantity = this.currentPriceMode === 'Q' ?
                                parseFloat(this.currentPrices[this.currentPriceSelected].MIN_QUANTITY) :
                                this.stepQuantity;

                            if (this.isDblQuantity) {
                                this.stepQuantity = Math.round(this.stepQuantity * this.precisionFactor) / this.precisionFactor;
                            }
                        }

                        this.product.canBuy = arParams.PRODUCT.CAN_BUY;

                        if (arParams.PRODUCT.MORE_PHOTO_COUNT) {
                            this.product.morePhotoCount = arParams.PRODUCT.MORE_PHOTO_COUNT;
                            this.product.morePhoto = arParams.PRODUCT.MORE_PHOTO;
                        }

                        if (arParams.PRODUCT.RCM_ID) {
                            this.product.rcmId = arParams.PRODUCT.RCM_ID;
                        }

                        this.canBuy = this.product.canBuy;
                        this.product.name = arParams.PRODUCT.NAME;
                        this.product.pict = arParams.PRODUCT.PICT;
                        this.product.id = arParams.PRODUCT.ID;
                        this.product.DETAIL_PAGE_URL = arParams.PRODUCT.DETAIL_PAGE_URL;

                        if (arParams.PRODUCT.ADD_URL) {
                            this.product.addUrl = arParams.PRODUCT.ADD_URL;
                        }

                        if (arParams.PRODUCT.BUY_URL) {
                            this.product.buyUrl = arParams.PRODUCT.BUY_URL;
                        }

                        if (arParams.BASKET && typeof arParams.BASKET === 'object') {
                            this.basketData.useProps = arParams.BASKET.ADD_PROPS;
                            this.basketData.emptyProps = arParams.BASKET.EMPTY_PROPS;
                        }
                    } else {
                        this.errorCode = -1;
                    }

                    break;
                case 3: // sku
                    if (arParams.PRODUCT && typeof arParams.PRODUCT === 'object') {
                        this.product.name = arParams.PRODUCT.NAME;
                        this.product.id = arParams.PRODUCT.ID;
                        this.product.DETAIL_PAGE_URL = arParams.PRODUCT.DETAIL_PAGE_URL;
                        this.product.morePhotoCount = arParams.PRODUCT.MORE_PHOTO_COUNT;
                        this.product.morePhoto = arParams.PRODUCT.MORE_PHOTO;

                        if (arParams.PRODUCT.RCM_ID) {
                            this.product.rcmId = arParams.PRODUCT.RCM_ID;
                        }
                    }

                    if (arParams.OFFERS && BX.type.isArray(arParams.OFFERS)) {
                        this.offers = arParams.OFFERS;
                        this.offerNum = 0;

                        if (arParams.OFFER_SELECTED) {
                            this.offerNum = parseInt(arParams.OFFER_SELECTED, 10);
                        }

                        if (isNaN(this.offerNum)) {
                            this.offerNum = 0;
                        }

                        if (arParams.TREE_PROPS) {
                            this.treeProps = arParams.TREE_PROPS;
                        }

                        if (arParams.DEFAULT_PICTURE) {
                            this.defaultPict.pict = arParams.DEFAULT_PICTURE.PICTURE;
                            this.defaultPict.secondPict = arParams.DEFAULT_PICTURE.PICTURE_SECOND;
                        }
                    }

                    break;
                default:
                    this.errorCode = -1;
            }
            if (arParams.BASKET && typeof arParams.BASKET === 'object') {
                if (arParams.BASKET.QUANTITY) {
                    this.basketData.quantity = arParams.BASKET.QUANTITY;
                }

                if (arParams.BASKET.PROPS) {
                    this.basketData.props = arParams.BASKET.PROPS;
                }

                if (arParams.BASKET.BASKET_URL) {
                    this.basketData.basketUrl = arParams.BASKET.BASKET_URL;
                }

                if (3 === this.productType) {
                    if (arParams.BASKET.SKU_PROPS) {
                        this.basketData.sku_props = arParams.BASKET.SKU_PROPS;
                    }
                }

                if (arParams.BASKET.ADD_URL_TEMPLATE) {
                    this.basketData.add_url = arParams.BASKET.ADD_URL_TEMPLATE;
                }

                if (arParams.BASKET.BUY_URL_TEMPLATE) {
                    this.basketData.buy_url = arParams.BASKET.BUY_URL_TEMPLATE;
                }

                if (this.basketData.add_url === '' && this.basketData.buy_url === '') {
                    this.errorCode = -1024;
                }
            }

            if (this.useCompare) {
                if (arParams.COMPARE && typeof arParams.COMPARE === 'object') {
                    if (arParams.COMPARE.COMPARE_PATH) {
                        this.compareData.comparePath = arParams.COMPARE.COMPARE_PATH;
                    }

                    if (arParams.COMPARE.COMPARE_URL_TEMPLATE) {
                        this.compareData.compareUrl = arParams.COMPARE.COMPARE_URL_TEMPLATE;
                    } else {
                        this.useCompare = false;
                    }

                    if (arParams.COMPARE.COMPARE_DELETE_URL_TEMPLATE) {
                        this.compareData.compareDeleteUrl = arParams.COMPARE.COMPARE_DELETE_URL_TEMPLATE;
                    } else {
                        this.useCompare = false;
                    }
                } else {
                    this.useCompare = false;
                }
            }
        }

        if (this.errorCode === 0) {
            BX.ready(BX.delegate(this.init, this));
        }
    };

	window.JCCatalogItem.prototype = {
		init: function()
		{
			var i = 0,
				treeItems = null;

			this.obProduct = BX(this.visual.ID);
			if (!this.obProduct)
			{
				this.errorCode = -1;
			}

            this.obPict = BX(this.visual.PICT_ID);
            if (!this.obPict) {
                this.errorCode = -2;
            }

            if (this.secondPict && this.visual.SECOND_PICT_ID) {
                this.obSecondPict = BX(this.visual.SECOND_PICT_ID);
            }

            this.obPictSlider = BX(this.visual.PICT_SLIDER_ID);
            this.obPictSliderIndicator = BX(this.visual.PICT_SLIDER_ID + '_indicator');
            this.obPictSliderProgressBar = BX(this.visual.PICT_SLIDER_ID + '_progress_bar');
            if (!this.obPictSlider) {
                this.errorCode = -4;
            }

            this.obPrice = BX(this.visual.PRICE_ID);
            this.obPriceOld = BX(this.visual.PRICE_OLD_ID);
            this.obPriceTotal = BX(this.visual.PRICE_TOTAL_ID);
            if (!this.obPrice) {
                this.errorCode = -16;
            }

			if (this.showQuantity && this.visual.QUANTITY_ID)
			{
				this.obQuantity = BX(this.visual.QUANTITY_ID);
				this.blockNodes.quantity = this.obProduct.querySelector('[data-entity="quantity-block"]');

                if (!this.isTouchDevice) {
                    BX.bind(this.obQuantity, 'focus', BX.proxy(this.onFocus, this));
                    BX.bind(this.obQuantity, 'blur', BX.proxy(this.onBlur, this));
                }

                if (this.visual.QUANTITY_UP_ID) {
                    this.obQuantityUp = BX(this.visual.QUANTITY_UP_ID);
                }

                if (this.visual.QUANTITY_DOWN_ID) {
                    this.obQuantityDown = BX(this.visual.QUANTITY_DOWN_ID);
                }
            }

            if (this.visual.QUANTITY_LIMIT && this.showMaxQuantity !== 'N') {
                this.obQuantityLimit.all = BX(this.visual.QUANTITY_LIMIT);
                if (this.obQuantityLimit.all) {
                    this.obQuantityLimit.value = this.obQuantityLimit.all.querySelector('[data-entity="quantity-limit-value"]');
                    if (!this.obQuantityLimit.value) {
                        this.obQuantityLimit.all = null;
                    }
                }
            }

            if (this.productType === 3 && this.fullDisplayMode) {
                if (this.visual.TREE_ID) {
                    this.obTree = BX(this.visual.TREE_ID);
                    if (!this.obTree) {
                        this.errorCode = -256;
                    }
                }

                if (this.visual.QUANTITY_MEASURE) {
                    this.obMeasure = BX(this.visual.QUANTITY_MEASURE);
                }
            }

            this.obBasketActions = BX(this.visual.BASKET_ACTIONS_ID);
            if (this.obBasketActions) {
                if (this.visual.BUY_ID) {
                    this.obBuyBtn = BX(this.visual.BUY_ID);
                }
            }

            this.obNotAvail = BX(this.visual.NOT_AVAILABLE_MESS);

            if (this.showSubscription) {
                this.obSubscribe = BX(this.visual.SUBSCRIBE_ID);
            }

            if (this.showPercent) {
                if (this.visual.DSC_PERC) {
                    this.obDscPerc = BX(this.visual.DSC_PERC);
                }
                if (this.secondPict && this.visual.SECOND_DSC_PERC) {
                    this.obSecondDscPerc = BX(this.visual.SECOND_DSC_PERC);
                }
            }

            if (this.showSkuProps) {
                if (this.visual.DISPLAY_PROP_DIV) {
                    this.obSkuProps = BX(this.visual.DISPLAY_PROP_DIV);
                }
            }

            if (this.errorCode === 0) {
                // product slider events
                if (this.isTouchDevice) {
                    BX.bind(this.obPictSlider, 'touchstart', BX.proxy(this.touchStartEvent, this));
                    BX.bind(this.obPictSlider, 'touchend', BX.proxy(this.touchEndEvent, this));
                    BX.bind(this.obPictSlider, 'touchcancel', BX.proxy(this.touchEndEvent, this));
                } else {
                    if (this.viewMode === 'CARD') {
                        // product hover events
                        BX.bind(this.obProduct, 'mouseenter', BX.proxy(this.hoverOn, this));
                        BX.bind(this.obProduct, 'mouseleave', BX.proxy(this.hoverOff, this));
                    }

                    // product slider events
                    BX.bind(this.obProduct, 'mouseenter', BX.proxy(this.cycleSlider, this));
                    BX.bind(this.obProduct, 'mouseleave', BX.proxy(this.stopSlider, this));
                }

                if (this.bigData) {
                    var links = BX.findChildren(this.obProduct, { tag: 'a' }, true);
                    if (links) {
                        for (i in links) {
                            if (links.hasOwnProperty(i)) {
                                if (links[i].getAttribute('href') == this.product.DETAIL_PAGE_URL) {
                                    BX.bind(links[i], 'click', BX.proxy(this.rememberProductRecommendation, this));
                                }
                            }
                        }
                    }
                }

                if (this.showQuantity) {
                    var startEventName = this.isTouchDevice ? 'touchstart' : 'mousedown';
                    var endEventName = this.isTouchDevice ? 'touchend' : 'mouseup';

                    if (this.obQuantityUp) {
                        BX.bind(this.obQuantityUp, startEventName, BX.proxy(this.startQuantityInterval, this));
                        BX.bind(this.obQuantityUp, endEventName, BX.proxy(this.clearQuantityInterval, this));
                        BX.bind(this.obQuantityUp, 'mouseout', BX.proxy(this.clearQuantityInterval, this));
                        BX.bind(this.obQuantityUp, 'click', BX.delegate(this.quantityUp, this));
                    }

                    if (this.obQuantityDown) {
                        BX.bind(this.obQuantityDown, startEventName, BX.proxy(this.startQuantityInterval, this));
                        BX.bind(this.obQuantityDown, endEventName, BX.proxy(this.clearQuantityInterval, this));
                        BX.bind(this.obQuantityDown, 'mouseout', BX.proxy(this.clearQuantityInterval, this));
                        BX.bind(this.obQuantityDown, 'click', BX.delegate(this.quantityDown, this));
                    }

                    if (this.obQuantity) {
                        BX.bind(this.obQuantity, 'change', BX.delegate(this.quantityChange, this));
                    }
                }

                switch (this.productType) {
                    case 0: // no catalog
                    case 1: // product
                    case 2: // set
                        if (parseInt(this.product.morePhotoCount) > 1 && this.obPictSlider) {
                            this.initializeSlider();
                        }

                        this.checkQuantityControls();

                        break;
                    case 3: // sku
                        if (this.offers.length > 0) {
                            treeItems = BX.findChildren(this.obTree, { tagName: 'li' }, true);

                            if (treeItems && treeItems.length) {
                                for (i = 0; i < treeItems.length; i++) {
                                    BX.bind(treeItems[i], 'click', BX.delegate(this.selectOfferProp, this));
                                }
                            }

                            this.setCurrent();
                        } else if (parseInt(this.product.morePhotoCount) > 1 && this.obPictSlider) {
                            this.initializeSlider();
                        }

                        break;
                }

                if (this.obBuyBtn) {
                    if (this.basketAction === 'ADD') {
                        BX.bind(this.obBuyBtn, 'click', BX.proxy(this.add2Basket, this));
                    } else {
                        BX.bind(this.obBuyBtn, 'click', BX.proxy(this.buyBasket, this));
                    }
                }

                if (this.useCompare) {
                    this.obCompare = BX(this.visual.COMPARE_LINK_ID);
                    if (this.obCompare) {
                        BX.bind(this.obCompare, 'click', BX.proxy(this.compare, this));
                    }

                    BX.addCustomEvent('onCatalogDeleteCompare', BX.proxy(this.checkDeletedCompare, this));
                }
            }
        },

        setAnalyticsDataLayer: function(action) {
            if (!this.useEnhancedEcommerce || !this.dataLayerName)
                return;

            var item = {},
                info = {},
                variants = [],
                i, k, j, propId, skuId, propValues;

            switch (this.productType) {
                case 0: //no catalog
                case 1: //product
                case 2: //set
                    item = {
                        'id': this.product.id,
                        'name': this.product.name,
                        'price': this.currentPrices[this.currentPriceSelected] && this.currentPrices[this.currentPriceSelected].PRICE,
                        'brand': BX.type.isArray(this.brandProperty) ? this.brandProperty.join('/') : this.brandProperty
                    };
                    break;
                case 3: //sku
                    for (i in this.offers[this.offerNum].TREE) {
                        if (this.offers[this.offerNum].TREE.hasOwnProperty(i)) {
                            propId = i.substring(5);
                            skuId = this.offers[this.offerNum].TREE[i];

                            for (k in this.treeProps) {
                                if (this.treeProps.hasOwnProperty(k) && this.treeProps[k].ID == propId) {
                                    for (j in this.treeProps[k].VALUES) {
                                        propValues = this.treeProps[k].VALUES[j];
                                        if (propValues.ID == skuId) {
                                            variants.push(propValues.NAME);
                                            break;
                                        }
                                    }

                                }
                            }
                        }
                    }

                    item = {
                        'id': this.offers[this.offerNum].ID,
                        'name': this.offers[this.offerNum].NAME,
                        'price': this.currentPrices[this.currentPriceSelected] && this.currentPrices[this.currentPriceSelected].PRICE,
                        'brand': BX.type.isArray(this.brandProperty) ? this.brandProperty.join('/') : this.brandProperty,
                        'variant': variants.join('/')
                    };
                    break;
            }

            switch (action) {
                case 'addToCart':
                    info = {
                        'event': 'addToCart',
                        'ecommerce': {
                            'currencyCode': this.currentPrices[this.currentPriceSelected] && this.currentPrices[this.currentPriceSelected].CURRENCY || '',
                            'add': {
                                'products': [{
                                    'name': item.name || '',
                                    'id': item.id || '',
                                    'price': item.price || 0,
                                    'brand': item.brand || '',
                                    'category': item.category || '',
                                    'variant': item.variant || '',
                                    'quantity': this.showQuantity && this.obQuantity ? this.obQuantity.value : 1
                                }]
                            }
                        }
                    };
                    break;
            }

            window[this.dataLayerName] = window[this.dataLayerName] || [];
            window[this.dataLayerName].push(info);
        },

        hoverOn: function(event) {
            clearTimeout(this.hoverTimer);
            this.obProduct.style.height = getComputedStyle(this.obProduct).height;
            BX.addClass(this.obProduct, 'hover');

            BX.PreventDefault(event);
        },

        hoverOff: function(event) {
            if (this.hoverStateChangeForbidden)
                return;

            BX.removeClass(this.obProduct, 'hover');
            this.hoverTimer = setTimeout(
                BX.delegate(function() {
                    this.obProduct.style.height = 'auto';
                }, this),
                300
            );

            BX.PreventDefault(event);
        },

        onFocus: function() {
            this.hoverStateChangeForbidden = true;
            BX.bind(document, 'mousemove', BX.proxy(this.captureMousePosition, this));
        },

        onBlur: function() {
            this.hoverStateChangeForbidden = false;
            BX.unbind(document, 'mousemove', BX.proxy(this.captureMousePosition, this));

            var cursorElement = document.elementFromPoint(this.mouseX, this.mouseY);
            if (!cursorElement || !this.obProduct.contains(cursorElement)) {
                this.hoverOff();
            }
        },

        captureMousePosition: function(event) {
            this.mouseX = event.clientX;
            this.mouseY = event.clientY;
        },

        getCookie: function(name) {
            var matches = document.cookie.match(new RegExp(
                "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
            ));

            return matches ? decodeURIComponent(matches[1]) : null;
        },

        rememberProductRecommendation: function() {
            // save to RCM_PRODUCT_LOG
            var cookieName = BX.cookie_prefix + '_RCM_PRODUCT_LOG',
                cookie = this.getCookie(cookieName),
                itemFound = false;

            var cItems = [],
                cItem;

            if (cookie) {
                cItems = cookie.split('.');
            }

            var i = cItems.length;

            while (i--) {
                cItem = cItems[i].split('-');

                if (cItem[0] == this.product.id) {
                    // it's already in recommendations, update the date
                    cItem = cItems[i].split('-');

                    // update rcmId and date
                    cItem[1] = this.product.rcmId;
                    cItem[2] = BX.current_server_time;

                    cItems[i] = cItem.join('-');
                    itemFound = true;
                } else {
                    if ((BX.current_server_time - cItem[2]) > 3600 * 24 * 30) {
                        cItems.splice(i, 1);
                    }
                }
            }

            if (!itemFound) {
                // add recommendation
                cItems.push([this.product.id, this.product.rcmId, BX.current_server_time].join('-'));
            }

            // serialize
            var plNewCookie = cItems.join('.'),
                cookieDate = new Date(new Date().getTime() + 1000 * 3600 * 24 * 365 * 10).toUTCString();

            document.cookie = cookieName + "=" + plNewCookie + "; path=/; expires=" + cookieDate + "; domain=" + BX.cookie_domain;
        },

        startQuantityInterval: function() {
            var target = BX.proxy_context;
            var func = target.id === this.visual.QUANTITY_DOWN_ID ?
                BX.proxy(this.quantityDown, this) :
                BX.proxy(this.quantityUp, this);

            this.quantityDelay = setTimeout(
                BX.delegate(function() {
                    this.quantityTimer = setInterval(func, 150);
                }, this),
                300
            );
        },

        clearQuantityInterval: function() {
            clearTimeout(this.quantityDelay);
            clearInterval(this.quantityTimer);
        },

        quantityUp: function() {
            var curValue = 0,
                boolSet = true;

            if (this.errorCode === 0 && this.showQuantity && this.canBuy) {
                curValue = (this.isDblQuantity ? parseFloat(this.obQuantity.value) : parseInt(this.obQuantity.value, 10));
                if (!isNaN(curValue)) {
                    curValue += this.stepQuantity;
                    if (this.checkQuantity) {
                        if (curValue > this.maxQuantity) {
                            boolSet = false;
                        }
                    }

                    if (boolSet) {
                        if (this.isDblQuantity) {
                            curValue = Math.round(curValue * this.precisionFactor) / this.precisionFactor;
                        }

                        this.obQuantity.value = curValue;

                        this.setPrice();
                    }
                }
            }
        },

        quantityDown: function() {
            var curValue = 0,
                boolSet = true;

            if (this.errorCode === 0 && this.showQuantity && this.canBuy) {
                curValue = (this.isDblQuantity ? parseFloat(this.obQuantity.value) : parseInt(this.obQuantity.value, 10));
                if (!isNaN(curValue)) {
                    curValue -= this.stepQuantity;

                    this.checkPriceRange(curValue);

                    if (curValue < this.minQuantity) {
                        boolSet = false;
                    }

                    if (boolSet) {
                        if (this.isDblQuantity) {
                            curValue = Math.round(curValue * this.precisionFactor) / this.precisionFactor;
                        }

                        this.obQuantity.value = curValue;

                        this.setPrice();
                    }
                }
            }
        },

        quantityChange: function() {
            var curValue = 0,
                intCount;

            if (this.errorCode === 0 && this.showQuantity) {
                if (this.canBuy) {
                    curValue = this.isDblQuantity ? parseFloat(this.obQuantity.value) : Math.round(this.obQuantity.value);
                    if (!isNaN(curValue)) {
                        if (this.checkQuantity) {
                            if (curValue > this.maxQuantity) {
                                curValue = this.maxQuantity;
                            }
                        }

                        this.checkPriceRange(curValue);

                        if (curValue < this.minQuantity) {
                            curValue = this.minQuantity;
                        } else {
                            intCount = Math.round(
                                Math.round(curValue * this.precisionFactor / this.stepQuantity) / this.precisionFactor
                            ) || 1;
                            curValue = (intCount <= 1 ? this.stepQuantity : intCount * this.stepQuantity);
                            curValue = Math.round(curValue * this.precisionFactor) / this.precisionFactor;
                        }

                        this.obQuantity.value = curValue;
                    } else {
                        this.obQuantity.value = this.minQuantity;
                    }
                } else {
                    this.obQuantity.value = this.minQuantity;
                }

                this.setPrice();
            }
        },

        quantitySet: function(index) {
            var resetQuantity, strLimit;

            var newOffer = this.offers[index],
                oldOffer = this.offers[this.offerNum];

            if (this.errorCode === 0) {
                this.canBuy = newOffer.CAN_BUY;

                this.currentPriceMode = newOffer.ITEM_PRICE_MODE;
                this.currentPrices = newOffer.ITEM_PRICES;
                this.currentPriceSelected = newOffer.ITEM_PRICE_SELECTED;
                this.currentQuantityRanges = newOffer.ITEM_QUANTITY_RANGES;
                this.currentQuantityRangeSelected = newOffer.ITEM_QUANTITY_RANGE_SELECTED;

                if (this.canBuy) {
                    if (this.blockNodes.quantity) {
                        BX.style(this.blockNodes.quantity, 'display', '');
                    }

                    if (this.obBasketActions) {
                        BX.style(this.obBasketActions, 'display', '');
                    }

                    if (this.obNotAvail) {
                        BX.style(this.obNotAvail, 'display', 'none');
                    }

                    if (this.obSubscribe) {
                        BX.style(this.obSubscribe, 'display', 'none');
                    }
                } else {
                    if (this.blockNodes.quantity) {
                        BX.style(this.blockNodes.quantity, 'display', 'none');
                    }

                    if (this.obBasketActions) {
                        BX.style(this.obBasketActions, 'display', 'none');
                    }

                    if (this.obNotAvail) {
                        BX.style(this.obNotAvail, 'display', '');
                    }

                    if (this.obSubscribe) {
                        if (newOffer.CATALOG_SUBSCRIBE === 'Y') {
                            BX.style(this.obSubscribe, 'display', '');
                            this.obSubscribe.setAttribute('data-item', newOffer.ID);
                            BX(this.visual.SUBSCRIBE_ID + '_hidden').click();
                        } else {
                            BX.style(this.obSubscribe, 'display', 'none');
                        }
                    }
                }

                this.isDblQuantity = newOffer.QUANTITY_FLOAT;
                this.checkQuantity = newOffer.CHECK_QUANTITY;

                if (this.isDblQuantity) {
                    this.stepQuantity = Math.round(parseFloat(newOffer.STEP_QUANTITY) * this.precisionFactor) / this.precisionFactor;
                    this.maxQuantity = parseFloat(newOffer.MAX_QUANTITY);
                    this.minQuantity = this.currentPriceMode === 'Q' ? parseFloat(this.currentPrices[this.currentPriceSelected].MIN_QUANTITY) : this.stepQuantity;
                } else {
                    this.stepQuantity = parseInt(newOffer.STEP_QUANTITY, 10);
                    this.maxQuantity = parseInt(newOffer.MAX_QUANTITY, 10);
                    this.minQuantity = this.currentPriceMode === 'Q' ? parseInt(this.currentPrices[this.currentPriceSelected].MIN_QUANTITY) : this.stepQuantity;
                }

                if (this.showQuantity) {
                    var isDifferentMinQuantity = oldOffer.ITEM_PRICES.length &&
                        oldOffer.ITEM_PRICES[oldOffer.ITEM_PRICE_SELECTED] &&
                        oldOffer.ITEM_PRICES[oldOffer.ITEM_PRICE_SELECTED].MIN_QUANTITY != this.minQuantity;

                    if (this.isDblQuantity) {
                        resetQuantity = Math.round(parseFloat(oldOffer.STEP_QUANTITY) * this.precisionFactor) / this.precisionFactor !== this.stepQuantity ||
                            isDifferentMinQuantity ||
                            oldOffer.MEASURE !== newOffer.MEASURE ||
                            (
                                this.checkQuantity &&
                                parseFloat(oldOffer.MAX_QUANTITY) > this.maxQuantity &&
                                parseFloat(this.obQuantity.value) > this.maxQuantity
                            );
                    } else {
                        resetQuantity = parseInt(oldOffer.STEP_QUANTITY, 10) !== this.stepQuantity ||
                            isDifferentMinQuantity ||
                            oldOffer.MEASURE !== newOffer.MEASURE ||
                            (
                                this.checkQuantity &&
                                parseInt(oldOffer.MAX_QUANTITY, 10) > this.maxQuantity &&
                                parseInt(this.obQuantity.value, 10) > this.maxQuantity
                            );
                    }

                    this.obQuantity.disabled = !this.canBuy;

                    if (resetQuantity) {
                        this.obQuantity.value = this.minQuantity;
                    }

                    if (this.obMeasure) {
                        if (newOffer.MEASURE) {
                            BX.adjust(this.obMeasure, { html: newOffer.MEASURE });
                        } else {
                            BX.adjust(this.obMeasure, { html: '' });
                        }
                    }
                }

                if (this.obQuantityLimit.all) {
                    if (!this.checkQuantity || this.maxQuantity == 0) {
                        BX.adjust(this.obQuantityLimit.value, { html: '' });
                        BX.adjust(this.obQuantityLimit.all, { style: { display: 'none' } });
                    } else {
                        if (this.showMaxQuantity === 'M') {
                            strLimit = (this.maxQuantity / this.stepQuantity >= this.relativeQuantityFactor) ?
                                BX.message('RELATIVE_QUANTITY_MANY') :
                                BX.message('RELATIVE_QUANTITY_FEW');
                        } else {
                            strLimit = this.maxQuantity;

                            if (newOffer.MEASURE) {
                                strLimit += (' ' + newOffer.MEASURE);
                            }
                        }

                        BX.adjust(this.obQuantityLimit.value, { html: strLimit });
                        BX.adjust(this.obQuantityLimit.all, { style: { display: '' } });
                    }
                }
            }
        },

        initializeSlider: function() {
            var wrap = this.obPictSlider.getAttribute('data-slider-wrap');
            if (wrap) {
                this.slider.options.wrap = wrap === 'true';
            } else {
                this.slider.options.wrap = this.defaultSliderOptions.wrap;
            }

            if (this.isTouchDevice) {
                this.slider.options.interval = false;
            } else {
                this.slider.options.interval = parseInt(this.obPictSlider.getAttribute('data-slider-interval')) || this.defaultSliderOptions.interval;
                // slider interval must be more than 700ms because of css transitions
                if (this.slider.options.interval < 700) {
                    this.slider.options.interval = 700;
                }

                if (this.obPictSliderIndicator) {
                    var controls = this.obPictSliderIndicator.querySelectorAll('[data-go-to]');
                    for (var i in controls) {
                        if (controls.hasOwnProperty(i)) {
                            BX.bind(controls[i], 'click', BX.proxy(this.sliderClickHandler, this));
                        }
                    }
                }

                if (this.obPictSliderProgressBar) {
                    if (this.slider.progress) {
                        this.resetProgress();
                        this.cycleSlider();
                    } else {
                        this.slider.progress = new BX.easing({
                            transition: BX.easing.transitions.linear,
                            step: BX.delegate(function(state) {
                                this.obPictSliderProgressBar.style.width = state.width / 10 + '%';
                            }, this)
                        });
                    }
                }
            }
        },

        checkTouch: function(event) {
            if (!event || !event.changedTouches)
                return false;

            return event.changedTouches[0].identifier === this.touch.identifier;
        },

        touchStartEvent: function(event) {
            if (event.touches.length != 1)
                return;

            this.touch = event.changedTouches[0];
        },

        touchEndEvent: function(event) {
            if (!this.checkTouch(event))
                return;

            var deltaX = this.touch.pageX - event.changedTouches[0].pageX,
                deltaY = this.touch.pageY - event.changedTouches[0].pageY;

            if (Math.abs(deltaX) >= Math.abs(deltaY) + 10) {
                if (deltaX > 0) {
                    this.slideNext();
                }

                if (deltaX < 0) {
                    this.slidePrev();
                }
            }
        },

        sliderClickHandler: function(event) {
            var target = BX.getEventTarget(event),
                slideIndex = target.getAttribute('data-go-to');

            if (slideIndex) {
                this.slideTo(slideIndex)
            }

            BX.PreventDefault(event);
        },

        slideNext: function() {
            if (this.slider.sliding)
                return;

            return this.slide('next');
        },

        slidePrev: function() {
            if (this.slider.sliding)
                return;

            return this.slide('prev');
        },

        slideTo: function(pos) {
            this.slider.active = BX.findChild(this.obPictSlider, { className: 'item active' }, true, false);
            this.slider.progress && (this.slider.interval = true);

            var activeIndex = this.getItemIndex(this.slider.active);

            if (pos > (this.slider.items.length - 1) || pos < 0)
                return;

            if (this.slider.sliding)
                return false;

            if (activeIndex == pos) {
                this.stopSlider();
                this.cycleSlider();
                return;
            }

            return this.slide(pos > activeIndex ? 'next' : 'prev', this.eq(this.slider.items, pos));
        },

        slide: function(type, next) {
            var active = BX.findChild(this.obPictSlider, { className: 'item active' }, true, false),
                isCycling = this.slider.interval,
                direction = type === 'next' ? 'left' : 'right';

            next = next || this.getItemForDirection(type, active);

            if (BX.hasClass(next, 'active')) {
                return (this.slider.sliding = false);
            }

            this.slider.sliding = true;

            isCycling && this.stopSlider();

            if (this.obPictSliderIndicator) {
                BX.removeClass(this.obPictSliderIndicator.querySelector('.active'), 'active');
                var nextIndicator = this.obPictSliderIndicator.querySelectorAll('[data-go-to]')[this.getItemIndex(next)];
                nextIndicator && BX.addClass(nextIndicator, 'active');
            }

            if (BX.hasClass(this.obPictSlider, 'slide') && !BX.browser.IsIE()) {
                var self = this;
                BX.addClass(next, type);
                next.offsetWidth; // force reflow
                BX.addClass(active, direction);
                BX.addClass(next, direction);
                setTimeout(function() {
                    BX.addClass(next, 'active');
                    BX.removeClass(active, 'active');
                    BX.removeClass(active, direction);
                    BX.removeClass(next, type);
                    BX.removeClass(next, direction);
                    self.slider.sliding = false;
                }, 700);
            } else {
                BX.addClass(next, 'active');
                this.slider.sliding = false;
            }

            this.obPictSliderProgressBar && this.resetProgress();
            isCycling && this.cycleSlider();
        },

        stopSlider: function(event) {
            event || (this.slider.paused = true);

            this.slider.interval && clearInterval(this.slider.interval);

            if (this.slider.progress) {
                this.slider.progress.stop();

                var width = parseInt(this.obPictSliderProgressBar.style.width);

                this.slider.progress.options.duration = this.slider.options.interval * width / 200;
                this.slider.progress.options.start = { width: width * 10 };
                this.slider.progress.options.finish = { width: 0 };
                this.slider.progress.options.complete = null;
                this.slider.progress.animate();
            }
        },

        cycleSlider: function(event) {
            event || (this.slider.paused = false);

            this.slider.interval && clearInterval(this.slider.interval);

            if (this.slider.options.interval && !this.slider.paused) {
                if (this.slider.progress) {
                    this.slider.progress.stop();

                    var width = parseInt(this.obPictSliderProgressBar.style.width);

                    this.slider.progress.options.duration = this.slider.options.interval * (100 - width) / 100;
                    this.slider.progress.options.start = { width: width * 10 };
                    this.slider.progress.options.finish = { width: 1000 };
                    this.slider.progress.options.complete = BX.delegate(function() {
                        this.slider.interval = true;
                        this.slideNext();
                    }, this);
                    this.slider.progress.animate();
                } else {
                    this.slider.interval = setInterval(BX.proxy(this.slideNext, this), this.slider.options.interval);
                }
            }
        },

        resetProgress: function() {
            this.slider.progress && this.slider.progress.stop();
            this.obPictSliderProgressBar.style.width = 0;
        },

        getItemForDirection: function(direction, active) {
            var activeIndex = this.getItemIndex(active),
                willWrap = direction === 'prev' && activeIndex === 0 ||
                direction === 'next' && activeIndex == (this.slider.items.length - 1);

            if (willWrap && !this.slider.options.wrap)
                return active;

            var delta = direction === 'prev' ? -1 : 1,
                itemIndex = (activeIndex + delta) % this.slider.items.length;

            return this.eq(this.slider.items, itemIndex);
        },

        getItemIndex: function(item) {
            this.slider.items = BX.findChildren(item.parentNode, { className: 'item' }, true);

            return this.slider.items.indexOf(item || this.slider.active);
        },

        eq: function(obj, i) {
            var len = obj.length,
                j = +i + (i < 0 ? len : 0);

            return j >= 0 && j < len ? obj[j] : {};
        },

        selectOfferProp: function() {
            var i = 0,
                value = '',
                strTreeValue = '',
                arTreeItem = [],
                rowItems = null,
                target = BX.proxy_context;

            if (target && target.hasAttribute('data-treevalue')) {
                if (BX.hasClass(target, 'selected'))
                    return;

                strTreeValue = target.getAttribute('data-treevalue');
                arTreeItem = strTreeValue.split('_');
                if (this.searchOfferPropIndex(arTreeItem[0], arTreeItem[1])) {
                    rowItems = BX.findChildren(target.parentNode, { tagName: 'li' }, false);
                    if (rowItems && 0 < rowItems.length) {
                        for (i = 0; i < rowItems.length; i++) {
                            value = rowItems[i].getAttribute('data-onevalue');
                            if (value === arTreeItem[1]) {
                                BX.addClass(rowItems[i], 'selected');
                            } else {
                                BX.removeClass(rowItems[i], 'selected');
                            }
                        }
                    }
                }
            }
        },

        searchOfferPropIndex: function(strPropID, strPropValue) {
            var strName = '',
                arShowValues = false,
                i, j,
                arCanBuyValues = [],
                allValues = [],
                index = -1,
                arFilter = {},
                tmpFilter = [];

            for (i = 0; i < this.treeProps.length; i++) {
                if (this.treeProps[i].ID === strPropID) {
                    index = i;
                    break;
                }
            }

            if (-1 < index) {
                for (i = 0; i < index; i++) {
                    strName = 'PROP_' + this.treeProps[i].ID;
                    arFilter[strName] = this.selectedValues[strName];
                }
                strName = 'PROP_' + this.treeProps[index].ID;
                arShowValues = this.getRowValues(arFilter, strName);
                if (!arShowValues) {
                    return false;
                }
                if (!BX.util.in_array(strPropValue, arShowValues)) {
                    return false;
                }
                arFilter[strName] = strPropValue;
                for (i = index + 1; i < this.treeProps.length; i++) {
                    strName = 'PROP_' + this.treeProps[i].ID;
                    arShowValues = this.getRowValues(arFilter, strName);
                    if (!arShowValues) {
                        return false;
                    }
                    allValues = [];
                    if (this.showAbsent) {
                        arCanBuyValues = [];
                        tmpFilter = [];
                        tmpFilter = BX.clone(arFilter, true);
                        for (j = 0; j < arShowValues.length; j++) {
                            tmpFilter[strName] = arShowValues[j];
                            allValues[allValues.length] = arShowValues[j];
                            if (this.getCanBuy(tmpFilter))
                                arCanBuyValues[arCanBuyValues.length] = arShowValues[j];
                        }
                    } else {
                        arCanBuyValues = arShowValues;
                    }
                    if (this.selectedValues[strName] && BX.util.in_array(this.selectedValues[strName], arCanBuyValues)) {
                        arFilter[strName] = this.selectedValues[strName];
                    } else {
                        if (this.showAbsent)
                            arFilter[strName] = (arCanBuyValues.length > 0 ? arCanBuyValues[0] : allValues[0]);
                        else
                            arFilter[strName] = arCanBuyValues[0];
                    }
                    this.updateRow(i, arFilter[strName], arShowValues, arCanBuyValues);
                }
                this.selectedValues = arFilter;
                this.changeInfo();
            }
            return true;
        },

        updateRow: function(intNumber, activeID, showID, canBuyID) {
            var i = 0,
                value = '',
                isCurrent = false,
                rowItems = null;

            var lineContainer = this.obTree.querySelectorAll('[data-entity="sku-line-block"]'),
                listContainer;

            if (intNumber > -1 && intNumber < lineContainer.length) {
                listContainer = lineContainer[intNumber].querySelector('ul');
                rowItems = BX.findChildren(listContainer, { tagName: 'li' }, false);
                if (rowItems && 0 < rowItems.length) {
                    for (i = 0; i < rowItems.length; i++) {
                        value = rowItems[i].getAttribute('data-onevalue');
                        isCurrent = value === activeID;

                        if (isCurrent) {
                            BX.addClass(rowItems[i], 'selected');
                        } else {
                            BX.removeClass(rowItems[i], 'selected');
                        }

                        if (BX.util.in_array(value, canBuyID)) {
                            BX.removeClass(rowItems[i], 'notallowed');
                        } else {
                            BX.addClass(rowItems[i], 'notallowed');
                        }

                        rowItems[i].style.display = BX.util.in_array(value, showID) ? '' : 'none';

                        if (isCurrent) {
                            lineContainer[intNumber].style.display = (value == 0 && canBuyID.length == 1) ? 'none' : '';
                        }
                    }
                }
            }
        },

        getRowValues: function(arFilter, index) {
            var i = 0,
                j,
                arValues = [],
                boolSearch = false,
                boolOneSearch = true;

            if (0 === arFilter.length) {
                for (i = 0; i < this.offers.length; i++) {
                    if (!BX.util.in_array(this.offers[i].TREE[index], arValues)) {
                        arValues[arValues.length] = this.offers[i].TREE[index];
                    }
                }
                boolSearch = true;
            } else {
                for (i = 0; i < this.offers.length; i++) {
                    boolOneSearch = true;
                    for (j in arFilter) {
                        if (arFilter[j] !== this.offers[i].TREE[j]) {
                            boolOneSearch = false;
                            break;
                        }
                    }
                    if (boolOneSearch) {
                        if (!BX.util.in_array(this.offers[i].TREE[index], arValues)) {
                            arValues[arValues.length] = this.offers[i].TREE[index];
                        }
                        boolSearch = true;
                    }
                }
            }
            return (boolSearch ? arValues : false);
        },

        getCanBuy: function(arFilter) {
            var i, j,
                boolSearch = false,
                boolOneSearch = true;

            for (i = 0; i < this.offers.length; i++) {
                boolOneSearch = true;
                for (j in arFilter) {
                    if (arFilter[j] !== this.offers[i].TREE[j]) {
                        boolOneSearch = false;
                        break;
                    }
                }
                if (boolOneSearch) {
                    if (this.offers[i].CAN_BUY) {
                        boolSearch = true;
                        break;
                    }
                }
            }

            return boolSearch;
        },

        setCurrent: function() {
            var i,
                j = 0,
                arCanBuyValues = [],
                strName = '',
                arShowValues = false,
                arFilter = {},
                tmpFilter = [],
                current = this.offers[this.offerNum].TREE;

            for (i = 0; i < this.treeProps.length; i++) {
                strName = 'PROP_' + this.treeProps[i].ID;
                arShowValues = this.getRowValues(arFilter, strName);
                if (!arShowValues) {
                    break;
                }
                if (BX.util.in_array(current[strName], arShowValues)) {
                    arFilter[strName] = current[strName];
                } else {
                    arFilter[strName] = arShowValues[0];
                    this.offerNum = 0;
                }
                if (this.showAbsent) {
                    arCanBuyValues = [];
                    tmpFilter = [];
                    tmpFilter = BX.clone(arFilter, true);
                    for (j = 0; j < arShowValues.length; j++) {
                        tmpFilter[strName] = arShowValues[j];
                        if (this.getCanBuy(tmpFilter)) {
                            arCanBuyValues[arCanBuyValues.length] = arShowValues[j];
                        }
                    }
                } else {
                    arCanBuyValues = arShowValues;
                }
                this.updateRow(i, arFilter[strName], arShowValues, arCanBuyValues);
            }
            this.selectedValues = arFilter;
            this.changeInfo();
        },

        changeInfo: function() {
            var i, j,
                index = -1,
                boolOneSearch = true,
                quantityChanged;

            for (i = 0; i < this.offers.length; i++) {
                boolOneSearch = true;
                for (j in this.selectedValues) {
                    if (this.selectedValues[j] !== this.offers[i].TREE[j]) {
                        boolOneSearch = false;
                        break;
                    }
                }
                if (boolOneSearch) {
                    index = i;
                    break;
                }
            }
            if (index > -1) {
                if (parseInt(this.offers[index].MORE_PHOTO_COUNT) > 1 && this.obPictSlider) {
                    // hide pict and second_pict containers
                    if (this.obPict) {
                        this.obPict.style.display = 'none';
                    }

                    if (this.obSecondPict) {
                        this.obSecondPict.style.display = 'none';
                    }

                    // clear slider container
                    BX.cleanNode(this.obPictSlider);

                    // fill slider container with slides
                    for (i in this.offers[index].MORE_PHOTO) {
                        if (this.offers[index].MORE_PHOTO.hasOwnProperty(i)) {
                            this.obPictSlider.appendChild(
                                BX.create('SPAN', {
                                    props: { className: 'product-item-image-slide item' + (i == 0 ? ' active' : '') },
                                    style: { backgroundImage: 'url(\'' + this.offers[index].MORE_PHOTO[i].SRC + '\')' }
                                })
                            );
                        }
                    }

                    // fill slider indicator if exists
                    if (this.obPictSliderIndicator) {
                        BX.cleanNode(this.obPictSliderIndicator);

                        for (i in this.offers[index].MORE_PHOTO) {
                            if (this.offers[index].MORE_PHOTO.hasOwnProperty(i)) {
                                this.obPictSliderIndicator.appendChild(
                                    BX.create('DIV', {
                                        attrs: { 'data-go-to': i },
                                        props: { className: 'product-item-image-slider-control' + (i == 0 ? ' active' : '') }
                                    })
                                );
                                this.obPictSliderIndicator.appendChild(document.createTextNode(' '));
                            }
                        }

                        this.obPictSliderIndicator.style.display = '';
                    }

                    if (this.obPictSliderProgressBar) {
                        this.obPictSliderProgressBar.style.display = '';
                    }

                    // show slider container
                    this.obPictSlider.style.display = '';
                    this.initializeSlider();
                } else {
                    // hide slider container
                    if (this.obPictSlider) {
                        this.obPictSlider.style.display = 'none';
                    }

                    if (this.obPictSliderIndicator) {
                        this.obPictSliderIndicator.style.display = 'none';
                    }

                    if (this.obPictSliderProgressBar) {
                        this.obPictSliderProgressBar.style.display = 'none';
                    }

                    // show pict and pict_second containers
                    if (this.obPict) {
                        if (this.offers[index].PREVIEW_PICTURE) {
                            BX.adjust(this.obPict, { style: { backgroundImage: 'url(\'' + this.offers[index].PREVIEW_PICTURE.SRC + '\')' } });
                        } else {
                            BX.adjust(this.obPict, { style: { backgroundImage: 'url(\'' + this.defaultPict.pict.SRC + '\')' } });
                        }

                        this.obPict.style.display = '';
                    }

                    if (this.secondPict && this.obSecondPict) {
                        if (this.offers[index].PREVIEW_PICTURE_SECOND) {
                            BX.adjust(this.obSecondPict, { style: { backgroundImage: 'url(\'' + this.offers[index].PREVIEW_PICTURE_SECOND.SRC + '\')' } });
                        } else if (this.offers[index].PREVIEW_PICTURE.SRC) {
                            BX.adjust(this.obSecondPict, { style: { backgroundImage: 'url(\'' + this.offers[index].PREVIEW_PICTURE.SRC + '\')' } });
                        } else if (this.defaultPict.secondPict) {
                            BX.adjust(this.obSecondPict, { style: { backgroundImage: 'url(\'' + this.defaultPict.secondPict.SRC + '\')' } });
                        } else {
                            BX.adjust(this.obSecondPict, { style: { backgroundImage: 'url(\'' + this.defaultPict.pict.SRC + '\')' } });
                        }

                        this.obSecondPict.style.display = '';
                    }
                }

                if (this.showSkuProps && this.obSkuProps) {
                    if (this.offers[index].DISPLAY_PROPERTIES.length) {
                        BX.adjust(this.obSkuProps, { style: { display: '' }, html: this.offers[index].DISPLAY_PROPERTIES });
                    } else {
                        BX.adjust(this.obSkuProps, { style: { display: 'none' }, html: '' });
                    }
                }

                this.quantitySet(index);
                this.setPrice();
                this.setCompared(this.offers[index].COMPARED);

                this.offerNum = index;
            }
        },

        checkPriceRange: function(quantity) {
            if (typeof quantity === 'undefined' || this.currentPriceMode != 'Q')
                return;

            var range, found = false;

            for (var i in this.currentQuantityRanges) {
                if (this.currentQuantityRanges.hasOwnProperty(i)) {
                    range = this.currentQuantityRanges[i];

                    if (
                        parseInt(quantity) >= parseInt(range.SORT_FROM) &&
                        (
                            range.SORT_TO == 'INF' ||
                            parseInt(quantity) <= parseInt(range.SORT_TO)
                        )
                    ) {
                        found = true;
                        this.currentQuantityRangeSelected = range.HASH;
                        break;
                    }
                }
            }

            if (!found && (range = this.getMinPriceRange())) {
                this.currentQuantityRangeSelected = range.HASH;
            }

            for (var k in this.currentPrices) {
                if (this.currentPrices.hasOwnProperty(k)) {
                    if (this.currentPrices[k].QUANTITY_HASH == this.currentQuantityRangeSelected) {
                        this.currentPriceSelected = k;
                        break;
                    }
                }
            }
        },

        getMinPriceRange: function() {
            var range;

            for (var i in this.currentQuantityRanges) {
                if (this.currentQuantityRanges.hasOwnProperty(i)) {
                    if (!range ||
                        parseInt(this.currentQuantityRanges[i].SORT_FROM) < parseInt(range.SORT_FROM)
                    ) {
                        range = this.currentQuantityRanges[i];
                    }
                }
            }

            return range;
        },

        checkQuantityControls: function() {
            if (!this.obQuantity)
                return;

            var reachedTopLimit = this.checkQuantity && parseFloat(this.obQuantity.value) + this.stepQuantity > this.maxQuantity,
                reachedBottomLimit = parseFloat(this.obQuantity.value) - this.stepQuantity < this.minQuantity;

            if (reachedTopLimit) {
                BX.addClass(this.obQuantityUp, 'product-item-amount-field-btn-disabled');
            } else if (BX.hasClass(this.obQuantityUp, 'product-item-amount-field-btn-disabled')) {
                BX.removeClass(this.obQuantityUp, 'product-item-amount-field-btn-disabled');
            }

            if (reachedBottomLimit) {
                BX.addClass(this.obQuantityDown, 'product-item-amount-field-btn-disabled');
            } else if (BX.hasClass(this.obQuantityDown, 'product-item-amount-field-btn-disabled')) {
                BX.removeClass(this.obQuantityDown, 'product-item-amount-field-btn-disabled');
            }

            if (reachedTopLimit && reachedBottomLimit) {
                this.obQuantity.setAttribute('disabled', 'disabled');
            } else {
                this.obQuantity.removeAttribute('disabled');
            }
        },

        setPrice: function() {
            var obData, price;

            if (this.obQuantity) {
                this.checkPriceRange(this.obQuantity.value);
            }

            this.checkQuantityControls();

            price = this.currentPrices[this.currentPriceSelected];

            if (this.obPrice) {
                if (price) {
                    BX.adjust(this.obPrice, { html: BX.Currency.currencyFormat(price.RATIO_PRICE, price.CURRENCY, true) });
                } else {
                    BX.adjust(this.obPrice, { html: '' });
                }

                if (this.showOldPrice && this.obPriceOld) {
                    if (price && price.RATIO_PRICE !== price.RATIO_BASE_PRICE) {
                        BX.adjust(this.obPriceOld, {
                            style: { display: '' },
                            html: BX.Currency.currencyFormat(price.RATIO_BASE_PRICE, price.CURRENCY, true)
                        });
                    } else {
                        BX.adjust(this.obPriceOld, {
                            style: { display: 'none' },
                            html: ''
                        });
                    }
                }

                if (this.obPriceTotal) {
                    if (price && this.obQuantity && this.obQuantity.value != this.stepQuantity) {
                        BX.adjust(this.obPriceTotal, {
                            html: BX.message('PRICE_TOTAL_PREFIX') + ' <strong>' +
                                BX.Currency.currencyFormat(price.PRICE * this.obQuantity.value, price.CURRENCY, true) +
                                '</strong>',
                            style: { display: '' }
                        });
                    } else {
                        BX.adjust(this.obPriceTotal, {
                            html: '',
                            style: { display: 'none' }
                        });
                    }
                }

                if (this.showPercent) {
                    if (price && parseInt(price.DISCOUNT) > 0) {
                        obData = { style: { display: '' }, html: -price.PERCENT + '%' };
                    } else {
                        obData = { style: { display: 'none' }, html: '' };
                    }

                    if (this.obDscPerc) {
                        BX.adjust(this.obDscPerc, obData);
                    }

                    if (this.obSecondDscPerc) {
                        BX.adjust(this.obSecondDscPerc, obData);
                    }
                }
            }
        },

        compare: function(event) {
            var checkbox = this.obCompare.querySelector('[data-entity="compare-checkbox"]'),
                target = BX.getEventTarget(event),
                checked = true;

            if (checkbox) {
                checked = target === checkbox ? checkbox.checked : !checkbox.checked;
            }

            var url = checked ? this.compareData.compareUrl : this.compareData.compareDeleteUrl,
                compareLink;

            if (url) {
                if (target !== checkbox) {
                    BX.PreventDefault(event);
                    this.setCompared(checked);
                }

                switch (this.productType) {
                    case 0: // no catalog
                    case 1: // product
                    case 2: // set
                        compareLink = url.replace('#ID#', this.product.id.toString());
                        break;
                    case 3: // sku
                        compareLink = url.replace('#ID#', this.offers[this.offerNum].ID);
                        break;
                }

                BX.ajax({
                    method: 'POST',
                    dataType: checked ? 'json' : 'html',
                    url: compareLink + (compareLink.indexOf('?') !== -1 ? '&' : '?') + 'ajax_action=Y',
                    onsuccess: checked ?
                        BX.proxy(this.compareResult, this) : BX.proxy(this.compareDeleteResult, this)
                });
            }
        },

        compareResult: function(result) {
            var popupContent, popupButtons;

            if (this.obPopupWin) {
                this.obPopupWin.close();
            }

            if (!BX.type.isPlainObject(result))
                return;

            this.initPopupWindow();

            if (this.offers.length > 0) {
                this.offers[this.offerNum].COMPARED = result.STATUS === 'OK';
            }

            if (result.STATUS === 'OK') {
                BX.onCustomEvent('OnCompareChange');

                popupContent = '<div style="width: 100%; margin: 0; text-align: center;"><p>' +
                    BX.message('COMPARE_MESSAGE_OK') +
                    '</p></div>';

                if (this.showClosePopup) {
                    popupButtons = [
                        new BasketButton({
                            text: BX.message('BTN_MESSAGE_COMPARE_REDIRECT'),
                            events: {
                                click: BX.delegate(this.compareRedirect, this)
                            },
                            style: { marginRight: '10px' }
                        }),
                        new BasketButton({
                            text: BX.message('BTN_MESSAGE_CLOSE_POPUP'),
                            events: {
                                click: BX.delegate(this.obPopupWin.close, this.obPopupWin)
                            }
                        })
                    ];
                } else {
                    popupButtons = [
                        new BasketButton({
                            text: BX.message('BTN_MESSAGE_COMPARE_REDIRECT'),
                            events: {
                                click: BX.delegate(this.compareRedirect, this)
                            }
                        })
                    ];
                }
            } else {
                popupContent = '<div style="width: 100%; margin: 0; text-align: center;"><p>' +
                    (result.MESSAGE ? result.MESSAGE : BX.message('COMPARE_UNKNOWN_ERROR')) +
                    '</p></div>';
                popupButtons = [
                    new BasketButton({
                        text: BX.message('BTN_MESSAGE_CLOSE'),
                        events: {
                            click: BX.delegate(this.obPopupWin.close, this.obPopupWin)
                        }
                    })
                ];
            }

            this.obPopupWin.setTitleBar(BX.message('COMPARE_TITLE'));
            this.obPopupWin.setContent(popupContent);
            this.obPopupWin.setButtons(popupButtons);
            this.obPopupWin.show();
        },

        compareDeleteResult: function() {
            BX.onCustomEvent('OnCompareChange');

            if (this.offers && this.offers.length) {
                this.offers[this.offerNum].COMPARED = false;
            }
        },

        setCompared: function(state) {
            if (!this.obCompare)
                return;

            var checkbox = this.obCompare.querySelector('[data-entity="compare-checkbox"]');
            if (checkbox) {
                checkbox.checked = state;
            }
        },

        setCompareInfo: function(comparedIds) {
            if (!BX.type.isArray(comparedIds))
                return;

            for (var i in this.offers) {
                if (this.offers.hasOwnProperty(i)) {
                    this.offers[i].COMPARED = BX.util.in_array(this.offers[i].ID, comparedIds);
                }
            }
        },

        compareRedirect: function() {
            if (this.compareData.comparePath) {
                location.href = this.compareData.comparePath;
            } else {
                this.obPopupWin.close();
            }
        },

        checkDeletedCompare: function(id) {
            switch (this.productType) {
                case 0: // no catalog
                case 1: // product
                case 2: // set
                    if (this.product.id == id) {
                        this.setCompared(false);
                    }

                    break;
                case 3: // sku
                    var i = this.offers.length;
                    while (i--) {
                        if (this.offers[i].ID == id) {
                            this.offers[i].COMPARED = false;

                            if (this.offerNum == i) {
                                this.setCompared(false);
                            }

                            break;
                        }
                    }
            }
        },

        initBasketUrl: function() {
            this.basketUrl = (this.basketMode === 'ADD' ? this.basketData.add_url : this.basketData.buy_url);
            switch (this.productType) {
                case 1: // product
                case 2: // set
                    this.basketUrl = this.basketUrl.replace('#ID#', this.product.id.toString());
                    break;
                case 3: // sku
                    this.basketUrl = this.basketUrl.replace('#ID#', this.offers[this.offerNum].ID);
                    break;
            }
            this.basketParams = {
                'ajax_basket': 'Y'
            };
            if (this.showQuantity) {
                this.basketParams[this.basketData.quantity] = this.obQuantity.value;
            }
            if (this.basketData.sku_props) {
                this.basketParams[this.basketData.sku_props_var] = this.basketData.sku_props;
            }
        },

        fillBasketProps: function() {
            if (!this.visual.BASKET_PROP_DIV) {
                return;
            }
            var
                i = 0,
                propCollection = null,
                foundValues = false,
                obBasketProps = null;

            if (this.basketData.useProps && !this.basketData.emptyProps) {
                if (this.obPopupWin && this.obPopupWin.contentContainer) {
                    obBasketProps = this.obPopupWin.contentContainer;
                }
            } else {
                obBasketProps = BX(this.visual.BASKET_PROP_DIV);
            }
            if (obBasketProps) {
                propCollection = obBasketProps.getElementsByTagName('select');
                if (propCollection && propCollection.length) {
                    for (i = 0; i < propCollection.length; i++) {
                        if (!propCollection[i].disabled) {
                            switch (propCollection[i].type.toLowerCase()) {
                                case 'select-one':
                                    this.basketParams[propCollection[i].name] = propCollection[i].value;
                                    foundValues = true;
                                    break;
                                default:
                                    break;
                            }
                        }
                    }
                }
                propCollection = obBasketProps.getElementsByTagName('input');
                if (propCollection && propCollection.length) {
                    for (i = 0; i < propCollection.length; i++) {
                        if (!propCollection[i].disabled) {
                            switch (propCollection[i].type.toLowerCase()) {
                                case 'hidden':
                                    this.basketParams[propCollection[i].name] = propCollection[i].value;
                                    foundValues = true;
                                    break;
                                case 'radio':
                                    if (propCollection[i].checked) {
                                        this.basketParams[propCollection[i].name] = propCollection[i].value;
                                        foundValues = true;
                                    }
                                    break;
                                default:
                                    break;
                            }
                        }
                    }
                }
            }
            if (!foundValues) {
                this.basketParams[this.basketData.props] = [];
                this.basketParams[this.basketData.props][0] = 0;
            }
        },

        add2Basket: function() {
            this.basketMode = 'ADD';
            this.basket();
        },

        buyBasket: function() {
            this.basketMode = 'BUY';
            this.basket();
        },

        sendToBasket: function() {
            if (!this.canBuy) {
                return;
            }

            // check recommendation
            if (this.product && this.product.id && this.bigData) {
                this.rememberProductRecommendation();
            }

            this.initBasketUrl();
            this.fillBasketProps();
            BX.ajax({
                method: 'POST',
                dataType: 'json',
                url: this.basketUrl,
                data: this.basketParams,
                onsuccess: BX.proxy(this.basketResult, this)
            });
        },

        basket: function() {
            var contentBasketProps = '';
            if (!this.canBuy) {
                return;
            }
            switch (this.productType) {
                case 1: // product
                case 2: // set
                    if (this.basketData.useProps && !this.basketData.emptyProps) {
                        this.initPopupWindow();
                        this.obPopupWin.setTitleBar(BX.message('TITLE_BASKET_PROPS'));
                        if (BX(this.visual.BASKET_PROP_DIV)) {
                            contentBasketProps = BX(this.visual.BASKET_PROP_DIV).innerHTML;
                        }
                        this.obPopupWin.setContent(contentBasketProps);
                        this.obPopupWin.setButtons([
                            new BasketButton({
                                text: BX.message('BTN_MESSAGE_SEND_PROPS'),
                                events: {
                                    click: BX.delegate(this.sendToBasket, this)
                                }
                            })
                        ]);
                        this.obPopupWin.show();
                    } else {
                        this.sendToBasket();
                    }
                    break;
                case 3: // sku
                    this.sendToBasket();
                    break;
            }
        },

        basketResult: function(arResult) {
            var strContent = '',
                strPict = '',
                successful,
                buttons = [];

            if (this.obPopupWin)
                this.obPopupWin.close();

            if (!BX.type.isPlainObject(arResult))
                return;

            successful = arResult.STATUS === 'OK';

            if (successful) {
                this.setAnalyticsDataLayer('addToCart');
            }

            if (successful && this.basketAction === 'BUY') {
                this.basketRedirect();
            } else {
                this.initPopupWindow();

                if (successful) {
                    BX.onCustomEvent('OnBasketChange');

                    if (BX.findParent(this.obProduct, { className: 'bx_sale_gift_main_products' }, 10)) {
                        BX.onCustomEvent('onAddToBasketMainProduct', [this]);
                    }

                    switch (this.productType) {
                        case 1: // product
                        case 2: // set
                            strPict = this.product.pict.SRC;
                            break;
                        case 3: // sku
                            strPict = (this.offers[this.offerNum].PREVIEW_PICTURE ?
                                this.offers[this.offerNum].PREVIEW_PICTURE.SRC :
                                this.defaultPict.pict.SRC
                            );
                            break;
                    }

                    strContent = '<div style="width: 100%; margin: 0; text-align: center;"><img src="' +
                        strPict + '" height="130" style="max-height:130px"><p>' + this.product.name + '</p></div>';

                    if (this.showClosePopup) {
                        buttons = [
                            new BasketButton({
                                text: BX.message("BTN_MESSAGE_BASKET_REDIRECT"),
                                events: {
                                    click: BX.delegate(this.basketRedirect, this)
                                },
                                style: { marginRight: '10px' }
                            }),
                            new BasketButton({
                                text: BX.message("BTN_MESSAGE_CLOSE_POPUP"),
                                events: {
                                    click: BX.delegate(this.obPopupWin.close, this.obPopupWin)
                                }
                            })
                        ];
                    } else {
                        buttons = [
                            new BasketButton({
                                text: BX.message("BTN_MESSAGE_BASKET_REDIRECT"),
                                events: {
                                    click: BX.delegate(this.basketRedirect, this)
                                }
                            })
                        ];
                    }
                } else {
                    strContent = '<div style="width: 100%; margin: 0; text-align: center;"><p>' +
                        (arResult.MESSAGE ? arResult.MESSAGE : BX.message('BASKET_UNKNOWN_ERROR')) +
                        '</p></div>';
                    buttons = [
                        new BasketButton({
                            text: BX.message('BTN_MESSAGE_CLOSE'),
                            events: {
                                click: BX.delegate(this.obPopupWin.close, this.obPopupWin)
                            }
                        })
                    ];
                }
                this.obPopupWin.setTitleBar(successful ? BX.message('TITLE_SUCCESSFUL') : BX.message('TITLE_ERROR'));
                this.obPopupWin.setContent(strContent);
                this.obPopupWin.setButtons(buttons);
                this.obPopupWin.show();
            }
        },

        basketRedirect: function() {
            location.href = (this.basketData.basketUrl ? this.basketData.basketUrl : BX.message('BASKET_URL'));
        },

        initPopupWindow: function() {
            if (this.obPopupWin)
                return;

            this.obPopupWin = BX.PopupWindowManager.create('CatalogSectionBasket_' + this.visual.ID, null, {
                autoHide: true,
                offsetLeft: 0,
                offsetTop: 0,
                overlay: true,
                closeByEsc: true,
                titleBar: true,
                closeIcon: true,
                contentColor: 'white',
                className: this.templateTheme ? 'bx-' + this.templateTheme : ''
            });
        }
    };
})(window);

$(document).ready(function() {
    let trShowHiddenBlock = document.querySelectorAll(".index_blank .index_blank-table .table .offer-footer"),
        btnShowMore = document.querySelectorAll(".offer-show-more-content .offer-show-more-content__btn"),
        propBlock = document.querySelectorAll(".offer-properties-item-inner"),
        propItem = [];

    for (let i = 0; i < trShowHiddenBlock.length; i++) {
        trShowHiddenBlock[i].addEventListener("click", function () {
            let tBodyParent = getParent(this, 'index_blank-table-tbody');

            if (this.classList.contains('active')) {

                this.classList.remove('active');

                if (tBodyParent.querySelector('.offer-properties-hidden')) {
                    tBodyParent.querySelector('.offer-properties-hidden').style.display = 'none';
                }

            } else {
                this.classList.add('active');
            }
        });
    }

    for (let k = 0; k < btnShowMore.length; k++) {
        btnShowMore[k].addEventListener("click", function () {
            let tBodyParent = getParent(this, 'index_blank-table-tbody');

            this.classList.toggle('opened');

            for (let s = 0; s < tBodyParent.children.length; s++) {

                if (tBodyParent.children[s].classList.contains("offer-properties-hidden")) {
                    if (window.getComputedStyle(tBodyParent.children[s]).getPropertyValue("display") === "none") {
                        tBodyParent.children[s].style.display = "table-row";
                    } else {
                        tBodyParent.children[s].style.display = "none";
                    }
                }

            }
        });
    }

    for (let n = 0; n < propBlock.length; n++) {
        propItem[n] = propBlock[n].querySelectorAll(".offer-properties-item-inner__item");

        for (let q = 0; q < propItem[n].length; q++) {
            propItem[n][q].addEventListener("click", function() {
                this.classList.toggle('active');
            });
        }
    }

});

//-------------------------------------

var $topBar;
var $button;
var $visibleLinks;
var $hiddenLinks;
var responsiveBreaks; // Empty List (Array) on initialization

var $topBar2;
var $button2;
var $visibleLinks2;
var $hiddenLinks2;
var responsiveBreaks2; // Empty List (Array) on initialization


window.onresize = function(event) {

	if ( $(window).width() < 576 ) {
		ChangePhoneMobile();
		ChangeCouponMobile();
		titleChangeMobile();
	}
	else {
		titleChangeDevice();
		ChangePhoneDevice();
		ChangeCouponAllDevice();
	}
	if ( $(window).width() < 992 ) {
		ChangePriceMobile();
	}
	else {
		ChangePriceDevice();
	}
	if ($(window).width() < 768) {
		galleryMoreItems(2);
		ChangePhoneMobileDetail();
		previewProductMobileNot();
	}
	else {
		galleryMoreItems(5);
		ChangePhoneDeviceDetail();
	}

	if ( $(window).width() > 768 ) {
		updateTopBar($topBar, $button, $visibleLinks, $hiddenLinks, responsiveBreaks);
		updateTopBar($topBar2, $button2, $visibleLinks2, $hiddenLinks2, responsiveBreaks2);
	}
};

$(document).ready(function() {
	$(".hover").mouseleave(
		function () {
			$(this).removeClass("hover");
		}
	);

	$topBar = $('.category-main-menu');
	$button = $('.category-main-menu .responsive-hidden-button');
	$visibleLinks = $('.category-main-menu .visible-links');
	$hiddenLinks = $('.category-main-menu .hidden-links');
	responsiveBreaks = []; // Empty List (Array) on initialization

	$topBar2 = $('.category-menu');
	$button2 = $('.category-menu .responsive-hidden-button');
	$visibleLinks2 = $('.category-menu .visible-links');
	$hiddenLinks2 = $('.category-menu .hidden-links');
	responsiveBreaks2 = []; // Empty List (Array) on initialization

	// Window listeners
	$button.on('click', function() {
		$hiddenLinks.toggleClass('hidden');
	});

	$button2.on('click', function() {
		$hiddenLinks2.toggleClass('hidden');
	});

	if ( $(window).width() > 768 ) {
		updateTopBar($topBar, $button, $visibleLinks, $hiddenLinks, responsiveBreaks);
		updateTopBar($topBar2, $button2, $visibleLinks2, $hiddenLinks2, responsiveBreaks2);
	}


	if ( $(window).width() < 768 ) {
		ChangeBrandMobile();
		galleryMoreItems(2);
		ChangePhoneMobileDetail();
		// ChangeRegions(true);
	}
	else {
		ChangeBrandAllDevice();
		galleryMoreItems(5);
		ChangePhoneDeviceDetail();
		// ChangeRegions(false);
	}

	if ( $(window).width() < 576 ) {
		ChangeNewsMobile();
		ChangeCouponMobile();
		ChangePhoneMobile();
		titleChangeMobile();
	}
	else {
		titleChangeDevice();
		ChangePhoneDevice();
		ChangeNewsAllDevice();
		ChangeCouponAllDevice();
	}

	if ( $(window).width() < 992 ) {
		ChangePriceMobile();
	}
	else {
		ChangePriceDevice();
	}

	MobileBasket();

	TransitionDetail();

	orderOpenProperty();

	//header phone
	allPhone();

	//celect
	MainSelect();
	itemsSort();
});

function previewProductMobileNot() {
	if($("div").is(".main_info_preview_product")) {
		$(".main_info_preview_product").closest(".wrap-popup-window").hide();
	}
}

/// top menu

function updateTopBar($topBar, $button, $visibleLinks, $hiddenLinks, responsiveBreaks) {

	var availableSpace = $button.hasClass('hidden') ? $topBar.width() : $topBar.width() - $button.width() - 30; // Calculation of available space on the logic of whether button has the class `hidden` or not
	//alert($topBar.width());
	if($visibleLinks.width() > availableSpace && window.screen.width>=768) { // Logic when visible list is overflowing the nav

		responsiveBreaks.push($visibleLinks.width()); // Record the width of the list
		$visibleLinks.children().last().prependTo($hiddenLinks); // Move item to the hidden list

		// Show the resonsive hidden button
		if($button.hasClass('hidden')) {
			$button.removeClass('hidden');
		}

	} else { // Logic when visible list is not overflowing the nav
		if(availableSpace > responsiveBreaks[responsiveBreaks.length-1]) { // Logic when there is space for another item in the nav
			$hiddenLinks.children().first().appendTo($visibleLinks);
			responsiveBreaks.pop(); // Move the item to the visible list
			updateTopBar($topBar, $button, $visibleLinks, $hiddenLinks, responsiveBreaks);
		}

		//Hide the resonsive hidden button if list is empty
		if(responsiveBreaks.length < 1) {
			$button.addClass('hidden');
			$hiddenLinks.addClass('hidden');
		}
	}

	$button.attr("count", responsiveBreaks.length); // Keeping counter updated

	if($visibleLinks.width() > availableSpace && window.screen.width>=768) { // Occur again if the visible list is still overflowing the nav
		updateTopBar($topBar, $button, $visibleLinks, $hiddenLinks, responsiveBreaks);
	}
}

/// top menu end

var count_2 = 5;

// ----- catalog galery ----- ///
function galleryMoreItems(count2) {
    var size_li_2 = $(".catalog_content__category_block .catalog_content__category_item").length;
    $('.catalog_content__category_block .catalog_content__category_item').css("display", "none");
    $('.catalog_content__category_block .catalog_content__category_item:lt(' + count_2 + ')').show();
    $('#loadMore').click(function () {

        count_2 = (count_2 + count_2 <= size_li_2) ? count_2 + count_2 : size_li_2;

        $('.catalog_content__category_block .catalog_content__category_item:lt(' + count_2 + ')').show();
        if (size_li_2 === count_2) {
            $("#loadMore").remove();
        }
    });
    if ($("div").is('#loadMore2')) {
        if (size_li_2 <= count_2) {
            $("#loadMore").remove();
        }
    }
}
/// ----- end catalog galery ----- ///

// order Mobile open property
function orderOpenProperty() {
	$(".main_order_block__item .main_order_block__item_slide_open" ).click(function() {
		$(this).parent().find(".main_order_block__item_slide").toggleClass("active");
		$(this).toggleClass("active");
	});
}
// end order Mobile open property

function ChangeBrandMobile() {
	$(".brand_block").addClass("brand_block_variant owl-carousel");
	$(".brand_block_variant_two").addClass("brand_block_variant owl-carousel");
	$(".brand_block_variant_two").removeClass("row");
}
function ChangeBrandAllDevice() {
	$(".brand_block").removeClass("brand_block_variant owl-carousel");
	$(".brand_block_variant_two").removeClass("brand_block_variant owl-carousel");
	$(".brand_block_variant_two").addClass("row");
}

function ChangeNewsMobile() {
	$(".news_block").addClass("carousel-phone owl-carousel");
	$(".news_block_three").addClass("carousel-phone owl-carousel");

}
function ChangeNewsAllDevice() {
	$(".news_block").removeClass("carousel-phone owl-carousel");
	$(".news_block_three").removeClass("carousel-phone owl-carousel");

}

// ChangeCoupon
function ChangeCouponMobile() {
	$( ".main_order_coupon" ).prependTo( ".main_order" );
}
function ChangeCouponAllDevice() {
	$( ".main_order_coupon" ).appendTo( ".main_order" );
}
// end ChangeCoupon

// ChangePrice
function ChangePriceMobile() {
	$( ".main_order_all_price" ).prependTo( ".main_order" );
}
function ChangePriceDevice() {
	$( ".main_order_coupon" ).appendTo( ".main_order" );
}
// end ChangePrice


// ChangePhoneMobile
function ChangePhoneMobile() {
	$( ".container_menu_mobile__phone_block" ).appendTo( ".header_mmenu__phone_block_hidden" );
}

function ChangePhoneDevice() {
	$( ".container_menu_mobile__phone_block" ).prependTo( ".header_mmenu__content_phone_and_basket" );
}
// end ChangePrice


// ChangePhoneMobile
function ChangePhoneMobileDetail() {
	$( ".product-detail-info-block-comment" ).prependTo( ".product-detail-photo-block" );
	$( ".product-detail-info-block-title" ).prependTo( ".product-detail-photo-block" );
	$(".product-detail-info-block-brand").prependTo( ".article-mobile-block" );
	$(".product-detail-share-block").appendTo(".product-detail-info-block-one-click-basket");
	//$(".slider-ditail-card").addClass("slider-product_variant owl-carousel");
}

function ChangePhoneDeviceDetail() {
	$( ".product-detail-info-block-comment" ).prependTo( ".detail-title-block" );
	$( ".product-detail-info-block-title" ).prependTo( ".detail-title-block" );
	$(".product-detail-info-block-brand").prependTo( "#right_detail_card" );
	$(".product-detail-share-block").prependTo( ".product-detail-share" );
	//$(".slider-ditail-card").removeClass("slider-product_variant owl-carousel");
}

function TransitionDetail() {
	$("#all_property").on("click",".block-basic-property-link", function (event) {
		event.preventDefault();
		var id  = $(this).attr('href'),
			top = $(id).offset().top;
		$('body,html').animate({scrollTop: top}, 1500);
	});
}

function showShares()
{
	$('#sharing-buttons').toggleClass('active');
}

function showModal(html) {
    var block = '<div class="wrap-popup-window">' +
        '<div class="modal-popup-bg" onclick="closeModal();">&nbsp;</div>' +
        '<div class="popup-window">' +
        '<div class="popup-close" onclick="closeModal();"></div>' +
        '<div class="popup-content">';
    block = block + html;
    block = block + '</div>'
    '</div>' +
    '</div>';
    $("body").append(block);
}

function closeModal() {
	BX.onCustomEvent('OnBasketChange');
	$('.wrap-popup-window').last().remove();
}

function allPhone() {
	if($(".header_top_block__phone__title span").is(".many_tels_wrapper")) {
		$(".header_top_block__phone__number").addClass("icons");
	}
}

function titleChangeMobile() {
	$( "div.personal_title_block" ).appendTo( ".sidebar" );
}

function titleChangeDevice() {
	$( "div.personal_title_block" ).appendTo( ".personal_block_component" );
}

function foundCheaper(siteDir, lid, name)
{
	$.ajax({
		url: siteDir + 'include/ajax/foundcheaper.php',
		type: 'POST',
		data: {
			'lid': lid,
			'name': name
		},
		success: function(html)
		{
			showModal(html);
		}
	});
}

function wantGift(siteDir, lid, name, url, img, price, oldPrice)
{
	$.ajax({
		url: siteDir + 'include/ajax/wantgift.php',
		type: 'POST',
		data: {
			'lid': lid,
			'name': name,
			'url': url,
			'img': img,
			'price': price,
			'oldPrice': oldPrice
		},
		success: function(html)
		{
			showModal(html);
		}
	});
}

function checkStock(siteDir, lid, name)
{
	$.ajax({
		url: siteDir + 'include/ajax/checkstock.php',
		type: 'POST',
		data: {
			'lid': lid,
			'name': name
		},
		success: function(html)
		{
			showModal(html);
		}
	});
}

function MobileBasket()
{
	let cntBasket = $('.basket-block.header_info_block__item .basket-block__link_main_basket .basket-block__link_basket_cal').html();
	let cntCompare = $('.basket-block.header_info_block__item .basket-block__link:eq(1) .basket-block__link_basket_cal').html();
	let cntFavorite = $('.basket-block.header_info_block__item .basket-block__link:eq(2) .basket-block__link_basket_cal').html();

	$('.container_menu_mobile__item_link:eq(1) .container_menu_mobile__item_link_col').html(cntCompare);
	$('.container_menu_mobile__item_link:eq(2) .container_menu_mobile__item_link_col').html(cntFavorite);
	$('.container_menu_mobile__item_link:eq(3) .container_menu_mobile__item_link_col').html(cntBasket);
	$('.header_mmenu__content_phone_and_basket .basket-block__link_basket_cal').html(cntBasket);
}


function quickView(url)
{

	let add = '&preview=Y';
	let location = window.location.href;
	if(location.indexOf('clear_cache=Y') !== false)
	{
		add+='&clear_cache=Y';
	}
	url += add;

	$.ajax({
		url: url,
		type: 'POST',
		data:{'sessid': BX.bitrix_sessid()},
		beforeSend: function(){
			BX.showWait();
		},
		success: function(html){
            showModal(html);
		},
		complete: function(){
			BX.closeWait();
		},
	});

}

function MainSelect() {
	$(".custom-select-block").each(function() {
		if(!$(this).parent('.custom-select-wrapper').find('div.custom-select-block').length)
		{
			var classes = $(this).attr("class"),
				id = $(this).attr("id"),
				name = $(this).attr("name");

			var name = $(this).find('option:selected').html();

			$(this).attr('placeholder', name);
			let placeholder = $(this).data('placeholder');
			if(placeholder === undefined){
				placeholder = $(this).attr("placeholder");
			}
			let option = $(this).find("option:selected");
			if(option !== undefined){
				placeholder = option.html();
			}
			var template = '<div class="' + classes + '">';
			template += '<span class="custom-select-trigger">' + placeholder + '</span>';
			template += '<div class="custom-options">';
			$(this).find("option").each(function ()
			{
				template += '<span class="custom-option ' + $(this).attr("class") + '" data-value="' + $(this).attr("value") + '">' + $(this).html() + '</span>';
			});
			template += '</div></div>';

			$(this).wrap('<div class="custom-select-wrapper"></div>');
			$(this).hide();
			$(this).after(template);
		}
	});
	$(".custom-option:first-of-type").hover(function() {
		$(this).parents(".custom-options").addClass("option-hover");
	}, function() {
		$(this).parents(".custom-options").removeClass("option-hover");
	});
	$(".custom-select-trigger").on("click", function(event) {
		$('html').one('click',function() {
			$(".custom-select-block").removeClass("opened");
		});
		$(this).parents(".custom-select-block").toggleClass("opened");
		event.stopPropagation();
	});
	$(".custom-option").on("click", function() {
		$(this).parents(".custom-options").find(".custom-option").removeClass("selection");
		$(this).addClass("selection");
		$(this).parents(".custom-select-block").removeClass("opened");
		$(this).parents(".custom-select-block").find(".custom-select-trigger").text($(this).text());
		$(this).parents(".custom-select-wrapper").find("select").val($(this).data("value"));
		$(this).parents(".custom-select-wrapper").find("select").trigger("change");
	});


}

function itemsSort() {
    const btnApply = Array.prototype.slice.call(document.querySelectorAll('.offer-properties-item-inner__item'));
    const btnReset = Array.prototype.slice.call(document.querySelectorAll('.offer-properties-item-btnBlock__btn[data-action="reset-sort"]'));
    btnApply.forEach(function (item) {
        item.addEventListener('click', function () {
            const parentBlock = getParent(this, 'index_blank-table-tbody');
            const groupPropsParent =  getParent(this, 'offer-properties-item-inner');
            if (groupPropsParent.querySelector('.active')) {
                groupPropsParent.classList.add('checked');
            } else {
                groupPropsParent.classList.remove('checked');
            }
            const countParams = Array.prototype.slice.call(parentBlock.querySelectorAll('.offer-properties-item-inner.checked')).length;
            const offerItems = Array.prototype.slice.call(parentBlock.querySelectorAll('.offer-item'));
            const propsOfferItem = offerItems.map(function (item) {
                return Array.prototype.slice.call(item.querySelectorAll('[data-propvalue]'));
            });
            const propsOfferValue = propsOfferItem.map(function (item) {
                return item.map(function (element) {
                    return _defineProperty({}, element.dataset.propname, element.dataset.propvalue);
                });
            });
            const activeProps = Array.prototype.slice.call(parentBlock.querySelectorAll('.offer-properties-item-inner'));
            const activePropsItem = activeProps.map(function (item) {
                return Array.prototype.slice.call(item.querySelectorAll('.active [data-value]'));
            });
            const activePropsValue = activePropsItem.map(function (item) {
                return item.map(function (element) {
                    return _defineProperty({}, element.dataset.name, element.dataset.value);
                });
            });

            function _defineProperty(obj, key, value) {
                if (key in obj) {
                    Object.defineProperty(obj, key,
                        {value: value, enumerable: true, configurable: true, writable: true});
                } else {
                    obj[key] = value;
                }
                return obj;
            }

            propsOfferValue.forEach(function (item, index) {
                let conformity = [];
                item.forEach(function (element) {

                    for (let key in element) {

                        activePropsValue.forEach(function (props) {

                            props.forEach(function (prop) {
                                if (element[key] === prop[key]) {
                                    conformity.push(index)
                                }
                            });
                        });
                    }
                });
                if (countParams === conformity.length) {
                    offerItems[index].classList.remove('hide');
                } else {
                    offerItems[index].classList.add('hide');
                }
            });
        });

    });
    btnReset.forEach(function (item) {
        item.addEventListener('click', function () {
            const parentBlock = getParent(this, 'index_blank-table-tbody');
            const activeProps = Array.prototype.slice.call(parentBlock.querySelectorAll('.offer-properties-item-inner__item.active'));
            const offerItems = Array.prototype.slice.call(parentBlock.querySelectorAll('.offer-item'));
            const groupPropsParent = Array.prototype.slice.call(parentBlock.querySelectorAll('.offer-properties-item-inner.checked'));
            offerItems.forEach(function (item) {
                item.classList.remove('hide');
            });
            activeProps.forEach(function (item) {
                item.classList.remove('active');
            });
            groupPropsParent.forEach(function (item) {
                item.classList.remove('checked');
            });
        })
    });
}

function getParent (item, className) {
    let parentItem = item;
    while (!parentItem.classList.contains(className) && (parentItem !== document.body)) {
        parentItem = parentItem.parentElement;
    }
    if(parentItem !== document.body) {
        return parentItem;
    }
}

$(function () {

    // let quantityInput = Array.prototype.slice.call(document.querySelectorAll(".form-control.touchspin-empty")),
    //     quantityBtn = Array.prototype.slice.call(document.querySelectorAll(".form-group button"));
    //
    // quantityBtn.forEach(function (el) {
    //     el.addEventListener("click", function () {
    //         setWidthInput(this.closest(".input-group").querySelector("input"));
    //     });
    // });
    //
    // quantityInput.forEach(function (el) {
    //     setWidthInput(el);
    //     el.addEventListener("input", function () {
    //         setWidthInput(this);
    //     });
    // });
    //
    // function setWidthInput(el) {
    //     el.style.width = el.value.length * 10 + "px";
    //     el.style.maxWidth = el.style.width;
    // }

});
