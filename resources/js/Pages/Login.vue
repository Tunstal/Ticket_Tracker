<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import Layout from "@/Layouts/AppLayout.vue";

// Loads the layout component
defineOptions({
    layout: Layout,
});

// Define the prop for errors to be stored in the page
defineProps({
    errors: Object,
});

// Form object to store the form data to be used for login
const form = useForm({
    email: "",
    password: "",
});

// Function to submit the form data to be validated for login
const submit = () => {
    form.post("/login");
};
</script>

<template>
    <Head>
        <title>Ticket Tracker - Login</title>
    </Head>
    <div class="grid place-items-center min-h-screen">
        <section class="text-center mx-auto border w-1/2 rounded-xl shadow-md">
            <form @submit.prevent="submit">
                <div class="font-bold text-2xl m-6">
                    <h1>Login</h1>
                </div>
                <div class="mb-6">
                    <label
                        for="email"
                        class="block text-sm font-bold text-gray-700 mb-3"
                        >Email</label
                    >
                    <input
                        type="email"
                        id="email"
                        v-model="form.email"
                        required
                        class="border-2 rounded w-1/2 p-2"
                    />
                    <div
                        v-if="errors.email"
                        class="text-red-500 text-sm"
                    >
                        {{ errors.email }}
                    </div>
                </div>
                <div class="mb-6">
                    <label
                        for="password"
                        class="block text-sm font-bold text-gray-700 mb-3"
                        >Password</label
                    >
                    <input
                        type="password"
                        id="password"
                        v-model="form.password"
                        required
                        class="border-2 rounded w-1/2 p-2"
                    />
                    <div
                        v-if="errors.password"
                        v-text="errors.password"
                        class="text-red-500 text-sm"
                    ></div>
                </div>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded m-6 shadow-md"
                    :class="{ 'transform transition duration-300 ease-in-out hover:scale-110': true }"
                >
                    Login
                </button>
            </form>
        </section>
    </div>
</template>
