<template>
    <div class="row sticky-top sticky-offset">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ item.behaviour.name }} <a :href="item.behaviour.path" target="_blank"><i class="fas fa-link"></i></a></div>
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

                    <table class="table table-fixed table-hover table-striped table-sm bg-white" v-if="item.values.length">
                        <thead>
                            <tr>
                                <th>Attribut</th>
                                <th>Wert</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr :key="value.id" v-for="(value, index) in item.values">
                                <td>{{ value.attribute.name }}</td>
                                <td>{{ value.raw }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-between">
                        <button class="btn btn-sm btn-secondary" @click="previous">Zurück</button>
                        <button class="btn btn-sm btn-secondary" @click="incomplete" v-if="item.is_completed"><i class="fas fa-fw fa-check-square"></i></button>
                        <button class="btn btn-sm btn-primary" @click="complete" v-else><i class="far fa-fw fa-square"></i></button>
                        <button class="btn btn-sm btn-secondary" @click="next">Weiter</button>
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

        mounted() {
            const component = this;
            window.addEventListener('keydown', function(event) {

                // Not in input or textarea
                const target_tag_name = event.target.tagName.toLowerCase();
                if (target_tag_name === 'input' || target_tag_name === 'textarea') {
                    return;
                }

                // Space
                if (event.keyCode === 32) {
                    event.preventDefault();
                    component.complete();
                }
                // Left arrow
                else if (event.keyCode === 39) {
                    event.preventDefault();
                    component.next();
                }
                // Right arrow
                else if (event.keyCode === 37) {
                    event.preventDefault();
                    component.previous();
                }
            });
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
            next() {
                this.$emit('next');
            },
            previous() {
                this.$emit('previous');
            },
            complete() {
                this.$emit('complete');
            },
            incomplete() {
                this.$emit('incomplete');
            },
        },
    };
</script>

<style scoped>
    .sticky-offset {
        top: 60px;
    }
</style>
