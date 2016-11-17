

//注册条款的展开和收回
$("#reg_term_show, #reg_term").on("click", function() {
  $("body").toggleClass( "reg_term_show" );
});


/** 弹出提示插件 开始 **/
/**
弹出提示
UPDATE: 2016/05/13
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
  
  if( (typeof options == "object") ) {
    
  } else {
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
    $("#poptips").addClass( "show" );
  
  if( !settings.btnOK && !settings.btnCancel ) {
      $( "body" ).on("click", "#poptips", function(){
        $(this).removeClass( "show" );
        settings.btnOKClick();
      });
  } else {
      $( "body" ).on("click", "#poptips .ok", function(){
        $("#poptips").removeClass( "show" );
        settings.btnOKClick();
      });
      $( "body" ).on("click", "#poptips .cancel", function(){
        $("#poptips").removeClass( "show" );
        settings.btnCancelClick();
      });
  }

}
/** 弹出提示插件 结束 **/
























