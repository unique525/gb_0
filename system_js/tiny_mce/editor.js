/*

 tinyMCE_GZ.init

 ({

 mode: "textareas",

 theme: "advanced",

 editor_selector : "mceEditor",

 editor_deselector: "inputbox",

 force_br_newlines: false,

 force_p_newlines:true,



 //plugins: "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",

 plugins: "pagebreak,table,advhr,advimage,advlink,inlinepopups,insertdatetime,media,searchreplace,contextmenu,paste,fullscreen,noneditable,visualchars,nonbreaking,wordcount,advlist,autosave",



 // Theme options

 theme_advanced_buttons1: "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,forecolor,formatselect,fontselect,fontsizeselect,cut,copy,paste,pastetext,pasteword,|,backcoloroutdent,indent,outdent",

 theme_advanced_buttons2: "undo,redo,|,link,unlink,anchor,image,cleanup,code,|,bullist,numlist,|,pagebreak,restoredraft,search,replace,hr,removeformat,visualaid,|,sub,sup,|,charmap,media,advhr,|,ltr,rtl,fullscreen",

 //theme_advanced_buttons1: "",

 //theme_advanced_buttons2: "",

 theme_advanced_buttons3: "",

 theme_advanced_buttons4: "",

 theme_advanced_toolbar_location: "top",

 theme_advanced_toolbar_align: "left",

 theme_advanced_path_location: "bottom",

 theme_advanced_resizing: true,

 // Enable translation mode

 translate_mode: true,

 disk_cache : true,

 language: "zh"

 });

 */


var cid = Request["cid"];
var uploadfiletype = Request["uploadfiletype"];

if(cid == undefined){
    cid = 0;
}
if(uploadfiletype == undefined){
    uploadfiletype = 1;
}


tinyMCE.init({

    // General options


    //页面加载时把所有textarea转换成编辑器.
    mode: "textareas",

    //主题
    theme: "advanced",

    //在mode为specific_textareas时转化class为editor_selector的textarea
    editor_selector : "mceEditor",

    //不转化成编辑器的元素
    editor_deselector: "inputbox",

    //在每个新行前插入<br />
    force_br_newlines: false,

    //在每个新行前插入<p>
    force_p_newlines:true,



    //plugins: "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",

    plugins: "pagebreak,table,advhr,advimage,advlink,inlinepopups,insertdatetime,media,searchreplace,contextmenu,paste,fullscreen,noneditable,visualchars,nonbreaking,wordcount,advlist,autosave,clearstyle,textindent",



    // Theme options

    theme_advanced_buttons1: "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,forecolor,formatselect,fontselect,fontsizeselect,cut,copy,paste,pastetext,pasteword,|,backcoloroutdent,indent,outdent,undo,redo",

    theme_advanced_buttons2: "bullist,numlist,|,pagebreak,restoredraft,search,replace,hr,removeformat,visualaid,|,sub,sup,|,charmap,media,advhr,|,ltr,rtl,anchor,image,cleanup,code,|,link,unlink,fullscreen",

    //theme_advanced_buttons1: "",

    //theme_advanced_buttons2: "",

    theme_advanced_buttons3: "tablecontrols,|,clearstyle,|,textindent",

    theme_advanced_buttons4: "",

    theme_advanced_toolbar_location: "top",

    theme_advanced_toolbar_align: "left",

    theme_advanced_path_location: "bottom",

    theme_advanced_resizing: true,


    //external_image_list_url : "js/image_list.js",
    documentchannelid : cid,
    uploadfiletype : uploadfiletype,
    // Enable translation mode


    translate_mode: true,
    convert_urls : false,
    relative_urls : false,
    remove_script_host : false,
    //media_strict: false,
    allow_script_urls: true,

    language: "zh-cn"

});






