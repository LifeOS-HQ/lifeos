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
            axios.delete(this.item.path);
            this.$emit("deleted", this.item);
        },
        edit() {
            location.href = this.item.path + '/edit';
        },
        show() {
            location.href = this.item.path;
        }
    },
};