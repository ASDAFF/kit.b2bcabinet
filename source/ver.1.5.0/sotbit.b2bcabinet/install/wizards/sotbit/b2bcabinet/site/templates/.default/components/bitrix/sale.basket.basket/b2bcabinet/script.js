$(document).ready(
    function () {
        if ($('.row-under-modifications').length > 0) {
            var topPos = $('.row-under-modifications').offset().top;
            if (topPos > $(window).height()) {
                topPos = $(window).height();
            }
        }

        $(document).ready(setAddCartPosition);

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

        $(window).scroll(setAddCartPosition);

        let table = document.querySelector("#basket-root");

        $(document).ready(function () {
            document.querySelector(".row-under-modifications").style.width = table.clientWidth + "px";
        });

        window.addEventListener("resize", function () {

            let table = document.querySelector("#basket-root");

            document.querySelector(".row-under-modifications").style.width = table.clientWidth + "px";
        })
    }
);