$.ajax({
    'url': '/getDataPie',
    'type': 'GET',
    'dataType': 'json',
    'success': function (log) {
        if (!Array.isArray(log) || log.length === 0) {
            $(".capacity").html("0 Pupuk");
            return;
        }

        // Total Capacity
        let total = 0;
        let labels = [];
        let chartData = [];
        let colors = ['#e3e3e3', '#4acccd', '#fcc468', '#46b352'];

        log.forEach((val, index) => {
            let jumlah = parseInt(val.Jumlah_Pupuk, 10) || 0;
            total += jumlah;

            labels.push(val.Pupuk ?? ('Item ' + (index + 1)));
            chartData.push(jumlah);
        });

        $(".capacity").html(total + " Pupuk");
        // End Of Total Capacity

        // Pie Chart
        let canvas = document.getElementById('chartEmail');
        if (!canvas) return;
        let ctx = canvas.getContext("2d");

        let myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: "Pupuk",
                    pointRadius: 0,
                    pointHoverRadius: 0,
                    backgroundColor: labels.map((_, i) => colors[i % colors.length]),
                    borderWidth: 0,
                    data: chartData
                }]
            },

            options: {
                legend: {
                    display: true,
                    position: 'bottom'
                },

                pieceLabel: {
                    render: 'percentage',
                    fontColor: ['white'],
                    precision: 2
                },

                tooltips: {
                    enabled: true
                },

                scales: {
                    yAxes: [{
                        ticks: {
                            display: false
                        },
                        gridLines: {
                            drawBorder: false,
                            zeroLineColor: "transparent",
                            color: 'rgba(255,255,255,0.05)'
                        }
                    }],

                    xAxes: [{
                        barPercentage: 1.6,
                        gridLines: {
                            drawBorder: false,
                            color: 'rgba(255,255,255,0.1)',
                            zeroLineColor: "transparent"
                        },
                        ticks: {
                            display: false
                        }
                    }]
                }
            }
        });
    }
});