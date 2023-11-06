const array = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17];
const data = {
    datasets: [
        {
            label: 'Стоимость всех активов 1',
            backgroundColor: 'rgb(184, 184, 184)',
            borderColor: 'rgb(184, 184, 184)',
        },
        {
            label: 'Стоимость пая 1',
            backgroundColor: 'rgb(167, 207, 67)',
            borderColor: 'rgb(167, 207, 67)',
        },
        {
            label: 'Стоимость всех активов 2',
            backgroundColor: 'rgb(184, 184, 184)',
            borderColor: 'rgb(184, 184, 184)',
            visible: false,
        },
        {
            label: 'Стоимость пая 2',
            backgroundColor: 'rgb(167, 207, 67)',
            borderColor: 'rgb(167, 207, 67)',
            visible: false,
        },
        {
            label: 'Стоимость всех активов 3',
            backgroundColor: 'rgb(184, 184, 184)',
            borderColor: 'rgb(184, 184, 184)',
        },
        {
            label: 'Стоимость пая 3',
            backgroundColor: 'rgb(167, 207, 67)',
            borderColor: 'rgb(167, 207, 67)',
        },
        {
            label: 'Стоимость всех активов 4',
            backgroundColor: 'rgb(184, 184, 184)',
            borderColor: 'rgb(184, 184, 184)',
        },
        {
            label: 'Стоимость пая 4',
            backgroundColor: 'rgb(167, 207, 67)',
            borderColor: 'rgb(167, 207, 67)',
        },
        {
            label: 'Стоимость всех активов 5',
            backgroundColor: 'rgb(184, 184, 184)',
            borderColor: 'rgb(184, 184, 184)',
        },
        {
            label: 'Стоимость пая 5',
            backgroundColor: 'rgb(167, 207, 67)',
            borderColor: 'rgb(167, 207, 67)',
        },
        {
            label: 'Стоимость всех активов 6',
            backgroundColor: 'rgb(184, 184, 184)',
            borderColor: 'rgb(184, 184, 184)',
        },
        {
            label: 'Стоимость пая 6',
            backgroundColor: 'rgb(167, 207, 67)',
            borderColor: 'rgb(167, 207, 67)',
        },
        {
            label: 'Стоимость всех активов 7',
            backgroundColor: 'rgb(184, 184, 184)',
            borderColor: 'rgb(184, 184, 184)',
        },
        {
            label: 'Стоимость пая 7',
            backgroundColor: 'rgb(167, 207, 67)',
            borderColor: 'rgb(167, 207, 67)',
        },
        {
            label: 'Стоимость всех активов 8',
            backgroundColor: 'rgb(184, 184, 184)',
            borderColor: 'rgb(184, 184, 184)',
        },
        {
            label: 'Стоимость пая 8',
            backgroundColor: 'rgb(167, 207, 67)',
            borderColor: 'rgb(167, 207, 67)',
        },
        {
            label: 'Стоимость всех активов 9',
            backgroundColor: 'rgb(184, 184, 184)',
            borderColor: 'rgb(184, 184, 184)',
        },
        {
            label: 'Стоимость пая 9',
            backgroundColor: 'rgb(167, 207, 67)',
            borderColor: 'rgb(167, 207, 67)',
        },
    ]

};

const config = {
    type: 'line',
    data: data,
    options: {
        responsive: false,
        plugins: {
            legend: {
                display: false,
            }
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'right',
                ticks: {
                    // Include a dollar sign in the ticks
                    callback: function (value, index, ticks) {
                        return value + 'млн';
                    }
                }
            },
        }
    }
};
const myChart = new Chart(
        document.getElementById('myChart'),
        config
        );

function dataToggle(e) {
    array.forEach((e) => {
        myChart.hide(e);
    })
    const target = e.target;
    let idx = target.getAttribute('data-index').split(',');
    let dataset1 = target.getAttribute('data-points1').split(',');
    let dataset2 = target.getAttribute('data-points2').split(',');
    const labels = target.getAttribute('data-labels').split(",");

    data.labels = labels;
    data.datasets[idx[0]].data = dataset1;
    data.datasets[idx[1]].data = dataset2;

    const visibilityData1 = myChart.isDatasetVisible(idx[0]);
    const visibilityData2 = myChart.isDatasetVisible(idx[1]);
    if (visibilityData1 === true) {
        myChart.hide(idx[0]);
    }
    if (visibilityData1 === false) {
        myChart.show(idx[0]);
    }
    if (visibilityData2 === true) {
        myChart.hide(idx[1]);
    }
    if (visibilityData2 === false) {
        myChart.show(idx[1]);
    }
}
const customLegendBtn = document.querySelectorAll('.btn-ctrl-chart');

array.forEach((e) => {
    myChart.hide(e);
    if (e === 1 || e === 0) {
        const datapoints1 = customLegendBtn[0].getAttribute('data-points1').split(",");
        const datapoints2 = customLegendBtn[0].getAttribute('data-points2').split(",");
        const labels = customLegendBtn[0].getAttribute('data-labels').split(",");
        data.labels = labels;
        data.datasets[0].data = datapoints1;
        data.datasets[1].data = datapoints2;
        myChart.show(e);
    }
})

for (let i = 0; i < customLegendBtn.length; i++) {
    customLegendBtn[i].getAttribute('data-index');
    customLegendBtn[i].addEventListener('click', dataToggle)
}