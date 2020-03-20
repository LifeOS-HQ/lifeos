<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div>Server</div>
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
            <table class="table table-hover table-striped" v-else>
                <thead>
                    <tr>
                        <th>Server</th>
                        <th class="text-right">Uptime</th>
                        <th class="text-right">Load 1 min</th>
                        <th class="text-right">Load 5 min</th>
                        <th class="text-right">Load 15 min</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Lokal</td>
                        <td class="text-right">{{ data.uptime.uptime.formatted }}</td>
                        <td class="text-right">{{ data.uptime.load[1].format(2, ',', '.') }}</td>
                        <td class="text-right">{{ data.uptime.load[5].format(2, ',', '.') }}</td>
                        <td class="text-right">{{ data.uptime.load[15].format(2, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                isLoading: true,
                data: {},
            };
        },

        mounted () {
            this.fetch();
        },

        methods: {
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get('/home/server')
                    .then( function (response) {
                        component.data = response.data;
                        component.isLoading = false;
                    });
            },
        },

    };
</script>