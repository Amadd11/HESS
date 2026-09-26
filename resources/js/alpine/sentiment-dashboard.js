export default (config = {}) => ({
    activeCloudTab: 'positive',

    sentimentSeries: config.sentimentSeries || [],
    sentimentUnit: config.sentimentUnit || 'kata',

    charts: {},

    initDashboard() {
        this.$nextTick(() => {
            if (window.ApexCharts) {
                this.renderSentimentPropChart();
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

    renderSentimentPropChart() {
        const el = document.getElementById('sentimentPropDonutChart');
        if (!el || !this.sentimentSeries || this.sentimentSeries.length === 0) return;

        const total = this.sentimentSeries.reduce((a, b) => Number(a) + Number(b), 0);
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
