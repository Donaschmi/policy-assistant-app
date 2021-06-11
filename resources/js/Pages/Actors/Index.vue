<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ tenant.name + "'s actors" }}
            </h2>
        </template>

        <div class="flex flex-col px-60 pt-6">
            <form>
                <jet-button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-6" @click.prevent="showCreateActor = true">
                    Nouvel acteur
                </jet-button>

                <inertia-link :href="route('user.policies', {user: tenant.id})" class="btn btn-primary ml-6 ">
                    Règles de sécurité
                </inertia-link>
                <jet-dialog-modal :show="showCreateActor" @close="showCreateActor = false">
                    <template #content>
                        <create-actor-form :actor_types="actor_types" :tenant="tenant"/>
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
                                    Type
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nom
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Numéro de téléphone
                                </th>

                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Delete</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="actor in actors">
                                <actor-item :actor="actor"/>
                            </tr>
                            <!-- More items... -->
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
import ActorItem from './Item'
import JetButton from '@/Jetstream/Button'
import JetDialogModal from "../../Jetstream/DialogModal";
import CreateActorForm from "./CreateActorForm";
export default {
    props: ['tenant', 'actor_types'],

    components: {
        CreateActorForm,
        AppLayout,
        ActorItem,
        JetButton,
        JetDialogModal,
    },
    data(){
        return {
            showCreateActor: false,
            actors: this.tenant.actors
        }
    },
    created() {
        this.emitter.on('new-actor', this.addNewActor)
        this.emitter.on('remove-actor', this.removeActor)
    },
    methods: {
        addNewActor(args) {
            this.actors.push(args.actor)
            this.showCreateActor = false
        },
        removeActor(args) {
            let id = args.actor_id
            this.actors = this.actors.filter((item) => {
                return item.id !== id;
            })
        }
    }
}
</script>
