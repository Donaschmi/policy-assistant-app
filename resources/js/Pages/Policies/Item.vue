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
                    {{ action_actor.action.name + ' ' + action_actor.actor.name}}
                </li>
            </ul>
        </div>
    </td>

    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
        <a :href="route('user.policy', {user: tenant.id, policy: policy.id})" class="text-indigo-600 hover:text-indigo-900">Détail</a>
    </td>

</template>

<script>
import AppLayout from '@/Layouts/AppLayout'

export default {
    props: ['tenant', 'policy'],

    components: {
        AppLayout,
    },
    computed: {
    },
    methods: {
        eventString(e) {
            return e.triggerable.name + ':' + e.attribute + ' ' + e.operator + ' ' + e.value
        }
    }
}
</script>
