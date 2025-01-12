<template>
    <div class="row">
        <div class="col-6">
            <h3>Tag</h3>
            <draggable
                class="dragArea list-group"
                ghost-class="ghost"
                :list="histories"
                group="people"
                handle=".handle"
                @change="log"
                @end="update"
                @add="store"
            >
                <div class="list-group-item d-flex align-items-center" :key="index" v-for="(history, index) in histories">
                    <i class="fa fa-align-justify handle pointer mr-3"></i>

                    <div class="flex-grow-1">
                        {{ history.ordinal }}
                        {{ history.behaviour.name }}
                        <i class="fa fa-spinner fa-spin mr-1" v-if="history.id === null"></i>
                    </div>

                    <i class="fa fa-trash pointer" @click="destroy(index)"></i>
                </div>

            </draggable>
        </div>

        <div class="col-6">
            <h3>Verhalten</h3>
            <draggable
                class="dragArea list-group"
                ghost-class="ghost"
                :list="behaviours"
                :group="{ name: 'people', pull: 'clone', put: false }"
                :sort="false"
                :clone="cloneBehaviour"
                @change="log"
            >
                <div class="list-group-item pointer" v-for="element in behaviours" :key="element.id">
                    {{ element.name }}
                </div>
            </draggable>
        </div>

    </div>
</template>

<script>
import draggable from "vuedraggable";

export default {
    name: "custom-clone",
    display: "Custom Clone",
    order: 3,

    components: {
        draggable,
    },

    props: {
        day: {
            type: Object,
            required: true
        },
        initialBehaviours: {
            type: Array,
            required: true
        }
    },

    data() {
        return {
            behaviours: this.initialBehaviours,
            histories: this.day.behaviour_histories,
        };
    },
    methods: {
        store(event) {
            const component = this;
            const history = component.histories[event.newIndex];
            console.log(event);
            axios.post(this.day.behaviour_histories_path, {
                behaviour_id: history.behaviour.id,
                ordinal: event.newIndex
            })
            .then(response => {
                Vue.set(component.histories, event.newIndex, response.data);
                Vue.success('Das Verhalten <b>' + response.data.behaviour.name + '</b> wurde angelegt.');
            })
        },
        update(event) {
            const component = this;
            const history = component.histories[event.newIndex];

            if (event.newIndex === event.oldIndex) {
                return;
            }

            axios.put(this.day.behaviour_histories_path + '/' + history.id, {
                ordinal: event.newIndex + (event.newIndex > event.oldIndex ? 2 : 1)
            })
            .then(response => {
                Vue.set(component.histories, event.newIndex, response.data);
                Vue.success('Das Verhalten <b>' + response.data.behaviour.name + '</b> wurde aktualisiert.');
            })
        },
        destroy(index) {
            const component = this;
            const history = component.histories[index];
                axios.delete(this.day.behaviour_histories_path + '/' + history.id)
                    .then(response => {
                        component.histories.splice(index, 1);
                    });
            },
        log: function(evt) {
            window.console.log(evt);
        },
        cloneBehaviour(behaviour) {
            return {
                    id: null,
                    behaviour: {
                        id: behaviour.id,
                        name: behaviour.name,
                    }
                };
            },
    }
};
</script>
<style scoped>
    .ghost {
        opacity: 0.5;
        background: var(--success);
    }
</style>
