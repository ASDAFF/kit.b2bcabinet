var insertFilter = false;
var reBindOnce = false;

function checkMobileFilter(mql) {

    if(typeof window.trackBarOptions !== 'undefined') {
        window.trackBarCurrentValues = {};
        for(var key in window.trackBarOptions) {
            window.trackBarCurrentValues[key] = {
                'leftValue': window['trackBar' + key].minInput.value,
                'rightValue': window['trackBar' + key].maxInput.value,
            }
        }
    }

    if (mql.matches) {
        if(!insertFilter)
        {
            $(".mobile_filter_form").html($(".bx_filter_wrapper").html());
            $(".bx_filter_wrapper .bx_filter").remove();
            insertFilter = true;
        }
        if(!reBindOnce)
        {
            reBindBtns();
            reBindOnce = true;
        }
	} else {
        if(insertFilter)
        {
            $(".bx_filter_wrapper").html($(".mobile_filter_form").html()).find(".bx_filter").removeAttr("style");
            $(".mobile_filter_form .bx_filter").remove();
            insertFilter = false;
        }
    }

    searchfieldRefresh();

    if(typeof window.trackBarOptions !== 'undefined') {
        for(var key in window.trackBarOptions) {
			window.trackBarOptions[key].curMinPrice = window.trackBarCurrentValues[key].leftValue;
            window.trackBarOptions[key].curMaxPrice = window.trackBarCurrentValues[key].rightValue;
            window['trackBar' + key] = new BX.Iblock.SmartFilter(window.trackBarOptions[key]);
            window['trackBar' + key].minInput.value = window.trackBarCurrentValues[key].leftValue;
			window['trackBar' + key].maxInput.value = window.trackBarCurrentValues[key].rightValue;
        }
    }
}

var mql = window.matchMedia("(max-width: 991px)");
mql.addListener(checkMobileFilter);


function reBindBtns() {
    $(".mobile_filter_form, .bx_filter_wrapper").on("click", "[name='del_filter']", function() {
        window.location.href = $(".bx_filter .bx_filter_popup_result .popup_result_btns a.del_filter").attr("href");
        return false;
    });
    $(".mobile_filter_form, .bx_filter_wrapper").on("click", "[name='set_filter']", function() {
        window.location.href = $(".bx_filter .bx_filter_popup_result .popup_result_btns a.set_filter").attr("href");
        return false;
    });
}


$(document).ready(function() {

    checkMobileFilter(mql);

    $(".mobile_filter_btn").click(function() {
        $(".mobile_filter_form .bx_filter .bx_filter_block_wrapper").css("display", "");
        $(".mobile_filter_form .bx_filter").show("slide", { direction: "left" });
        $(".mobile_filter_form .bx_filter").after("<div class='mobile_filter_overlay'></div>");
    });

    $(".mobile_filter_form").on("click", ".block_main_left_menu__title", function() {
        $(".mobile_filter_form .bx_filter").hide("slide", { direction: "left" });
        $(".mobile_filter_overlay").remove();
    });

    $(".mobile_filter_form").on("click", ".mobile_filter_overlay", function() {
        $(".mobile_filter_form .bx_filter").hide("slide", { direction: "left" });
        $(this).remove();
    });

    $(".mobile_filter_form").on("click", ".properties_block_title", function() {
        $(this).parent().hide("slide", { direction: "right" }, function() {
            $(this).find("span.color_value").remove();
            $(this).find(".properties_block_title").remove();
        });
    });

});
