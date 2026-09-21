export default () => ({
    createModalOpen: false,
    editModalOpen: false,
    editQuestion: {
        id: '',
        category_id: '',
        code: '',
        text: '',
        scale: 'satisfaction',
        subscale: '',
        order: 1,
        is_active: true
    },

    openEdit(q) {
        this.editQuestion = {
            id: q.id,
            category_id: q.category_id,
            code: q.code,
            text: q.text,
            scale: q.scale,
            subscale: q.subscale || '',
            order: q.order,
            is_active: Boolean(q.is_active)
        };
        this.editModalOpen = true;
    }
});
