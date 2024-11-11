<template>

    <div class="list-group-item list-group-item-action">
        <div class="d-flex align-items-center">
            <div class="text-center" style="width: 75px; margin-left: -15px;">
                <i class="far fa-fw fa-2x fa-square pointer" v-show="false"></i>
                <i class="fas fa-fw fa-2x fa-check-square text-success" v-show="true"></i>
            </div>
            <div class="flex-grow-1 pointer" @click="show">
                <div class="d-flex justify-content-between">
                    <h5 class="mb-1">{{ item.behaviour.name }}</h5>
                    <small>{{ item.end_at_time_formatted }}</small>
                </div>
                <p class="mb-1">Some placeholder content in a paragraph.</p>
                <small>And some small print.</small>
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
                //
            };
        },

        methods: {
            show() {
                this.$emit('show', this.item);
            }
        },

    };
</script>

<style scoped>
.fa-square:hover {
    color: #38c172;
}
</style>
