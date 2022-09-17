// Setup the canvas as full-screen
todoistElement = document.getElementById('todoist');
todoistElement.style.width = '100%';
todoistElement.style.height = '100%';
todoistElement.width = todoistElement.offsetWidth;
todoistElement.height = todoistElement.offsetHeight;

// Bar chart
var todoistChart = new Chart(todoistElement, {
    type: 'bar',
    data: {
        labels: [
            moment().subtract(29, 'd').format('ddd'),
            moment().subtract(28, 'd').format('ddd'),
            moment().subtract(27, 'd').format('ddd'),
            moment().subtract(26, 'd').format('ddd'),
            moment().subtract(25, 'd').format('ddd'),
            moment().subtract(24, 'd').format('ddd'),
            moment().subtract(23, 'd').format('ddd'),
            moment().subtract(22, 'd').format('ddd'),
            moment().subtract(21, 'd').format('ddd'),
            moment().subtract(20, 'd').format('ddd'),
            moment().subtract(19, 'd').format('ddd'),
            moment().subtract(18, 'd').format('ddd'),
            moment().subtract(17, 'd').format('ddd'),
            moment().subtract(16, 'd').format('ddd'),
            moment().subtract(15, 'd').format('ddd'),
            moment().subtract(14, 'd').format('ddd'),
            moment().subtract(13, 'd').format('ddd'),
            moment().subtract(12, 'd').format('ddd'),
            moment().subtract(11, 'd').format('ddd'),
            moment().subtract(10, 'd').format('ddd'),
            moment().subtract(9, 'd').format('ddd'),
            moment().subtract(8, 'd').format('ddd'),
            moment().subtract(7, 'd').format('ddd'),
            moment().subtract(6, 'd').format('ddd'),
            moment().subtract(5, 'd').format('ddd'),
            moment().subtract(4, 'd').format('ddd'),
            moment().subtract(3, 'd').format('ddd'),
            moment().subtract(2, 'd').format('ddd'),
            moment().subtract(1, 'd').format('ddd'),
            moment().format('ddd'),
        ],
        datasets: [
            // Completed tasks per day (green)
            {
                label: 'P4',
                backgroundColor: '#187c19',
                data: JSON.parse(todoistElement.dataset.completedP4),
            },
            {
                label: 'P3',
                backgroundColor: '#69b41e',
                data: JSON.parse(todoistElement.dataset.completedP3),
            },
            {
                label: 'P2',
                backgroundColor: '#8dc71e',
                data: JSON.parse(todoistElement.dataset.completedP2),
            },
            {
                label: 'P1',
                backgroundColor: '#b8d53d',
                data: JSON.parse(todoistElement.dataset.completedP1),
            },
            {
                label: 'Overdue',
                backgroundColor: '#E53E3E',
                data: JSON.parse(todoistElement.dataset.overdue).map(x => -x),
                datalabels: {
                    anchor: 'center',
                    align: 'end',
                },
            },
        ],
    },
    options: {
        legend: { display: false },
        scales: {
            xAxes: [{
                stacked: true,
            }],
            yAxes: [{
                stacked: true,
                ticks: {
                    beginAtZero: true,
                },
            }],
        },
        plugins: {
            datalabels: {
                anchor: 'start',
                align: 'top',
                color: 'white',
                display: 'auto',
            },
        },
    }
});
