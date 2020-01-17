<template>
    <div>
        <div class="row">
            <div class="col mb-1 mb-sm-0">

            </div>
            <div class="col-auto d-flex">
                <div class="form-group" style="margin-bottom: 0;">

                </div>
            </div>
        </div>

        <form v-if="filter.show" id="filter" class="mt-1">
            <div  class="form-row">



            </div>
        </form>

        <div v-if="isLoading" class="mt-3 p-5">
            <center>
                <span style="font-size: 48px;">
                    <i class="fas fa-spinner fa-spin"></i><br />
                </span>
                Lade Daten..
            </center>
        </div>
        <div class="table-responsive mt-3" v-else="items.length">
            <table class="table table-hover table-striped bg-white">
                <tbody>
                    <row :item="item" :key="item.id" :uri="uri" v-for="(item, index) in items" @updated="updated(index, $event)" @deleted="remove(index)"></row>
                    <tr>
                        <td class="align-middle" width="80%">
                            <input class="form-control align-middle" :class="'text' in errors ? 'is-invalid' : ''" type="text" v-model="form.text" @keydown.enter="store">
                            <div class="invalid-feedback" v-text="'text' in errors ? errors.text[0] : ''"></div>
                        </td>
                        <td class="align-middle text-right" width="20%">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-secondary" title="Anlegen" @click="store"><i class="fas fa-fw fa-plus"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import row from "./row.vue";

    export default {

        components: {
            row,
        },

        props: {
            model: {
                required: true,
                type: Object,
            },
        },

        data () {

            var d = new Date();

            return {
                uri: this.model.path + '/gratitude',
                items: [],
                isLoading: true,
                paginate: {
                    nextPageUrl: null,
                    prevPageUrl: null,
                    lastPage: 0,
                },
                filter: {
                    show: true,
                    page: 1,
                },
                form: {
                    text: '',
                },
                errors: {},
            };
        },

        mounted() {

            this.fetch();

        },

        watch: {

        },

        computed: {

        },

        methods: {
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(component.uri, {
                    params: component.filter
                })
                    .then(function (response) {
                        component.items = response.data;
                        component.isLoading = false;
                    })
                    .catch(function (error) {
                        Vue.error('Jahre konnten nicht geladen werden!');
                        console.log(error);
                    });
            },
            store() {
                var component = this;
                axios.post(component.uri, component.form)
                    .then(function (response) {
                        component.errors = {};
                        component.items.push(response.data);
                        component.form.text = '';
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                        Vue.error('Datensatz konnte nicht erzeugt werden!');
                        console.log(error);
                    });
            },
            search() {
                this.filter.page = 1;
                this.fetch();
            },
            updated(index, item) {
                Vue.set(this.items, index, item);
            },
            remove(index) {
                this.items.splice(index, 1);
            },
            showPageButton(page) {
                if (page == 1 || page == this.paginate.lastPage) {
                    return true;
                }

                if (page <= this.filter.page + 2 && page >= this.filter.page - 2) {
                    return true;
                }

                return false;
            },
        },
    };
</script>