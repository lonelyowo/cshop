/********
俱乐部脚本 2016-10-24 10:11
********/
//芝麻开门
console.log("%cGiant®%c GoGoGo！(｡・`ω´･)", "color:blue;font-weight:bold", "");
/********
俱乐部通用脚本 2016-10-10 11:42
********/

/** 浏览器环境
browser["b"] 浏览器
browser["v"] 版本
browser["m"] 是否移动端
**/
browser = (function(ua){
  var a=new Object();
  var b = {
    msie: /msie/.test(ua) && !/opera/.test(ua),
    opera: /opera/.test(ua),
    safari: /webkit/.test(ua) && !/chrome/.test(ua),
    firefox: /firefox/.test(ua),
    edge: /edge/.test(ua),
    trident: /trident/.test(ua),
    chrome: /chrome/.test(ua)
  };
  var vMark = "";
  for (var i in b) {
    if (b[i]) { vMark = i; break; }//"safari" == i ? "version" : i;
  }
  b.version = vMark && RegExp("(?:" + vMark + ")[\\/: ]([\\d.]+)").test(ua) ? RegExp.$1 : "0";
  b.ie = b.msie;
  b.ie6 = b.msie && parseInt(b.version, 10) == 6;
  b.ie7 = b.msie && parseInt(b.version, 10) == 7;
  b.ie8 = b.msie && parseInt(b.version, 10) == 8;
  
  //if(vMark == "trident") vMark = "ie11";
  
  a.b=vMark;
  a.v=b.version;
  a.m=/mobile/.test(ua);
  return a;
})(window.navigator.userAgent.toLowerCase());
/** 浏览器环境 结束 **/

//不同设备采用不同动作事件（算了，不用了）
//var click_event = browser["m"]? "touchstart" : "click";
var click_event = "click";




/**
快速闪现提示
UPDATE: 2016/09/19
Powered by Joyton & dline.com.cn
用法：
$.fn.winktips({
  con: "内容",
  delay: 1600 //存活时间，默认1600
});
简单用法：
$.fn.winktips("内容");

**/
(function($){
$.fn.winktips = function( options ) {
  var defaults = {
    con: "(•ᴗ•)و",
    delay: 1600
  };
  if( (typeof options != "object") ) {
    defaults.con = options;
  }
  var settings = $.extend({}, defaults, options);
  
  if( !$("#winktips").length ) {
    $("body").append("<div id=\"winktips\"></div>");
  }
  $("<div class=\"item\"><span>" + settings.con + "</span></div>").appendTo("#winktips").fadeIn(200).delay( settings.delay ).fadeOut(200, function(){
    $(this).remove();
  });
  
}})(jQuery);
/** 快速闪现提示插件 结束 **/

