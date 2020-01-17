<template>
    <div v-if="item == null">
        nichts ausgewählt
    </div>
    <div class="row" v-else>
        <div class="col-8">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center">
                    <div class="col">{{ item.name }}</div>
                    <div class="btn-group btn-group-sm" role="group">
                        <template v-if="isEditing">
                            <button class="btn btn-secondary" @click="isEditing = false">Abbrechen</button>
                            <button class="btn btn-primary" @click="update">Speichern</button>
                        </template>
                        <template v-else>
                            <button class="btn btn-primary" @click="isEditing = true">Bearbeiten</button>
                        </template>
                        <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy"><i class="fas fa-fw fa-trash"></i></button>
                    </div>
                </div>
            </div>

            <div class="card" v-show="item.happiest_moment != null || isEditing">
                <div class="card-header">Glücklichster Moment des Tages</div>
                <template v-if="isEditing">
                    <div class="card-body">
                        <div class="form-group">
                            <textarea class="form-control" rows="3" v-model="form.happiest_moment"></textarea>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <div class="card-body white-space-pre" v-html="item.happiest_moment"></div>
                </template>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-header">Bewertung</div>
                    <template v-if="isEditing">
                        <div class="card-body">
                            <div class="form-group">
                                <select class="form-control" v-model="form.rating">
                                    <option :value="null">Keine Bewertung</option>
                                    <option :value="n" v-for="n in 10">{{ n }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" v-model="form.rating_comment">
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <button class="btn btn-link text-muted" @click="isEditing = false">Abbrechen</button>
                            <button class="btn btn-primary" @click="update">Speichern</button>
                        </div>
                    </template>
                    <template v-else>
                        <div class="card-body">
                            <div>{{ item.rating || 'noch nicht bewertet' }}</div>
                            <div>{{ item.rating_comment }}</div>
                        </div>
                        <div class="card-footer text-right"><button class="btn btn-primary" @click="isEditing = true">Bearbeiten</button></div>
                    </template>
                </div>

                <div class="card mt-3">
                    <div class="card-header">Dankbar für</div>
                    <div class="card-body">
                        <journal-gratitude-table :model="item"></journal-gratitude-table>
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
                required: false,
                type: Object,
                default: null,
            },
        },

        watch: {
            item(value) {
                this.form.body = value.body;
                this.form.date = value.date;
                this.form.happiest_moment = value.happiest_moment;
                this.form.rating = value.rating;
                this.form.rating_comment = value.rating_comment;
                if (this.form.body == null) {
                    this.isEditing = true;
                }
            },
        },

        data() {
            return {
                isEditing: false,
                form: {
                    body: null,
                    date: null,
                    happiest_moment: null,
                    rating: null,
                    rating_comment: null,
                },
            };
        },

        methods: {
            destroy() {
                var component = this;
                axios.delete(component.item.path)
                    .then( function (response) {
                        if (response.data.deleted) {
                            Vue.success('Tagebucheintrag gelöscht')
                            component.$emit("deleted", component.item.id);
                            return;
                        }

                        Vue.error('Tagebucheintrag konnte nicht gelöscht werden.');
                    });
            },
            update() {
                var component = this;
                axios.put(component.item.path, component.form)
                    .then( function (response) {
                        component.errors = {};
                        component.$emit('updated', response.data);
                        component.isEditing = false;
                        Vue.success('Tagebucheintrag gespeichert.');
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                        Vue.error('Tagebucheintrag konnte nicht gespeichert werden.');
                });
            },
        },

    };
</script>