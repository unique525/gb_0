<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>长沙晚报</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        $(document).on("pageinit","#pageone",function(){

        });
    </script>
</head>
<body>

<div data-role="page" id="pageone">
    <div id="datepicker"></div>
</div>

</body>
</html>