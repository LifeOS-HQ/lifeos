<template>
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between">
            <div>Stimmung</div>
        </div>
        <div class="card-body">

            <div v-if="isLoading" class="mt-3 p-5">

                <center>
                    <span style="font-size: 48px;">
                        <i class="fas fa-spinner fa-spin"></i><br />
                    </span>
                    Lade Daten..
                </center>

            </div>

            <div v-else>

                <div class="text-center">{{ days_over }}/{{ days_in_year }} (noch {{ days_in_year - days_over }} Tage)</div>
                <div class="row">
                    <div class="col-md-4 col-lg-3 col-lg-2 text-center mb-3" :key="month_index" v-for="(month, month_index) in months">
                        <span class="mb-1">{{ month.name }}</span>
                        {{ month.days_over }} / {{ month.days.length }} <span v-if="month.show_days_left">(noch {{ month.days_left }} Tage)</span>
                        <div class="d-flex align-items-center justify-content-center">
                            <a :href="day.path"
                                data-toggle="popover"
                                data-placement="bottom"
                                data-trigger="hover"
                                :title="day.name + ', ' + day.formatted + ' - Bewertung ' + day.mood.mood"
                                :data-content="day.mood.mood_note"
                                style="width: 10px; height: 10px; border: 1px solid black;"
                                :class="day.mood.bg_class"
                                :key="day_index"
                                v-for="(day, day_index) in month.days"></a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    export default {

        computed: {

        },

        data() {
            return {
                isLoading: false,
                months: [],
                days_over: 0,
                days_in_year: 0,
            };
        },

        mounted () {
            this.fetch();
        },

        methods: {
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get('/home/mood')
                    .then( function (response) {
                        component.months = response.data.months;
                        component.days_over = response.data.days_over;
                        component.days_in_year = response.data.days_in_year;
                        component.isLoading = false;

                        $('[data-toggle="popover"]').popover({
                            html: true,
                        });
                    });
            },
        },

    };
</script>