/**
弹出提示
UPDATE: 2016/10/12
Powered by Joyton & dline.com.cn
用法：
$.fn.poptips({
  title: "标题", //默认为空
  con: "内容",
  conCenter: false, //文本内容内容是否居中，默认居中
  btnOK: false, //是否显示确认按钮 默认显示
  btnCancel: false, //是否显示取消按钮 默认不显示
  btnOKText: "确认", //确认按钮的文案
  btnCancelText: "取消", //取消按钮的文案
  btnOKClick: function (){	//确认按钮点击事件（没有按钮时遵从此事件）
    console.log("你点了确认");
  },
  btnCancelClick: function (){	//取消按钮点击事件
    console.log("你点了取消");
  }
});
简单用法：
$.fn.poptips("内容");

**/
(function($){
$.fn.poptips = function( options ) {
  var defaults = {
    title: "",
    con: "无内容",
    conCenter: true,
    btnOK: true,
    btnCancel: false,
    btnOKText: "确认",
    btnCancelText: "取消",
    "btnOKClick": function (){},
    "btnCancelClick": function (){}
  };
  
  if( (typeof options != "object") ) {
    defaults.con = options;
  }
  
  var settings = $.extend({}, defaults, options);
  
  $("#poptips").remove();
  $("body").append("<div id=\"poptips\"><div class=\"poptips_box\">"
    +( settings.title ? "<div class=\"title\">" + settings.title + "</div>" : "" )
    + "<div class=\"con "
    +( settings.conCenter ? "center" : "" )
    +"\">" + settings.con 
    + "</div><div class=\"btn_group\">"
    +( settings.btnCancel ? "<a class=\"btn cancel\">" + settings.btnCancelText + "</a>" : "" )
    +( settings.btnOK ? "<a class=\"btn ok\">" + settings.btnOKText + "</a>" : "" )
    + "</div></div></div>");
    
  try {
    var scrollbarWidth = window.innerWidth - $(window).width();
    if( scrollbarWidth >= 20 || scrollbarWidth < 0 ) {
      scrollbarWidth = 0;
    }
  } catch(e) {
    var scrollbarWidth = 0;
  }

  $("body").addClass( "poptips_show" ).css( "padding-right", scrollbarWidth + "px" );
  
  if( $("#poptips .con").height() > $(window).height() - 100 ) {
    $("#poptips .con").css( "max-height", $(window).height() - 180 + "px" );
  }
  
  $("#poptips .poptips_box").css( "top", ($(window).height() - $("#poptips .poptips_box").height())/2 - 10 + "px" );

  var poptips_scrolltop = 0;
  var tmp_poptips_scrolltop = 0;

  $( "body" ).on({
    "touchstart.poptips": function(e){
      var touch = e.touches[0];
      poptips_scrolltop = $("#poptips .con").scrollTop();
      tmp_poptips_scrolltop = touch.pageY;
    },
    "touchmove.poptips": function(e){
      var e = e || window.event;
      e.preventDefault && e.preventDefault();
      var touch = e.touches[0];
      if( $(e.target).hasClass("con") ) {
        $("#poptips .con").scrollTop( poptips_scrolltop - ( touch.pageY - tmp_poptips_scrolltop ) );
      }
    },
    "touchend.poptips": function(e){
      if( ( poptips_scrolltop - $("#poptips .con").scrollTop() ) > 100 ) {
        $("#poptips .con").animate( {scrollTop: ( $("#poptips .con").scrollTop() - 20 )}, "normal", "swing" );
      } else if( ( $("#poptips .con").scrollTop() - poptips_scrolltop ) > 100 ) {
        $("#poptips .con").animate( {scrollTop: ( $("#poptips .con").scrollTop() + 20 )}, "normal", "swing" );
      }
    }
  });
  
  if( !settings.btnOK && !settings.btnCancel ) {
      $( "body" ).on("click.poptips", "#poptips", function(e){
        var e = e || window.event;
        e.stopPropagation && e.stopPropagation();
        $("body").removeClass( "poptips_show" ).css( "padding-right", "" );
        $("body").off(".poptips", "#poptips .ok")
                 .off(".poptips", "#poptips .cancel")
                 .off("touchstart.poptips")
                 .off("touchmove.poptips")
                 .off("touchend.poptips");
        settings.btnOKClick();
      });
  } else {
      $( "body" ).on("click.poptips", "#poptips .ok", function(e){
        var e = e || window.event;
        e.stopPropagation && e.stopPropagation();
        $("body").removeClass( "poptips_show" ).css( "padding-right", "" );
        $("body").off(".poptips", "#poptips .ok")
                 .off(".poptips", "#poptips .cancel")
                 .off("touchstart.poptips")
                 .off("touchmove.poptips")
                 .off("touchend.poptips");
        settings.btnOKClick();
      });
      $( "body" ).on("click.poptips", "#poptips .cancel", function(e){
        var e = e || window.event;
        e.stopPropagation && e.stopPropagation();
        $("body").removeClass( "poptips_show" ).css( "padding-right", "" );
        $("body").off(".poptips", "#poptips .ok")
                 .off(".poptips", "#poptips .cancel")
                 .off("touchstart.poptips")
                 .off("touchmove.poptips")
                 .off("touchend.poptips");
        settings.btnCancelClick();
      });
  }

}})(jQuery);
/** 弹出提示插件 结束 **/



/**图片按需懒加载
data-lazyimg="" 图片src
data-lazybgimg="" 背景图
**/
function lazy() {
  var lazyimg = 0;
  var lazybgimg = 0;
  
  var window_pageYOffset = window.pageYOffset?window.pageYOffset:document.documentElement.scrollTop;
  var window_innerHeight = window.innerHeight?window.innerHeight:document.documentElement.offsetHeight;

  $("img[data-lazyimg]").each( function( index, val ) {
    var valTop = $(val).offset().top;
    var valHeight = valTop + $(val).height();
    
      if( ( valTop >= window_pageYOffset && 
    valTop <= ( window_pageYOffset + window_innerHeight ) ) || ( valHeight >= window_pageYOffset && valHeight <= ( window_pageYOffset + window_innerHeight )  ) ){
        $(val).attr("src", $(val).attr("data-lazyimg"));
        $(val).removeAttr("data-lazyimg");
      }
    lazyimg++;
  });
  //console.log( lazyimg );
  
  
  $("[data-lazybgimg]").each( function( index, val ) {
    var valTop = $(val).offset().top;
    var valHeight = valTop + $(val).height();
    
      if( ( valTop >= window_pageYOffset && 
    valTop <= ( window_pageYOffset + window_innerHeight ) ) || ( valHeight >= window_pageYOffset && valHeight <= ( window_pageYOffset + window_innerHeight )  ) ){
        $(val).css("backgroundImage", "url(" + $(val).attr("data-lazybgimg") + ")");
        $(val).removeAttr("data-lazybgimg");
/* 缓存区载入图片不触发img.onload，故放弃
var img = new Image();
img.src = $(val).attr("data-lazybgimg");
img.onload = function(){
  console.log(lazybgimg);
  console.log( $(val).attr("data-lazybgimg") );
  $(val).css("backgroundImage", "url(" + $(val).attr("data-lazybgimg") + ")");
  $(val).removeAttr("data-lazybgimg");
};
*/
      }
    lazybgimg++;
    
  });
}
lazy();
$(window).on("scroll", function() {
  lazy();
});
/** 图片按需懒加载 结束 **/

