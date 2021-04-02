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
    <style>
        /*
	Side Navigation Menu V2, RWD
	===================
	Author: https://github.com/pablorgarcia
 */

        @charset "UTF-8";
        @import url(https://fonts.googleapis.com/css?family=Open+Sans:300,400,700);

        body {
            font-family: 'Open Sans', sans-serif;
            font-weight: 300;
            line-height: 1.42em;
            color:#A7A1AE;
            background-color:#1F2739;
        }

        h1 {
            font-size:3em;
            font-weight: 300;
            line-height:1em;
            text-align: center;
            color: #4DC3FA;
        }

        h2 {
            font-size:1em;
            font-weight: 300;
            text-align: center;
            display: block;
            line-height:1em;
            padding-bottom: 2em;
            color: #FB667A;
        }

        h2 a {
            font-weight: 700;
            text-transform: uppercase;
            color: #FB667A;
            text-decoration: none;
        }

        .blue { color: #185875; }
        .yellow { color: #FFF842; }

        .t-container th h1 {
            font-weight: bold;
            font-size: 1em;
            text-align: left;
            color: #185875;
        }

        .t-container td {
            font-weight: normal;
            font-size: 1em;
            -webkit-box-shadow: 0 2px 2px -2px #0E1119;
            -moz-box-shadow: 0 2px 2px -2px #0E1119;
            box-shadow: 0 2px 2px -2px #0E1119;
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
            padding-left:2%;
        }

        /* Background-color of the odd rows */
        .t-container tr:nth-child(odd) {
            background-color: #323C50;
        }

        /* Background-color of the even rows */
        .t-container tr:nth-child(even) {
            background-color: #2C3446;
        }

        .t-container th {
            background-color: #1F2739;
        }

        .t-container td:first-child { color: #FB667A; }

        .t-container tr:hover {
            background-color: #464A52;
            -webkit-box-shadow: 0 6px 6px -6px #0E1119;
            -moz-box-shadow: 0 6px 6px -6px #0E1119;
            box-shadow: 0 6px 6px -6px #0E1119;
        }

        .t-container td:hover {
            background-color: #FFF842;
            color: #403E10;
            font-weight: bold;

            box-shadow: #7F7C21 -1px 1px, #7F7C21 -2px 2px, #7F7C21 -3px 3px, #7F7C21 -4px 4px, #7F7C21 -5px 5px, #7F7C21 -6px 6px;
            transform: translate3d(6px, -6px, 0);

            transition-delay: 0s;
            transition-duration: 0.4s;
            transition-property: all;
            transition-timing-function: line;
        }

        @media (max-width: 800px) {
            .t-container td:nth-child(4),
            .t-container th:nth-child(4) { display: none; }
        }

        text {
            fill: white !important;
            font-family: 'Open Sans', sans-serif !important;
        }

        #chartdiv { margin: -.5em auto; text-align:center; width: 90%; height: 390px }

        rect[fill="#FFFFFF"] {
            display: none;
        }
    </style>

</head>
<body>
<h1><span class="blue"></span>Top 10<span class="blue"></span></h1>

<div id="chartdiv"></div>
<table class="t-container">
    <thead>
    <tr>
        <th><h1>query</h1></th>
        <th><h1>time</h1></th>
        <th><h1>queried_at</h1></th>
        <th><h1>connection</h1></th>
        <th><h1>database</h1></th>
        <th><h1>host</h1></th>
        <th><h1>user</h1></th>
    </tr>
    </thead>
    <tbody>
    @foreach($queries as $query)
        <tr>
            <td>{!! $query['query'] !!}</td>
            <td>{!! $query['time'] !!}</td>
            <td>{!! $query['queried_at'] !!}</td>
            <td>{!! $query['connection'] !!}</td>
            <td>{!! $query['database'] !!}</td>
            <td>{!! $query['host'] !!}</td>
            <td>{!! $query['user'] !!}</td>
        </tr>
    @endforeach

    </tbody>
</table>

<div class="mt-4">
    <ul class="pagination justify-content-center pb-5">

        @php
            $lastPage = ceil($total / 100);
            $currentPage = request()->page;
        @endphp

        @if ($total > 600)

            <li class="page-item"><a class="page-link" href="{!! route('_qs.show', ['page' => ($p = $currentPage - 1) > 0 ? $p : 1]) !!}">previous</a></li>
            <li class="page-item"><a class="page-link" href="{!! route('_qs.show', ['page' => ($p = $currentPage + 1) > $lastPage ? $p : 1]) !!}">next</a></li>
            <li class="page-item {!! $currentPage == 1 ? 'active' : '' !!}"><a class="page-link" href="{!! route('_qs.show', ['page' => 1]) !!}">1</a></li>
            <li class="page-item {!! $currentPage == 2 ? 'active' : '' !!}"><a class="page-link" href="{!! route('_qs.show', ['page' => 2]) !!}">2</a></li>

            @for($i = $currentPage - 1; $i >= $currentPage - 2; $i--)

                @if ($currentPage - 1 > 2)
                    <li class="page-item"><a class="page-link" href="{!! route('_qs.show', ['page' => $i]) !!}">{!! $i !!}</a></li>
                @endif
            @endfor

            @if ($currentPage < $lastPage && $currentPage > 2)
                <li class="page-item active"><a class="page-link" href="{!! route('_qs.show', ['page' => $currentPage]) !!}">{!! $currentPage !!}</a></li>
            @endif

            @for($i = $currentPage + 1; $i <= $currentPage + 2; $i++)

                @if ($i < $lastPage && $i > 2)
                    <li class="page-item"><a class="page-link" href="{!! route('_qs.show', ['page' => $i]) !!}">{!! $i !!}</a></li>
                @endif
            @endfor

            <li class="page-item"><a class="page-link" href="#">...</a></li>
            <li class="page-item {!! $currentPage == $lastPage ? 'active' : '' !!}"><a class="page-link" href="{!! route('_qs.show', ['page' => $lastPage]) !!}">{!! $lastPage !!}</a></li>
        @else

            @for($i = 1; $i <= $lastPage; $i++)
                <li class="page-item"><a class="page-link" href="{!! route('_qs.show', ['page' => $i]) !!}">{!! $i !!}</a></li>
            @endfor
        @endif

    </ul>
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

</script>

</html>