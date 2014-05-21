<!DOCTYPE html>
<html>
    <head>
        <style type="text/css">
            body{font-family: "微软雅黑";font-size:14px}
            .image_div{width:240px;height:320px;border:1px #CCC solid;margin:2px;float:left;}
            .pic_div{width:100%;height:240px}
        </style>
        <script type="text/javascript">

            var $$ = function (id) {
                return "string" == typeof id ? document.getElementById(id) : id;
            };
            function Event(e){
                var oEvent = document.all ? window.event : e;
                if (document.all) {
                    if(oEvent.type == "mouseout") {
                        oEvent.relatedTarget = oEvent.toElement;
                    }else if(oEvent.type == "mouseover") {
                        oEvent.relatedTarget = oEvent.fromElement;
                    }
                }
                return oEvent;
            }
            function addEventHandler(oTarget, sEventType, fnHandler) {
                if (oTarget.addEventListener) {
                    oTarget.addEventListener(sEventType, fnHandler, false);
                } else if (oTarget.attachEvent) {
                    oTarget.attachEvent("on" + sEventType, fnHandler);
                } else {
                    oTarget["on" + sEventType] = fnHandler;
                }
            };
            var Class = {
                create: function() {
                    return function() {
                        this.initialize.apply(this, arguments);
                    }
                }
            }
            Object.extend = function(destination, source) {
                for (var property in source) {
                    destination[property] = source[property];
                }
                return destination;
            }

            var GlideView = Class.create();
            GlideView.prototype = {
                //容器对象 容器宽度 展示标签 展示宽度
                initialize: function(obj, iHeight, sTag, iMaxHeight, options) {
                    var oContainer = $$(obj), oThis=this, len = 0;
                    this.SetOptions(options);
                    this.Step = Math.abs(this.options.Step);
                    this.Time = Math.abs(this.options.Time);
                    this._list = oContainer.getElementsByTagName(sTag);
                    len = this._list.length;
                    this._count = len;
                    this._height = parseInt(iHeight / len);
                    this._height_max = parseInt(iMaxHeight);
                    this._height_min = parseInt((iHeight - this._height_max) / (len - 1));
                    this._timer = null;
                    this.Each(function(oList, oText, i){
                        oList._target = this._height * i;//自定义一个属性放目标left
                        oList.style.top = oList._target + "px";
                        oList.style.position = "absolute";
                        addEventHandler(oList, "mouseover", function(){ oThis.Set.call(oThis, i); });
                    })
                    //容器样式设置
                    oContainer.style.height = iHeight + "px";
                    oContainer.style.overflow = "hidden";
                    oContainer.style.position = "relative";
                    //移出容器时返回默认状态
                    addEventHandler(oContainer, "mouseout", function(e){
                        //变通防止执行oList的mouseout
                        var o = Event(e).relatedTarget;
                        if (oContainer.contains ? !oContainer.contains(o) : oContainer != o && !(oContainer.compareDocumentPosition(o) & 16)) oThis.Set.call(oThis, -1);
                    })
                },
                //设置默认属性
                SetOptions: function(options) {
                    this.options = {//默认值
                        Step:20,//滑动变化率
                        Time:3,//滑动延时
                        TextTag:"",//说明容器tag
                        TextHeight:	0//说明容器高度
                    };
                    Object.extend(this.options, options || {});
                },
                //相关设置
                Set: function(index) {
                    if (index < 0) {
                        //鼠标移出容器返回默认状态
                        this.Each(function(oList, oText, i){ oList._target = this._height * i; if(oText){ oText._target = this._height_text; } })
                    } else {
                        //鼠标移到某个滑动对象上
                        this.Each(function(oList, oText, i){
                            oList._target = (i <= index) ? this._height_min * i : this._height_min * (i - 1) + this._height_max;
                            if(oText){ oText._target = (i == index) ? 0 : this._height_text; }
                        })
                    }
                    this.Move();
                },
                //移动
                Move: function() {
                    clearTimeout(this._timer);
                    var bFinish = true;//是否全部到达目标地址
                    this.Each(function(oList, oText, i){
                        var iNow = parseInt(oList.style.top), iStep = this.GetStep(oList._target, iNow);
                        if (iStep != 0) { bFinish = false; oList.style.top = (iNow + iStep) + "px"; }
                    })
                    //未到达目标继续移动
                    if (!bFinish) { var oThis = this; this._timer = setTimeout(function(){ oThis.Move(); }, this.Time); }
                },
                //获取步长
                GetStep: function(iTarget, iNow) {
                    var iStep = (iTarget - iNow) / this.Step;
                    if (iStep == 0) return 0;
                    if (Math.abs(iStep) < 1) return (iStep > 0 ? 1 : -1);
                    return iStep;
                },
                Each:function(fun) {
                    for (var i = 0; i < this._count; i++)
                        fun.call(this, this._list[i], (this.Showtext ? this._text[i] : null), i);
                }
            };
        </script>
    </head>
    <body>
        <div style="width:99%;border: 1px #ccc solid;height:auto;margin: 0 auto">
            <div id="search_div" style="width:100%;height:100px;margin: 1px;border: 1px #CCC solid">
                <form id="search_form" action="" method="post">
                    类别:
                    <select name="albumtag" id="albumtag">
                        <option value="">全部</option>
                        <cscms id="useralbumtaglist" type="useralbumlist">
                            <item><![CDATA[
                                <option value="{f_useralbumtag_value}">{f_useralbumtag_value}</option>
                                ]]>
                            </item>
                        </cscms>
                    </select>
                    <span>作者:<input type="text" id="author" name="author" value=""/></span>
                    <span>作品名:<input type="text" id="albumname" name="albumname" value=""/></span>
                </form>
            </div>
            <div id="list_div" style="width:100%;height:auto;margin:1px;border: 1px #CCC solid">
                <div class="image_div">
                    <div class="pic_div">
                        <img src="" style="width:240px;height:320px"/>
                    </div>
                    <div class="op_div">

                    </div>
                </div>
                <div class="image_div">

                </div>
                <div class="image_div">

                </div>
                <div class="image_div">

                </div>
                <div class="image_div">

                </div>
                <div class="image_div">

                </div>
                <div class="image_div">

                </div>
                <div class="image_div">

                </div>
                <div class="image_div">

                </div>
                <div style="clear:left"></div>
            </div>
        </div>
        <SCRIPT>
            var gv = new GlideView("image_div", 320, "div", 320,"");
        </SCRIPT>
    </body>
</html>