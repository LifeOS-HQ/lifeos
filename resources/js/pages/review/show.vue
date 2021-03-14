<template>
    <div>
        <div class="row">
            <h1 class="col">{{ model.title }}</h1>
            <div class="col-auto">
                <a class="btn btn-secondary btn-sm" :href="model.index_path">Übersicht</a>
            </div>
        </div>

        <div class="row">

            <div class="col-12 col-lg-6">

                <block-index :model="model"></block-index>

                <div class="card mt-3">
                    <div class="card-header">Newsletter</div>
                    <div class="card-body">

                        Newsletter aus Blocks erstellen. Hier eine Auswahl der Blocks treffen.

                    </div>
                </div>

            </div>

            <div class="col-12 col-lg-6">
                <lifearea-index :model="model"></lifearea-index>

                <div class="card mt-3">
                    <div class="card-header">Tage</div>
                    <div class="card-body">

                        <table class="table table-fixed table-hover table-striped table-sm bg-white">
                            <thead>
                                <th width="90">Datum</th>
                                <th class="text-right" width="90">Bewertung</th>
                                <th>Zusammenfassung</th>
                            </thead>
                            <tbody>
                                <tr v-for="(day, index) in days">
                                    <td>
                                        <div>{{ day.day_name }}</div>
                                        <div>{{ day.date_formatted }}</div>
                                    </td>
                                    <td class="text-right">{{ day.mood.raw }}</td>
                                    <td class="white-space-pre">{{ day.mood_note.raw }}</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
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
            days: {
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