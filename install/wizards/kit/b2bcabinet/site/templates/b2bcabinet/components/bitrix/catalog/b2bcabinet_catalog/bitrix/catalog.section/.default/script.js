(function () {
    'use strict';

    if (!!window.JCCatalogSectionComponent)
        return;

    window.JCCatalogSectionComponent = function (params) {
        this.formPosting = false;
        this.siteId = params.siteId || '';
        this.ajaxId = params.ajaxId || '';
        this.template = params.template || '';
        this.componentPath = params.componentPath || '';
        this.parameters = params.parameters || '';

        if (params.navParams) {
            this.navParams = {
                NavNum: params.navParams.NavNum || 1,
                NavPageNomer: parseInt(params.navParams.NavPageNomer) || 1,
                NavPageCount: parseInt(params.navParams.NavPageCount) || 1
            };
        }

        this.bigData = params.bigData || {enabled: false};
        this.container = document.querySelector('[data-entity="' + params.container + '"]');
        this.showMoreButton = null;
        this.showMoreButtonMessage = null;

        if (this.bigData.enabled && BX.util.object_keys(this.bigData.rows).length > 0) {
            BX.cookie_prefix = this.bigData.js.cookiePrefix || '';
            BX.cookie_domain = this.bigData.js.cookieDomain || '';
            BX.current_server_time = this.bigData.js.serverTime;

            BX.ready(BX.delegate(this.bigDataLoad, this));
        }

        if (params.initiallyShowHeader) {
            BX.ready(BX.delegate(this.showHeader, this));
        }

        if (params.deferredLoad) {
            BX.ready(BX.delegate(this.deferredLoad, this));
        }

        if (params.lazyLoad) {
            this.showMoreButton = document.querySelector('[data-use="show-more-' + this.navParams.NavNum + '"]');
            this.showMoreButtonMessage = this.showMoreButton.innerHTML;
            BX.bind(this.showMoreButton, 'click', BX.proxy(this.showMore, this));
        }

        if (params.loadOnScroll) {
            BX.bind(window, 'scroll', BX.proxy(this.loadOnScroll, this));
        }
    };

    window.JCCatalogSectionComponent.prototype =
        {
            checkButton: function () {
                if (this.showMoreButton) {
                    if (this.navParams.NavPageNomer == this.navParams.NavPageCount) {
                        BX.remove(this.showMoreButton);
                    } else {
                        this.container.appendChild(this.showMoreButton);
                    }
                }
            },

            enableButton: function () {
                if (this.showMoreButton) {
                    BX.removeClass(this.showMoreButton, 'disabled');
                    this.showMoreButton.innerHTML = this.showMoreButtonMessage;
                }
            },

            disableButton: function () {
                if (this.showMoreButton) {
                    BX.addClass(this.showMoreButton, 'disabled');
                    this.showMoreButton.innerHTML = BX.message('BTN_MESSAGE_LAZY_LOAD_WAITER');
                }
            },

            loadOnScroll: function () {
                var scrollTop = BX.GetWindowScrollPos().scrollTop,
                    containerBottom = BX.pos(this.container).bottom;

                if (scrollTop + window.innerHeight > containerBottom) {
                    this.showMore();
                }
            },

            showMore: function () {
                if (this.navParams.NavPageNomer < this.navParams.NavPageCount) {
                    var data = {};
                    data['action'] = 'showMore';
                    data['PAGEN_' + this.navParams.NavNum] = this.navParams.NavPageNomer + 1;

                    if (!this.formPosting) {
                        this.formPosting = true;
                        this.disableButton();
                        this.sendRequest(data);
                    }
                }
            },

            bigDataLoad: function () {
                var url = 'https://analytics.bitrix.info/crecoms/v1_0/recoms.php',
                    data = BX.ajax.prepareData(this.bigData.params);

                if (data) {
                    url += (url.indexOf('?') !== -1 ? '&' : '?') + data;
                }

                var onReady = BX.delegate(function (result) {
                    this.sendRequest({
                        action: 'deferredLoad',
                        bigData: 'Y',
                        items: result && result.items || [],
                        rid: result && result.id,
                        count: this.bigData.count,
                        rowsRange: this.bigData.rowsRange,
                        shownIds: this.bigData.shownIds
                    });
                }, this);

                BX.ajax({
                    method: 'GET',
                    dataType: 'json',
                    url: url,
                    timeout: 3,
                    onsuccess: onReady,
                    onfailure: onReady
                });
            },

            deferredLoad: function () {
                this.sendRequest({action: 'deferredLoad'});
            },

            sendRequest: function (data) {
                var defaultData = {
                    siteId: this.siteId,
                    template: this.template,
                    parameters: this.parameters
                };

                if (this.ajaxId) {
                    defaultData.AJAX_ID = this.ajaxId;
                }

                BX.ajax({
                    url: this.componentPath + '/ajax.php' + (document.location.href.indexOf('clear_cache=Y') !== -1 ? '?clear_cache=Y' : ''),
                    method: 'POST',
                    dataType: 'json',
                    timeout: 60,
                    data: BX.merge(defaultData, data),
                    onsuccess: BX.delegate(function (result) {
                        if (!result || !result.JS)
                            return;

                        BX.ajax.processScripts(
                            BX.processHTML(result.JS).SCRIPT,
                            false,
                            BX.delegate(function () {
                                this.showAction(result, data);
                            }, this)
                        );
                    }, this)
                });
            },

            showAction: function (result, data) {
                if (!data)
                    return;

                switch (data.action) {
                    case 'showMore':
                        this.processShowMoreAction(result);
                        break;
                    case 'deferredLoad':
                        this.processDeferredLoadAction(result, data.bigData === 'Y');
                        break;
                }
            },

            processShowMoreAction: function (result) {
                this.formPosting = false;
                this.enableButton();

                if (result) {
                    this.navParams.NavPageNomer++;
                    this.processItems(result.items);
                    this.processPagination(result.pagination);
                    this.checkButton();
                }
            },

            processDeferredLoadAction: function (result, bigData) {
                if (!result)
                    return;

                var position = bigData ? this.bigData.rows : {};

                this.processItems(result.items, BX.util.array_keys(position));
            },

            processItems: function (itemsHtml, position) {
                if (!itemsHtml)
                    return;

                var processed = BX.processHTML(itemsHtml, false),
                    temporaryNode = BX.create('DIV');

                var items, k, origRows;

                temporaryNode.innerHTML = processed.HTML;
                items = temporaryNode.querySelectorAll('[data-entity="items-row"]');

                if (items.length) {
                    this.showHeader(true);

                    for (k in items) {
                        if (items.hasOwnProperty(k)) {
                            origRows = position ? this.container.querySelectorAll('[data-entity="items-row"]') : false;
                            items[k].style.opacity = 0;

                            if (origRows && BX.type.isDomNode(origRows[position[k]])) {
                                origRows[position[k]].parentNode.insertBefore(items[k], origRows[position[k]]);
                            } else {
                                this.container.appendChild(items[k]);
                            }
                        }
                    }

                    new BX.easing({
                        duration: 2000,
                        start: {opacity: 0},
                        finish: {opacity: 100},
                        transition: BX.easing.makeEaseOut(BX.easing.transitions.quad),
                        step: function (state) {
                            for (var k in items) {
                                if (items.hasOwnProperty(k)) {
                                    items[k].style.opacity = state.opacity / 100;
                                }
                            }
                        },
                        complete: function () {
                            for (var k in items) {
                                if (items.hasOwnProperty(k)) {
                                    items[k].removeAttribute('style');
                                }
                            }
                        }
                    }).animate();
                }

                BX.ajax.processScripts(processed.SCRIPT);
            },

            processPagination: function (paginationHtml) {
                if (!paginationHtml)
                    return;

                var pagination = document.querySelectorAll('[data-pagination-num="' + this.navParams.NavNum + '"]');
                for (var k in pagination) {
                    if (pagination.hasOwnProperty(k)) {
                        pagination[k].innerHTML = paginationHtml;
                    }
                }
            },

            showHeader: function (animate) {
                var parentNode = BX.findParent(this.container, {attr: {'data-entity': 'parent-container'}}),
                    header;

                if (parentNode && BX.type.isDomNode(parentNode)) {
                    header = parentNode.querySelector('[data-entity="header"]');

                    if (header && header.getAttribute('data-showed') != 'true') {
                        header.style.display = '';

                        if (animate) {
                            new BX.easing({
                                duration: 2000,
                                start: {opacity: 0},
                                finish: {opacity: 100},
                                transition: BX.easing.makeEaseOut(BX.easing.transitions.quad),
                                step: function (state) {
                                    header.style.opacity = state.opacity / 100;
                                },
                                complete: function () {
                                    header.removeAttribute('style');
                                    header.setAttribute('data-showed', 'true');
                                }
                            }).animate();
                        } else {
                            header.style.opacity = 100;
                        }
                    }
                }
            }
        };


    $(document).on("click touchstart", 'button.add_to_cart', this, AddToBasket);
    $(document).ready(function () {
        $(document).on("click touchstart", "#blank-export-in-excel", this, excelOut);
    });


    function AddToBasket() {

        document.querySelector(".modal_add_to_bascket-preloader").style.display = "flex";
        document.querySelector(".modal_add_to_bascket-on_success").style.display = "none";
        document.querySelector(".modal_add_to_bascket-on_error").style.display = "none";

        setTimeout(
            $.ajax({
                type: 'POST',
                url: site_path + 'include/ajax/b2b_buy.php',
                data: {'action': 'add'},

                success: function (data) {
                    data = JSON.parse(data);

                    if (data.STATUS === 'OK') {
                        var arSpin = $('.form-control.touchspin-empty');

                        $.each(arSpin, function (key, el) {
                            $(el).val(0);
                        });

                        $('.cart_header a span:last-child').html(data.BASKET_ITEM_QNT);

                        document.querySelector(".modal_add_to_bascket-on_success").style.display = "block";
                        document.querySelector(".modal_add_to_bascket-preloader").style.display = "none";

                    } else if (data.length === 0) {

                        document.querySelector(".modal_add_to_bascket-on_error").style.display = "block";
                        document.querySelector(".modal_add_to_bascket-preloader").style.display = "none";

                    }

                },

                error: function () {
                    document.querySelector(".modal_add_to_bascket-on_error").style.display = "block";
                    document.querySelector(".modal_add_to_bascket-preloader").style.display = "none";
                }
            }), 15);
    }

    function setExcelOutIcon(icon) {
        let iconContainer = document.querySelector(".export_excel_preloader > i");
        iconContainer.setAttribute("class", icon);
    }

    function excelOut() {
        setExcelOutIcon("icon-spinner2 spinner mr-2");

        setTimeout(function () {

            // BX.showWait();
            var file = '';

            $.ajax({
                type: 'POST',
                async: false,
                url: site_path + 'include/ajax/blank_excel_export.php',
                data: {
                    table_header: tableHeader,
                    filterProps: filterProps,
                    priceCodes: priceCodes,
                    file: file
                },
                success: function (data) {
                    if (data !== undefined && data !== '') {
                        try {
                            data = JSON.parse(data);
                        } catch (e) {

                        }
                    }

                    if (data.TYPE !== undefined) {
                        console.log(data.MESSAGE);
                    } else if (data !== undefined && data !== '') {
                        file = data;
                    }
                },
                complete: function () {
                    setExcelOutIcon("icon-upload mr-2");
                }
            });

            if (file !== undefined && file !== '') {
                var now = new Date();

                var dd = now.getDate();
                if (dd < 10) dd = '0' + dd;
                var mm = now.getMonth() + 1;
                if (mm < 10) mm = '0' + mm;
                var hh = now.getHours();
                if (hh < 10) hh = '0' + hh;
                var mimi = now.getMinutes();
                if (mimi < 10) mimi = '0' + mimi;
                var ss = now.getSeconds();
                if (ss < 10) ss = '0' + ss;

                var rand = 0 - 0.5 + Math.random() * (999999999 - 0 + 1)
                rand = Math.round(rand);

                var name = 'blank_' + now.getFullYear() + '_' + mm + '_' + dd + '_' + hh + '_' + mimi + '_' + ss + '_' + rand + '.xlsx';

                var link = document.createElement('a');
                link.setAttribute('href', file);
                link.setAttribute('download', name);
                var event = document.createEvent("MouseEvents");
                event.initMouseEvent(
                    "click", true, false, window, 0, 0, 0, 0, 0
                    , false, false, false, false, 0, null
                );
                link.dispatchEvent(event);
            }
        }, 15);

        // BX.closeWait();
    }

    function setExcelOutIconImport(icon) {
        let iconContainer = document.querySelectorAll("#mfi-mfiEXCEL_FILE-button > span > i");
        for (let i = 0; i < iconContainer.length; i++) {
            iconContainer[i].setAttribute("class", icon);
        }
    }

    BX.addCustomEvent('onUploadDone', function (file) {
        if (file['file_id'] !== '' && file['file_id'] !== undefined) {
            setExcelOutIconImport("icon-spinner2 spinner mr-2");

            setTimeout(function () {
                $.ajax({
                    type: 'POST',
                    async: false,
                    url: site_path + 'include/ajax/blank_excel_import.php',
                    data: {
                        file_id: file['file_id'],
                        quantity: tableHeader['QUANTITY']
                    },
                    success: function (data) {
                        if (data !== undefined && data !== 'null' && data !== '') {
                            var arProducts = JSON.parse(data);
                            var prodCount = Object.keys(arProducts).length;

                            if (arProducts.TYPE === undefined || arProducts.TYPE === 'null' || arProducts.TYPE === '') {
                                location.reload();
                            }

                        } else {
                            location.reload();
                        }
                    },
                    complete: function () {
                        setExcelOutIconImport("icon-upload mr-2");
                    },
                });
            }, 15);
        }
    });

})();

