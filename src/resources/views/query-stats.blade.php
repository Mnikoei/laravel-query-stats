<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <script src="https://www.amcharts.com/lib/amcharts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Document</title>

    <style>
        #chartdiv { margin: -.5em auto; text-align:center; width: 90%; height: 390px }
        .queries { margin: -.5em auto; text-align:left; width: 90%; height: 390px }
    </style>
</head>
<body>
    <div id="chartdiv"></div>
    <div class="queries mt-4 pb-4">
{{--        <h2>Basic Table</h2>--}}
{{--        <p>The .table class adds basic styling (light padding and horizontal dividers) to a table:</p>--}}
        <table class="table">
            <thead>
            <tr>
                <th>query</th>
                <th>time</th>
                <th>queried_at</th>
                <th>connection</th>
                <th>database</th>
                <th>host</th>
                <th>user</th>
            </tr>
            </thead>
            <tbody id="table-body">
                @foreach($queries as $query)
                    <tr>
                        <th>{!! $query['query'] !!}</th>
                        <th>{!! $query['time'] !!}</th>
                        <th>{!! $query['queried_at'] !!}</th>
                        <th>{!! $query['connection'] !!}</th>
                        <th>{!! $query['database'] !!}</th>
                        <th>{!! $query['host'] !!}</th>
                        <th>{!! $query['user'] !!}</th>
                    </tr>
                @endforeach

            </tbody>
        </table>

       <div class="">
           <ul class="pagination justify-content-center pb-5">

               @php($lastPage = ceil($total / 100))

               @if ($total > 600)

                   <li class="page-item"><a class="page-link" href="{!! route('_qs.show', ['page' => 1]) !!}">1</a></li>
                   <li class="page-item"><a class="page-link" href="{!! route('_qs.show', ['page' => 2]) !!}">2</a></li>
                   <li class="page-item"><a class="page-link" href="#">...</a></li>
                   <li class="page-item"><a class="page-link" href="{!! route('_qs.show', ['page' => $lastPage - 2]) !!}">{!! $lastPage - 2 !!}</a></li>
                   <li class="page-item"><a class="page-link" href="{!! route('_qs.show', ['page' => $lastPage - 1]) !!}">{!! $lastPage - 1 !!}</a></li>
                   <li class="page-item"><a class="page-link" href="{!! route('_qs.show', ['page' => $lastPage]) !!}">{!! $lastPage !!}</a></li>

               @else

                   @for($i = 1; $i <= $lastPage; $i++)
                       <li class="page-item"><a class="page-link" href="{!! route('_qs.show', ['page' => $i]) !!}">{!! $i !!}</a></li>
                   @endfor
               @endif

           </ul>
       </div>
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

    chart.depth3D = 20;
    chart.angle = 30;

    chart.write("chartdiv");
  });

</script>
</html>