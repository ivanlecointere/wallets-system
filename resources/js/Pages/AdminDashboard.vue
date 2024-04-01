<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
                        <div class="sm:flex sm:items-center mt-6">
                            <div class="sm:flex-auto">
                                <h1 class="text-base font-semibold leading-6 text-gray-900">Users</h1>
                                <p class="mt-2 text-sm text-gray-700">Clients listed by transactions number in a date range.</p>
                            </div>
                            <div class="mt-4 sm:ml-16 sm:mt-0 flex">
                                <VueDatePicker
                                    class="z-50"
                                    v-model="date"
                                    :range="true"
                                    :enable-time-picker="false"
                                />
                                <button @click="setDateRange" type="button" class="block rounded-md bg-indigo-600 px-3 py-2 ml-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Apply</button>
                            </div>
                        </div>
                        <div class="mt-8 flow-root">
                            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead>
                                        <tr>
                                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Name</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Transactions</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Type</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Client since</th>
                                        </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                        <tr v-for="client in clients.data" :key="client.id">
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">{{ client.name }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ client.transactions_count }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 capitalize">{{ client.type }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ formatDate(client.created_at) }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-1 justify-between sm:justify-end mt-2">
                            <a v-if="clients.prev_page_url" :href="clients.prev_page_url" class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0">Previous</a>
                            <a v-if="clients.next_page_url" :href="clients.next_page_url" class="relative ml-3 inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0">Next</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import { formatDate } from "@/Utils/functions.js";
import '@vuepic/vue-datepicker/dist/main.css';
import VueDatePicker from '@vuepic/vue-datepicker';
import {ref} from "vue";

const props = defineProps({
    clients: Object,
});

const date = ref(null);

const setDateRange = () => {
    router.reload({ data: { range_from: date.value ? date.value[0] : null, range_to: date.value ? date.value[1] : null } });
};
</script>
