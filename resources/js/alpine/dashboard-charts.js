export default (config = {}) => {
    return {
        chartTab: (config.directorateLabels && config.directorateLabels.length > 0) ? 'directorate' : 'profession',
        selectedDirectorate: config.activeDirectorate || '', // drill-down ke satker dalam tab direktorat
        activeDirectorate: config.activeDirectorate || '',
        currentFilteredCount: (config.unitLabels || []).length,
        radarChart: null,
        barChart: null,

    rawRadarSeries: config.radarSeries || [],
    rawRadarCategories: config.radarCategories || [],
    unitLabels: config.unitLabels || [],
    unitData: (config.unitData || []).map(v => Number(v) || 0),
    unitCounts: config.unitCounts || [],
    unitDirectorates: config.unitDirectorates || [],
    profLabels: config.profLabels || [],
    profData: (config.profData || []).map(v => Number(v) || 0),
    directorateLabels: config.directorateLabels || [],
    directorateData: (config.directorateData || []).map(v => Number(v) || 0),
    totalResponses: Number(config.totalResponses) || 0,

    initCharts() {
        if (!window.ApexCharts) {
            return;
        }

        this.$nextTick(() => {
            this.renderRadarChart();
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

    renderBarChart() {
        const barEl = document.getElementById('comparisonBarChart');
        if (!barEl) {
            return;
        }

        let rawLabels = this.directorateLabels;
        let rawData = this.directorateData;
        let barColor = '#0284c7';
        let barHeight = '65%';
        let chartHeight = 300;

        if (this.chartTab === 'directorate') {
            if (this.selectedDirectorate) {
                // Drill-down: tampilkan satker dalam direktorat terpilih
                barHeight = '62%';
                const filtered = [];
                for (let i = 0; i < this.unitLabels.length; i++) {
                    if (this.unitDirectorates[i] === this.selectedDirectorate) {
                        filtered.push({
                            label: this.unitLabels[i],
                            score: this.unitData[i]
                        });
                    }
                }
                this.currentFilteredCount = filtered.length;
                rawLabels = filtered.map(item => item.label);
                rawData = filtered.map(item => item.score);
                barColor = '#7c3aed';
                const itemHeight = rawLabels.length > 15 ? 28 : 34;
                chartHeight = Math.max(260, (rawLabels.length || 1) * itemHeight);
            } else {
                // Overview: tampilkan semua direktorat
                rawLabels = this.directorateLabels;
                rawData = this.directorateData;
                barColor = '#0284c7';
                barHeight = '65%';
                chartHeight = Math.max(280, (rawLabels.length || 1) * 44);
            }
        } else if (this.chartTab === 'profession') {
            rawLabels = this.profLabels;
            rawData = this.profData;
            barColor = '#0d9488';
            barHeight = '60%';
            chartHeight = Math.max(220, (rawLabels.length || 1) * 48);
        }

        const labels = rawLabels.length ? rawLabels : ['Belum Ada Data'];
        const data = rawData.length ? rawData : [0];

        if (this.barChart) {
            this.barChart.destroy();
            this.barChart = null;
        }

        const barOptions = {
            chart: {
                type: 'bar',
                height: chartHeight,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 350
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    horizontal: true,
                    distributed: false,
                    barHeight: barHeight,
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
                    style: { fontSize: '11px', fontWeight: 600, colors: '#1f2937' },
                    maxWidth: 240
                }
            },
            colors: [barColor],
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
        // Reset drill-down saat pindah tab
        if (tab !== 'directorate') {
            this.selectedDirectorate = this.activeDirectorate || '';
        }
        this.renderBarChart();
    },

    setDirectorateFilter(dir) {
        this.selectedDirectorate = dir;
        this.renderBarChart();
    }
};
};
