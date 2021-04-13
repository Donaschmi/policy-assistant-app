<template>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <div class="">
                <div class="text-sm font-medium text-gray-900">
                    {{ tenant.id }}
                </div>
            </div>
        </div>
    </td>

    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <div class="">
                <div class="text-sm font-medium text-gray-900">
                    {{ tenant.name }}
                </div>
            </div>
        </div>
    </td>

    <td class="px-6 py-4 whitespace-nowrap">
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
          {{ tenant.policies.length}}
        </span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
        <inertia-link :href="route('user.policies', {user: tenant.id})" class="text-indigo-600 hover:text-indigo-900">Select</inertia-link>
    </td>

</template>

<script>
import AppLayout from '@/Layouts/AppLayout'

export default {
    props: ['tenant'],

    components: {
        AppLayout,
    },
}
</script>
