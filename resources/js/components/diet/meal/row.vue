<template>

    <show :item="item" :has-edit-button="true" :has-show-button="true" @destroying="destroy()">

        <template v-slot:show>
            <td class="align-middle pointer" @click="show">{{ item.name }}</td>
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