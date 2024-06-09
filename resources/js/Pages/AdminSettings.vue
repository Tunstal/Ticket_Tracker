<script setup>
import Layout from "@/Layouts/AppLayout.vue";
import Pagination from "../components/Pagination.vue";
import { ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { defineProps, defineOptions } from "vue";
import debounce from "lodash/debounce";

defineOptions({
    layout: Layout,
});

let props = defineProps({
    auth: Object,
    admin: Object,
    users: Object,
    errors: Object,
});

let search = ref("");

function handlePagination(page) {
    Inertia.get("/admin/settings", { page }, { preserveState: true });
}

watch(
    search,
    debounce(function (value) {
        Inertia.get(
            "/admin/settings",
            { search: value },
            { preserveState: true, replace: true }
        );
    }, 300)
);

function deleteUser(idUser) {
    if (confirm("Are you sure you want to delete this user?")) {
        Inertia.delete(`/admin/settings/${idUser}`, {
            preserveState: true,
        });
    }
}
</script>

<template>
    <head>
        <title>Ticket Tracker - Admin Settings</title>
    </head>

    <div class="flex w-full justify-center">
        <section class="mt-10 flex-col w-full">
            <div class="flex flex-col items-center">
                <div
                    class="mb-3 flex justify-between items-center w-full text-center font-semibold"
                >
                    <h1 class="text-xl">Admin Settings</h1>
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search"
                        class="border-2 rounded-full p-1 pl-3 pr-3 w-1/2 font-normal"
                    />
                </div>
                <div class="flex w-full justify-center">
                    <table class="w-full shadow-lg mb-5">
                        <thead class="border border-gray-400 bg-red-200">
                            <tr>
                                <th class="text-left p-3 w-1/4">Username</th>
                                <th class="text-left p-3 w-1/4">Email</th>
                                <th class="text-left p-3 w-1/4">User Id</th>
                                <th class="text-center p-3 w-1/4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="border border-gray-400">
                            <tr
                                v-for="(user, index) in users.data"
                                :key="user.id"
                                :class="{
                                    'border-b border-gray-400': true,
                                    'bg-gray-200': index % 2 === 0,
                                    'bg-white': index % 2 !== 0,
                                    'hover:bg-gray-300': true,
                                }"
                            >
                                <td class="p-5">{{ user.username }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.idUser }}</td>
                                <td class="text-center">
                                    <button
                                        @click="deleteUser(user.idUser)"
                                        class="text-sm bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2 shadow-md"
                                        :class="{
                                            'transform transition duration-300 ease-in-out hover:scale-110': true,
                                        }"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <Pagination
                    :links="users.links"
                    :first_page_url="users.first_page_url"
                    :last_page_url="users.last_page_url"
                    @pagination="handlePagination"
                    class="mt-5"
                />
            </div>
        </section>
    </div>
</template>
