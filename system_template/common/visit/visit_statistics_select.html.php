<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    {common_head}
    <title>统计选择</title>
    <script type="text/javascript">
        var site_id = Request["site_id"];
        $(function(){
            $("#year").datepicker({
                dateFormat: 'yy',
                numberOfMonths:1,
                showButtonPanel: true
            });

            $("#month").datepicker({
                dateFormat: 'mm',
                numberOfMonths: 1,
                showButtonPanel: true
            });

            $("#day").datepicker({
                dateFormat: 'dd',
                numberOfMonths: 1,
                showButtonPanel: true
            });

            $("#submit_day").click(function(){
                var year = $("#year").val();
                var month = $("#month").val();
                var day = $("#day").val();

                if(year > 0 && month > 0 && day > 0){
                    window.location.href="/default.php?secu=manage&mod=visit&m=statistics_by_site&year="+year+"&month="+month+"&day="+day+"&site_id="+site_id+"&statistics_type=2";
                }else{
                    alert("请选择日期");
                }
            });
            $("#submit_month").click(function(){
                var year = $("#year").val();
                var month = $("#month").val();

                if(year > 0 && month > 0 ){
                    window.location.href="/default.php?secu=manage&mod=visit&m=statistics_by_site&year="+year+"&month="+month+"&site_id="+site_id+"&statistics_type=1";
                }else{
                    alert("请选择月份");
                }
            });
            $("#submit_year").click(function(){
                var year = $("#year").val();

                if(year > 0){
                    window.location.href="/default.php?secu=manage&mod=visit&m=statistics_by_site&year="+year+"&site_id="+site_id+"&statistics_type=0";
                }else{
                    alert("请选择年份");
                }
            });
        });
    </script>
</head>
<body>
 <div style="margin:300px auto;width:200px;height:50px">
    <input  id="year" value="" type="text" class="input_box" style="width:80px;"/>
    <span class="btn2" style="height:35px" id="submit_year">按年查询</span><br/><br/>
    <input  id="month" value="" type="text" class="input_box" style="width:80px;"/>
    <span class="btn2" style="height:35px" id="submit_month">按月查询</span><br/><br/>
    <input  id="day" value="" type="text" class="input_box" style="width:80px;"/>
    <span class="btn2" style="height:35px" id="submit_day">按日查询</span><br/><br/>
 </div>
</body>
</html>
