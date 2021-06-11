<template>
    <div>
        <form class="w-full max-w-lg" @submit.prevent="submit">
            <div class="flex flex-wrap -mx-3 mb-2">
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-state">
                        Relation
                    </label>
                    <div class="relative">
                        <select v-model="actor_id" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state">
                            <option disabled value="">Choisissez</option>
                            <option v-for="a in actor_types" :value="a.id">{{ a.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <jet-label for="fullname" value="Nom" />
                    <jet-input id="fullname" type="name" class="mt-1 block w-full" v-model="fullname" required/>
                </div>
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0" >
                    <jet-label for="phone_number" value="Numéro de téléphone" />
                    <jet-input id="phone_number" class="mt-1 block w-full" v-model="phone_number" required/>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-2">
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <jet-button :disabled="!(actor_id && fullname && phone_number)">
                        Sauvegarder
                    </jet-button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import JetButton from '@/Jetstream/Button'
import JetDialogModal from '@/Jetstream/DialogModal'
import JetInput from '@/Jetstream/Input'
import JetLabel from '@/Jetstream/Label'

export default {
    props: ["tenant", "actions", "actor_types"],
    components: {
        JetButton,
        JetDialogModal,
        JetInput,
        JetLabel
    },
    data(){
        return {
            actor_id: null,
            fullname: "",
            phone_number: "",
        }
    },
    created() {
        console.log(this.actor_types)
    },
    methods: {
        async submit() {
            await axios.post(`/users/${this.tenant.id}/actors`, {
                actor_type_id: this.actor_id,
                fullname: this.fullname,
                phone_number: this.phone_number

            }).then(response => {
                this.emitter.emit('new-actor', {actor: response.data})
            })
            this.closeForm()
        },
        closeForm() {
            this.fullname = ""
            this.phone_number = ""
        }
    }
}
</script>
