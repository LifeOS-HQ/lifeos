<template>

    <div class="list-group-item list-group-item-action col-3" :class="{'list-group-item-dark': isActive}" :id="'behaviour-history-row-' + item.id">
        <div class="d-flex align-items-center h-100">
            <div class="text-center" style="width: 75px; margin-left: -15px;">
                <i class="fas fa-fw fa-2x fa-check-square text-primary pointer" v-if="item.is_completed" @click="$emit('incomplete')"></i>
                <i class="far fa-fw fa-2x fa-square pointer" v-else @click="$emit('complete')"></i>
            </div>
            <div class="flex-grow-1 pointer d-flex flex-column h-100" @click="show">
                <h5 class="mb-1">{{ item.behaviour.name }}</h5>
                <p class="mb-1 flex-grow-1">Description</p>
                <small>{{ item.end_at_time_formatted }}</small>
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
            isActive: {
                required: true,
                type: Boolean,
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
            },
        },

    };
</script>

<style scoped>
.fa-square:hover {
    color: var(--primary);
}
</style>
