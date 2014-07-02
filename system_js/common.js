function QueryString()
{
    var name, value, i;
    var str = location.href;
    var num = str.indexOf("?")
    if (num >= 0) {
        str = str.substr(num + 1);
        var arrtmp = str.split("&");
        for (i = 0; i < arrtmp.length; i++) {
            num = arrtmp[i].indexOf("=");
            if (num > 0) {
                name = arrtmp[i].substring(0, num);
                value = arrtmp[i].substr(num + 1);
                this[name] = value;
            }
        }
    }
}
var Request = new QueryString();

var ns6 = document.getElementById && !document.all;

function trim(str) {
    return str.replace(/(^\s*)|(\s*$)/g, "");
}
function ltrim(str) {
    return str.replace(/(^\s*)/g, "");
}
function rtrim(str) {
    return str.replace(/(\s*$)/g, "");
}

$().ready(function() {
    var inputPrice = $(".input_price");
    inputPrice.keyup(function() {
        checkPrice(this);
    });
    inputPrice.blur(function() {
        checkPrice(this);
    });
    var inputNumber = $(".input_number");
    inputNumber.keyup(function() {
        checkNumber(this);
    });
    inputNumber.blur(function() {
        checkNumber(this);
    });
});

function getDate() {
    var d = new Date();
    var vYear = d.getFullYear();
    var vMon = d.getMonth() + 1;
    var vDay = d.getDate();
    var h = d.getHours();
    var m = d.getMinutes();
    var se = d.getSeconds();
    return vYear + "-" + (vMon < 10 ? "0" + vMon : vMon) + "-" + (vDay < 10 ? "0" + vDay : vDay) + " " + (h < 10 ? "0" + h : h) + ":" + (m < 10 ? "0" + m : m) + ":" + (se < 10 ? "0" + se : se);
}

/**
 * 时间对象的格式化
 * @param date 时间对象
 * @param format 时间格式字符串
 * @return string format 格式化后的时间对象字符串
 */
function formatDate(date,format) {
    var o = {
        "M+" : date.getMonth() + 1,
        "d+" : date.getDate(),
        "h+" : date.getHours(),
        "m+" : date.getMinutes(),
        "s+" : date.getSeconds(),
        "q+" : Math.floor((date.getMonth() + 3) / 3),
        "S" : date.getMilliseconds()
    }

    if (/(y+)/.test(format))
    {
        format = format.replace(RegExp.$1, (date.getFullYear() + "").substr(4
            - RegExp.$1.length));
    }

    for (var k in o)
    {
        if (new RegExp("(" + k + ")").test(format))
        {
            format = format.replace(RegExp.$1, RegExp.$1.length == 1
                ? o[k]
                : ("00" + o[k]).substr(("" + o[k]).length));
        }
    }
    return format;
}

function checkPrice(me) {
    var reg = /[a-z]/ig;
    me.value = me.value.replace(reg, '');
}
function checkNumber(me) {
    var reg = /\D/g;
    me.value = me.value.replace(reg, '');
}

function JsLoader() {
    this.load = function(url) {
        //获取所有的<script>标记
        var ss = document.getElementsByTagName("script");
        //判断指定的文件是否已经包含，如果已包含则触发onSuccess事件并返回
        for (var i = 0; i < ss.length; i++) {
            if (ss[i].src && ss[i].src.indexOf(url) != -1) {
                this.onSuccess();
                return;
            }
        }
        //创建script结点,并将其属性设为外联JavaScript文件
        var s = document.createElement("script");
        s.type = "text/javascript";
        s.src = url;
        //获取head结点，并将<script>插入到其中
        var head = document.getElementsByTagName("head")[0];
        head.appendChild(s);

        //获取自身的引用
        var self = this;
        //对于IE浏览器，使用onReadyStateChange事件判断是否载入成功
        //对于其他浏览器，onLoad
        s.onload = s.onReadyStateChange = function() {
            //在此函数中this指针指的是s结点对象，而不是JsLoader实例,
            //所以必须用self来调用onSuccess事件，下同。
            if (this.readyState && this.readyState == "loading")
                return;
            self.onSuccess();
        }
        s.onError = function() {
            head.removeChild(s);
            self.onFailure();
        }
    };
    //定义载入成功事件
    this.onSuccess = function() {
    };
    //定义失败事件
    this.onFailure = function() {
    };
}

