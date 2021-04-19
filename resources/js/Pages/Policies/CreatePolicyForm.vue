<template>
    <div>
        <div class="w-full max-w-lg">
            <div class="flex flex-wrap -mx-3 mb-6" style="background: lavender; border-radius: 10px">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0" :style="eventTabStyle">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2 mt-2" for="grid-state">
                        Event
                    </label>
                </div>
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0" :style="actionTabStyle">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2 mt-2" for="grid-state">
                        Actions
                    </label>
                </div>
            </div>
        </div>
        <form class="w-full max-w-lg" @submit.prevent="submit">
            <div class="flex flex-wrap -mx-3 mb-6" v-if="currentTab==='event'">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-state">
                        Type de déclencheur
                    </label>
                    <div class="relative">
                        <select v-model="trigger_type" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state">
                            <option disabled value="">Choisissez</option>
                            <option>Device</option>
                        </select>
                    </div>
                </div>
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0" v-if="trigger_type">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-state">
                        Type de device
                    </label>
                    <div class="relative">
                        <select v-model="trigger" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state">
                            <option disabled value="">Choisissez</option>
                            <option v-for="type in triggers" :value="type">{{ type.name }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-2" v-if="currentTab==='event'">
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0" v-if="trigger">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-state">
                        Attributes
                    </label>
                    <div class="relative">
                        <select v-model="attribute" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state">
                            <option disabled value="">Choisissez</option>
                            <option v-for="a in attributes">{{ a }}</option>
                        </select>
                    </div>
                </div>
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0" v-if="attribute">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-state">
                        Operators
                    </label>
                    <div class="relative">
                        <select v-model="operator" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state">
                            <option disabled value="">Choisissez</option>
                            <option v-for="o in operators">{{ o }}</option>
                        </select>
                    </div>
                </div>
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0" v-if="operator">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-state">
                        Values
                    </label>
                    <div class="relative">
                        <select v-model="value" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state">
                            <option disabled value="">Choisissez</option>
                            <option v-for="v in values">{{ v }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6" v-if="currentTab==='action'">
                <div class="w-full px-3 mb-6 md:mb-0">
                    <jet-button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-6" @click.prevent="showCreateActor = true">
                        Add new actor
                    </jet-button>

                    <jet-dialog-modal :show="showCreateActor" @close="showCreateActor = false">
                        <template #content>
                            <create-actor-form :tenant="tenant" :actor_types="actor_types"/>
                        </template>
                    </jet-dialog-modal>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6" v-if="currentTab==='action'">
                <div class="w-full md:w-2/5 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-state">
                        Actor
                    </label>
                    <div class="relative">
                        <select v-model="currentActor" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state">
                            <option disabled value="">Choisissez</option>
                            <option v-for="actor in actors" :value="actor">{{ actor.fullname + ' (' + actor.actor_type.name + ')'}}</option>
                        </select>
                    </div>
                </div>
                <div class="w-full md:w-2/5 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-state">
                        Action
                    </label>
                    <div class="relative">
                        <select v-model="currentAction" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state">
                            <option disabled value="">Choisissez</option>
                            <option v-for="action in actions" :value="action">{{ action.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="w-full md:w-1/5 px-3 mb-6 md:mb-0 m-auto" >
                    <jet-button @click.prevent="addActionActorPair" :disabled="!(currentAction && currentActor)">
                        +
                    </jet-button>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6" v-if="currentTab==='action'">
                <div class="w-full px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-state">
                        Actions
                    </label>
                    <ul class="relative">
                        <li v-for="action_actor in actionActorPairs">
                            {{action_actor.action.name + ' ' + action_actor.actor.fullname}}
                        </li>
                    </ul>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-2">
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <jet-button v-if="currentTab === 'action'" @click.prevent="currentTab = 'event'">
                        Prev
                    </jet-button>
                </div>
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <jet-button v-if="currentTab === 'event' && this.value" @click.prevent="currentTab = 'action'">
                        Next
                    </jet-button>
                </div>
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <jet-button v-if="actionActorPairs.length > 0">
                        Save
                    </jet-button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import JetButton from '@/Jetstream/Button'
import JetDialogModal from '@/Jetstream/DialogModal'
import CreateActorForm from "../Actors/CreateActorForm";


export default {
    props: ["tenant", "actions", "actor_types"],
    components: {
        JetButton,
        JetDialogModal,
        CreateActorForm
    },
    data(){
        return {
            showCreateActor: false,
            trigger_type: null,
            triggers: null,
            attributes: null,
            operators: null,
            values: null,
            trigger: null,
            attribute: null,
            operator: null,
            value: null,
            events: [],
            currentTab:'event',
            actors: [],
            currentActor: null,
            currentAction: null,
            actionActorPairs: [],

        }
    },
    computed: {
        eventTabStyle() {
            if (this.currentTab === 'event'){
                return "border-radius: 10px;"
            }
            return "background: lavenderblush;"
        },
        actionTabStyle() {
            if (this.currentTab === 'action'){
                return "border-radius: 10px;"
            }
            return "background: lavenderblush;"
        },

    },
    async created(){
        await axios.get(`/events?tenant_id=${this.tenant.id})`).then(response => {
            this.events = response.data
        })
        await axios.get(`/actors?tenant_id=${this.tenant.id})`).then(response => {
            this.actors = response.data
        })
        this.emitter.on('new-actor', this.addActor)
    },
    methods: {
        async submit() {
            if (! (this.trigger_type))
                return;
            let e = this.currentEvent()
            if (e.length === 0)
                return;
            let data = new FormData();
            let action_actor = JSON.stringify(this.actionActorPairs)
            data.append('action_actor', action_actor)
            data.append('event_id', e.pop()["id"])
            await axios.post(`/users/${this.tenant.id}/policies`, data).then(response => {
                this.emitter.emit('new-policy', {policy: response.data})
            })
            this.closeForm()
        },

        currentEvent: function(){
            return this.events.filter((item) => {
                return ( this.trigger
                    && item.triggerable_id === this.trigger.id
                    && item.attribute === this.attribute
                    && item.operator === this.operator
                    && item.value === this.value)
            })
        },

        closeForm() {
            this.trigger_type = null;
            this.attribute = null;
            this.triggers = null;
            this.attributes = null;
            this.currentTab = 'event'
            this.operators = null;
            this.value = null;
            this.trigger = null;
            this.attribute = null;
            this.operator = null;
            this.value = null;
        },

        addActionActorPair(){
            this.actionActorPairs.push({actor: this.currentActor, action: this.currentAction})
            this.currentActor = null;
            this.currentAction = null;
        },

        addActor(args){
            this.actors.push(args.actor)
            this.showCreateActor = false
        }
    },
    watch: {
        trigger_type: async function(oldValue, newValue){
            await axios.get('/devices').then(response => {
                this.triggers = response.data
            })
        },
        trigger: function(oldValue, newValue){
            this.operators = this.values = null
            let events = this.events
            if (this.trigger){
                events = this.events.filter((item)=> {
                    return item.triggerable_id === this.trigger.id
                })
            }
            this.attributes = Array.from(
                new Set(
                    events.map(function (value, index, array) {
                        return value.attribute;
                    })
                )
            )
        },
        attribute: function(oldValue, newValue){
            this.values = null
            let events = this.events
            if (this.trigger && this.attribute){
                events = this.events.filter((item)=> {
                    return item.triggerable_id === this.trigger.id && item.attribute === this.attribute
                })
            }
            this.operators = Array.from(
                new Set(
                    events.map(function (value, index, array) {
                        return value.operator;
                    })
                )
            )
        },
        operator: function(oldValue, newValue){
            let events = this.events
            if (this.trigger && this.attribute && this.operator){
                events = this.events.filter((item)=> {
                    return (item.triggerable_id === this.trigger.id
                        && item.attribute === this.attribute
                        && item.operator === this.operator)
                })
            }
            this.values = Array.from(
                new Set(
                    events.map(function (value, index, array) {
                        return value.value;
                    })
                )
            )
        }
    }
}
</script>
