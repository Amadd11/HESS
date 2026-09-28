export default (config = {}) => ({
    showAdvanced: config.showAdvanced || false,
    selectedDirectorate: config.selectedDirectorate || '',
    selectedUnit: config.selectedUnit || '',
    directorateUnits: config.directorateUnits || {},
    allUnits: config.allUnits || [],

    init() {
        this.$watch('selectedDirectorate', (newDir) => {
            this.renderUnits(newDir);
        });
        this.$nextTick(() => {
            this.renderUnits(this.selectedDirectorate, true);
        });
    },

    renderUnits(dir, isInitial = false) {
        const select = this.$refs.unitSelect;
        if (!select) return;

        const currentVal = isInitial ? this.selectedUnit : (this.selectedUnit || select.value);
        select.innerHTML = '';

        const defaultOpt = document.createElement('option');
        defaultOpt.value = '';
        defaultOpt.textContent = 'Semua Satuan Kerja';
        select.appendChild(defaultOpt);

        if (dir && this.directorateUnits[dir]) {
            const list = this.directorateUnits[dir];
            let found = false;
            list.forEach(u => {
                const opt = document.createElement('option');
                opt.value = u;
                opt.textContent = u;
                if (u === currentVal) {
                    opt.selected = true;
                    found = true;
                }
                select.appendChild(opt);
            });
            if (!found && !isInitial) {
                this.selectedUnit = '';
                select.value = '';
            }
        } else {
            Object.entries(this.directorateUnits).forEach(([dirName, units]) => {
                const grp = document.createElement('optgroup');
                grp.label = dirName;
                units.forEach(u => {
                    const opt = document.createElement('option');
                    opt.value = u;
                    opt.textContent = u;
                    if (u === currentVal) {
                        opt.selected = true;
                    }
                    grp.appendChild(opt);
                });
                select.appendChild(grp);
            });
        }
    }
});