$(document).ready(function () {

    var touchspin_up = document.querySelectorAll(".bootstrap-touchspin-up"),
        touchspin_down = document.querySelectorAll(".bootstrap-touchspin-down");
        for(let i=0;i<touchspin_up.length;i++){
            if ('ontouchstart' in window) {
            touchspin_up[i].addEventListener("touchstart", function () {
                spinCount(this);
            });
            touchspin_down[i].addEventListener("touchstart", function () {
                spinCount(this);
            });
            }
            else{
            touchspin_up[i].addEventListener("click", function () {
                spinCount(this);
            });
            touchspin_down[i].addEventListener("click", function () {
                spinCount(this);
            });
            }
        }

    function spinCount(element) {
        var dataObj = $(element).offsetParent().find('.form-control.touchspin-empty');

        if (dataObj.length > 0) {
            var productId = $(dataObj).data('id');
            var productQnt = $(dataObj).val();

            var mainBlock = $(element).parentsUntil('tbody').filter('tr');
            var arProps = $(mainBlock).children('td.js-product-property');
            var arPrices = $(mainBlock).children('td.js-price');

            var productProps = {};
            var productPrices = {};

            $.each(arProps, function (key, value) {
                var tmpProps = {};

                tmpProps['CODE'] = $(value).data('propcode');
                tmpProps['VALUE'] = $(value).html();
                tmpProps['NAME'] = $(value).data('propname');
                productProps[key] = tmpProps;
            });


            $.each(arPrices, function (key, value) {
                var tmpPrice = {};

                if ($(value).html() !== '') {
                    tmpPrice['VALUE'] = $(value).data('price_value');
                    tmpPrice['NAME'] = $(value).data('price_name');
                    tmpPrice['CODE'] = $(value).data('price_code');
                    tmpPrice['CURRENCY'] = $(value).html().replace(/[\d\s]+/g, '');
                    productPrices[key] = tmpPrice;
                }
            });

            $.ajax({
                type: 'POST',
                url: site_path + 'include/ajax/blank_ids.php',
                data: {
                    'id': productId,
                    'qnt': productQnt,
                    'props': productProps,
                    'prices': productPrices,
                    'baseCurrency': baseCurrency,
                },
                success: function (data) {
                    var items = JSON.parse(data);
                    if (items) {
                        var totalCount = 0;
                        if (items['TOTAL_COUNT']) {
                            totalCount = items['TOTAL_COUNT'];
                        }
                        $('.index_blank-add_cart-number').html(totalCount);

                        var totalPrice = 0;
                        if (items['TOTAL_PRICE']) {
                            totalPrice = items['TOTAL_PRICE'];
                        }
                        $('.index_blank-add_cart-total').html(totalPrice);
                    }
                },
            });
        }
    }

    var spin = $('.form-control.touchspin-empty');

    $.each(spin, function (key, val) {
        val.addEventListener(
            'input',
            function () {
                if (!!this.timer) {
                    clearTimeout(this.timer);
                }
                this.timer = setTimeout(spinCount(this), 700);
            });
    });
});

