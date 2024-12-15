<template>

    <div class="list-group-item">
        <div class="form-group row mb-0">
            <label class="col-sm-4 col-form-label">Wunsch</label>
            <div class="col-sm-8">
                <input-text v-model="form.wish" :error="error('wish')" @keydown.enter="update"></input-text>
            </div>
        </div>
    </div>

</template>

<script>
    import inputText from '../../forms/inputs/text.vue';

    import { editableMixin } from '../../../mixins/tables/rows/editable.js';

    export default {

        components: {
            inputText,
        },

        mixins: [
            editableMixin,
        ],

        props: {
            item: {
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
                    level: this.item.level,
                    title: this.item.title,
                    wish: this.item.wish,
                    outcome: this.item.outcome,
                    obstacle: this.item.obstacle,
                    plan: this.item.plan,
                    loot: this.item.loot,
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
