<template>

    <table-base :is-loading="isLoading" :is-showing-footer="true" :items-length="items.length" :has-filter="hasFilter()" @creating="create" @paginating="filter.page = $event" @searching="searching($event)">

        <template v-slot:form>
            <select-food v-model="form.food_id" :foods="foods"></select-food>
        </template>

        <template v-slot:filter>

        </template>

        <template v-slot:no-data>
            <div>
                <select-meal v-model="form.meal_id" :diet_meals="diet_meals" @input="addMeal($event)"></select-meal>
            </div>
            Mahlzeit von gestern hinzufügen (TODO)<br />
            Mahlzeit von Tag letzter Woche hinzufügen (TODO)<br />
        </template>

        <template v-slot:thead>
            <tr>
                <th class="">Nahrungsmittel</th>
                <th class="text-right">Menge</th>
                <th class="text-right">Kalorien</th>
                <th class="text-right">Kohlenhydrate</th>
                <th class="text-right">Proteine</th>
                <th class="text-right">Fette</th>
                <th class="text-right d-none d-sm-table-cell w-action">Aktion</th>
            </tr>
        </template>

        <template v-slot:tbody>
            <row :item="item" :key="item.id" :foods="foods" v-for="(item, index) in items" @deleted="deleted(index)" @updated="updated(index, $event)"></row>
        </template>

        <template v-slot:tfoot>
            <tr class="font-weight-bold">
                <td></td>
                <td></td>
                <td class="text-right">{{ nutrition_values.calories.format(2, ',', '.') }} kcal</td>
                <td class="text-right">{{ nutrition_values.carbohydrate.format(2, ',', '.') }} g</td>
                <td class="text-right">{{ nutrition_values.fat.format(2, ',', '.') }} g</td>
                <td class="text-right">{{ nutrition_values.protein.format(2, ',', '.') }} g</td>
                <td></td>
            </tr>
        </template>

    </table-base>

</template>

<script>
    import row from './row.vue';
    import tableBase from '../../../../tables/base.vue';
    import inputText from '../../../../forms/inputs/text.vue';
    import selectFood from '../../../../forms/inputs/food.vue';
    import selectMeal from '../../../../forms/inputs/meal.vue';

    import { baseMixin } from '../../../../../mixins/tables/base.js';

    export default {

        components: {
            inputText,
            row,
            selectFood,
            selectMeal,
            tableBase,
        },

        mixins: [
            baseMixin,
        ],

        props: {
            foods: {
                required: true,
                type: Array,
            },
            diet_meals: {
                required: true,
                type: Array,
            },
            model: {
                required: true,
                type: Object,
            },
        },

        watch: {
            model(newValue, oldValue) {
                this.fetch();
            }
        },

        data () {
            return {
                filter: {
                    //
                },
                form: {
                    food_id: null,
                    meal_id: null,
                },
            };
        },

        computed: {
            nutrition_values() {
                var values = {
                    calories: 0,
                    carbohydrate: 0,
                    fat: 0,
                    protein: 0,
                };

                this.items.forEach(function (food, index) {
                    values.calories += food.calories;
                    values.carbohydrate += food.carbohydrate;
                    values.fat += food.fat;
                    values.protein += food.protein;
                });

                return values;
            }
        },

        methods: {
            addMeal(meal_id) {
                if (! meal_id) {
                    return;
                }
                let component = this;
                axios.post(this.model.foods_meals_path, {
                    meal_id: meal_id
                })
                    .then(function (response) {
                        component.items.push(...response.data);
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(function () {
                        component.form.meal_id = null;
                    });
            },
            resetForm() {
                this.resetErrors();
                this.form.food_id = null;
            },
        },
    };
</script>
