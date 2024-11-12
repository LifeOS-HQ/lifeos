<template>
    <div class="row sticky-top sticky-offset">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ item.behaviour.name }}</div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label col-form-label-sm" for="start_at_formatted">Start</label>
                        <div class="col-sm-8">
                            <inputText id="start_at_formatted" v-model="form.start_at_formatted" :error="error('start_at_formatted')"></inputText>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label col-form-label-sm" for="end_at_formatted">Ende</label>
                        <div class="col-sm-8">
                            <inputText id="end_at_formatted" v-model="form.end_at_formatted" :error="error('end_at_formatted')"></inputText>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import inputText from '../../../forms/inputs/text.vue';

    export default {

        components: {
            inputText,
        },

        props: {
            item: {
                required: true,
                type: Object,
            },
        },

        data() {
            return {
                form: {
                    end_at_formatted: this.item.end_at_formatted,
                    start_at_formatted: this.item.start_at_formatted,
                },
                errors: {},
                isEditing: false,
            };
        },

        methods: {
            error(name) {
                return (name in this.errors ? this.errors[name][0] : '');
            },
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

<style scoped>
    .sticky-offset {
        top: 60px;
    }
</style>
