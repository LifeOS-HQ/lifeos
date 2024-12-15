<template>

    <div>
        <div>
            <div class="form-group">
                <label>Was stört mich?</label>
                <input-textarea v-model="form.challenge" :rows="15" :error="error('challenge')"></input-textarea>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Wunsch</label>
            <div class="col-sm-8">
                <input-text v-model="form.wish" :error="error('wish')" @keydown.enter="update"></input-text>
            </div>
        </div>
        <div class="form-group">
            <label>Ergebnis</label>
            <input-textarea v-model="form.outcome" :error="error('outcome')"></input-textarea>
        </div>
        <div class="form-group">
            <label>Hindernisse</label>
            <input-textarea v-model="form.obstacle" :error="error('obstacle')"></input-textarea>
        </div>
        <div class="form-group">
            <label>Plan</label>
            <input-textarea v-model="form.plan" :error="error('plan')"></input-textarea>
        </div>
        <div class="form-group">
            <label>Loot</label>
            <input-textarea v-model="form.loot" :error="error('loot')"></input-textarea>
        </div>

        <div class="row my-5"><div class="col"></div></div>

        <div class="fixed-bottom bg-white p-3 text-right">
            <button type="button" class="btn btn-primary btn-sm" @click="update">Speichern</button>
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
            //
        },

        data () {
            return {
                filter: {
                    //
                },
                form: {
                    challenge: this.item.challenge,
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
            update() {
            var component = this;
            axios.put(component.item.path, component.form)
                .then( function (response) {
                    component.errors = {};
                    component.isEditing = false;
                    Vue.success('Das Hindernis wurde erfolgreich gespeichert.');
                })
                .catch(function (error) {
                    component.errors = error.response.data.errors;
            });
        },
        },
    };
</script>
