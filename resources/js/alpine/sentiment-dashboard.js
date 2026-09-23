export default (config = {}) => ({
    activeCloudTab: 'positive',

    sentimentSeries: config.sentimentSeries || [],
    sentimentUnit: config.sentimentUnit || 'kata',

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
        this.$nextTick(() => {
            if (window.ApexCharts) {
                this.renderSentimentPropChart();
                this.renderTrendChart();
                this.renderUnitChart();
                this.renderProfChart();
                this.renderStatusChart();
                this.renderTenureChart();
            }
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
    },

    renderSentimentPropChart() {
        const el = document.getElementById('sentimentPropDonutChart');
        if (!el || !this.sentimentSeries || this.sentimentSeries.length === 0) return;

        const total = this.sentimentSeries.reduce((a, b) => a + b, 0);
        if (total === 0) return;

        const unit = this.sentimentUnit || 'kata';

        const options = {
            chart: {
                type: 'donut',
                height: 210,
                width: 210,
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false },
                sparkline: { enabled: true },
                parentHeightOffset: 0,
                animations: {
                    enabled: true,
                    speed: 600
                }
            },
            series: this.sentimentSeries,
            labels: ['Positif', 'Netral', 'Negatif'],
            colors: ['#22C55E', '#F59E0B', '#EF4444'], // Green, Yellow, Red matching mockup
            stroke: {
                width: 2.5,
                colors: ['#ffffff']
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return Math.round(val) + '%';
                },
                style: {
                    fontSize: '11px',
                    fontFamily: 'Inter, sans-serif',
                    fontWeight: 900,
                    colors: ['#ffffff', '#0f172a', '#ffffff'] // dark text for yellow slice
                },
                dropShadow: {
                    enabled: false
                }
            },
            plotOptions: {
                pie: {
                    expandOnClick: false,
                    donut: {
                        size: '64%',
                        background: 'transparent'
                    }
                }
            },
            legend: {
                show: false
            },
            tooltip: {
                enabled: true,
                y: {
                    formatter: function (val, { seriesIndex, w }) {
                        const tot = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                        const pct = tot > 0 ? Math.round((val / tot) * 100) : 0;
                        return val.toLocaleString() + ' ' + unit + ' (' + pct + '%)';
                    }
                }
            }
        };

        if (this.charts.sentimentProp) {
            this.charts.sentimentProp.destroy();
        }
        this.charts.sentimentProp = new window.ApexCharts(el, options);
        this.charts.sentimentProp.render();
    }
});
