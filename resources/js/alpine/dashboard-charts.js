export default (config = {}) => ({
    chartTab: 'unit',
    radarChart: null,
    barChart: null,
    radarSeries: config.radarSeries || [],
    radarCategories: config.radarCategories || [],
    unitLabels: config.unitLabels || [],
    unitData: config.unitData || [],
    profLabels: config.profLabels || [],
    profData: config.profData || [],

    initCharts() {
        if (!window.ApexCharts) {
            return;
        }

        this.renderRadarChart();
        this.renderBarChart();
    },

    renderRadarChart() {
        const radarEl = document.getElementById('hospitalRadarChart');
        if (!radarEl || this.radarCategories.length === 0) {
            return;
        }

        const radarOptions = {
            chart: {
                type: 'radar',
                height: 340,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            series: [{
                name: 'Skor Dimensi (%)',
                data: this.radarSeries.length ? this.radarSeries : [0, 0, 0, 0, 0, 0, 0, 0]
            }],
            labels: this.radarCategories,
            colors: ['#6f3f7e'],
            fill: {
                opacity: 0.25,
                colors: ['#6f3f7e']
            },
            stroke: {
                width: 2.5,
                colors: ['#6f3f7e']
            },
            markers: {
                size: 4,
                colors: ['#ffffff'],
                strokeColors: '#6f3f7e',
                strokeWidth: 2,
                hover: { size: 6 }
            },
            yaxis: {
                show: false,
                min: 0,
                max: 100,
                tickAmount: 5
            },
            xaxis: {
                labels: {
                    style: {
                        fontSize: '11px',
                        fontWeight: 600,
                        colors: Array(this.radarCategories.length).fill('#4b5563')
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: (val) => `${val}% Indeks Kepuasan`
                }
            }
        };

        this.radarChart = new window.ApexCharts(radarEl, radarOptions);
        this.radarChart.render();
    },

    renderBarChart() {
        const barEl = document.getElementById('comparisonBarChart');
        if (!barEl) {
            return;
        }

        const isUnit = this.chartTab === 'unit';
        const labels = isUnit ? this.unitLabels : this.profLabels;
        const data = isUnit ? this.unitData : this.profData;

        if (this.barChart) {
            this.barChart.updateOptions({
                xaxis: { categories: labels.length ? labels : ['Belum Ada Data'] },
                series: [{ name: 'Rata-rata Kepuasan (%)', data: data.length ? data : [0] }]
            });
            return;
        }

        const barOptions = {
            chart: {
                type: 'bar',
                height: 340,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    horizontal: true,
                    distributed: false,
                    barHeight: '60%',
                    dataLabels: { position: 'top' }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: (val) => `${val}%`,
                offsetX: 28,
                style: {
                    fontSize: '10px',
                    fontWeight: 700,
                    colors: ['#4b5563']
                }
            },
            series: [{
                name: 'Rata-rata Kepuasan (%)',
                data: data.length ? data : [0]
            }],
            xaxis: {
                categories: labels.length ? labels : ['Belum Ada Data'],
                min: 0,
                max: 100,
                labels: {
                    style: { fontSize: '10px', fontWeight: 600, colors: '#6b7280' },
                    formatter: (val) => `${val}%`
                }
            },
            yaxis: {
                labels: {
                    style: { fontSize: '11px', fontWeight: 600, colors: '#374151' },
                    maxWidth: 160
                }
            },
            colors: ['#7c3aed'],
            grid: {
                borderColor: '#f3f4f6',
                strokeDashArray: 3
            },
            tooltip: {
                y: {
                    formatter: (val) => `${val}% Rata-rata Skor`
                }
            }
        };

        this.barChart = new window.ApexCharts(barEl, barOptions);
        this.barChart.render();
    },

    setChartTab(tab) {
        this.chartTab = tab;
        this.renderBarChart();
    }
});
