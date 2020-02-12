<template>
    <div>
        <div class="row">
            <h1 class="col">{{ model.title }}</h1>
            <div class="col-auto">
                <a class="btn btn-secondary" href="/review">Übersicht</a>
            </div>
        </div>

        <div class="row">

            <block-index class="col-8" :model="model"></block-index>

            <div class="col-4">
                <lifearea-index :model="model"></lifearea-index>

                <div class="card" v-if="false">
                    <div class="card-header"></div>
                    <div class="card-body"></div>
                </div>
            </div>


        </div>
    </div>
</template>

<script>
    import blockIndex from '../../components/review/block/index.vue';
    import lifeareaIndex from '../../components/review/lifearea/index.vue';

    export default {

        components: {
            blockIndex,
            lifeareaIndex,
        },

        props: {
            model: {
                required: true,
                type: Object,
            },
        },

        data() {
            return {
                errors: {},
                blocks: this.model.blocks,
            };
        },

        methods: {

        },

    };
</script>