<template>

    <show :item="item" :has-edit-button="true" :has-show-button="true" @destroying="destroy()">

        <template v-slot:show>
            <td class="align-middle pointer" @click="show">{{ item.at_formatted }}</td>
            <td class="align-middle text-right pointer" @click="show">{{ item.calories.format(2, ',', '') }} kcal</td>
            <td class="align-middle text-right pointer" @click="show">{{ item.carbohydrate.format(2, ',', '') }} g</td>
            <td class="align-middle text-right pointer" @click="show">{{ item.fat.format(2, ',', '') }} g</td>
            <td class="align-middle text-right pointer" @click="show">{{ item.protein.format(2, ',', '') }} g</td>
            <td class="align-middle pointer" @click="show">{{ item.rating_comment }}</td>
        </template>

    </show>

</template>

<script>
    import show from '../../tables/rows/show';

    import { showMixin } from '../../../mixins/tables/rows/show.js';

    export default {

        components: {
            show,
        },

        mixins: [
            showMixin,
        ],

        props: {
            //
        },

        data () {
            return {
                form: {
                    //
                },
            };
        },

        methods: {
            //
        },

    };
</script>
