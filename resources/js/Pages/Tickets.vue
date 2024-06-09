<script setup>
import Layout from "@/Layouts/AppLayout.vue";
import Pagination from "../components/Pagination.vue";
import { formatDate, lastUpdate } from "../utils";
import { defineProps, ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import debounce from "lodash/debounce";

// Loads the layout component
defineOptions({
    layout: Layout,
});

// Define the prop for the user to be stored in the page
let props = defineProps({
    tickets: Object,
    errors: Object,
    auth: Object,
    categories: Array,
    filters: Object,
});

// Define the search variable with a reactive object
let search = ref(props.filters.search);

// Function to handle pagination
function handlePagination(page) {
    Inertia.get("/tickets", { page }, { preserveState: true });
}

// Function to search for tickets
watch(
    search,
    debounce(function (value) {
        Inertia.get(
            "/tickets",
            { search: value },
            { preserveState: true, replace: true }
        );
    }, 300)
);
</script>

<template>
    <head>
        <title>Ticket Tracker - Tickets</title>
    </head>

    <div class="flex w-full justify-center">
        <section v-if="auth.user.role === 'user'" class="mt-10 mb-5">
            <div class="flex flex-col items-center w-full max-w-screen-lg">
                <div
                    class="mb-3 flex justify-between items-center w-full text-center font-semibold"
                >
                    <h1 class="text-xl">Your Tickets</h1>
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search"
                        class="border-2 rounded-full p-1 pl-3 pr-3 w-1/2 mx-4 font-normal"
                    />
                    <div>
                        <Link
                            href="/tickets/create"
                            class="text-sm bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-md"
                            :class="{'transform transition duration-300 ease-in-out hover:scale-110': true,}"
                            as="button"
                        >
                            Create Ticket
                        </Link>
                    </div>
                </div>
                <div class="flex w-full justify-center">
                    <table class="table-fixed font-normal text-center w-full">
                        <thead class="border border-gray-400 bg-blue-200">
                            <tr>
                                <th class="p-3 w-1/6">Status</th>
                                <th class="p-3 w-1/6">Title</th>
                                <th class="p-3 w-1/6">Category</th>
                                <th class="p-3 w-1/6">Date Created</th>
                                <th class="p-3 w-1/6">Last Updated</th>
                                <th class="p-3 w-1/6">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="border border-gray-400">
                            <tr
                                v-for="(ticket, index) in tickets.data"
                                :key="ticket.id"
                                :class="{
                                    'border-b border-gray-400': true,
                                    'bg-gray-200': index % 2 === 0,
                                    'bg-white': index % 2 !== 0,
                                    'hover:bg-gray-300': true,
                                }"
                            >
                                <td
                                    class="p-5 w-1/6"
                                    v-if="ticket.status === 'open'"
                                >
                                    <span class="text-green-500">Open</span>
                                </td>
                                <td class="p-5 w-1/6" v-else>
                                    <span class="text-red-500">Closed</span>
                                </td>
                                <td
                                    class="px-15 w-1/6 text-left overflow-hidden overflow-ellipsis whitespace-nowrap"
                                >
                                    {{ ticket.title }}
                                </td>
                                <td class="p-5 w-1/6">
                                    {{
                                        categories.find(
                                            (category) =>
                                                category.idCategory ===
                                                ticket.idCategory
                                        ).name
                                    }}
                                </td>
                                <td class="p-4 w-1/6 whitespace-nowrap">
                                    {{ formatDate(ticket.created_at) }}
                                </td>
                                <td class="p-5 w-1/6 text-center">
                                    {{ lastUpdate(ticket.updated_at) }}
                                </td>
                                <td class="p-5 w-1/6">
                                    <Link
                                        :href="`/tickets/${ticket.idTicket}`"
                                        class="text-sm bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-md"
                                        :class="{'transform transition duration-300 ease-in-out hover:scale-110': true,}"
                                        as="button"
                                    >
                                        View
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <Pagination
                :links="tickets.links"
                :first_page_url="tickets.first_page_url"
                :last_page_url="tickets.last_page_url"
                @paginate="handlePagination"
                class="mt-5 text-center"
            />
        </section>
    </div>
</template>
