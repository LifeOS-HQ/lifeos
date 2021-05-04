<template>

    <div class="card mb-3">
        <div class="card-header">Makro Rechner</div>
        <div class="card-body">

            <div class="form-group row">
                <label class="col-sm-6 col-form-label col-form-label-sm" for="weight">Gewicht [kg]</label>
                <div class="col-sm-6">
                    <input type="number" min="0" steps="1" class="form-control form-control-sm" id="weight" v-model="weight">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-6 col-form-label col-form-label-sm" for="calories_out">Kalorien [kcal]</label>
                <div class="col-sm-6">
                    <input type="number" min="0" steps="1" class="form-control form-control-sm" id="calories_out" v-model="calories_out">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-6 col-form-label col-form-label-sm" for="protein.per_kg_weight_formatted">Protein / kg Körpergewicht [g]</label>
                <div class="col-sm-6">
                    <input type="number" min="0" steps="1" class="form-control form-control-sm" id="protein.per_kg_weight_formatted" v-model="protein.per_kg_weight_formatted">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-6 col-form-label col-form-label-sm" for="fat.percentage">Fett [%]</label>
                <div class="col-sm-6">
                    <input type="number" min="0" steps="1" class="form-control form-control-sm" id="fat.percentage" v-model="fat.percentage">
                </div>
            </div>

            <table class="table table-fixed table-hover table-striped table-sm bg-white">
                <thead>
                    <tr>
                        <th width="100%">Nährstoff</th>
                        <th class="text-right" width="100">g</th>
                        <th class="text-right" width="100">kcal</th>
                        <th class="text-right" width="100">%</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Proteine</td>
                        <td class="text-right">{{ protein_grams.format(0, ',', '') }} g</td>
                        <td class="text-right">{{ protein.calories.format(0, ',', '') }} kcal</td>
                        <td class="text-right">{{ protein.percentage.format(0, ',', '') }} %</td>
                    </tr>
                    <tr>
                        <td>Fette</td>
                        <td class="text-right">{{ fat_grams.format(0, ',', '') }} g</td>
                        <td class="text-right">{{ fat.calories.format(0, ',', '') }} kcal</td>
                        <td class="text-right">{{ fat.percentage.format(0, ',', '') }} %</td>
                    </tr>
                    <tr>
                        <td>Kohlenhydrate</td>
                        <td class="text-right">{{ carbohydrates_grams.format(0, ',', '') }} g</td>
                        <td class="text-right">{{ carbohydrates.calories.format(0, ',', '') }} kcal</td>
                        <td class="text-right">{{ carbohydrates.percentage.format(0, ',', '') }} %</td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>

</template>

<script>
    export default {

        components: {
            //
        },

        mixins: [
            //
        ],

        props: {
            //
        },

        computed: {
            protein_per_kg_weight() {
                if (! this.protein.per_kg_weight_formatted) {
                    return 0;
                }

                return parseFloat(Number(this.protein.per_kg_weight_formatted.replace(',', '.')).format(1, '.', ''));
            },
            protein_grams() {
                if (this.calories_out <= 0 || this.weight <= 0) {
                    return 0;
                }

                var grams = this.protein_per_kg_weight * this.weight;

                this.protein.calories = grams * 4;
                this.protein.percentage = this.protein.calories / this.calories_out * 100;

                return grams;
            },
            fat_grams() {
                if (this.calories_out <= 0 || this.weight <= 0) {
                    return 0;
                }

                this.fat.calories = this.calories_out * this.fat.percentage / 100;

                return this.fat.calories / 9;
            },
            carbohydrates_grams() {
                if (this.calories_out <= 0 || this.weight <= 0) {
                    return 0;
                }

                this.carbohydrates.calories = this.calories_out - this.protein.calories - this.fat.calories;
                this.carbohydrates.percentage = this.carbohydrates.calories / this.calories_out * 100;

                return this.carbohydrates.calories / 4;
            },
        },

        data () {
            return {
                calories_out: 2500,
                weight: 75,
                carbohydrates: {
                    calories: 0,
                    percentage: 0,
                },
                fat: {
                    percentage: 25,
                    calories: 0
                },
                protein: {
                    per_kg_weight_formatted: '2,0',
                    calories: 0,
                    percentage: 0,
                },
            };
        },

    };
</script>