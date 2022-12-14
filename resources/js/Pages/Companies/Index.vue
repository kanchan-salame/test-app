<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import JetButton from '@/Components/Button.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import JetDialogModal from '@/Components/DialogModal.vue';
import JetSecondaryButton from '@/Components/SecondaryButton.vue';
import { createToaster } from "@meforma/vue-toaster";

import JetDangerButton from '@/Components/DangerButton.vue';

const form = useForm({
    id: '',
});

defineProps({
    companies: Array,
});

const toaster = createToaster({
    position: "bottom-left",
 });

 const confirmingCompanyDeletion = ref(false);

const confirmCompanyDeletion = (company) => {
    confirmingCompanyDeletion.value = true;

    form.id = company.id
};

const deleteCompany = () => {
    form.delete(route('companies.destroy', form.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal()
            toaster.success('Company deleted!')
        },
        onError: () => {
            closeModal()
            toaster.error('Something went wrong!')
        },
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingCompanyDeletion.value = false;

    form.reset();
};
</script>

<template>
    <AppLayout title="Companies">

        <Link :href="route('companies.create')" class="underline text-sm text-gray-600 hover:text-gray-900">
            <JetButton>
                Create Company
            </JetButton>
        </Link>


        <div>
            <div class="mt-8">

                <div class="mt-6">
                    <h2 class="text-xl font-semibold leading-tight text-gray-700">Companies</h2>

                    <div class="flex flex-col mt-3 sm:flex-row">
                    <div class="flex">

                    </div>

                    <div class="relative block mt-2 sm:mt-0">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-2">
                        <svg
                            viewBox="0 0 24 24"
                            class="w-4 h-4 text-gray-500 fill-current"
                        >
                            <path
                            d="M10 4a6 6 0 100 12 6 6 0 000-12zm-8 6a8 8 0 1114.32 4.906l5.387 5.387a1 1 0 01-1.414 1.414l-5.387-5.387A8 8 0 012 10z"
                            />
                        </svg>
                        </span>

                        <input
                        placeholder="Search"
                        class="block w-full py-2 pl-8 pr-6 text-sm text-gray-700 placeholder-gray-400 bg-white border border-b border-gray-400 rounded-l rounded-r appearance-none sm:rounded-l-none focus:bg-white focus:placeholder-gray-600 focus:text-gray-700 focus:outline-none"
                        />
                    </div>
                    </div>

                    <div class="px-4 py-4 -mx-4 overflow-x-auto sm:-mx-8 sm:px-8">
                    <div
                        class="inline-block min-w-full overflow-hidden rounded-lg shadow"
                    >
                        <table class="min-w-full leading-normal" v-if="companies.length">
                        <thead>
                            <tr>
                            <th
                                class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase bg-gray-100 border-b-2 border-gray-200"
                            >
                                Name
                            </th>
                            <th
                                class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase bg-gray-100 border-b-2 border-gray-200"
                            >
                                Address
                            </th>
                            <th
                                class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase bg-gray-100 border-b-2 border-gray-200"
                            >
                                Phone
                            </th>
                            <th
                                class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase bg-gray-100 border-b-2 border-gray-200"
                            >
                                Status
                            </th>
                            <th
                                class="px-6 py-3 bg-gray-100 border-b border-gray-200"
                            ></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(company, index) in companies" :key="index">
                            <td
                                class="px-5 py-5 text-sm bg-white border-b border-gray-200"
                            >
                                <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10">
                                    <img
                                    class="w-full h-full rounded-full"
                                    :src="company.photo_path"
                                    alt="profile pic"
                                    />
                                </div>
                                <div class="ml-4">
                                    <div
                                    class="text-sm font-medium leading-5 text-gray-900"
                                    >
                                    {{ company.company_name }}
                                    </div>
                                    <div class="text-sm leading-5 text-gray-500">
                                    {{ company.company_email }}
                                    </div>
                                </div>
                                </div>
                            </td>
                            <td
                                class="px-5 py-5 text-sm bg-white border-b border-gray-200"
                            >
                                <p class="text-gray-900 whitespace-nowrap">{{ company.company_address }}</p>
                            </td>
                            <td
                                class="px-5 py-5 text-sm bg-white border-b border-gray-200"
                            >
                                <p class="text-gray-900 whitespace-nowrap">
                                {{ company.company_phone }}
                                </p>
                            </td>
                            <td
                                class="px-5 py-5 text-sm bg-white border-b border-gray-200"
                            >
                                <span
                                :class="`relative inline-block px-3 py-1 font-semibold text-${company.status == 1 ? 'green' : 'red'}-900 leading-tight`"
                                >
                                <span
                                    aria-hidden
                                    :class="`absolute inset-0 bg-${company.status == 1 ? 'green' : 'red'}-200 opacity-50 rounded-full`"
                                ></span>
                                <span class="relative">{{ company.status == 1 ? 'Active' : 'Inactive' }}</span>
                                </span>
                            </td>
                            <td
                                class="px-6 py-4 text-sm font-medium bg-white leading-5 text-right border-b border-gray-200 whitespace-nowrap"
                            >
                                <Link :href="route('companies.edit', company.id)" class="underline text-sm text-gray-600 hover:text-gray-900">
                                    <JetButton>
                                        Edit
                                    </JetButton>
                                </Link>
                                <br>
                                <div class="mt-5">
                                    <JetDangerButton @click="confirmCompanyDeletion(company)">
                                        Delete
                                    </JetDangerButton>
                                </div>
                            </td>
                            </tr>
                        </tbody>
                        </table>
                        <div v-else>
                            Companies not found! <br> Please create company
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Account Confirmation Modal -->
            <JetDialogModal :show="confirmingCompanyDeletion" @close="closeModal">
                <template #title>
                    Delete Contact
                </template>

                <template #content>
                    Are you sure you want to delete company? Once your account is company, all of its resources and data will be permanently deleted.
                </template>

                <template #footer>
                    <JetSecondaryButton @click="closeModal">
                        Cancel
                    </JetSecondaryButton>

                    <JetDangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteCompany"
                    >
                        Delete Company
                    </JetDangerButton>
                </template>
            </JetDialogModal>
    </AppLayout>
</template>