/**
菜单排序，将当前项置顶
UPDATE: 2016/09/20
用法：
$("#xxx").menuSort();
**/
(function($){
$.fn.menuSort = function( options ) {
  return this.each(function(){
    $(this).find(".active").prependTo(this);
  });
}})(jQuery);
/** 菜单排序，将当前项置顶 结束 **/

/**
筛选区 单选开关
UPDATE: 2016/09/20
用法：
$(".tab_filter_item_toggle").tabFilterToggle(function (){});
**/
(function($){
$.fn.tabFilterToggle = function( afterFn ) {
  if( (typeof afterFn != "function") ) {
    afterFn = function(){};
  }
  return this.on("click", function(){
    $(this).toggleClass("active");
    if( $(this).hasClass("active") ) {
      $(this).find("input").val( $(this).find(".on").attr("rel") );
    } else {
      $(this).find("input").val( $(this).find(".off").attr("rel") );
    }
    afterFn();
  });
}})(jQuery);
/** 筛选区 单选开关 结束 **/

/**
筛选区 菜单
UPDATE: 2016/10/24
用法：
$(".tab_filter_item .dropdown_menu").tabFilterMenu(function (){});
**/
(function($){
$.fn.tabFilterMenu = function( afterFn ) {
  if( (typeof afterFn != "function") ) {
    afterFn = function (){};
  }
  return this.on("click", ".menu_item", function(){
    var tmp_is_change = 1;
    var $tmp_tab_filter_item = $(this).parents(".tab_filter_item");
    $tmp_tab_filter_item.find("input").val( $(this).attr("rel") );
    $tmp_tab_filter_item.find(".dropdown").text( $(this).text() );
    if( $(this).hasClass("active") ) tmp_is_change = 0;
    $(this).addClass( "active" ).siblings( ".menu_item" ).removeClass( "active" );
    $tmp_tab_filter_item.find(".dropdown_menu").hide();
    $("body").off("click");
    afterFn( tmp_is_change );
  });
}})(jQuery);
/** 筛选区 菜单 结束 **/


/**
表单提示
UPDATE: 2016/09/29
**/
(function($){
$.fn.checktips = function( options ) {
  var defaults = {
    target: "",//焦点
    parent: "",//添加 haserror 的元素
    tips: "",//form_help 中的文字
    winktips: "",//快速闪现的提示文字
    act: ""//hide 为隐藏
  };
  var settings = $.extend({}, defaults, options);
  
  if( settings.act == "hide" ) {
    settings.parent.removeClass( "haserror" ).find(".form_help").hide();
    return false;
  }
  
  if( settings.target ) {
    settings.target.focus();
  }
  //this.focus();
  
  if( settings.tips ) {
    settings.parent.addClass( "haserror" );
    if( !settings.parent.find(".form_help").length ) {
      settings.parent.append("<div class=\"form_help\">" + settings.tips + "</div>");
    } else {
      settings.parent.find(".form_help").html( settings.tips ).show();
    }
  }
  
  if( settings.winktips ) {
    $.fn.winktips( settings.winktips );
  }
 
}})(jQuery);

//移除特殊字符
function stripscript(s) { 
  var pattern = new RegExp("[%--`~!@#$^&*()=|{}':;',\\[\\].<>/?~！@#￥……&*（）——|{}【】‘；：”“'。，、？]")  //格式 RegExp("[在中间定义特殊过滤字符]")
  var rs = ""; 
  for (var i = 0; i < s.length; i++) { 
    rs = rs+s.substr(i, 1).replace(pattern, ''); 
  }
  return rs;
}

//获取URL参数
function getQueryString(name) {
  var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
  var r = window.location.search.substr(1).match(reg);
  //if (r != null) return unescape(r[2]); return null;
  if (r != null) return decodeURI(r[2]); return null;
}

//汉字转编码
function toUtf8(str) {
  var out, i, len, c;
  out = "";
  len = str.length;
  for(i = 0; i < len; i++) {
    c = str.charCodeAt(i);
    if ((c >= 0x0001) && (c <= 0x007F)) {
      out += str.charAt(i);
    } else if (c > 0x07FF) {
      out += String.fromCharCode(0xE0 | ((c >> 12) & 0x0F));
      out += String.fromCharCode(0x80 | ((c >>  6) & 0x3F));
      out += String.fromCharCode(0x80 | ((c >>  0) & 0x3F));
    } else {
      out += String.fromCharCode(0xC0 | ((c >>  6) & 0x1F));
      out += String.fromCharCode(0x80 | ((c >>  0) & 0x3F));
    }
  }
  return out;
}

