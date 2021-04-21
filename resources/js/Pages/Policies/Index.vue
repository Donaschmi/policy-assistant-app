<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ tenant.name }}'s policies
            </h2>
        </template>

        <div class="flex flex-col px-60 pt-6">
            <form>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle mb-6" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        New policy
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <jet-button
                            class="dropdown-item bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded text-black-50"
                            @click.prevent="showCreatePolicy = true"
                            style="color:black!important"
                        >
                            Manual
                        </jet-button>
                        <jet-button
                            class="dropdown-item bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded text-black-50"
                            @click.prevent="showRandomQuestions = true"
                            style="color:black!important"
                        >
                            Random questions
                        </jet-button>
                        <jet-button
                            class="dropdown-item bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded text-black-50"
                            style="color:black!important"
                            @click.prevent="showDialogue = true"
                        >
                            Dialogue
                        </jet-button>
                    </div>
                    <inertia-link :href="route('user.actors', {user: tenant.id})" class="btn btn-primary mb-6 ml-6">
                        Actors
                    </inertia-link>
                </div>
                <jet-dialog-modal :show="showCreatePolicy" @close="showCreatePolicy = false">
                    <template #content>
                        <create-policy-form :tenant="tenant" :actions="actions" :actor_types="actor_types"/>
                    </template>
                </jet-dialog-modal>

                <jet-dialog-modal :show="showRandomQuestions" @close="showRandomQuestions = false">
                    <template #content>
                        <random-questions-form :tenant="tenant" :actor_types="actor_types"/>
                    </template>
                </jet-dialog-modal>

                <jet-dialog-modal :show="showDialogue" @close="showDialogue = false">
                    <template #content>
                        <dialogue-form :tenant="tenant" :actor_types="actor_types"/>
                    </template>
                </jet-dialog-modal>
            </form>
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Event
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Action(s)
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Détail</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="policy in policies">
                                    <policy-item :tenant="tenant" :policy="policy"/>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import PolicyItem from './Item'
import JetButton from '@/Jetstream/Button'
import JetDangerButton from "../../Jetstream/DangerButton"
import JetSecondaryButton from "../../Jetstream/SecondaryButton"
import JetDialogModal from "../../Jetstream/DialogModal"
import CreatePolicyForm from "./CreatePolicyForm"
import RandomQuestionsForm from "./RandomQuestionsForm"
import DialogueForm from "./DialogueForm"

export default {
    props: ['tenant', 'actions', 'actor_types'],
    components: {
        CreatePolicyForm,
        AppLayout,
        PolicyItem,
        JetDangerButton,
        JetSecondaryButton,
        JetDialogModal,
        JetButton,
        RandomQuestionsForm,
        DialogueForm
    },
    data(){
        return {
            policies: [],
            showCreatePolicy: false,
            showRandomQuestions: false,
            showDialogue: false
        }
    },
    created() {
        this.policies = this.tenant.policies
        this.emitter.on('new-policy', this.addPolicy)
        this.emitter.on('new-policies', this.addPolicies)
        this.emitter.on('remove-policy', this.removePolicy)
    },

    methods: {
        addPolicy(args){
            console.log(this.policies)
            this.policies.push(args.policy)
            this.showCreatePolicy = false
        },
        addPolicies(args){
            this.policies = this.policies.concat(args.policies)
            this.showRandomQuestions = false
        },
        removePolicy(args){
            let id = args.policy_id
            this.policies = this.policies.filter((item) => {
                return item.id !== id;
            })
        }
    }


}
</script>
