import Chart from 'chart.js/auto';

// نسجّل مكونات Alpine الخاصة بالرسوم البيانية قبل ما Alpine يبلش (Livewire بيشغّل Alpine تلقائياً)
document.addEventListener('alpine:init', () => {

    // رسم خطي: نمو المستخدمين عبر الوقت
    Alpine.data('lineChart', (dataset) => ({
        chart: null,
        init(canvas) {
            const labels = Object.keys(dataset);
            const values = Object.values(dataset);

            this.chart = new Chart(canvas, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        data: values,
                        borderColor: '#17685D',
                        backgroundColor: 'rgba(23,104,93,0.12)',
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#C9A24B',
                        pointBorderColor: '#C9A24B',
                        pointRadius: 4,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#F0EEE8' } },
                        x: { grid: { display: false } },
                    },
                },
            });
        },
    }));

    // رسم دائري (Doughnut): توزيع المستخدمين حسب الدور
    Alpine.data('donutChart', (dataset) => ({
        chart: null,
        init(canvas) {
            const labels = Object.keys(dataset);
            const values = Object.values(dataset);

            this.chart = new Chart(canvas, {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data: values,
                        backgroundColor: ['#0F4A43', '#C9A24B', '#2F9E6E', '#17685D', '#E4CD93', '#0A2E2A'],
                        borderWidth: 0,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 10, padding: 14, font: { size: 11 } } },
                    },
                },
            });
        },
    }));
});
