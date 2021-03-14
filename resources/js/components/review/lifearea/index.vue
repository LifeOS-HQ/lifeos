<template>
    <div class="card">
        <div class="card-header">Lebensbereiche</div>
        <div class="card-body">
            <table class="table table-fixed table-hover table-striped table-sm bg-white">
                <thead>
                    <tr>
                        <th>Lebensbereich</th>
                        <th class="text-right">Bewertung</th>
                        <th>Beschreibung</th>
                        <th width="100"></th>
                    </tr>
                </thead>
                <tbody>
                    <show :item="item" :key="index" v-for="(item, index) in items" @deleted="remove(index)" @updated="updated(index, $event)"></show>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="align-middle"></td>
                        <td class="align-middle font-weight-bold text-right">Ø {{ avarage }}</td>
                        <td class="align-middle"></td>
                        <td class="align-middle text-right"></td>
                    </tr>
                </tfoot>
            </table>
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
        },

        computed: {
            avarage() {
                var sum = 0,
                    count = 0;

                for (var index in this.items) {
                    var rating = this.items[index]['rating'];
                    if (rating == null) {
                        continue;
                    }
                    sum += rating;
                    count++;
                }

                return (count == 0 ? 0 : sum / count);
            },
            categories() {
                return [
                    'A', 'B', 'C', 'D', 'E',
                ];
            },
            series() {
                return [
                    1,2,3,4,5
                ];
            },
        },

        data() {
            return {
                items: this.model.lifeareas,
            };
        },

        methods: {
            store() {
                var component = this;
                axios.post(component.model.path + '/block')
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