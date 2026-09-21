export default () => ({
    tab: 'all',
    search: '',

    matches(hasLike, hasImprove, likeText, improveText, profession, unit) {
        if (this.tab === 'like' && !hasLike) {
            return false;
        }

        if (this.tab === 'improve' && !hasImprove) {
            return false;
        }

        if (!this.search.trim()) {
            return true;
        }

        const q = this.search.toLowerCase();

        return (likeText && likeText.toLowerCase().includes(q))
            || (improveText && improveText.toLowerCase().includes(q))
            || (profession && profession.toLowerCase().includes(q))
            || (unit && unit.toLowerCase().includes(q));
    }
});