window.addEventListener("load", function () {
    setEarsTopPosition();
    relocateTableHeader();
    window.addEventListener("scroll", setEarsTopPosition);
});

window.addEventListener("DOMContentLoaded", function () {
    setEventsIndexBlankTable();
    fixAddToCard();
});

function relocateTableHeader() {
    let tableHeader = document.getElementById("index_blank-thead");
    let tableHeaderTitles = tableHeader.querySelectorAll("th");
    let tableHeaderFixed = document.getElementById("index_blank-thead_fixed");

    for (let i = 0; i < tableHeaderTitles.length; i++) {
        let fixedHeaderChild = document.createElement("div");

        fixedHeaderChild.innerText = tableHeaderTitles[i].innerText;
        fixedHeaderChild.style.display = "inline-block";
        tableHeaderFixed.appendChild(fixedHeaderChild);
    }

    let displayTableHeader = {
        hide: function () {
            tableHeader.style.opacity = "0";
            tableHeader.style.visibility = "hidden";
        },
        show: function () {
            tableHeader.style.opacity = "1";
            tableHeader.style.visibility = "visible";
        }
    };

    displayTableHeader.hide();
    resizeTableFixed();

    window.addEventListener("resize", resizeTableFixed);

    function resizeTableFixed() {
        displayTableHeader.show();

        let tableHeaderFixedItems = tableHeaderFixed.querySelectorAll("div");

        for (let i = 0; i < tableHeaderTitles.length; i++) {
            tableHeaderFixedItems[i].style.width = tableHeaderTitles[i].offsetWidth + "px";
            tableHeaderFixedItems[i].style.height = tableHeaderTitles[i].offsetHeight + "px";
        }

        displayTableHeader.hide();
    }
}

