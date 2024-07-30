<template>
    <div class="card mb-3">
        <div class="card-header d-flex align-items-center" v-if="is_editing">
            <div class="col px-0 mr-3">
                <input-text v-model="form.name" placeholder="Bezeichnung" :error="error('name')" @keydown.enter="update"></input-text>
            </div>
            <div class="col px-0 mr-3 d-flex">
                <input-text v-model="form.time_formatted" placeholder="Zeit" :error="error('time_formatted')" @keydown.enter="update"></input-text>
                <button class="btn btn-sm btn-secondary ml-1" @click="setTimeFormatted(false)"><i class="fas fa-fw fa-clock"></i></button>
            </div>
            <div class="col px-0 mr-3">
                <input-text v-model="form.rating_comment" placeholder="Notiz" :error="error('notiz')" @keydown.enter="update"></input-text>
            </div>
            <div class="btn-group btn-group-sm" role="group">
                <button class="btn btn-primary" @click="update"><i class="fas fa-fw fa-save"></i></button>
                <button class="btn btn-secondary" @click="is_editing = false"><i class="fas fa-fw fa-times"></i></button>
            </div>
        </div>
        <div class="card-header d-flex align-items-center" v-else>
            <div class="col px-0 d-flex align-items-center">
                <div>{{ item.name }} um {{ item.time_formatted }} {{ item.rating_comment ? ' (' + item.rating_comment + ')' : '' }}</div>
                <button class="btn btn-sm btn-secondary ml-1" @click="setTimeFormatted(true)"><i class="fas fa-fw fa-clock"></i></button>
            </div>
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

        watch: {
            item(newValue, oldValue) {
                this.form.name = newValue.name;
                this.form.time_formatted = newValue.time_formatted;
                this.form.rating_comment = newValue.rating_comment;
            }
        },

        computed: {
            nutrition_values() {
                var values = {
                    calories: 0,
                    carbohydrate: 0,
                    fat: 0,
                    protein: 0,
                };

                this.meal_foods.forEach(function (food, index) {
                    values.calories += food.calories;
                    values.carbohydrate += food.carbohydrate;
                    values.fat += food.fat;
                    values.protein += food.protein;
                });

                return values;
            }
        },

        data() {
            return {
                form: {
                    name: this.item.name,
                    time_formatted: this.item.time_formatted,
                    rating_comment: this.item.rating_comment,
                },
                errors: {},
                is_editing: false,
                meal_foods: [],
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
            setTimeFormatted(should_save = false) {
                this.form.time_formatted = new Date().toLocaleTimeString('de-DE', {hour: '2-digit', minute:'2-digit'});
                if (should_save) {
                    this.update();
                }
            }
        },
    };
</script>
