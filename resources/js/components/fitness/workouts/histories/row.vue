<template>

    <show :item="item" :has-edit-button="false" @destroying="destroy()">

        <template v-slot:show>

            <td class="align-middle pointer" @click="show">{{ item.start_at_formatted }}</td>
            <td class="align-middle text-right pointer" @click="show">{{ item.distance_accum_formatted }} km</td>
            <td class="align-middle text-right pointer" @click="show">{{ item.duration_total_accum_formatted }}</td>
            <td class="align-middle text-right pointer" @click="show">{{ item.ascent_accum_formatted }} m</td>
            <td class="align-middle text-right pointer" @click="show">{{ item.calories_accum_formatted }} kcal</td>
            <td class="align-middle text-right pointer" @click="show">{{ item.heart_rate_avg_formatted }} bpm</td>
            <td class="align-middle text-right pointer" @click="show">{{ item.power_avg_formatted }} W</td>

        </template>

    </show>

</template>

<script>
    import show from '../../../tables/rows/show';
    import inputText from '../../../forms/inputs/text.vue';

    import { showMixin } from "../../../../mixins/tables/rows/show.js";

    export default {

        components: {
            show,
            inputText
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
                    name: this.item.name,
                },
            };
        },

        methods: {
            show() {
                location.href = this.item.path;
            }
        },

    };
</script>
