<template>

        <div class="card mb-3">
            <div class="card-header d-flex align-items-center">
                <div class="col pl-0">Hindernisse</div>
                <div class="">
                    <a :href="indexPath + '/create'" target="_blank" class="btn btn-sm btn-primary"><i class="fas fa-plus-square"></i></a>
                </div>
            </div>
            <div v-if="isLoading">
                <div class="p-5">
                    <center>
                        <span style="font-size: 48px;">
                            <i class="fas fa-spinner fa-spin"></i><br />
                        </span>
                        Lade Daten..
                    </center>
                </div>
            </div>
            <div class="list-group" v-else>
                <template v-for="(item, index) in items">
                    <row :item="item" :key="item.id" @deleted="deleted(index)" @updated="updated(index, $event)"></row>
                </template>
            </div>
        </div>

</template>

<script>
    import row from './row.vue';
    import inputText from '../../forms/inputs/text.vue';

    import { baseMixin } from '../../../mixins/tables/base.js';

    export default {

        components: {
            inputText,
            row,
        },

        mixins: [
            baseMixin,
        ],

        props: {
            day: {
                required: false,
                type: Object,
                default: null,
            },
        },

        data () {
            return {
                filter: {
                    //
                },
                form: {
                    wish: '',
                    created_day_id: this.day.id,
                },
            };
        },

        computed: {
            //
        },

        methods: {
            //
        },
    };
</script>
