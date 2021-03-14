const Flash = {
    install: function (Vue, options) {
        Vue.flash = function (text, type) {
            type = typeof type === 'undefined' ? 'success' : type;

            Bus.$emit('flash-message', {
                text: text,
                type: type
            });
        };
        Vue.success = function (text) {
            Vue.flash(text, 'success');
        };
        Vue.successCreate = function (item) {
            Vue.success(item.label_singular + ' erstellt.');
        };
        Vue.successUpdate = function (item) {
            Vue.success(item.label_singular + ' gespeichert.');
        };
        Vue.successDelete = function (item) {
            Vue.success(item.label_singular + ' gelöscht.');
        };

        Vue.error = function (text) {
            Vue.flash(text, 'danger');
        };
        Vue.errorCreate = function () {
            Vue.error('Datensatz konnte nicht erstellt werden.');
        };
        Vue.errorUpdate = function (item) {
            Vue.error(item.label_singular + ' konnte nicht gespeichert werden.');
        };
        Vue.errorDelete = function (item) {
            Vue.error(item.label_singular + ' konnte nicht gelöscht werden.');
        };
    }
}

export default Flash;