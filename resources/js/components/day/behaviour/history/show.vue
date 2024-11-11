<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ item.behaviour.name }}</div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label col-form-label-sm" for="condition">Zustand</label>
                        <div class="col-sm-8">
                            <select id="condition" class="form-control form-control-sm" v-model="form.condition">
                                <option>Bitte wählen</option>
                            </select>
                            <div class="invalid-feedback" v-text="'condition' in errors ? errors.condition[0] : ''"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        props: {
            item: {
                required: true,
                type: Object,
            },
        },

        data() {
            return {
                form: {
                    description: this.item.description,
                },
                errors: {},
                isEditing: false,
            };
        },

        methods: {
            update() {
                var component = this;
                axios.put(component.item.path, component.form)
                    .then( function (response) {
                        component.errors = {};
                        component.$emit('updated', response.data);
                        component.isEditing = false;
                        Vue.success('Datensatz gespeichert.');
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                        Vue.error('Datensatz konnte nicht gespeichert werden.');
                });
            },
            destroy() {
                var component = this;
                axios.delete(component.item.path)
                    .then( function (response) {
                        component.errors = {};
                        component.$emit('deleted');
                        Vue.success('Datensatz gelöscht.');
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                        Vue.error('Datensatz konnte nicht gelöscht werden.');
                });
            },
            show() {
                location.href = this.item.show_path;
            },
        },
    };
</script>
