<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Users
            </h2>
        </template>

        <div class="flex flex-col px-60 pt-6">
            <form>
                <jet-button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-6" @click.prevent="showCreateUser = true">
                    New user
                </jet-button>

                <jet-dialog-modal :show="showCreateUser" @close="showCreateUser = false">
                    <template #content>
                        <create-user-form/>
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
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    # Policies
                                </th>

                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Select</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="user in tenants">
                                <user-item :tenant="user"/>
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
import UserItem from './Item'
import JetButton from '@/Jetstream/Button'
import JetDialogModal from "../../Jetstream/DialogModal";
import CreateUserForm from "./CreateUserForm";
export default {
    props: ['users'],

    components: {
        AppLayout,
        UserItem,
        JetButton,
        JetDialogModal,
        CreateUserForm
    },
    data(){
        return {
            showCreateUser: false,
            tenants: this.users
        }
    },
    created() {
        this.emitter.on('new-user', this.addNewUser)
    },
    methods: {
        addNewUser(args) {
            this.tenants.push(args.user)
            this.showCreateUser = false
        }
    }
}
</script>
