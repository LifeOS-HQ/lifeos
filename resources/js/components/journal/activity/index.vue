<template>
    <div class="mt-3 mb-3">
        <h2>Aktivitäten</h2>
        <show :item="item" :activities="activities" :key="index" v-for="(item, index) in items" @deleted="remove(index)" @updated="updated(index, $event)"></show>
        <div class="d-flex align-items-start">
            <div class="form-group mb-0 mr-1">
                <select class="form-control" v-model="form.activity_id">
                    <option :value="activity.id" v-for="(activity, index) in activities">{{ activity.title }}</option>
                </select>
            </div>
            <button type="button" class="btn btn-secondary" title="Anlegen" @click="store"><i class="fas fa-fw fa-plus"></i></button>
        </div>
    </div>
</template>

<script>
    import show from './show.vue';

    export default {

        components: {
            show,
        },

        props: {
            model: {
                required: true,
                type: Object,
            },
            activities: {
                required: true,
                type: Array,
            },
        },

        data() {
            return {
                items: this.model.activities,
                form: {
                    activity_id: this.activities[0].id
                },
            };
        },

        methods: {
            store() {
                var component = this;
                axios.post(component.model.path + '/activity', component.form)
                    .then(function (response) {
                        component.errors = {};
                        component.items.push(response.data);
                    })
                    .catch(function (error) {
                        console.log(error);
                        component.errors = error.response.data.errors;
                        Vue.error('Datensatz konnte nicht erzeugt werden!');
                    });
            },
            remove(index) {
                this.items.splice(index, 1);
            },
            updated(index, item) {
                Vue.set(this.items, index, item);
            },
        },
    };
</script>