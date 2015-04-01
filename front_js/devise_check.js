/**
 * Created by a525 on 15-3-16.
 */
function QueryString()
{
    var name,value,i;
    var str=location.href;
    var num=str.indexOf("?")
    str=str.substr(num+1);
    var arrtmp=str.split("&");
    for(i=0;i < arrtmp.length;i++){
        num=arrtmp[i].indexOf("=");
        if(num>0){
            name=arrtmp[i].substring(0,num);
            value=arrtmp[i].substr(num+1);
            this[name]=value;
        }
    }
}
var Request=new QueryString();



function checkDevice(){
    var stay=Request["stay"];
    if(stay<=0||stay==undefined){

        var devise=browserRedirect();

        var oriUrl=location.href.toLowerCase();
        var url=oriUrl.replace("_m.html",".html");
        url=url.replace("_p.html",".html");
        url=url.replace("_t.html",".html");
        switch(devise){
            case "PC":

                if(url!=oriUrl){
                    location.href=url;
                }
                break;
            case "Mobile":

                if(url.indexOf(".htm")<0){
                    //default
                    location.href = url + "/default_m.html"
                }


                url=url.replace(".html","_m.html");
                if(url!=oriUrl){
                    location.href=url;
                }
                break;
            case "Pad":

                if(url.indexOf(".htm")<0){
                    //default
                    location.href = url + "/default_p.html"
                }

                url=url.replace(".html","_p.html");
                if(url!=oriUrl){
                    location.href=url;
                }
                break;
            case "TV":

                if(url.indexOf(".htm")<0){
                    //default
                    location.href = url + "/default_t.html"
                }

                url=url.replace(".html","_t.html");
                if(url!=oriUrl){
                    location.href=url;
                }
                break;
            default :
                if(url!=oriUrl){
                    location.href=url;
                }
                break;
        }

    }



}

checkDevice();

    function browserRedirect() {
        var devise="PC";
        var sUserAgent = navigator.userAgent.toLowerCase();        var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";
        var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
        var bIsMidp = sUserAgent.match(/midp/i) == "midp";
        var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
        var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";
        var bIsAndroid = sUserAgent.match(/android/i) == "android";
        var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";
        var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";
        if (bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM) {
            //var width=window.screen.width;
            //var height=window.screen.height;
            //if(width>1000&&height>1000){
            //    devise="Pad";
            //}else{
                devise="Mobile";
            //}
        } else {
            devise="PC";
        }
        return devise;
    }




function SetATagForUrl(idString){

    var content = document.getElementById(idString);
    var str=content.innerHTML;
    var re = /(http:\/\/[\w.\/]+)(?![^<]+>)/gi;
    content.innerHTML=str.replace(re,"<a href='$1' target='_blank'>$1</a>");//http

    str=content.innerHTML;
    var re2 = /(https:\/\/[\w.\/]+)(?![^<]+>)/gi;
    content.innerHTML=str.replace(re2,"<a href='$1' target='_blank'>$1</a>");//https
}