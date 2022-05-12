<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://www.amcharts.com/lib/amcharts.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>

    <style>

        @charset "UTF-8";
        @import url(https: //fonts.googleapis.com/css?family=Open+Sans:300, 400, 700);
        body {
            font-family: 'Open Sans', sans-serif;
            font-weight: 300;
            line-height: 1.42em;
            color: #a7a1ae;
            background-color: #1f2739;
        }
        h1 {
            font-size: 3em;
            font-weight: 300;
            line-height: 1em;
            text-align: center;
            color: #4dc3fa;
        }
        h2 {
            font-size: 1em;
            font-weight: 300;
            text-align: center;
            display: block;
            line-height: 1em;
            padding-bottom: 2em;
            color: #fb667a;
        }
        h2 a {
            font-weight: 700;
            text-transform: uppercase;
            color: #fb667a;
            text-decoration: none;
        }
        .blue {
            color: #185875;
        }
        .yellow {
            color: #fff842;
        }
        .t-container th h1 {
            font-weight: bold;
            font-size: 1em;
            text-align: left;
            color: #185875;
        }
        .t-container th {
            position: relative;
        }
        .t-container td {
            font-weight: normal;
            font-size: 1em;
            -webkit-box-shadow: 0 2px 2px -2px #0e1119;
            -moz-box-shadow: 0 2px 2px -2px #0e1119;
            box-shadow: 0 2px 2px -2px #0e1119;
            cursor: pointer;
        }
        .t-container {
            text-align: left;
            overflow: hidden;
            width: 80%;
            margin: 0 auto;
            display: table;
            padding: 0 0 8em 0;
        }
        .t-container td, .t-container th {
            padding-bottom: 2%;
            padding-top: 2%;
            padding-left: 10px;
            word-wrap: break-word;
        }
        .t-container tr:nth-child(odd) {
            background-color: #323c50;
        }
        .t-container tr:nth-child(even) {
            background-color: #2c3446;
        }
        .t-container th {
            background-color: #1f2739;
        }
        .t-container td:first-child {
            color: #fb667a;
        }

        @media(max-width:800px) {
            .t-container td: nth-child(4), .t-container th:nth-child(4) {
                                               display: none;
                                           }
        }
        text {
             fill: white !important;
             font-family: 'Open Sans', sans-serif !important;
         }
        #chartdiv {
            margin: -.5em auto;
            text-align: center;
            width: 90%;
            height: 390px;
        }
        rect[fill="#FFFFFF"] {
            display: none;
        }
        .pagination li a, .pagination li span {
            position: relative;
            display: block;
            padding: .5rem .75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #007bff;
            background-color: #fff;
            border: 1px solid #dee2e6;
        }
        .active span {
            z-index: 3 !important;
            color: #fff !important;
            background-color: #007bff !important;
            border-color: #007bff !important;
        }
        nav {
            display: flex;
            justify-content: center;
        }

        div[data-tippy-root] {
            word-wrap: break-word !important;
        }

    </style>
</head>
<body>
<h1><span class="blue"></span >Top 20<span class="blue"></span></h1>

<div id="chartdiv"></div>
<table class="t-container">
    <thead>
    <tr>
        <th><h1>query</h1></th>
        <th><h1 id="myButton">bindings</h1></th>
        <th><h1 data-tippy="test" title="ajdkf">time</h1></th>
        <th><h1>queried_at</h1></th>
        <th><h1>executed_in</h1></th>
        <th><h1>connection</h1></th>
        <th><h1>database</h1></th>
        <th><h1>host</h1></th>
        <th><h1>user</h1></th>
    </tr>
    </thead>
    <tbody>

    @php($str = \Illuminate\Support\Str::class)

    @foreach($queries as $query)
        <tr>
            <td data-title="{!! str_replace('"', '&quot;', $query['query']) !!}">

                {!! $str::limit($query['query'], 50) !!}

            </td>
            <td data-title="{!! '[' . $query['bindings'] .']' !!}">

                {!! $str::limit('[' . $query['bindings'] .']', 15) !!}

            </td>
            <td >{!! $query['time'] !!}</td>
            <td>{!! $query['queried_at'] !!}</td>
            <td data-title="{!! $query['executed_in'] !!}">{!! $str::limit($query['executed_in'], 50) !!}</td>
            <td>{!! $query['connection'] !!}</td>
            <td>{!! $query['database'] !!}</td>
            <td>{!! $query['host'] !!}</td>
            <td>{!! $query['user'] !!}</td>
        </tr>
    @endforeach

    </tbody>
</table>

<div class="mt-4 mb-4">
    {!! $queries->links() !!}
</div>

</body>
<script>

  let chart;
  let legend;
  let chartData = {!! json_encode($pieChartData) !!};

  chartData = chartData.map((item) => {
    return {
      query: item.query,
      value: item.count / {!! $total !!}
    }
  });

  AmCharts.ready(function() {

    chart = new AmCharts.AmPieChart();
    chart.dataProvider = chartData;
    chart.titleField = "query";
    chart.valueField = "value";
    chart.outlineColor = "";
    chart.outlineAlpha = 0.8;
    chart.outlineThickness = 2;
    chart.labelTickColor = "white";

    chart.depth3D = 20;
    chart.angle = 30;

    chart.write("chartdiv");
  });

  tippy('[data-title]', {
    content: function (ref) {
      return ref.getAttribute('data-title')
    }
  });

</script>

</html>