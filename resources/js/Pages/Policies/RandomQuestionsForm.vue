<template>
    <form class="w-full max-w-lg" @submit.prevent="submit">

        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-full px-3 mb-6 md:mb-0">
                <jet-button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-6" @click.prevent="showCreateActor = true">
                    Add new actor
                </jet-button>

                <jet-dialog-modal :show="showCreateActor" @close="showCreateActor = false">
                    <template #content>
                        <create-actor-form :tenant="tenant" :actors="actors"/>
                    </template>
                </jet-dialog-modal>
            </div>
            <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                <p v-if="error">
                    {{ error }}
                </p>
                <jet-button @click.prevent="fetchQuestions">
                    Get Questions
                </jet-button>
            </div>
            <div class="w-full px-3 mb-6 md:mb-0" v-for="question in questions">
                <p>
                    {{ question.sentence }}
                </p>
                <select required v-model="question.answer" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state">
                    <option disabled value="">Choisissez</option>
                    <option v-if="question.actor" :value="question.actor">Oui</option>
                    <option v-if="question.actor">Non</option>
                    <option v-if="question.actor_1" :value="question.actor_1">{{ question.actor_1.actor.sentence }}</option>
                    <option v-if="question.actor_2" :value="question.actor_2">{{ question.actor_2.actor.sentence }}</option>
                </select>
            </div>

            <div class="flex flex-wrap -mx-3 mb-2">
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <jet-button class="mt-6">
                        Save
                    </jet-button>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
import JetButton from '@/Jetstream/Button'
import JetDialogModal from '@/Jetstream/DialogModal'
import CreateActorForm from "../Actors/CreateActorForm";


export default {
    props: ["tenant", "actors"],
    components: {
        JetButton,
        JetDialogModal,
        CreateActorForm
    },
    data(){
        return {
            showCreateActor: false,
            questions: null,
            error: null
        }
    },
    computed: {

    },
    async created(){
        this.emitter.on('new-actor', () => {this.showCreateActor = false})
    },
    methods: {
        async fetchQuestions() {
            axios.get(`/users/${this.tenant.id}/questions?question_type=random`)
                .then(response => {
                    this.questions = response.data
                }).catch(e => {
                    if (e.response.data === "actor_missing"){
                        this.error = "Veuillez créer au moins un acteur au préalable."
                    }
                    else if (e.response.data === "event_missing"){
                        this.error = "Vous couvrez déjà tous les évenements disponibles."
                    }
                })
        },
        async submit() {
            let data = new FormData();
            let events = JSON.stringify(this.questions)
            data.append('events', events)
            axios.post(`/users/${this.tenant.id}/questions`, data)
                .then(response => {
                    this.emitter.emit('new-policies', {policies: response.data})
                }).catch(e => {
            })
        },
        closeForm() {
        },
    }
}
</script>
