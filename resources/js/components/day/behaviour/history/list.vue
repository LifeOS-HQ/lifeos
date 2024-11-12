<template>

    <div class="list-group">
        <row
            :item="item"
            :key="item.id"
            :is-active="isActive(item)"
            v-for="(item, index) in items"
            @show="show(index, $event)"
        ></row>
    </div>

</template>

<script>
    import row from './row.vue';
    import inputText from '../../../forms/inputs/text.vue';

    export default {

        components: {
            inputText,
            row,
        },

        mixins: [
            //
        ],

        props: {
            items: {
                required: true,
                type: Array,
            },
            itemToShow: {
                required: false,
                type: Object,
                default: null,
            },
        },

        data () {
            return {
                //
            };
        },

        computed: {
            //
        },

        methods: {
            show(index, item) {
                this.$emit('show', {
                    index: index,
                    item: item,
                });
            },
            isActive(item) {
                if (!this.itemToShow) {
                    return false;
                }

                return item.id === this.itemToShow.item.id;
            },
        },
    };
</script>
