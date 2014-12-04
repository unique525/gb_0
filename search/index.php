
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>站内搜索</title>
        <link href="/images/css1.css" rel="stylesheet" type="text/css" />
        <style type="text/css">
            /* global */
            body { font-size: 14px; font-family: tahoma; color: #444; line-height: 140%; }
            a { color: #04c; text-decoration: none; }
            a:hover { text-decoration: underline; }
            #headerr h1 { font-size: 24px; margin: 0 0 10px 0; }
            #headerr a, #headerr a:hover { text-decoration: none; color: #444; }
            /* form */
            form#q-form { margin: 0; }
            #q-input { overflow: hidden; zoom: 1; clear: both; }
            #q-input .text { 
                float: left; width: 333px;
                padding: 0 3px; line-height: 26px; 
                -moz-border-bottom-colors: none;
                -moz-border-image: none;
                -moz-border-left-colors: none;
                -moz-border-right-colors: none;
                -moz-border-top-colors: none;
                border-color: #9A9A9A #CDCDCD #CDCDCD #9A9A9A;
                border-style: solid; border-width: 1px;
                font: 16px arial; height: 22px;
                padding: 4px 7px; vertical-align: top;
                background: url("/images/spis_167a8734.png") no-repeat scroll 0 0 transparent;
            }
            #q-input .button { 
                float: left; font-size: 14px; margin-left: 10px;
                background: url("/images/spis_167a8734.png") repeat scroll 0 -35px #DDDDDD;
                border: 0 none; cursor: pointer; height: 32px; padding: 0; width: 95px;	
            }
            #q-input .tips { color: #aaa; font-size: 12px; }
            #q-options { overflow: hidden; zoom: 1; margin: 10px 0; font-size: 12px; clear: both; }
            #q-options h4 { font-size: 14px; float: left; margin: 0; }
            #q-options ul { float: left; margin: 0; padding: 0; }
            #q-options li { float: left; margin: 0 0 0 10px; padding: 0; list-style: none; }
            ul { float: left; margin: 0; padding: 0; }
            li { float: left; margin: 0 0 0 10px; padding: 0; list-style: none; }

        </style>
        <?php
        $isMobile = false;
        $isMobile = IsMobile();
        if ($isMobile) {
            header("Location: search.php");
        }


        function IsMobile() {
            global $_SERVER;
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $mobile_agents = Array("240x320", "acer", "acoon", "acs-", "abacho", "ahong", "airness", "alcatel", "amoi", "android", "anywhereyougo.com", "applewebkit/525", "applewebkit/532", "asus", "audio", "au-mic", "avantogo", "becker", "benq", "bilbo", "bird", "blackberry", "blazer", "bleu", "cdm-", "compal", "coolpad", "danger", "dbtel", "dopod", "elaine", "eric", "etouch", "fly ", "fly_", "fly-", "go.web", "goodaccess", "gradiente", "grundig", "haier", "hedy", "hitachi", "htc", "huawei", "hutchison", "inno", "ipad", "ipaq", "ipod", "jbrowser", "kddi", "kgt", "kwc", "lenovo", "lg ", "lg2", "lg3", "lg4", "lg5", "lg7", "lg8", "lg9", "lg-", "lge-", "lge9", "longcos", "maemo", "mercator", "meridian", "micromax", "midp", "mini", "mitsu", "mmm", "mmp", "mobi", "mot-", "moto", "nec-", "netfront", "newgen", "nexian", "nf-browser", "nintendo", "nitro", "nokia", "nook", "novarra", "obigo", "palm", "panasonic", "pantech", "philips", "phone", "pg-", "playstation", "pocket", "pt-", "qc-", "qtek", "rover", "sagem", "sama", "samu", "sanyo", "samsung", "sch-", "scooter", "sec-", "sendo", "sgh-", "sharp", "siemens", "sie-", "softbank", "sony", "spice", "sprint", "spv", "symbian", "tablet", "talkabout", "tcl-", "teleca", "telit", "tianyu", "tim-", "toshiba", "tsm", "up.browser", "utec", "utstar", "verykool", "virgin", "vk-", "voda", "voxtel", "vx", "wap", "wellco", "wig browser", "wii", "windows ce", "wireless", "xda", "xde", "zte");
            $is_mobile = false;
            foreach ($mobile_agents as $device) {
                if (stristr($user_agent, $device)) {
                    $is_mobile = true;
                    break;
                }
                return $is_mobile;
            }
        }
        ?>
    </head>
    <body>
        <div class="shell">
            <div id="content">
                <div style="margin:0px auto; width:520px; padding-top:85px;">
                    <form id="q-form" method="get" action="search.php">
                        <table width="520" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td colspan="2"><div id="headerr"  style="line-height:130%;"><h1  style="line-height:130%;text-align: left;">站内搜索</h1></div></td>
                            </tr>
                            <tr>
                                <td colspan="2" id="q-input"><label>
                                        <input class="text" type="text" name="q" size="40" title="输入任意关键词皆可搜索" value="" />	
                                        <input class="button" type="submit" value="  搜索!  " />
                                    </label></td>
                            </tr>
                            <tr>
                                <td width="324" height="40" >
                                    <div id="q-options">
                                        <h4>选项</h4>
                                        <ul>
                                            <li><input type="radio" name="f" value="s_title"  />标题</li>
                                            <li><input type="radio" name="f" value="_all" checked="checked"   />全文</li>
                                            <li><input type="checkbox" name="m" value="yes"  />模糊搜索</li>
                                            <li><input type="checkbox" name="syn" value="yes"  />同义词</li>
                                            <li>
				按
                                                <select name="s" size="1">
                                                    <option value="s_publish_date_DESC" selected="selected">按时间降序</option>
                                                    <option value="relevance">相关性</option>
                                                    <option value="s_publish_date" >按时间升序</option>
                                                </select>
				排序
                                            </li>
                                        </ul>
                                    </div></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>