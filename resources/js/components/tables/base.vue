<template>
    <div>
        <div class="row mb-3">
            <div class="col d-flex align-items-start mb-1 mb-sm-0">
                <slot name="form"></slot>
                <button class="btn btn-primary btn-sm" @click="$emit('creating')" v-if="hasCreateButton"><i class="fas fa-plus-square"></i></button>
            </div>
            <div class="col-auto d-flex">
                <div class="form-group" style="margin-bottom: 0;">
                    <filter-search v-model="filter.searchtext" @input="$emit('searching', filter.searchtext)" v-if="isSearchable"></filter-search>
                </div>
                <button class="btn btn-secondary btn-sm ml-1" @click="filter.show = !filter.show" v-if="hasFilter"><i class="fas fa-filter"></i></button>
            </div>
        </div>

        <form v-if="filter.show" id="filter" class="pb-1">
            <div class="form-row">

                <slot name="filter"></slot>

            </div>
        </form>

        <div v-if="isLoading" class="p-5">
            <center>
                <span style="font-size: 48px;">
                    <i class="fas fa-spinner fa-spin"></i><br />
                </span>
                Lade Daten..
            </center>
        </div>
        <table class="table table-fixed table-hover table-striped table-sm bg-white" v-else-if="itemsLength">
            <thead>
                <slot name="thead"></slot>
            </thead>
            <tbody>
                <slot name="tbody"></slot>
            </tbody>
            <tfoot v-show="isShowingFooter">
                <slot name="tfoot"></slot>
            </tfoot>
        </table>
        <div class="alert alert-dark" v-else><center><slot name="no-data">Keine Datensätze vorhanden</slot></center></div>
        <nav aria-label="Page navigation example">
            <ul class="pagination" v-show="paginate.lastPage > 1">
                <li class="page-item" v-show="paginate.prevPageUrl">
                    <a class="page-link" href="#" @click.prevent="$emit('paginating', paginate.currentPage - 1)">Previous</a>
                </li>

                <li class="page-item" v-for="n in paginate.lastPage" v-bind:class="{ active: (n == paginate.currentPage) }"><a class="page-link" href="#" @click.prevent="$emit('paginating', n)">{{ n }}</a></li>

                <li class="page-item" v-show="paginate.nextPageUrl">
                    <a class="page-link" href="#" @click.prevent="$emit('paginating', paginate.currentPage + 1)">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
    import filterSearch from "../filter/search.vue";

    export default {

        components: {
            filterSearch
        },

        props: {
            itemsLength: {
                type: Number,
                required: true,
            },
            hasCreateButton: {
                type: Boolean,
                required: false,
                default: true,
            },
            hasFilter: {
                type: Boolean,
                required: false,
                default: false,
            },
            isLoading: {
                type: Boolean,
                required: false,
                default: false,
            },
            isSearchable: {
                type: Boolean,
                required: false,
                default: true,
            },
            isShowingFooter: {
                type: Boolean,
                required: false,
                default: false,
            },
            paginate: {
                type: Object,
                required: false,
                default () {
                    return {

                    }
                },
            },
        },

        data () {
            return {
                filter: {
                    show: false,
                    searchtext: '',
                },
            };
        },

        methods: {

        },
    };
</script>