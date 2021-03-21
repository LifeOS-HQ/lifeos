export const editableMixin = {

    props: {
        item: {
            type: Object,
            required: true,
        },
        isSelected: {
            type: Boolean,
            required: false,
            default: false,
        },
    },

    data () {
        return {
            errors: {},
            form: {

            },
            isEditing: false,
        };
    },

    methods: {
        destroy() {
            var component = this;
            axios.delete(component.item.path)
                .then(function (response) {
                    component.$emit("deleted", component.item);
            })
                .catch(function (error) {
                    component.errors = error.response.data.errors;
                    Vue.errorDelete(component.item);
            });
        },
        error(name) {
            return (name in this.errors ? this.errors[name][0] : '');
        },
        update() {
            var component = this;
            axios.put(component.item.path, component.form)
                .then( function (response) {
                    component.errors = {};
                    component.isEditing = false;
                    component.$emit('updated', response.data);
                })
                .catch(function (error) {
                    component.errors = error.response.data.errors;
            });
        },
    },
};