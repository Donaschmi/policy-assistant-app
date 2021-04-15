<template>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <div class="">
                <div class="text-sm font-medium text-gray-900">
                    {{ policy.id }}
                </div>
            </div>
        </div>
    </td>

    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <div class="">
                <a class="text-sm font-medium text-gray-900" href="#">
                    {{ eventString(policy.event) }}
                </a>
            </div>
        </div>
    </td>


    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <ul class="">
                <li class="text-sm font-medium text-gray-900" v-for="action_actor in policy.action_actor">
                    {{ action_actor.action.name + ' ' + action_actor.user_actor.fullname}}
                </li>
            </ul>
        </div>
    </td>

    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
        <jet-danger-button class="text-indigo-600 hover:text-indigo-900" @click.prevent="remove">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
            </svg>
        </jet-danger-button>
    </td>

</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetDangerButton from '@/Jetstream/DangerButton'
export default {
    props: ['tenant', 'policy'],

    components: {
        AppLayout,
        JetDangerButton
    },
    computed: {
    },
    methods: {
        eventString(e) {
            return e.triggerable.name + ':' + e.attribute + ' ' + e.operator + ' ' + e.value
        },
        async remove() {
            await axios.delete(`/policies/${this.policy.id}`)
            .then(response => {
                this.emitter.emit('remove-policy', {policy_id: this.policy.id})
            })
        }
    }
}
</script>
