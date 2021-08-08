<template>
    <div class="card mb-3">
        <div class="card-header d-flex align-items-center" v-if="is_editing">
            <div class="col px-0 mr-3">
                <input-text v-model="form.name" placeholder="Bezeichnung" :error="error('name')" @keydown.enter="update"></input-text>
            </div>
            <div class="btn-group btn-group-sm" role="group">
                <button class="btn btn-primary" @click="update"><i class="fas fa-fw fa-save"></i></button>
                <button class="btn btn-secondary" @click="is_editing = false"><i class="fas fa-fw fa-times"></i></button>
            </div>
        </div>
        <div class="card-header d-flex align-items-center" v-else>
            <div class="col px-0">{{ item.name }}</div>
            <div class="btn-group btn-group-sm" role="group">
                <button class="btn btn-secondary" @click="is_editing = true"><i class="fas fa-fw fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy"><i class="fas fa-fw fa-trash"></i></button>
            </div>
        </div>
        <div class="card-body">
            <food-table :model="item" :foods="foods" :diet_meals="diet_meals" :index-path="item.foods_path"></food-table>
        </div>
    </div>
</template>

<script>
    import foodTable from './food/table.vue';
    import inputText from '../../../forms/inputs/text.vue';

    export default {

        components: {
            foodTable,
            inputText,
        },

        props: {
            foods: {
                required: true,
                type: Array,
            },
            diet_meals: {
                required: true,
                type: Array,
            },
            item: {
                required: true,
                type: Object,
            },
        },

        data() {
            return {
                form: {
                    name: this.item.name,
                },
                errors: {},
                is_editing: false,
            };
        },

        methods: {
            update() {
                var component = this;
                axios.put(component.item.path, component.form)
                    .then( function (response) {
                        component.errors = {};
                        component.$emit('updated', response.data);
                        component.is_editing = false;
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
            error(name) {
                return (name in this.errors ? this.errors[name][0] : '');
            },
        },
    };
</script>