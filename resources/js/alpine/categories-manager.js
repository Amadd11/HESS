export default () => ({
    createModalOpen: false,
    editModalOpen: false,
    editCategory: {
        id: '',
        name: '',
        code: '',
        type: 'hospital',
        order: 1
    },

    openEdit(cat) {
        this.editCategory = {
            id: cat.id,
            name: cat.name,
            code: cat.code,
            type: cat.type,
            order: cat.order
        };
        this.editModalOpen = true;
    }
});
