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

        // Listener resize responsif: otomatis sesuaikan grafik saat orientasi atau ukuran layar berubah
        window.addEventListener('resize', () => {
            clearTimeout(this._resizeTimer);
            this._resizeTimer = setTimeout(() => {
                this.renderRadarChart();
                this.renderBarChart();
            }, 200);
        });
    },

    renderRadarChart() {
        const el = document.getElementById('hospitalRadarChart');
        if (!el || this.rawRadarCategories.length === 0) {
            return;
        }

        if (this.radarChart) {
            this.radarChart.destroy();
            this.radarChart = null;
        }

        // Label ringkas & jelas untuk sumbu indikator
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
        const isMobile = window.innerWidth < 640;

        const barOptions = {
            chart: {
                type: 'bar',
                height: isMobile ? Math.max(340, categories.length * 40) : 350,
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
                    borderRadius: isMobile ? 4 : 6,
                    horizontal: isMobile, // Horizontal di HP agar teks kategori tidak berdesakan, vertikal di desktop
                    columnWidth: '48%',
                    barHeight: '62%',
                    dataLabels: { position: 'top' }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: (val) => `${val}%`,
                offsetX: isMobile ? 22 : 0,
                offsetY: isMobile ? 0 : -18,
                style: {
                    fontSize: isMobile ? '10px' : '10.5px',
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
                min: 0,
                max: 100,
                tickAmount: isMobile ? 4 : 5,
                labels: {
                    rotate: isMobile ? 0 : -35,
                    rotateAlways: false,
                    style: { fontSize: isMobile ? '10px' : '11px', fontWeight: 600, colors: '#4b5563' },
                    formatter: isMobile ? (val) => `${val}%` : undefined
                },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                min: 0,
                max: 100,
                tickAmount: 5,
                labels: {
                    maxWidth: isMobile ? 120 : undefined,
                    style: { fontSize: isMobile ? '10.5px' : '10px', fontWeight: 600, colors: isMobile ? '#1f2937' : '#9ca3af' },
                    formatter: isMobile ? undefined : (val) => `${val}%`
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

        const isMobile = window.innerWidth < 640;
        const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;

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
                const itemHeight = rawLabels.length > 15 ? (isMobile ? 26 : 28) : (isMobile ? 30 : 34);
                chartHeight = Math.max(260, (rawLabels.length || 1) * itemHeight);
            } else {
                // Overview: tampilkan semua direktorat
                rawLabels = this.directorateLabels;
                rawData = this.directorateData;
                barColor = '#0284c7';
                barHeight = '65%';
                chartHeight = Math.max(260, (rawLabels.length || 1) * (isMobile ? 38 : 44));
            }
        } else if (this.chartTab === 'profession') {
            rawLabels = this.profLabels;
            rawData = this.profData;
            barColor = '#0d9488';
            barHeight = '60%';
            chartHeight = Math.max(220, (rawLabels.length || 1) * (isMobile ? 42 : 48));
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
                offsetX: isMobile ? 18 : 28,
                style: {
                    fontSize: isMobile ? '10px' : '11px',
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
                tickAmount: isMobile ? 4 : 5,
                labels: {
                    style: { fontSize: isMobile ? '10px' : '11px', fontWeight: 600, colors: '#6b7280' },
                    formatter: (val) => `${val}%`
                }
            },
            yaxis: {
                labels: {
                    style: { fontSize: isMobile ? '10px' : '11px', fontWeight: 600, colors: '#1f2937' },
                    maxWidth: isMobile ? 120 : (isTablet ? 170 : 240)
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
