export default (config = {}) => ({
    activeCloudTab: 'positive',

    trendCategories: config.trendCategories || [],
    trendSeries: config.trendSeries || [],

    unitCategories: config.unitCategories || [],
    unitSeries: config.unitSeries || [],

    profCategories: config.profCategories || [],
    profSeries: config.profSeries || [],

    statusLabels: config.statusLabels || [],
    statusSeries: config.statusSeries || [],

    tenureCategories: config.tenureCategories || [],
    tenureSeries: config.tenureSeries || [],

    charts: {},

    initDashboard() {
        if (!window.ApexCharts) {
            return;
        }

        this.$nextTick(() => {
            this.renderTrendChart();
            this.renderUnitChart();
            this.renderProfChart();
            this.renderStatusChart();
            this.renderTenureChart();
        });
    },

    filterKeyword(word) {
        const input = document.getElementById('feedbackSearchInput');
        if (input) {
            input.value = word;
            if (input.form) {
                input.form.submit();
            }
        }
    },

    renderTrendChart() {
        const el = document.getElementById('sentimentTrendChart');
        if (!el || this.trendCategories.length === 0) return;

        const options = {
            chart: {
                type: 'line',
                height: 320,
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            series: this.trendSeries,
            xaxis: {
                categories: this.trendCategories,
                labels: { style: { fontSize: '11px', fontWeight: 600 } }
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            markers: {
                size: 5,
                hover: { size: 7 }
            },
            colors: ['#16A34A', '#EAB308', '#DC2626'],
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4
            },
            legend: { show: false }
        };

        this.charts.trend = new window.ApexCharts(el, options);
        this.charts.trend.render();
    },

    renderUnitChart() {
        const el = document.getElementById('breakdownUnitChart');
        if (!el || this.unitCategories.length === 0) return;

        const options = {
            chart: {
                type: 'bar',
                height: 270,
                stacked: true,
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false }
            },
            series: this.unitSeries,
            xaxis: {
                categories: this.unitCategories,
                labels: { style: { fontSize: '10px', fontWeight: 600 } }
            },
            colors: ['#16A34A', '#EAB308', '#DC2626'],
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '45%'
                }
            },
            grid: { borderColor: '#f1f5f9' },
            legend: { show: false }
        };

        this.charts.unit = new window.ApexCharts(el, options);
        this.charts.unit.render();
    },

    renderProfChart() {
        const el = document.getElementById('breakdownProfChart');
        if (!el || this.profCategories.length === 0) return;

        const options = {
            chart: {
                type: 'bar',
                height: 270,
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false }
            },
            series: this.profSeries,
            xaxis: {
                categories: this.profCategories,
                labels: { style: { fontSize: '10px', fontWeight: 600 } }
            },
            colors: ['#16A34A', '#EAB308', '#DC2626'],
            plotOptions: {
                bar: {
                    borderRadius: 3,
                    columnWidth: '60%'
                }
            },
            grid: { borderColor: '#f1f5f9' },
            legend: { show: false }
        };

        this.charts.prof = new window.ApexCharts(el, options);
        this.charts.prof.render();
    },

    renderStatusChart() {
        const el = document.getElementById('breakdownStatusChart');
        if (!el || this.statusSeries.length === 0) return;

        const options = {
            chart: {
                type: 'donut',
                height: 270,
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false }
            },
            series: this.statusSeries,
            labels: this.statusLabels,
            colors: ['#173F78', '#6F3F7E', '#3B82F6', '#8B5CF6'],
            legend: {
                position: 'bottom',
                fontSize: '11px',
                fontWeight: 600
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return Math.round(val) + '%';
                }
            },
            stroke: { width: 2, colors: ['#ffffff'] }
        };

        this.charts.status = new window.ApexCharts(el, options);
        this.charts.status.render();
    },

    renderTenureChart() {
        const el = document.getElementById('breakdownTenureChart');
        if (!el || this.tenureCategories.length === 0) return;

        const options = {
            chart: {
                type: 'bar',
                height: 270,
                stacked: true,
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false }
            },
            series: this.tenureSeries,
            xaxis: {
                categories: this.tenureCategories,
                labels: { style: { fontSize: '10px', fontWeight: 600 } }
            },
            colors: ['#16A34A', '#EAB308', '#DC2626'],
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '45%'
                }
            },
            grid: { borderColor: '#f1f5f9' },
            legend: { show: false }
        };

        this.charts.tenure = new window.ApexCharts(el, options);
        this.charts.tenure.render();
    }
});
