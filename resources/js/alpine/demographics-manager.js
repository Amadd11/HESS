export default (defaultType = 'unit') => ({
    createModalOpen: false,
    editModalOpen: false,
    currentType: defaultType,
    createData: {
        type: defaultType,
        name: '',
        order: 0,
        is_active: 1
    },
    editDemographic: {
        id: '',
        type: defaultType,
        name: '',
        order: 0,
        is_active: 1
    },

    openCreate(type = null) {
        this.createData = {
            type: type || this.currentType || 'unit',
            name: '',
            order: 0,
            is_active: 1
        };
        this.createModalOpen = true;
    },

    openEdit(item) {
        this.editDemographic = {
            id: item.id,
            type: item.type,
            name: item.name,
            order: item.order ?? 0,
            is_active: item.is_active ? 1 : 0
        };
        this.editModalOpen = true;
    }
});
