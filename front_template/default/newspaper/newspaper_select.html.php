<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>长沙晚报</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="/system_js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.css" />
    <link type="text/css" href="/system_template/default/images/jquery_ui/jquery-ui.min.css" rel="stylesheet" />
    <script src="/system_js/jquery-1.9.1.min.js"></script>
    <script src="/system_js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui.min.js"></script>
    <style>
        body{background:#efefef;}
        img, object { max-width: 100%;}
        #datepicker {margin:0 auto;position:relative}
    </style>
    <script type="text/javascript">
        $(function() {

        });
        $(document).on("pageinit","#pageone",function(){
            $( "#datepicker" ).datepicker({
                changeMonth: true,
                changeYear: true,
                onSelect: function(dateText, inst) {
                    var today=new Date();
                    var dateArray=dateText.substr(0,10).split("-");
                    var date=new Date(dateArray[0],dateArray[1]-1,dateArray[2]);
                    if(date<=today){
                        window.location.href = '/default.php?mod=newspaper&a=gen_one&channel_id={ChannelId}&publish_date='+dateText;
                    }

                },
                dateFormat: 'yy-mm-dd'
            });
        });
    </script>

</head>
<body>

<div data-role="page" id="pageone">

    <div style="background:#ebebeb;">
        <div><img src="/image_02/2_05.jpg" width="100%" alt="" id="img_logo" /></div>
    </div>
    <div style="background:#ebebeb;">
        <div>
            <table cellpadding="0" cellspacing="0" width="100%" border="0">
                <tr>
                    <td style="text-align:center;"><a style="text-decoration:none;color:#333;" href="/" target="_blank">首页</a></td>
                    <td style="text-align:center;"><img src="/image_02/1.jpg" alt="" id="" /></td>
                    <td style="text-align:center;"><a style="text-decoration:none;color:#333;" href="/default.php?mod=newspaper&a=gen_select&channel_id={ChannelId}" target="_blank">往期回顾</a></td>
                    <td style="text-align:center;"><img src="/image_02/1.jpg" alt="" id="" /></td>
                    <td style="text-align:center;"><img src="/image_02/2.jpg" alt="" id="" /></td>
                </tr>
            </table>


        </div>
    </div>
    <div id="datepicker" style="margin:10px;width:100%"></div>
</div>
</body>
</html>