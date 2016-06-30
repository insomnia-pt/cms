var Script = function () {



//    sidebar dropdown menu
    //set parent active
    $('.sidebar-menu li > ul > li.active').parents('.sub-menu').addClass('active');
    ////

    jQuery('#sidebar .sub-menu > a').click(function () {
        var last = jQuery('.sub-menu.open', $('#sidebar'));
        last.removeClass("open");
        jQuery('.arrow', last).removeClass("open");
        jQuery('.sub', last).slideUp(200);
        var sub = jQuery(this).next();
        if (sub.is(":visible")) {
            jQuery('.arrow', jQuery(this)).removeClass("open");
            jQuery(this).parent().removeClass("open");
            sub.slideUp(200);
        } else {
            jQuery('.arrow', jQuery(this)).addClass("open");
            jQuery(this).parent().addClass("open");
            sub.slideDown(200);
        }
        var o = ($(this).offset());
        diff = 200 - o.top;
        if(diff>0)
            $("#sidebar").scrollTo("-="+Math.abs(diff),500);
        else
            $("#sidebar").scrollTo("+="+Math.abs(diff),500);
    });

//    sidebar toggle

    $.noty.defaults = {
            layout: 'topRight',
            theme: 'bootstrapTheme', // or 'relax'
            type: 'alert',
            dismissQueue: true, // If you want to use queue feature set this true
            template: '<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
            animation: {
                open: 'animated slideInDown', // or Animate.css class names like: 'animated bounceInLeft'
                close: 'animated slideOutUp', // or Animate.css class names like: 'animated bounceOutLeft'
                easing: 'swing',
                speed: 500 // opening & closing animation speed
            },
            timeout: 4000,
            force: false, // adds notification to the beginning of queue when set to true
            modal: false,
            maxVisible: 5, // you can set max visible notification for dismissQueue true option,
            killer: false, // for close all notifications before show
            closeWith: ['click','hover','backdrop'], // ['click', 'button', 'hover', 'backdrop'] // backdrop click will close all notifications
            callback: {
                onShow: function() {},
                afterShow: function() {},
                onClose: function() {},
                afterClose: function() {},
                onCloseClick: function() {},
            },
            buttons: false // an array of buttons
        };


    $(function() {
        function responsiveView() {
            var wSize = $(window).width();
            if (wSize <= 768) {
                $('#container').addClass('sidebar-close');
                $('#sidebar > ul').hide();
            }

            if (wSize > 768) {
                $('#container').removeClass('sidebar-close');
                $('#sidebar > ul').show();
            }
        }
        $(window).on('load', responsiveView);
        $(window).on('resize', responsiveView);
    });

    $('.icon-reorder').click(function () {
        if ($('#sidebar > ul').is(":visible") === true) {
            $('#main-content').css({
                'margin-left': '0px'
            });
            $('#sidebar').css({
                'margin-left': '-180px'
            });
            $('#sidebar > ul').hide();
            $("#container").addClass("sidebar-closed");
        } else {
            $('#main-content').css({
                'margin-left': '180px'
            });
            $('#sidebar > ul').show();
            $('#sidebar').css({
                'margin-left': '0'
            });
            $("#container").removeClass("sidebar-closed");
        }
    });

// custom scrollbar
$("#sidebar").niceScroll({styler:"fb",cursorcolor:"#da9f3b", cursorwidth: '3', cursorborderradius: '10px', background: '#404040', cursorborder: ''});
$("html").niceScroll({styler:"fb",cursorcolor:"#da9f3b", cursorwidth: '6', cursorborderradius: '10px', background: '#404040', cursorborder: '', zindex: '1000'});

// widget tools

    jQuery('.widget .tools .icon-chevron-down').click(function () {
        var el = jQuery(this).parents(".widget").children(".widget-body");
        if (jQuery(this).hasClass("icon-chevron-down")) {
            jQuery(this).removeClass("icon-chevron-down").addClass("icon-chevron-up");
            el.slideUp(200);
        } else {
            jQuery(this).removeClass("icon-chevron-up").addClass("icon-chevron-down");
            el.slideDown(200);
        }
    });

    jQuery('.widget .tools .icon-remove').click(function () {
        jQuery(this).parents(".widget").parent().remove();
    });

//    tool tips

    $('.tooltips').tooltip();

//    popovers

    $('.popovers').popover();



// custom bar chart

    if ($(".custom-bar-chart")) {
        $(".bar").each(function () {
            var i = $(this).find(".value").html();
            $(this).find(".value").html("");
            $(this).find(".value").animate({
                height: i
            }, 2000);
        });
    }


//custom select box

//    $(function(){
//
//        $('select.styled').customSelect();
//
//    });


}();

function convertToSlug(str) {
  str = str.replace(/^\s+|\s+$/g, ''); // trim
  str = str.toLowerCase();

  // remove accents, swap ñ for n, etc
  var from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;";
  var to   = "aaaaaeeeeeiiiiooooouuuunc------";
  for (var i=0, l=from.length ; i<l ; i++) {
    str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
  }

  str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
    .replace(/\s+/g, '-') // collapse whitespace and replace by -
    .replace(/-+/g, '-'); // collapse dashes

  return str;
}
