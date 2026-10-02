export default (config = {}) => ({
    config: config || {},
    charts: {},
    _resizeTimer: null,

    initCharts() {
        if (!window.ApexCharts) {
            return;
        }

        this.$nextTick(() => {
            this.renderAllCharts();
        });

        window.addEventListener('resize', () => {
            clearTimeout(this._resizeTimer);
            this._resizeTimer = setTimeout(() => {
                this.renderAllCharts();
            }, 200);
        });
    },

    renderAllCharts() {
        const chartConfigs = [
            { id: 'demographicAgeChart', data: this.config.age },
            { id: 'demographicGenderChart', data: this.config.gender },
            { id: 'demographicIncomeChart', data: this.config.income },
            { id: 'demographicStatusChart', data: this.config.status },
            { id: 'demographicEducationChart', data: this.config.education },
        ];

        chartConfigs.forEach(cfg => {
            if (cfg.data && cfg.data.labels && cfg.data.labels.length > 0) {
                this.renderDonut(cfg.id, cfg.data.labels, cfg.data.counts, cfg.data.colors);
            }
        });
    },

    renderDonut(chartId, labels, counts, colors) {
        const el = document.getElementById(chartId);
        if (!el) return;

        if (this.charts[chartId]) {
            this.charts[chartId].destroy();
            this.charts[chartId] = null;
        }

        const options = this.createDonutOptions(labels, counts, colors);
        this.charts[chartId] = new window.ApexCharts(el, options);
        this.charts[chartId].render();
    },

    createDonutOptions(labels, series, colors) {
        const hasData = Array.isArray(series) && series.some(v => v > 0);
        return {
            chart: {
                type: 'donut',
                height: 220,
                fontFamily: 'inherit',
                toolbar: { show: false },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 400
                }
            },
            labels: labels,
            series: hasData ? series : [1],
            colors: hasData ? colors : ['#e2e8f0'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '68%',
                        labels: {
                            show: hasData,
                            name: {
                                show: true,
                                fontSize: '11px',
                                fontWeight: 600,
                                color: '#64748b',
                                offsetY: -4
                            },
                            value: {
                                show: true,
                                fontSize: '16px',
                                fontWeight: 800,
                                color: '#0f172a',
                                offsetY: 4,
                                formatter: (val) => `${val} org`
                            },
                            total: {
                                show: true,
                                showAlways: false,
                                label: 'Responden',
                                fontSize: '11px',
                                fontWeight: 600,
                                color: '#64748b',
                                formatter: (w) => {
                                    const sum = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    return `${sum}`;
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: { enabled: false },
            legend: { show: false },
            stroke: {
                width: 2,
                colors: ['#ffffff']
            },
            tooltip: {
                theme: 'light',
                custom: hasData ? function({ series, seriesIndex, dataPointIndex, w }) {
                    const val = series[seriesIndex];
                    const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                    const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                    const label = w.globals.labels[seriesIndex];
                    const color = w.globals.colors[seriesIndex];
                    return `
                        <div class="px-3 py-2 bg-white rounded-xl shadow-lg border border-gray-100 text-xs">
                            <div class="flex items-center gap-1.5 font-bold text-gray-800 mb-0.5">
                                <span class="w-2 h-2 rounded-full" style="background:${color}"></span>
                                <span>${label}</span>
                            </div>
                            <div class="text-gray-600">
                                <strong class="text-gray-900 font-extrabold">${val}</strong> orang (${pct}%)
                            </div>
                        </div>
                    `;
                } : undefined
            }
        };
    }
});
