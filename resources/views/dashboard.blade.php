<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Todoist statistics</title>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.5/css/bulma.min.css">
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/app.css') }}">
</head>
<body>
    <div class="box box-2">
        <header>
            <h1>Todoist completed and overdue</h1>
        </header>
        <canvas id="todoist" width="10" height="10"
            data-completed="{{ json_encode($completed) }}"
            data-completed-p1="{{ json_encode($completed_p1) }}"
            data-overdue="{{ json_encode($overdue) }}"></canvas>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.6.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>