/**
 * 用于把用utf16编码的字符转换成实体字符，以供后台存储
 * @param  {string} str 将要转换的字符串，其中含有utf16字符将被自动检出
 * @return {string}     转换后的字符串，utf16字符将被转换成&#xxxx;形式的实体字符
 */
function utf16toEntities(str) {
  var patt=/[\ud800-\udbff][\udc00-\udfff]/g; // 检测utf16字符正则
  str = str.replace(patt, function(char){
    var H, L, code;
    if (char.length===2) {
      H = char.charCodeAt(0); // 取出高位
      L = char.charCodeAt(1); // 取出低位
      code = (H - 0xD800) * 0x400 + 0x10000 + L - 0xDC00; // 转换算法
      return "&#" + code + ";";
    } else {
      return char;
    }
  });
  return str;
}


//验证身份证号码有效性
function isValidityIdCard( idCard ) {
  
  if ( idCard.length < 18 ) {
    return false;
  }
  var Wi = [ 7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2, 1 ];  // 加权因子
  var ValideCode = [ 1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2 ];  // 身份证验证位值.10代表X
  var sum = 0;  // 声明加权求和变量
  if (idCard[17].toLowerCase() == 'x') {
    var tmp_vcode = 10;  // 将最后位为x的验证码替换为10方便后续操作
  } else {
    var tmp_vcode = idCard[17];
  }

  for ( var i = 0; i < 17; i++) {
    sum += Wi[i] * idCard[i];  // 加权求和
  }
  valCodePosition = sum % 11;  // 得到验证码所位置   
  if (tmp_vcode == ValideCode[valCodePosition]) {
    return true;
  } else {
    return false;
  }
}

//通过身份证号获取生日性别等信息
function infoByIdCard( idCard ) {
  var info = [];
  info["year"] =  Math.floor(idCard.substring(6,10));
  info["month"] = Math.floor(idCard.substring(10,12));
  info["day"] = Math.floor(idCard.substring(12,14));
  info["sex"] = (idCard.substring(14,17)%2==0)?1:2;
  return info;
}

//通用下拉菜单
$(function(){
  $( "html" ).on( "click", ".dropdown", function(e){
    var tmp_this = this;
    var $tmp_this_menu = $(this).next( ".dropdown_menu" ).eq(0);
    //$(this).next( ".dropdown_menu" ).toggle();
    if( $tmp_this_menu.is( ":visible" ) ) {
      $tmp_this_menu.hide();
      
    } else {
      //$.fn.hasShadow();
      $tmp_this_menu.show();
      $("body").on("click.dropdown", function(e) {
        //if( $tmp_this_menu.is( ":visible" ) ) {
          var e = e || window.event;
          e.preventDefault && e.preventDefault();
          $tmp_this_menu.hide();
          $("body").off(".dropdown");
          return false;
        //}
      });
      var e = e || window.event;
      e.stopPropagation && e.stopPropagation();
      
      $tmp_this_menu.on( "click", function(e){//touchmove
        var e = e || window.event;
        e.stopPropagation && e.stopPropagation();
      });
    }
  });
});

//生成通用全屏黑幕（黑色遮罩）
(function($){
$.fn.hasShadow = function( options ) {
  if( options == "hide" ) {
    $("#shadow").fadeOut("fast");
    $("body").off(".hasShadow")
             .removeClass( "shadow_show" )
             .css("margin-top", $("body").data("margin-top"))
             .scrollTop( $("body").data("scrollTop") )
             .removeData("margin-top scrollTop");
  } else {
    if( !$("#shadow").length ) {
      $("body").append( "<div id=\"shadow\"></div>" );
    }
    $("#shadow").fadeIn("fast");
    $( "body" ).on("touchmove.hasShadow", "#shadow", function(e){//click
      return false;
    });
    if( !$("body").data("scrollTop") ) {
      $("body").data({"margin-top": $("body").css("margin-top"), "scrollTop": $("body").scrollTop()});
    }
    $("body").css("margin-top", ( $("body").data("margin-top").replace("px","") - $("body").data("scrollTop") ))
             .addClass( "shadow_show" );
  } 
}})(jQuery);


//通用页签切换
$(function(){
  $(".tab_nav_switch").on("click", "a", function(){
    //console.log( location.hash );
    if( $(this).data("for") != "" ){
      $(this).siblings( ".active" ).data( "scrolltop", $(window).scrollTop() ).removeClass( "active" );
      $(this).addClass( "active" );
      $( $(this).data("for") ).addClass( "active" ).siblings( ".tab_plate" ).removeClass( "active" );
      /*if( $(this).data("scrolltop") ) {
        $("html, body").animate( {scrollTop: $(this).data("scrolltop")}, "slow" );
      }*/
    }
    return false;
  });
});

