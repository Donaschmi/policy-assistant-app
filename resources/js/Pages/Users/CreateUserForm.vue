<template>
    <div>
        <form class="w-full max-w-lg" @submit.prevent="submit">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <jet-label for="name" value="name" />
                    <div v-if="errors" class="">
                    <div v-for="(v, k) in errors" :key="k">
                        <p v-for="error in v" :key="error" class="text-sm">
                            {{ error }}
                        </p>
                    </div>
                </div>
                    <jet-input id="name" class="mt-1 block w-full" v-model="name" required/>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-2">
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <jet-button v-if="name.length > 0">
                        Save
                    </jet-button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import JetButton from '@/Jetstream/Button'
import JetInput from '@/Jetstream/Input'
import JetLabel from '@/Jetstream/Label'

export default {
    props: [],
    components: {
        JetButton,
        JetInput,
        JetLabel
    },
    data(){
        return {
            name: '',
            errors: null
        }
    },
    methods: {
        async submit() {
            try {
                let response = await axios.post(`/users`,
                    {
                        name: this.name
                    })
                this.emitter.emit('new-user', {user: response.data})
            }
            catch (e){
                console.log(e)
                this.errors = e.response.data.errors
            }
        }
    },
}
</script>
