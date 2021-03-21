export const showMixin = {

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
            //
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
        edit() {
            location.href = this.item.path + '/edit';
        },
        show() {
            location.href = this.item.path;
        }
    },
};