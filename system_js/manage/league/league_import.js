var container=new Array();
var importTitle="";
$(function() {

    //拖动排序变化
    var sortGrid = $("#sort_grid");
    sortGrid.sortable();
    sortGrid.bind("sortstop", function(event, ui) {
    });
    sortGrid.disableSelection();


    $("#jsonStr").val('');
    $("#text").val('');

});

/**
 * 解析csv字符串
 *
 */
function DealRow(){
    var csv=$("#text").val();
    container=csv.split("\n");  //每一行是一条数据
    for(var i=0;i<container.length;++i){
        container[i]=container[i].split(",");  //字段由逗号隔开
    }


    var row=container[0];
    var into=$("#into").find("li");
    var length=row.length>into.length?row.length:into.length;// 少则置空  多出来的溢出

    var html="";
    for(var i=0;i<length;++i){  //生成字段比对html
        if(i<row.length){
            html+='<li class="" idvalue="'+i+'">'+row[i]+'</li>';
        }else{
            html+='<li class="" idvalue="'+i+'"></li>';
        }
    }
    $("#sort_grid").html(html);
    $("#text").hide();
    $("#pre_show").hide();
    $("#check").show();

}



function preView(domId, jsonObj,importTitle){


    var importTable=importTitle;
    $.each(jsonObj,function(i,v){
        importTable+='<tr class="grid_item">';
        $.each(v,function(j,vj){
            importTable+='<td class="spe_line2" style="padding:5px">'+vj+'</td>';
        });
        importTable+='</tr>';
    });
    importTable+='</table>';
    $('#'+domId).html(importTable);
}