//通用展开
$(function(){
  $( ".card_more" ).on("click", function(){
    $(this).parents(".folded").each(function(){
      $(this).removeClass("folded");
      return false;
    });
  });
});


//评论框相关
$(function(){
  //评论框展开
  $( ".comment_form_text" ).on("focus", function(){
    $(this).parents(".comment_form").removeClass("folded");
    $("html, body").animate( {scrollTop: $(this).offset().top - 100}, "slow" );
  });
  //评论点击回复按钮
  $( ".list" ).on("click", ".comment_item .btn_reply", function(){
    var $tmp_comment_form_text = $(this).parents(".card_comment").find(".comment_form_text");
    $tmp_comment_form_text.focus().val( "回复 " + $(this).parents(".comment_item").find(".name").text() + "："+ $tmp_comment_form_text.val() );
  });
});

//通用投诉举报
$(function(){
  $( ".btn_feedback_show" ).on("click", function(){
    $.fn.hasShadow();
    $("#feedback").show();
    $("#feedback").find(".origi_info_url .form_static").text( window.location.href );
    $("#feedback").find(".origi_info_url input").val( window.location.href );
    $("#feedback").find(".origi_info .form_static").text( $("#header_title").text() );
    $("#feedback").find(".origi_info input").val( $("#header_title").text() );
  });
  
  $( "body" ).on("click", "#feedback .bottombar_btn_cancel", function(){
    $("#feedback").hide();
    $.fn.hasShadow("hide");
  });
  
  
});