function setEarsTopPosition() {
    let ears = document.querySelectorAll(".scroll-ears"),
        clientHeight = document.documentElement.clientHeight,
        anchorHeader = document.querySelector('.anchor_header'),
        tableHeight = document.querySelector(".table-responsive").clientHeight,
        anchorTop = 0;

    if (anchorHeader) {
        anchorTop = anchorHeader.getBoundingClientRect().top;
    }

    let earsTopPos = anchorTop < 0
        ? clientHeight / 2 - anchorTop
        : (clientHeight - anchorTop) / 2;

    earsTopPos = -anchorTop + clientHeight > tableHeight
        ? (tableHeight + anchorTop) / 2 - anchorTop
        : earsTopPos;

    let earsTopPosPercents = 100 * earsTopPos / tableHeight;

    if (earsTopPosPercents < 0) {
        earsTopPosPercents = 0;
    } else if (earsTopPosPercents > 100) {
        earsTopPosPercents = 100;
    }

    for (let i = 0; i < ears.length; i++) {
        ears[i].style.top = earsTopPosPercents + "%";
    }
}

function setEventsIndexBlankTable() {
    window.addEventListener("scroll", setAddCartPosition);
    window.addEventListener("load", setAddCartPosition);
    window.addEventListener("resize", function () {
        setAddCartPosition();
        showEars();
    });

    let datatableScroll = document.querySelector(".datatable-scroll"),
        leftEar = document.querySelector('.main-grid-ear-left'),
        rightEar = document.querySelector('.main-grid-ear-right'),
        idTimer,
        table = document.querySelector(".index_blank-table");

    if (table && document.querySelector(".index_blank-thead_fixed-wrapper")) {
        document.querySelector(".index_blank-thead_fixed-wrapper").style.width = table.clientWidth + "px";
    }

    leftEar.addEventListener("mouseover", function () {
        idTimer = setInterval(function () {
            scrollTable("left");
        }, 0.5);
    });

    leftEar.addEventListener("mouseout", function () {
        clearTimeout(idTimer);
    });

    rightEar.addEventListener("mouseover", function () {
        idTimer = setInterval(function () {
            scrollTable("right");
        }, 0.5);
    });

    rightEar.addEventListener("mouseout", function () {
        clearTimeout(idTimer);
    });

    var prfscr = new PerfectScrollbar('.datatable-scroll', {
        wheelSpeed: 0.5,
        wheelPropagation: true,
        minScrollbarLength: 20,
        suppressScrollY: true
    });

    let scrollEars = {
        showAll: function () {
            let ears = document.querySelectorAll(".index_blank .table-responsive .scroll-ears");

            for (let i = 0; i < ears.length; i++) {
                ears[i].style.display = "flex"
            }
        },

        hideAll: function () {
            let ears = document.querySelectorAll(".index_blank .table-responsive .scroll-ears");

            for (let i = 0; i < ears.length; i++) {
                ears[i].style.display = "none"
            }
        },

        hideLeft: function () {
            leftEar.style.opacity = "0";
            leftEar.style.visibility = "hidden";
        },

        hideRight: function () {
            rightEar.style.opacity = "0";
            rightEar.style.visibility = "hidden";
        },

        showLeft: function () {
            leftEar.style.opacity = "1";
            leftEar.style.visibility = "visible";
        },

        showRight: function () {
            rightEar.style.opacity = "1";
            rightEar.style.visibility = "visible";
        },
    };

    showEars();

    function showEars() {
        let tableScrollWidth = datatableScroll.scrollWidth,
            tableScrollWidthClientWidth = datatableScroll.clientWidth,
            tableScrollLeft = datatableScroll.scrollLeft;

        if (tableScrollWidth - tableScrollWidthClientWidth > 2) {
            scrollEars.showAll();

            if (tableScrollLeft === 0) {
                scrollEars.hideLeft();
            } else {
                scrollEars.showLeft();
            }

            if (tableScrollWidthClientWidth + tableScrollLeft === tableScrollWidth) {
                scrollEars.hideRight();
            } else {
                scrollEars.showRight();
            }

        } else {
            scrollEars.hideAll();
        }
    }

    function scrollTheadFixed() {
        let datatableScroll = document.querySelector(".datatable-scroll");
        let tableResponsive = document.querySelector(".table-responsive");

        datatableScroll.addEventListener("scroll", scrollHeader);
        tableResponsive.addEventListener("scroll", scrollHeader);
        scrollHeader();
        function scrollHeader() {
            let datatableScrollLeft = datatableScroll.scrollLeft;
            let tableResponsiveScrollLeft = tableResponsive.scrollLeft;
            let wrapperTheadFixed = document.querySelector("#index_blank-thead_fixed");
            let scrollLeft = Math.max(datatableScrollLeft, tableResponsiveScrollLeft);

            if (document.querySelector(".thead_fixed-wrapper-fixed")) {
                wrapperTheadFixed.style.left = "-" + scrollLeft + "px";
            }
        }
    }

    function setAddCartPosition() {
        let scrollTop = $(document).scrollTop(),
            anchorBottom = 0,
            pip = 0;

        if (document.querySelector(".anchor_header") && document.querySelector(".anchor")) {
            pip = $('.anchor_header').offset().top;
            anchorBottom = $('.anchor').offset().top;
        }

        let wrapperTheadFixed = document.querySelector(".index_blank-thead_fixed-wrapper");

        if (pip > scrollTop || anchorBottom < (scrollTop + 80)) {

            $('.index_blank-thead_fixed-wrapper').removeClass('thead_fixed-wrapper-fixed');
            wrapperTheadFixed.style.width = "auto";
            wrapperTheadFixed.style.height = "auto";
            wrapperTheadFixed.style.overflow = "";
            document.querySelector("#index_blank-thead_fixed").style.left = "0";

        } else {

            { 
                $('.index_blank-thead_fixed-wrapper').addClass('thead_fixed-wrapper-fixed');

                let table = document.querySelector(".index_blank-table");
                let tableHeaderFixed = document.getElementById("index_blank-thead_fixed");
                

                wrapperTheadFixed.style.width = table.clientWidth + "px";
                wrapperTheadFixed.style.height = tableHeaderFixed.clientHeight + "px";
                wrapperTheadFixed.style.overflow = "hidden";

                scrollTheadFixed();

            }

        }
    }

    function scrollTable(side) {
        let tableScrollWidth = datatableScroll.scrollWidth;
        let tableScrollWidthClientWidth = datatableScroll.clientWidth;

        if (tableScrollWidth - tableScrollWidthClientWidth > 2) {

            showEars();

            switch (side) {
                case 'left':
                    scrollTableLeft();
                    break;

                case 'right':
                    scrollTableRight();
                    break;
            }

        } else {

            showEars();
        }
    }

    function scrollTableLeft() {
        let tableScrollLeft = datatableScroll.scrollLeft;

        if (tableScrollLeft !== 0) {
            datatableScroll.scrollLeft = datatableScroll.scrollLeft - 5;
        }
        showEars();
    }

    function scrollTableRight() {
        let tableScrollWidth = datatableScroll.scrollWidth,
            tableScrollWidthClientWidth = datatableScroll.clientWidth,
            tableScrollLeft = datatableScroll.scrollLeft;

        if (tableScrollWidthClientWidth + tableScrollLeft < tableScrollWidth) {
            datatableScroll.scrollLeft = datatableScroll.scrollLeft + 5;
        }
        showEars();
    }
};

