<template>

    <show :item="item" :has-edit-button="false" @destroying="destroy()">

        <template v-slot:show>

            <td class="align-middle pointer" @click="show">{{ item.start_at_formatted }}</td>

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