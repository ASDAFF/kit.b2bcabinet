<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
$frame = $this->createFrame()->begin("");
?>
<?/*?>
<script src="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/site_files/plugins/fancybox2/lib/jquery.mousewheel-3.0.6.pack.js', true)?>" type="text/javascript"></script>
<script src="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/site_files/plugins/fancybox2/source/jquery.fancybox.js?v=2.1.5', true)?>" type="text/javascript"></script>
<script src="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/site_files/plugins/fancybox2/source/helpers/jquery.fancybox-buttons.js?v=1.0.5', true)?>" type="text/javascript"></script>
<script src="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/site_files/plugins/fancybox2/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7', true)?>" type="text/javascript"></script>
<script src="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/site_files/plugins/fancybox2/source/helpers/jquery.fancybox-media.js?v=1.0.6', true)?>" type="text/javascript"></script>
<link rel="stylesheet" data-template-style="true" type="text/css" href="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/site_files/plugins/fancybox2/source/jquery.fancybox.css?v=2.1.5', true)?>">
<link rel="stylesheet" data-template-style="true" type="text/css" href="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/site_files/plugins/fancybox2/source/helpers/jquery.fancybox-buttons.css?v=1.0.5', true)?>">
<link rel="stylesheet" data-template-style="true" type="text/css" href="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/site_files/plugins/fancybox2/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7', true)?>">
<?*/?>
<script type="text/javascript">
	$(document).off("click", ".quick_view<?=$arParams["RAND"]?>");
	$(document).on("click", ".quick_view<?=$arParams["RAND"]?>", function(e){
		e.preventDefault();
		var index = $(this).data("index");
		<?$e = 1;?>
		$.fancybox([
			<?foreach($arParams['DETAIL_PAGE_URL'] as $key => $url):
				if(strpos($url, "?")!==false)
					$url = $url."&preview=1";
				else
					$url = $url."?preview=1";
				?>

				{
					index: <?=$key?>,
					href: "<?=$url?>",
					type : 'ajax',
					ajax : {
					dataType: "html"
				}
				}<?if(count($arParams['ELEMENT_ID']) != $e):?>,<?endif;?>
				<?$e++;?>
				<?endforeach; ?>
			],
			{

				afterShow:function(){

					if($('.nabor-preview .nabor #nabor-scroll').length>0)
						$(".nabor-preview .nabor #nabor-scroll").tinyscrollbar({ axis: "x"});



			        if($(".giftmain").length) {
			            $(".giftmain").owlCarousel({
			            	nav:true,
			            	smartSpeed:400,
			            	dots:false,
			            	 navText:["", ""],
			            	 responsive:{
			            	        0:{
			            	            items:1
			            	        },
			            	        768:{
			            	            items:3
			            	        },
			            	    },
			            	    onInitialized:function(){

			                    }
			              });
			        }

       var CntVideoEl=$('.preview .js_slider_pic_small .item-video').length;
        var CntImgEl=$('.preview .js_slider_pic_small .item').length;
        var CntEl=CntVideoEl+CntImgEl;
        if(CntEl>4)
        {
        	if($('.preview .js_slider_pic_small').find('.owl-stage-outer').length>0)
        	{
				$owl = $('.preview .js_slider_pic_small');
				$owl.trigger('destroy.owl.carousel').removeClass('owl-carousel owl-loaded');
				if ( $(this).find('.item').parent().is('.owl-item') ) {
					$(this).find('.item').unwrap();
				}
				if ( $(this).find('.item-video').parent().is('.owl-item') ) {
					$(this).find('.item-video').unwrap();
				}
				if ( $(this).find('.item').parent().is('.owl-stage') ) {
					$(this).find('.item').unwrap();
				}
				if ( $(this).find('.item-video').parent().is('.owl-stage') ) {
					$(this).find('.item-video').unwrap();
				}
				$('.preview .js_slider_pic_small').html($('.preview .js_slider_pic_small .owl-stage-outer').html());

	$(this).find('.owl-controls').remove();

	   	$('.preview .js_slider_pic_small').owlCarousel({
        	nav:true,
        	smartSpeed:400,
        	 dots:false,
        	 navText:["", ""],
        	 items:4,
        	 loop:false,
          });

        	}

		}
					var ColorsHor=$("#SliderColors").attr('data-colors-hor');
					var ColorsVer=$("#SliderColors").attr('data-colors-ver');
					var ColorsCode=$("#SliderColors").attr('data-colors-code');
					var ColorsCount=$("#SliderColors").attr('data-colors-count');
					if(ColorsHor>0 && ColorsVer>0 && ColorsHor*ColorsVer<ColorsCount)
					{
						$(".detail_page_wrap.preview #offer_prop_"+ColorsCode).owlCarousel({
							nav: true,
							mouseDrag:true,
    						touchDrag:true,
    						pullDrag:true,
							responsive:{
						        0:{
						            items:ColorsHor
						        }
						    },
						    navText:["", ""]
						});
					}
					if($('.modification-preview .viewport').width()<$('.modification-preview .sizes').width())
					{
						$('.modification-preview .empty-bottom').show();
						$('.modification-preview .viewport').css('border-right','1px solid #d3d3d3');
						$(".modification-preview .sizes-col").tinyscrollbar({ axis: "x"});
					}
 		else
 		{
 			$(".modification-preview .scrollbar").hide();

 			var CntEmpty=Math.floor(($('.modification-preview .viewport').width()-$('.modification-preview .sizes').width())/$('.modification-preview .size').width());
 			for(var i=0;i<CntEmpty;++i)
				$(".modification-preview .size-row").append('<div class="col-sm-3 size"><div class="row size-row-inner size-row-inner-name"><div class="col-sm-24 size-item-row"></div></div><div class="row size-row-inner size-row-inner-cnt"><div class="col-sm-24 size-item-row-cnt"></div></div><div class="row size-row-inner size-row-inner-price"><div class="col-sm-24 size-item-row-price"></div></div></div>');

			NewWidth=Math.round($('.modification-preview .viewport').width()/$(".modification-preview .size-row:first .size").length);
			$('.modification-preview .size').width(NewWidth-1);
 		}

			var LeftHeight1=parseInt($("#left-under .description_content").height());
		var RightHeight1=parseInt($("#right-under .description_content").height());
		if(LeftHeight1<RightHeight1)
			$("#left-under .description_content").height(RightHeight1);
		else
			$("#right-under .description_content").height(LeftHeight1);
				},
				'index' : index,
				"mouseWheel" : false,
				"maxWidth" : 940,
				"minWidth" : 940,
				"padding" : 7,
				'openEffect'    : 'elastic',
				'closeEffect'    : 'elastic',
				"helpers": {
					"overlay": {
						"locked": false
					}
				}
			}

		);

		return false;
	})
</script>
<?$frame->end();?>