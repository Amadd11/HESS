export default (config = {}) => ({
    chartTab: 'unit',
    radarChart: null,
    barChart: null,
    npsChart: null,

    rawRadarSeries: config.radarSeries || [],
    rawRadarCategories: config.radarCategories || [],
    unitLabels: config.unitLabels || [],
    unitData: (config.unitData || []).map(v => Number(v) || 0),
    profLabels: config.profLabels || [],
    profData: (config.profData || []).map(v => Number(v) || 0),
    directorateLabels: config.directorateLabels || [],
    directorateData: (config.directorateData || []).map(v => Number(v) || 0),
    promoters: Number(config.promoters) || 0,
    passives: Number(config.passives) || 0,
    detractors: Number(config.detractors) || 0,
    npsScore: Number(config.npsScore) || 0,
    totalResponses: Number(config.totalResponses) || 0,

    initCharts() {
        if (!window.ApexCharts) {
            return;
        }

        this.$nextTick(() => {
            this.renderRadarChart();
            this.renderNpsChart();
            this.renderBarChart();
        });
    },

    renderRadarChart() {
        const el = document.getElementById('hospitalRadarChart');
        if (!el || this.rawRadarCategories.length === 0) {
            return;
        }

        if (this.radarChart) {
            this.radarChart.destroy();
        }

        // Label ringkas & jelas untuk sumbu horizontal bawah
        const labelMap = {
            'Leadership & Supervision': 'Kepemimpinan',
            'Workload & Staffing': 'Beban Kerja',
            'Compensation & Reward': 'Kompensasi',
            'Career & Professional Development': 'Karir',
            'Work Environment & Facilities': 'Fasilitas',
            'Teamwork & Interprofessional Collaboration': 'Kerjasama',
            'Psychological & Patient Safety': 'Keselamatan',
            'Work-Life Balance': 'Work-Life',
            'Lingkungan Kerja': 'Lingk. Kerja',
            'Hubungan dengan Atasan': 'Hub. Atasan',
            'Penghargaan dan Pengukuran Kerja': 'Penghargaan',
            'Kesempatan Pengembangan Karir': 'Pengemb. Karir',
            'Gaji dan Kompensasi': 'Gaji & Komp.',
            'Keseimbangan Kerja dan Kehidupan / Work Life Balance': 'Work-Life Balance',
            'Komunikasi dalam Rumah Sakit': 'Komunikasi RS',
            'Budaya Rumah Sakit': 'Budaya RS'
        };

        const categories = this.rawRadarCategories.map(name => labelMap[name] || name);
        const seriesData = (this.rawRadarSeries || []).map(v => Number(v) || 0);

        const barOptions = {
            chart: {
                type: 'bar',
                height: 350,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    horizontal: false, // Vertikal ke atas
                    columnWidth: '48%',
                    dataLabels: { position: 'top' }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: (val) => `${val}%`,
                offsetY: -18,
                style: {
                    fontSize: '10.5px',
                    fontWeight: 700,
                    colors: ['#374151']
                }
            },
            series: [{
                name: 'Skor Dimensi (%)',
                data: seriesData
            }],
            xaxis: {
                categories: categories,
                labels: {
                    rotate: -35,
                    rotateAlways: false,
                    style: { fontSize: '11px', fontWeight: 600, colors: '#4b5563' }
                },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                min: 0,
                max: 100,
                tickAmount: 5,
                labels: {
                    style: { fontSize: '10px', fontWeight: 600, colors: '#9ca3af' },
                    formatter: (val) => `${val}%`
                }
            },
            colors: ['#0284c7'],
            grid: {
                borderColor: '#f3f4f6',
                strokeDashArray: 3
            },
            tooltip: {
                y: {
                    formatter: (val, opts) => {
                        const originalName = this.rawRadarCategories[opts.dataPointIndex] || '';
                        return `${val}% (${originalName})`;
                    }
                }
            }
        };

        this.radarChart = new window.ApexCharts(el, barOptions);
        this.radarChart.render();
    },

    renderNpsChart() {
        const npsEl = document.getElementById('npsDonutChart');
        if (!npsEl) {
            return;
        }

        if (this.npsChart) {
            this.npsChart.destroy();
        }

        const hasData = (this.promoters + this.passives + this.detractors) > 0;
        const seriesData = hasData ? [this.promoters, this.passives, this.detractors] : [1, 0, 0];
        const colorsData = hasData ? ['#10b981', '#f59e0b', '#f43f5e'] : ['#e5e7eb', '#e5e7eb', '#e5e7eb'];

        const npsOptions = {
            chart: {
                type: 'donut',
                height: 250,
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false }
            },
            series: seriesData,
            labels: ['Promoter (9–10)', 'Pasif (7–8)', 'Detractor (0–6)'],
            colors: colorsData,
            plotOptions: {
                pie: {
                    donut: {
                        size: '72%',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '11px',
                                fontWeight: 600,
                                color: '#6b7280',
                                offsetY: -4
                            },
                            value: {
                                show: true,
                                fontSize: '26px',
                                fontWeight: 900,
                                color: '#111827',
                                offsetY: 4,
                                formatter: (val) => `${val}`
                            },
                            total: {
                                show: true,
                                label: this.getNpsGradeLabel(),
                                fontSize: '11px',
                                fontWeight: 800,
                                color: this.npsScore >= 20 ? '#059669' : (this.npsScore >= 0 ? '#7c3aed' : '#e11d48'),
                                formatter: () => (this.totalResponses > 0 ? (this.npsScore > 0 ? `+${this.npsScore}` : `${this.npsScore}`) : '0')
                            }
                        }
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                show: false
            },
            stroke: {
                width: 2.5,
                colors: ['#ffffff']
            },
            tooltip: {
                enabled: hasData,
                y: {
                    formatter: (val) => `${val} Pegawai (${this.totalResponses > 0 ? Math.round((val / this.totalResponses) * 100) : 0}%)`
                }
            }
        };

        this.npsChart = new window.ApexCharts(npsEl, npsOptions);
        this.npsChart.render();
    },

    renderBarChart() {
        const barEl = document.getElementById('comparisonBarChart');
        if (!barEl) {
            return;
        }

        let rawLabels = this.unitLabels;
        let rawData = this.unitData;

        if (this.chartTab === 'profession') {
            rawLabels = this.profLabels;
            rawData = this.profData;
        } else if (this.chartTab === 'directorate') {
            rawLabels = this.directorateLabels;
            rawData = this.directorateData;
        }

        const labels = rawLabels.length ? rawLabels : ['Belum Ada Data'];
        const data = rawData.length ? rawData : [0];
        const chartHeight = Math.max(340, labels.length * 28);

        if (this.barChart) {
            this.barChart.updateOptions({
                chart: { height: chartHeight },
                xaxis: { categories: labels },
                series: [{ name: 'Rata-rata Kepuasan (%)', data: data }]
            });
            return;
        }

        const barOptions = {
            chart: {
                type: 'bar',
                height: chartHeight,
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
                offsetX: 30,
                style: {
                    fontSize: '11px',
                    fontWeight: 700,
                    colors: ['#374151']
                }
            },
            series: [{
                name: 'Rata-rata Kepuasan (%)',
                data: data
            }],
            xaxis: {
                categories: labels,
                min: 0,
                max: 100,
                tickAmount: 5,
                labels: {
                    style: { fontSize: '11px', fontWeight: 600, colors: '#6b7280' },
                    formatter: (val) => `${val}%`
                }
            },
            yaxis: {
                labels: {
                    style: { fontSize: '11.5px', fontWeight: 600, colors: '#1f2937' },
                    maxWidth: 180
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
    },

    getNpsGradeLabel() {
        if (this.npsScore >= 50) {
            return 'Predikat A+';
        }
        if (this.npsScore >= 20) {
            return 'Predikat A';
        }
        if (this.npsScore >= 0) {
            return 'Predikat B';
        }
        if (this.npsScore >= -20) {
            return 'Predikat C';
        }
        return 'Predikat D';
    }
});
