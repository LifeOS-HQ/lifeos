export const selectableMixin = {

    computed: {
        selectAll: {
            get: function () {
                return this.items.length ? this.items.length == this.selected.length : false;
            },
            set: function (value) {
                this.selected = [];
                if (value) {
                    for (let i in this.items) {
                        this.selected.push(this.items[i].id);
                    }
                }
            },
        },
    },

    data () {
        return {
            selected: [],
        };
    },

    methods: {
        isSelected(id) {
            return ((this.selected.indexOf(id) == -1) ? false : true);
        },
        toggleSelected (id) {
            var index = this.selected.indexOf(id);
            if (index == -1) {
                this.selected.push(id);
            }
            else {
                this.selected.splice(index, 1);
            }
        },
    },
};