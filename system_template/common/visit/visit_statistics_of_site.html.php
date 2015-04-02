<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    {common_head}
    <title>Highcharts Example</title>

    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <style type="text/css">
        ${demo.css}
    </style>
    <script type="text/javascript">

        $(function () {
            $('#container').highcharts({
                title: {
                    text: '{title}',
                    x: -20 //center
                },
                xAxis: {
                    categories: {categories}
                },
                yAxis: {
                    title: {
                        text: '数量'
                    },
                    plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
                },
                tooltip: {
                    valueSuffix: ''
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    borderWidth: 0
                },
                series: [{data}]
            });

            $('#container2').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                title: {
                    text: '来源统计'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            color: '#000000',
                            connectorColor: '#000000',
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Browser share',
                    data: [
                        ['外部链接',   {outer_link}],
                        ['内部链接',   {inner_link}],
                        ['搜索引擎',   {search_link}]
                    ]
                }]
            });
        });

    </script>
</head>
<body>
<script src="/system_js/high_chart/highcharts.js"></script>
<script src="/system_js/high_chart/modules/exporting.js"></script>
<div class="btn2" style="width:50px; margin-left: 5px" onclick="window.history.go(-1);">返&nbsp;&nbsp;&nbsp;&nbsp;回</div>
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<div id="container2" style="min-width:700px;height:400px;"></div>

<div>
    PV总值：{TotalPVCount}     UV总值：{TotalUVCount}       IP总值：{TotalIPCount}
    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
        <tr class="grid_title2">
            <td style="text-align: center">栏目</td>
            <td style="width: 100px">PV</td>
            <td style="width: 100px">UV</td>
            <td style="width: 100px">IP</td>
            <td>栏目统计</td>
        </tr>
        <icms id="visit_count_of_channel">
            <item><![CDATA[
                <tr class="grid_item2">
                    <td style="text-align: center">{f_ChannelName}</td>
                    <td style="width: 100px">{f_PV}</td>
                    <td style="width: 100px">{f_UV}</td>
                    <td style="width: 100px">{f_IP}</td>
                    <td><a href="/default.php?secu=manage&mod=visit&m=statistics_by_channel&channel_id={f_ChannelId}&statistics_type={statistics_type}&year={year}&month={month}&day={day}">栏目统计</a></td>
                </tr>
                ]]></item>
        </icms>
    </table>
</div>
</body>
</html>
