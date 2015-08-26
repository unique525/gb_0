/*

tinyMCE_GZ.init

({

    mode: "textareas",

    theme: "advanced",

    editor_selector : "mceEditor",

    editor_deselector: "inputbox",

    force_br_newlines: true,



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







tinyMCE.init({

    // General options

    mode: "textareas",

    theme: "advanced",

    editor_selector : "mceEditor",

    editor_deselector: "inputbox",

    force_br_newlines: true,

    

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

    // Enable translation mode

    translate_mode: true,

    language : "zh-cn"


});







