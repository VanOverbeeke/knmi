<!DOCTYPE HTML>
<html>
<head>
    <script>
        window.onload = function () {
            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                title:{
                    text: "Temperatuur"
                },
                axisY: {
                    title: "0.1 Celsius",
                    valueFormatString: "#0.",
                    suffix: "",
                    stripLines: [{
                        value: 0,
                        label: "Vriespunt"
                    }]
                },
                data: [{
                    yValueFormatString: "#,# graden",
                    xValueFormatString: "YYYY",
                    type: "spline",
                    dataPoints: <?php echo json_encode($data, JSON_NUMERIC_CHECK); ?>
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