//通用分享
$(function(){
//console.log( $("#travelogue_main_top .richtext img").attr("src") );
//console.log( $("#club_main_top .clublogo").css("background-image").match(/.*url\("([^"]+)"\).*/i)[1] );
  $( ".btn_share_show" ).on("click", function(){
    $.fn.hasShadow();
    $("#share").show();
    
    //获取活动头图
    try{
      if( $(".main_top .cover").css("background-image").match(/.*url\("([^"]+)"\).*/i)[1] ) {
        $("#share .ds-share").data( "images", $(".main_top .cover").css("background-image").match(/.*url\("([^"]+)"\).*/i)[1] )
      }
    } catch(e) {}
    
    //获取相片图
    try{
      if( $(".main_top .photo_image img").attr("src") ) {
        $("#share .ds-share").data( "images", $(".main_top .photo_image img").attr("src") )
      }
    } catch(e) {}
    
    //获取相册图
    try{
      if( $("#photo_list_main .photo_item_box").css("background-image").match(/.*url\("([^"]+)"\).*/i)[1] ) {
        $("#share .ds-share").data( "images", $("#photo_list_main .photo_item_box").css("background-image").match(/.*url\("([^"]+)"\).*/i)[1] )
      }
    } catch(e) {}
    
    //游记
    try{
      if( $("#travelogue_main_top .richtext img:not(.face)").attr("src") ) {
        $("#share .ds-share").data( "images", $("#travelogue_main_top .richtext img:not(.face)").attr("src") )
      }
    } catch(e) {}
    
    //获取俱乐部标志
    try{
      if( $("#club_main_top .clublogo").css("background-image").match(/.*url\("([^"]+)"\).*/i)[1] ) {
        $("#share .ds-share").data( "images", $("#club_main_top .clublogo").css("background-image").match(/.*url\("([^"]+)"\).*/i)[1] )
      }
    } catch(e) {}
    

    $("#share .ds-share").data( "url", window.location.href.split("#")[0] )
      .data( "title", document.title )
      .data( "thread-key", document.title )
      //.data( "content", 1 )

      $("[name=share_thisurl]").val( window.location.href.split("#")[0] );
      
  });
  
  $( "body" ).on("click", "#share .bottombar_btn_cancel, #share .btn_cancel", function(){
    $("#share").hide();
    $.fn.hasShadow("hide");
  });

  //特殊处理微信按钮
  if(navigator.userAgent.toLowerCase().match(/MicroMessenger/i)=="micromessenger") {
    $("#share .inwechat_0").hide();
    $("#share .inwechat_1").show();
  }
  $( "body" ).on("click", "#share .inwechat_1", function(){
    $.fn.poptips("请在微信右上角菜单直接分享。");
  });
  
  
});




//滚动屏幕自动载入新内容
//例：scrollLoad( $("#event_list_main .main_more"), -100 );
function scrollLoad( target, offset ){
  offset = offset || 0;
  if( target.length ) {
    if( $(window).scrollTop() >= target.offset().top - $(window).height() - offset ) {
      target.click();
    }
  }
}



/**
上传并裁切图片
UPDATE: 2016/09/26
先这样，有空再改
用法：
$(".form_imgupload").cropimgupload(function (){});
**/
(function($){
$.fn.cropimgupload = function( options ) {
  var defaults = {
    file_data: "",
    max_side: 640,
    max_height: 1000,
    aspect_ratio: 0,
    btnOKClick: function (){},
    btnCancelClick: function (){}
  };
  var settings = $.extend({}, defaults, options);
  
  //弹出界面
  if( !this[0] ){
    cropimgupload_init();
  } else {
    this.on( "click", function(){
      cropimgupload_init();
    });
  }
  
  var jcrop_api;
  
  function cropimgupload_init() {
    
    
    var imgupload_x, imgupload_y, imgupload_w, imgupload_h;
    var imgupload_scale = 1;
    
    if( $( settings.file_data ).val() ) {
      $("#imgupload_preview").prop( "src", $( settings.file_data ).val() );
      $("#imgupload_file_data").val( $( settings.file_data ).val() );
    } else {
      $("#imgupload_preview").prop( "src", $("#imgupload_preview").data("blank") );
      $("#submit_imgupload").hide();
    }
  
    $.fn.hasShadow();
    $("#part_imgupload").show();
    try {
      jcrop_api.destroy();
    } catch(e) {}


    //获得图片原始尺寸
    function getImgNaturalDimensions(img, callback) {
      var nWidth, nHeight;
      if (img.naturalWidth) { // 现代浏览器
        nWidth = img.naturalWidth;
        nHeight = img.naturalHeight;
      } else { // IE6/7/8
        var imgae = new Image()
        image.src = img.src;
        image.onload = function() {
          callback(image.width, image.height);
        }
      }
      return [nWidth, nHeight];
    }
    

    
    
    function updateCoords(c) {

      $("#btn_crop_ok").off( ".click_crop_ok");
      $("#btn_crop_cancel").off( ".click_crop_cancel");
      //确认裁切
      $("#btn_crop_ok").on( "click.click_crop_ok", function(){

        imgupload_scale = getImgNaturalDimensions( document.getElementById("imgupload_preview") )[0] / $("#imgupload_preview").width();
        
        imgupload_x = c.x * imgupload_scale;
        imgupload_y = c.y * imgupload_scale;
        imgupload_w = c.w * imgupload_scale;
        imgupload_h = c.h * imgupload_scale;
        
        $.fn.getimgdata({
          img: $("#imgupload_file_data").val(),
          dir: 0,
          crop_x: imgupload_x,
          crop_y: imgupload_y,
          crop_w: imgupload_w,
          crop_h: imgupload_h,
          next: function(data){

            $("#imgupload_preview").prop( "src", data ).css({"width":"","height":""});
          
            $("#imgupload_file_data").val( data );
            
            try {
              jcrop_api.destroy();
            } catch(e) {}
            
            $("#imgupload_preview").Jcrop({
              aspectRatio: settings.aspect_ratio,
              onSelect: updateCoords,
              onRelease: function(){
                $("#btn_crop_ok").off( ".click_crop_ok");
              }
            },function(){
              jcrop_api = this;
            });
            
            //imgupload_natural_w = getImgNaturalDimensions( document.getElementById("imgupload_preview") )[0];
            
            //imgupload_natural_h = getImgNaturalDimensions( document.getElementById("imgupload_preview") )[1];

          }
        });
        
        //$(this).hide();
        $("#crop_bottombar").hide();
        //$("#btn_crop_ok").off( ".click_crop_ok");
      });
      $("#btn_crop_cancel").on( "click.click_crop_cancel", function(){
        try {
          jcrop_api.release();
        } catch(e) {}
        $("#crop_bottombar").hide();
      });
      
      $("#crop_bottombar").show();
      //$("#imgupload_bottombar").hide();
    };
    



    $("#imgupload_file").on("change.change_imgupload_file", function() {
      //var exp = /.jpg$|.gif$|.png$|.bmp$/;
      var expFilter = /^(image\/jpeg|image\/gif|image\/png)$/i;
      var file = this.files[0];
      var orientation = 0;
      
      if( !$(this).val() ){
        //$("#user_avatar_file_preview").css( "background-image", "url( ./carnival/img/photo_upload.png )" );
        return false;
      }
      
      //if( !exp.test(file.value) ){
      if( !expFilter.test(file.type) ){
        $.fn.poptips("图片不支持，仅支持JPG、GIF、PNG图片格式");
        return false;
      }
      
      //$("#user_avatar .user_profile_tip").html( "载入预览中..." );
      
      EXIF.getData( file, function(){
        //orientation = EXIF.getTag( this, "Orientation");
        var file_exif = EXIF.getAllTags(this);
        orientation = file_exif.Orientation;
      });
      

      var reader = new FileReader();
      
      reader.onload = function(e) {

        $.fn.getimgdata({
          img: this.result,
          dir: orientation,
          maxside: settings.max_side,
          maxheight: settings.max_height,
          next: function(data){

            $("#imgupload_preview").prop( "src", data ).css({"width":"","height":""});
          
            $("#imgupload_file_data").val( data );
            
            try {
              jcrop_api.destroy();
            } catch(e) {}

            
            
            $("#imgupload_preview").Jcrop({
              aspectRatio: settings.aspect_ratio,
              onSelect: updateCoords,
              onRelease: function(){
                $("#btn_crop_ok").off( ".click_crop_ok");
                $("#btn_crop_cancel").off( ".click_crop_cancel");
              }
            },function(){
              jcrop_api = this;
              
              //如果限制了比例则自动裁选
              if( settings.aspect_ratio ) {
                var tmp_select_initial = Math.min($("#imgupload_preview").width(), $("#imgupload_preview").height());
                jcrop_api.setSelect([ 0,0,tmp_select_initial,tmp_select_initial ]);
              }

            });
            
            imgupload_scale = getImgNaturalDimensions( document.getElementById("imgupload_preview") )[0] / $("#imgupload_preview").width();

          }
        });
        
        
      }
      
      reader.readAsDataURL( file );
      
      $("#submit_imgupload").show();

    });


    //取消插入图片
    $("#part_imgupload .bottombar_btn_cancel, #part_imgupload .btn_cancel").on( "click.imgupload_cancel", function(){
      $("#part_imgupload").hide();
      $.fn.hasShadow("hide");
      
      $("#crop_bottombar").hide();
      //$("#imgupload_bottombar").show();
      
      $("#btn_crop_ok").off( ".click_crop_ok");
      $("#btn_crop_cancel").off( ".click_crop_cancel");
      $("#imgupload_file").off(".change_imgupload_file").val("");
      $("#part_imgupload .bottombar_btn_cancel, #part_imgupload .btn_cancel").off( ".imgupload_cancel");
      $("#submit_imgupload").off( ".imgupload_ok");
      $("#imgupload_preview").css({"width":"","height":""});
      try {
        jcrop_api.destroy();
      } catch(e) {}
    });
    
    //确认插入图片
    $("#submit_imgupload").on( "click.imgupload_ok", function(){

      if( !$("#imgupload_file_data").val() ){
         $.fn.poptips("请选择需要上传的图片，仅支持JPG、GIF、PNG图片格式");
        return false;
      }
      if( settings.file_data ) {
        $( settings.file_data ).val( $("#imgupload_file_data").val() );
      }
      
      settings.btnOKClick( $("#imgupload_file_data").val() );
      
      $("#part_imgupload").hide();
      $.fn.hasShadow("hide");
      
      $("#crop_bottombar").hide();
      //$("#imgupload_bottombar").show();
      
      $("#btn_crop_ok").off( ".click_crop_ok");
      $("#btn_crop_cancel").off( ".click_crop_cancel");
      $("#imgupload_file").off(".change_imgupload_file").val("");
      $("#part_imgupload .bottombar_btn_cancel, #part_imgupload .btn_cancel").off( ".imgupload_cancel");
      $("#submit_imgupload").off( ".imgupload_ok").attr("style","");
      $("#imgupload_preview").css({"width":"","height":""});
      try {
        jcrop_api.destroy();
      } catch(e) {}
    });
    

    
    
  }


  return this;

}})(jQuery);
//alert( typeof origi_sports_items );
/** 上传并裁切图片 结束 **/

/**
选择标签
UPDATE: 2016/09/28
用法：
$(".tab_filter_item .dropdown_menu").tabFilterMenu(function (){});
**/
(function($){
$.fn.selectTag = function( options, act ) {
  var defaults = {
    tag_input: "",
    origi_box: "",
    curr_box: "",
    arr_origi: "",
    arr_curr: "",
    origi_item: "<a class=\"item_one [[active]]\" data-text=\"[[tag]]\">[[tag]]</a>",
    curr_item: "<a class=\"item_one\" data-text=\"[[tag]]\">[[tag]]</a>",
    origi_update_fn: function (){},
    curr_update_fn: function (){}
  };

  var settings = $.extend({}, defaults, options);

  //console.log( settings.arr_origi )
  //eval( "console.log( settings.arr_origi )" );
  
  //刷新原始待选区
  function origi_update() {
    //console.log(settings.arr_curr);
    var tmp_html = "";
    var tmp_active = "";
    var tmp_item_html = "";
    for ( i=0; i < settings.arr_origi.length; i++ ) {
      if( $.inArray( settings.arr_origi[i], settings.arr_curr ) != -1 ) {
        tmp_active = "active";
      } else {
        tmp_active = "";
      }
      tmp_item_html = settings.origi_item.replace(/\[\[tag\]\]/gi, settings.arr_origi[i]);
      tmp_item_html = tmp_item_html.replace(/\[\[active\]\]/gi, tmp_active);
      tmp_html += tmp_item_html;
    }
    $( settings.origi_box ).html( tmp_html );
  }
  
  //刷新当前选项区
  function curr_update() {
    var tmp_html = "";
    var tmp_item_html = "";
    for ( i=0; i < settings.arr_curr.length; i++ ) {
      tmp_item_html = settings.curr_item.replace(/\[\[tag\]\]/gi, settings.arr_curr[i]);
      tmp_html += tmp_item_html;
    }
    $( settings.curr_box ).html( tmp_html );
  }
  
  //组合最终字符串
  function mix_items() {
    var temp_text = "";
    for ( i=0; i < settings.arr_curr.length; i++ ) {
      temp_text += settings.arr_curr[i];
      if( i < settings.arr_curr.length - 1 ) {
        temp_text += ",";
      }
    }
    $( settings.tag_input ).val( temp_text );
  }
  
  if( act == "update" ) {
    origi_update();
    curr_update();
  }
  
  $( settings.origi_box ).on("click", "a", function(){
    

    
    //curr_sports_items.splice($.inArray('c',curr_sports_items),1);
    if( $.inArray( $(this).text(), settings.arr_curr ) == -1 ) {
      settings.arr_curr.push( $(this).text() );
    } else {
      //if( $.inArray( $(this).text(), settings.arr_curr ) != -1 ) {
        settings.arr_curr.splice($.inArray( $(this).text(), settings.arr_curr), 1);
      //}
    }

    mix_items();
    origi_update();
    curr_update();
  });
  
  
  return this;
  
}})(jQuery);
  

/** 选择标签 结束 **/


/**
下拉滑动
UPDATE: 2016/10/13
简单用法：
$("#xxx").pullsurprise();
用法：
$("#xxx").pullsurprise({
  offset: 0, //拖动多少
  ing: function(tmp_t_y){}, //拖动中
  end: function(){} //拖动结束
});
**/
(function($){
$.fn.pullsurprise = function( options ) {
  var defaults = {
    offset: 0,
    limit: 0,
    ing: function(){},
    end: function(){}
  };
  var settings = $.extend({}, defaults, options);
  
  if( this[0] ){
    var $target = this;
  } else {
    var $target = $("body");
  }
  var pullsurprise = {};
  
  if( settings.limit != 0 && settings.limit <= settings.offset ) {
    settings.limit = settings.offset + 1;
  }
  
  $target.on({
    "touchstart.pullsurprise": function(e){
      var touch = e.touches[0];
      pullsurprise.t_x = touch.pageX;
      pullsurprise.t_y = touch.pageY;
      $target.css( "transition", "" );
    },
    "touchmove.pullsurprise": function(e){
      var touch = e.touches[0];
      pullsurprise.tmp_t_x = touch.pageX - pullsurprise.t_x;
      pullsurprise.tmp_t_y = touch.pageY - pullsurprise.t_y;
      if( pullsurprise.tmp_t_y > 0 ) {
        if( $(document).scrollTop() == 0 ) {
          if( pullsurprise.tmp_t_y > pullsurprise.tmp_t_x ) {
            pullsurprise.tmp_ontop = 1;
          }
        }
      }
      if( pullsurprise.tmp_ontop == 1 ) {
        $(document).on("touchmove.pullsurprise_tmp", function(e){
          e.preventDefault();
        });
        settings.ing( ((pullsurprise.tmp_t_y>0)?pullsurprise.tmp_t_y:0) );
        if( settings.limit != 0 && pullsurprise.tmp_t_y > settings.limit ) {
          pullsurprise.tmp_t_y = settings.limit;
        }
        $target.css({ "transform": "translate3d(0,"+ ((pullsurprise.tmp_t_y>0)?pullsurprise.tmp_t_y:0) +"px,0)", "transition": "all 0s" });
      }
    },
    "touchend.pullsurprise": function(e){
      if( pullsurprise.tmp_ontop == 1 ) {
        if( pullsurprise.tmp_t_y > settings.offset ) {
          settings.end();
        }
        pullsurprise.tmp_t_y = 0;
        pullsurprise.tmp_ontop = 0;
        $target.css( { "transform": "", "transition": "all .3s" } );
        $(document).off("touchmove.pullsurprise_tmp");
      }
    }
  });
  return this;
}})(jQuery);

//顶部返回按钮
(function($){
$.extend({
  header_back: function( url, force ){
    if( history.length <= 1 || force ) {
      $("#header .back").prop( "href", url );
    }
  }
});
//  try {
    //header_back( manual_referrer.url, manual_referrer.force );
//  } catch(e) {}
})(jQuery);

/************************************************************************************/

$(function(){

  if( $(".main_top").hasClass("needfixed") ) {
    $("body").addClass("main_top_fixed");
    $(".main").css("padding-top", $(".main_top").height());
  }

  //载入指示器
  $("#loader").css({"-webkit-animation":"loader_hide .5s 1 both",
                    "animation":"loader_hide .5s 1 both"});
});


/******** 滚动相关 ********/
$(function(){
$(window).scroll(function() {
  var scrollTop = $(window).scrollTop();


});
});













