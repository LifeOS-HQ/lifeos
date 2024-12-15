<template>

    <div class="list-group-item">
        <div class="pointer">
            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <h5 class="mb-0">{{ item.wish }}</h5>
                    <small><div class="badge badge-primary badge-pill ml-1">{{ item.level }}</div></small>
                </div>
                <div>
                    <a :href="item.edit_path" class="btn btn-sm btn-secondary" target="_blank"><i class="fas fa-edit"></i></a>
                </div>
            </div>
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
            item: {
                required: true,
                type: Object,
            },
        },

        data () {
            return {
                is_open: false,
                filter: {
                    //
                },
                form: {
                    level: this.item.level,
                    title: this.item.title,
                    wish: this.item.wish,
                    outcome: this.item.outcome,
                    obstacle: this.item.obstacle,
                    plan: this.item.plan,
                    loot: this.item.loot,
                },
            };
        },

        computed: {
            //
        },

        methods: {
            //
        },
    };
</script>
