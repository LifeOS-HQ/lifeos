<template>

    <div>
        <div v-for="(item, index) in items">
            <h4>{{ item.name }}</h4>
            <meal-index :model="item" :foods="foods"></meal-index>
        </div>
        <div>
            <button class="btn btn-primary btn-sm btn-block" @click="create()"><i class="fas fa-plus-square"></i></button>
        </div>
    </div>

</template>

<script>
    import row from './row.vue';
    import mealIndex from './meal/index.vue';
    import tableBase from '../../../tables/base.vue';
    import inputText from '../../../forms/inputs/text.vue';

    import { baseMixin } from '../../../../mixins/tables/base.js';

    export default {

        components: {
            inputText,
            mealIndex,
            row,
            tableBase,
        },

        mixins: [
            baseMixin,
        ],

        props: {
            foods: {
                required: true,
                type: Array,
            },
            model: {
                required: true,
                type: Object,
            },
        },

        data () {

            return {
                filter: {
                    //
                },
                form: {
                    //
                },
            };
        },

        computed: {

        },

        methods: {
            created(item) {
                this.items.push(item);
            }
        },
    };
</script>