function fixAddToCard() {
    window.addEventListener("load", setAddCartPosition);
    window.addEventListener("scroll", setAddCartPosition);
    window.addEventListener("resize", function () {

        let table = document.querySelector(".datatable-scroll");

        document.querySelector(".row-under-modifications").style.width = table.clientWidth + "px";
        setAddCartPosition();
    });

    if ($('.row-under-modifications').length > 0) {
        var topPos = $('.row-under-modifications').offset().top;
        if (topPos > $(window).height()) {
            topPos = $(window).height();
        }
    }

    let table = document.querySelector(".datatable-scroll");
    document.querySelector(".row-under-modifications").style.width = table.clientWidth + "px";

    function setAddCartPosition() {
        let top = $(document).scrollTop(),
            pip = $('.anchor').offset().top,
            pip2 = $('.anchor_header').offset().top,
            height = $('.row-under-modifications').outerHeight();

        if ((pip < top + height + topPos) || (pip2 + 100 > (top + $(window).height()))) {
            $('.row-under-modifications').addClass('row-under-modifications-fixed');
            $('.row-under-modifications').removeClass('fixed-add-cart-animation');
        } else {
            if (top > pip - height) {
                $('.row-under-modifications').removeClass('row-under-modifications-fixed');
                $('.row-under-modifications').addClass('fixed-add-cart-animation');
            } else {
                $('.row-under-modifications').removeClass('row-under-modifications-fixed');
                $('.row-under-modifications').addClass('fixed-add-cart-animation');
            }
        }
    }

};
