<!DOCTYPE HTML>
<html>
<head>
    <script>
        window.onload = function () {
            var data = <?php echo json_encode($csvData, JSON_NUMERIC_CHECK); ?>;
            data.forEach(function(datum, index, data) {
                data[index]["x"] = new Date(data[index]["x"]);
            });
            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                title:{
                    text: '<?php echo \app\models\Query::getVars()[$request['vars']] . ' in ' .  \app\models\Query::getStns()[$request['stns']]?>'
                },
                axisY: {
                    title: '<?php echo \app\models\Query::getVarLabels()[$request['vars']] ?>;',
                    valueFormatString: "#0.",
                    suffix: ""
                },
                data: [{
                    yValueFormatString: "#,# graden",
                    xValueFormatString: "YYYYMMDD",
                    xValueType: "time",
                    type: "spline",
                    dataPoints: data
                }]
            });
            chart.render();
        }
    </script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>