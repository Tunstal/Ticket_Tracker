<script setup>
import Layout from "@/Layouts/AppLayout.vue";
import { transform } from "lodash";
import { defineProps, ref, onMounted } from "vue";

// Loads the layout component
defineOptions({
    layout: Layout,
});

// Define the prop for the user to be stored in the page
defineProps({
    auth: Object,
    admin: Object,
    openTickets: Number,
    closedTickets: Number,
});
</script>

<!-- Uses vue's conditionals to display content based user's login status
    and authentication status -->
<template>
    <Head>
        <title>Ticket Tracker - Home</title>
    </Head>
    <section class="flex flex-col items-center justify-center min-h-screen">
        <div class="text-center font-semibold">
            <h1 class="mb-3">Welcome to the Ticket Tracker App!</h1>
            <p class="mb-20">
                This is a simple ticket tracker app built with Laravel, Vue.js
                and Inertia.js.
            </p>
            <div v-if="auth && auth.user">
                <p class="mb-20">
                    You are logged in as:
                    <span
                        :class="{
                            'text-blue-500':
                                auth && auth.user && auth.user.role === 'user',
                            'text-red-500':
                                admin &&
                                admin.user &&
                                admin.user.role === 'admin',
                        }"
                        >{{ auth.user.username }}</span
                    >
                </p>
            </div>
            <div v-else>
                <p class="mb-3 text-red-700">
                    Please login or register to access the app
                </p>
            </div>
            <div v-if="auth && auth.user && auth.user.role === 'user'">
                <Link
                    href="/tickets"
                    class="text-sm bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-4"
                    :class="{'transform transition duration-300 ease-in-out hover:scale-110': true,}"
                    as="button"
                >
                    View Tickets
                </Link>
                <Link
                    href="/tickets/create"
                    class="text-sm bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-4"
                    :class="{'transform transition duration-300 ease-in-out hover:scale-110': true,}"
                    as="button"
                >
                    Create Ticket
                </Link>
            </div>
            <div v-if="auth && auth.user">
                <h2 class="text-lg font-semibold mb-5 mt-3">Ticket Status:</h2>
                <div class="flex justify-center items-center mb-10">
                    <div class="flex flex-col items-center mr-10">
                        <h3 class="text-lg">Open Tickets</h3>
                        <p class="text-2xl text-green-500">{{ openTickets }}</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <h3 class="text-lg">Closed Tickets</h3>
                        <p class="text-2xl text-red-500">{{ closedTickets }}</p>
                    </div>
                </div>
                <div v-if="admin && admin.user && admin.user.role === 'admin'">
                    <Link
                        href="/admin/tickets"
                        class="text-sm bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                        :class="{'transform transition duration-300 ease-in-out hover:scale-110': true,}"
                        as="button"
                    >
                        Manage Tickets
                    </Link>
                </div>
            </div>
        </div>
    </section>
</template>
