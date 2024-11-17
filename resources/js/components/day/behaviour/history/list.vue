<template>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>Verhalten</div>
                <div>
                    <span class="badge badge-pill pointer" :class="filter.status == 'all' ? 'badge-primary' : 'badge-light'" @click="filter.status = 'all'">Alle</span>
                    <span class="badge badge-pill pointer" :class="filter.status == 'incompleted' ? 'badge-primary' : 'badge-light'" @click="filter.status = 'incompleted'">Fällig</span>
                    <span class="badge badge-pill pointer" :class="filter.status == 'completed' ? 'badge-primary' : 'badge-light'" @click="filter.status = 'completed'">Erledigt</span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="list-group">
                <row
                    :item="item"
                    :key="item.id"
                    :is-active="isActive(item)"
                    v-for="(item, index) in filteredItems"
                    @show="show(index, $event)"
                    @complete="$emit('complete', index)"
                    @incomplete="$emit('incomplete', index)"
                ></row>
            </div>
        </div>
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
                filter: {
                    status: 'incompleted',
                }
            };
        },

        computed: {
            filteredItems() {
                if (this.filter.status === 'all') {
                    return this.items;
                }

                return this.items.filter(item => {
                    return item.is_completed == (this.filter.status === 'completed');
                });
            },
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
