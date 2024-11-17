<template>

    <div class="list-group-item list-group-item-action" :class="{'list-group-item-dark': isActive}">
        <div class="d-flex align-items-center">
            <div class="text-center" style="width: 75px; margin-left: -15px;">
                <i class="fas fa-fw fa-2x fa-check-square text-primary pointer" v-if="item.is_completed" @click="$emit('incomplete')"></i>
                <i class="far fa-fw fa-2x fa-square pointer" v-else @click="$emit('complete')"></i>
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
