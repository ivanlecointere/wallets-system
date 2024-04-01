<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="pt-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
                        <div class="sm:flex sm:items-center">
                            <div class="sm:flex-auto">
                                <h1 class="text-base font-semibold leading-6 text-gray-900">Balance</h1>
                                <h1 class="mt-2 text-xl text-gray-700 font-bold">{{currency}} {{ account.balance }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-visible shadow-sm sm:rounded-lg">
                    <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
                        <div class="sm:flex sm:items-center">
                            <div class="sm:flex-auto">
                                <h1 class="text-base font-semibold leading-6 text-gray-900">Incomes by date</h1>
                                <h1 class="mt-2 text-xl text-gray-700 font-bold">{{currency}} {{ incomes }}</h1>
                            </div>
                            <div class="sm:flex-auto">
                                <h1 class="text-base font-semibold leading-6 text-gray-900">Expenses by date</h1>
                                <h1 class="mt-2 text-xl text-gray-700 font-bold">{{currency}} {{ expenses }}</h1>
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
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
                        <div class="sm:flex sm:items-center">
                            <div class="sm:flex-auto">
                                <h1 class="text-base font-semibold leading-6 text-gray-900">Transactions by date</h1>
                            </div>
                        </div>
                        <div class="mt-8 flow-root">
                            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead>
                                        <tr>
                                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Amount</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Type</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Date</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">City</th>
                                        </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                        <tr v-for="transaction in transactions.data" :key="transaction.id">
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">{{currency}} {{ transaction.formatted_amount }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 capitalize">{{ transaction.type }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ formatDate(transaction.created_at) }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ transaction.address.city }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-1 justify-between sm:justify-end mt-2">
                            <a v-if="transactions.prev_page_url" :href="transactions.prev_page_url" class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0">Previous</a>
                            <a v-if="transactions.next_page_url" :href="transactions.next_page_url" class="relative ml-3 inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0">Next</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { formatDate } from '@/Utils/functions';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { ref } from 'vue';

const props = defineProps({
    account: Object,
    currency: String,
    transactions: Object,
    incomes: String,
    expenses: String,
});

const date = ref(null);

const setDateRange = () => {
    router.reload({ data: { range_from: date.value ? date.value[0] : null, range_to: date.value ? date.value[1] : null } });
};
</script>
