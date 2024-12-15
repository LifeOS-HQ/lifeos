<template>

    <div>
        <div>
            <div class="form-group">
                <label>Was stört mich?</label>
                <input-textarea v-model="form.challenge" :rows="15" :error="error('challenge')"></input-textarea>
            </div>
        </div>
        <div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Was möchte Ich?</label>
                <div class="col-sm-8">
                    <input-text v-model="form.wish" :error="error('wish')"></input-text>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Level</label>
                <div class="col-sm-8">
                    <input-text v-model.number="form.level" :error="error('level')"></input-text>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Was wäre das schönste Ergebnis?</label>
            <input-textarea v-model="form.outcome" :rows="15" :error="error('outcome')"></input-textarea>
        </div>
        <div class="form-group">
            <label>Was steht mir im Weg?</label>
            <input-textarea v-model="form.obstacle" :rows="15" :error="error('obstacle')"></input-textarea>
        </div>
        <div>
            <div class="form-group">
                <label>Was kann ich das Hindernis überwinden?</label>
                <input-textarea v-model="form.plan" :rows="15" :error="error('plan')"></input-textarea>
            </div>
        </div>
        <div class="form-group">
            <label>Was habe ich daraus gelernt?</label>
            <input-textarea v-model="form.loot" :rows="15" :error="error('loot')"></input-textarea>
        </div>

        <div class="row my-5"><div class="col"></div></div>

        <div class="fixed-bottom bg-white p-3 text-right">
            <button type="button" class="btn btn-primary btn-sm" @click="create">Anlegen</button>
        </div>
    </div>

</template>

<script>
    import inputText from '../../components/forms/inputs/text.vue';
    import inputTextarea from '../../components/forms/inputs/textarea.vue';

    import { editableMixin } from '../../mixins/tables/rows/editable.js';

    export default {

        components: {
            inputText,
            inputTextarea,
        },

        mixins: [
            editableMixin,
        ],

        props: {
            indexPath: {
                required: true,
                type: String,
            },
            item: {
                required: false,
                type: Object,
                default: null,
            },
        },

        data () {
            return {
                errors: {},
                filter: {
                    //
                },
                form: {
                    challenge: null,
                    level: 1,
                    title: null,
                    wish: null,
                    outcome: null,
                    obstacle: null,
                    plan: null,
                    loot: null,
                },
            };
        },

        computed: {
            //
        },

        methods: {
            create() {
                var component = this;
                component.isStoring = true
                axios.post(this.indexPath, component.form)
                    .then(function (response) {
                        component.created(response.data);
                        Vue.successCreate(response.data);
                })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                        Vue.errorCreate();
                })
                    .finally(function () {
                        component.isStoring = false;
                });
            },
            created(item) {
                location.href = item.path;
            },
        },
    };
</script>
