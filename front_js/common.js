function QueryString() {
    var name, value, i;
    var str = location.href;
    var num = str.indexOf("?");
    if (num >= 0) {
        str = str.substr(num + 1);
        var arrTmp = str.split("&");
        for (i = 0; i < arrTmp.length; i++) {
            num = arrTmp[i].indexOf("=");
            if (num > 0) {
                name = arrTmp[i].substring(0, num);
                value = arrTmp[i].substr(num + 1);
                //过滤掉锚定，#号后不带入参数值
                var anchorNum = value.indexOf("#");
                if (anchorNum > 0)
                    this[name] = value.substring(0, anchorNum);
                else this[name] = value;
            }
        }
    }
}
var Request = new QueryString();


/**
 * 加入收藏
 */
function addFavorite(tableId, favoriteName, tableType, userFavoriteTag, siteId) {
    $.ajax({
        url: "/default.php?mod=user_favorite&a=async_add",
        type: "POST",
        data: {table_id: tableId, table_type: tableType, user_favorite_title: favoriteName, user_favorite_tag: userFavoriteTag, site_id: siteId},
        dataType: "jsonp",
        jsonp: "jsonpcallback",
        success: function (data) {
            if (data["result"] == -1) {
                //失败
                alert("收藏失败");
                location.replace(location);
            } else if (data["result"] == -2) {
                alert("已被收藏");
                location.replace(location);
            } else {
                //成功
                alert("收藏成功");
                location.replace(location);
            }
        }
    });
}

function formatPrice(price) {
    if (price != undefined) {
        if (parseFloat(price) > 0) {
            return parseFloat(price).toFixed(2);
        } else {
            return "0.00";
        }

    } else {
        return "";
    }

}

/**
 * 时间对象的格式化
 * @param date 时间对象
 * @param format 时间格式字符串
 * @return string format 格式化后的时间对象字符串
 */
function formatDate(date, format) {
    var o = {
        "M+": date.getMonth() + 1,
        "d+": date.getDate(),
        "H+": date.getHours(),
        "m+": date.getMinutes(),
        "s+": date.getSeconds(),
        "q+": Math.floor((date.getMonth() + 3) / 3),
        "S": date.getMilliseconds()
    };

    if (/(y+)/.test(format)) {
        format = format.replace(RegExp.$1, (date.getFullYear() + "").substr(4
            - RegExp.$1.length));
    }

    for (var k in o) {
        if (new RegExp("(" + k + ")").test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length == 1
                ? o[k]
                : ("00" + o[k]).substr(("" + o[k]).length));
        }
    }
    return format;
}

/**
 * 全文搜索替换
 */
String.prototype.replaceAll = function (s1, s2) {
    return this.replace(new RegExp(s1, "gm"), s2);
}

$(function () {
    $(".show_price").each(function () {
        $(this).text(formatPrice($(this).text()));
    });
    $(".show_date").each(function () {
        $(this).text(FormatDateString($(this).text()));
    });
});

function FormatDateString(date) {
    date = date.replace(/-/g, "/");
    var d = new Date(date);

    var year = d.getFullYear();
    var month = d.getMonth() + 1;
    if (month < 10) {
        month = '0' + month;
    }
    var day = d.getDate();
    if (day < 10) {
        day = '0' + day;
    }
    var hours = d.getHours();
    if (hours < 10) {
        hours = '0' + hours;
    }
    var minutes = d.getMinutes();
    if (minutes < 10) {
        minutes = '0' + minutes;
    }
    var seconds = d.getSeconds();
    if (seconds < 10) {
        seconds = '0' + seconds;
    }

    return year
        + "-" + month
        + "-" + day
        + " " + hours
        + ":" + minutes
        + ":" + seconds;
}