function UrlEncode(string) {
    string = string.replace(/\r\n/g, "\n");
    var utftext = "";

    for (var n = 0; n < string.length; n++) {

        var c = string.charCodeAt(n);

        if (c < 128) {
            utftext += String.fromCharCode(c);
        }
        else if ((c > 127) && (c < 2048)) {
            utftext += String.fromCharCode((c >> 6) | 192);
            utftext += String.fromCharCode((c & 63) | 128);
        }
        else {
            utftext += String.fromCharCode((c >> 12) | 224);
            utftext += String.fromCharCode(((c >> 6) & 63) | 128);
            utftext += String.fromCharCode((c & 63) | 128);
        }

    }

    return utftext;
}

function UrlDecode(utftext) {
    var string = "";
    var i = 0;
    var c = c1 = c2 = 0;

    while (i < utftext.length) {

        c = utftext.charCodeAt(i);

        if (c < 128) {
            string += String.fromCharCode(c);
            i++;
        }
        else if ((c > 191) && (c < 224)) {
            c2 = utftext.charCodeAt(i + 1);
            string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
            i += 2;
        }
        else {
            c2 = utftext.charCodeAt(i + 1);
            c3 = utftext.charCodeAt(i + 2);
            string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
            i += 3;
        }

    }

    return string;
}


/**
 * 全文搜索替换
 */
String.prototype.replaceAll = function(s1, s2) {
    return this.replace(new RegExp(s1, "gm"), s2);
}

/**
 * 复制指定内容到剪贴板
 */
function copyToClipboard(txt) {
    if (window.clipboardData) {
        window.clipboardData.clearData();
        window.clipboardData.setData("Text", txt);
        alert("复制成功！您可以用 Ctrl + v 粘贴此内容");
    }
    else if (navigator.userAgent.indexOf("Opera") != -1) {
        window.location = txt;
    }
    else if (window.netscape) {
        try {
            netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
        }
        catch (e) {
            alert("被浏览器拒绝！请手动复制");
        }
        var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
        if (!clip)
            return;
        var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
        if (!trans)
            return;
        trans.addDataFlavor('text/unicode');
        var str = new Object();
        var len = new Object();
        var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
        var copytext = txt;
        str.data = copytext;
        trans.setTransferData("text/unicode", str, copytext.length * 2);
        var clipid = Components.interfaces.nsIClipboard;
        if (!clip) {
            return false;
        }
        clip.setData(trans, null, clipid.kGlobalClipboard);
        alert("复制成功！您可以用 Ctrl + v 粘贴此内容");
    }
}

function setcookie(name, value) {
    var Days = 1000;
    var exp = new Date();
    exp.setTime(exp.getTime() + Days * 24 * 60 * 60 * 1000);
    document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString() + ";path=/";
}
function getcookie(name) {
    var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
    if (arr != null)
        return unescape(arr[2]);
    return "";
}
function delCookie(name) {
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval = getCookie(name);
    if (cval != null)
        document.cookie = name + "=" + cval + ";expires=" + exp.toGMTString();
}

function loadcss(rootpath, defaultcss) {
    var css = getcookie("usercss");
    if (css != "") {
        applycss(css, rootpath);
    }
    else {
        css = defaultcss;
        applycss(css, rootpath);
    }

    $("#setfont14").css("font-size", "14px");
    $("#setfont14").css("cursor", "pointer");
    $("#setfont12").css("font-size", "12px");
    $("#setfont12").css("cursor", "pointer");

}
function setcss(css, rootpath) {
    setcookie("usercss", css);
    loadcss(rootpath, css);
}
function applycss(css, rootpath) {
    $("#css_font").attr("href", rootpath + "/system_images/" + css);
}



//div show box
function ShowBox(id) {
    document.getElementById(id).style.display = "block";
}
