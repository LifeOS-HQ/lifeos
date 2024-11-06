<template>

    <show :item="item" :has-edit-button="false" @destroying="destroy()">

    <template v-slot:show>

        <td class="align-middle pointer" @click="show">{{ item.name }}</td>

    </template>

    </show>

</template>

<script>
    import show from '../tables/rows/show.vue';

    import { showMixin } from '../../mixins/tables/rows/show.js';

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
                //
            };
        },

        methods: {
            //
        },

    };
</script>
