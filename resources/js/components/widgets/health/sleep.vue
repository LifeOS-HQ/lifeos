<template>

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div>Schlaf</div>
            <div></div>
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

                <highcharts :options="chartOptions"></highcharts>

                <table class="table table-fixed table-hover table-striped table-sm bg-white">
                    <tbody>
                        <tr>
                            <td>Ø Zeit im Bett</td>
                            <td>{{ table.time_in_bed_avg_formatted }} h</td>
                        </tr>
                        <tr>
                            <td>Ø Schlaf</td>
                            <td>{{ table.sleep_avg_formatted }} h</td>
                        </tr>
                        <tr>
                            <td>Ø Eingeschlafen</td>
                            <td>{{ table.sleep_start_avg_formatted }} Uhr</td>
                        </tr>
                        <tr>
                            <td>Ø Aufgewacht</td>
                            <td>{{ table.sleep_end_avg_formatted }} Uhr</td>
                        </tr>
                    </tbody>
                </table>

            </div>

        </div>

    </div>

</template>

<script>
    import {Chart} from 'highcharts-vue'

    export default {

        components: {
            highcharts: Chart
        },

        mixins: [
            //
        ],

        props: {
            //
        },

        data () {
            return {
                chartOptions: {

                },
                isLoading: true,
                table: {

                },
                indexPath: '/widgets/health/sleep',
                filter: {

                },
            };
        },

        mounted () {
            this.fetch();
        },

        methods: {
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(component.indexPath, {
                    params: component.filter,
                })
                    .then( function (response) {
                        component.fetched(response)

                        component.isLoading = false;
                    })
                    .catch(function (error) {
                        console.log(error);
                        Vue.error('Daten konnten nicht geladen werden.');
                    });
            },
            fetched(response) {
                this.chartOptions = response.data.chartOptions;
                this.table = response.data.table;
            }
        },

    };
</script>