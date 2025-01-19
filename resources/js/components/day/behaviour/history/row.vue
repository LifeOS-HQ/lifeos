<template>

    <div class="list-group-item list-group-item-action col-3" :class="{'list-group-item-dark': isActive}" :id="'behaviour-history-row-' + item.id">
        <div class="d-flex align-items-center h-100">
            <div class="text-center" style="width: 75px; margin-left: -15px;">
                <span class="fa-stack fa-2x pointer" style="vertical-align: top;" @click="toggleComplete()">
                    <i class="fas fa-bullseye fa-stack-1x"></i>
                    <i class="fas fa-times fa-stack-2x text-success" v-if="item.is_completed"></i>
                </span>
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
            toggleComplete() {
                if (this.item.is_completed) {
                    this.$emit('incomplete');
                } else {
                    this.$emit('complete');
                }
            },
            show() {
                this.$emit('show', this.item);
            },
        },

    };
</script>

<style scoped>
.fa-bullseye:hover {
    color: var(--success);
}
</style>
