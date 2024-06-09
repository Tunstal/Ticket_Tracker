<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Layout from "@/Layouts/AppLayout.vue";

// Loads the layout component
defineOptions({
    layout: Layout,
});
// Props
const props = defineProps({
    auth: Object,
    errors: Object,
});
// Form
const form = useForm({
    currentPassword: "",
    password: "",
    password_confirmation: "",
});

const successMessage = ref("");

// Submit
const submit = () => {
    form.put("/account/password", {
        preserveState: true,
        onSuccess: () => {
            form.reset();

            successMessage.value = "Password updated successfully!";
        },
    });
};

// Username
let editing = ref(false);
const successMessageName = ref("");

const submitUsername = () => {
    Inertia.put("/account/username", props.auth.user, {
        preserveState: true,
        onSuccess: () => {
            editing.value = false;
            successMessageName.value = "Username updated successfully!";
        },
    });
};
</script>

<template>
    <head>
        <title>Ticket Tracker - Account Settings</title>
    </head>

    <div class="flex flex-col justify-center items-center mt-10">
        <div
            class="mb-3 flex justify-center items-center w-full text-center font-semibold"
        >
            <h1 class="text-2xl">Account Settings</h1>
        </div>
        <div class="mt-10 flex justify-center items-center w-full text-center font-semibold">
            <h2 class="text-xl">Update Password</h2>
        </div>
        <form @submit.prevent="submit" class="w-1/2 mt-5 border rounded-xl p-5 shadow-lg">
            <div class="mb-6">
                <label
                    for="Current Password"
                    class="block text-sm font-bold text-gray-700 mb-3"
                    >Current Password</label
                >
                <input
                    type="password"
                    id="currentPassword"
                    v-model="form.currentPassword"
                    required
                    class="border-2 rounded w-2/3 p-2"
                />
                <div v-if="errors.currentPassword" class="text-red-500 text-sm">
                    {{ errors.currentPassword }}
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
                    class="border-2 rounded w-2/3 p-2"
                />
                <div v-if="errors.password" class="text-red-500 text-sm">
                    {{ errors.password }}
                </div>
            </div>
            <div class="mb-6">
                <label
                    for="confirmPassword"
                    class="block text-sm font-bold text-gray-700 mb-3"
                    >Confirm Password</label
                >
                <input
                    type="password"
                    id="confirmPassword"
                    v-model="form.password_confirmation"
                    required
                    class="border-2 rounded w-2/3 p-2"
                />
                <div
                    v-if="errors.password_confirmation"
                    class="text-red-500 text-sm"
                >
                    {{ errors.password_confirmation }}
                </div>
            </div>
            <div class="mb-6 flex items-center">
                <button
                    type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-md"
                    :class="{'transform transition duration-300 ease-in-out hover:scale-110': true,}"
                >
                    Update Password
                </button>
                <div v-if="successMessage" class="text-green-500 text-sm ml-5">
                    {{ successMessage }}
                </div>
            </div>
        </form>
        <div class="mt-10 flex items-center justify-center w-full text-center font-semibold">
            <h2 class="text-xl">Username</h2>
        </div>
        <div class="mt-5 flex items-center justify-center w-full text-center font-semibold">
            <input
                type="text"
                id="username"
                v-model="auth.user.username"
                :readonly="!editing"
                :class="{
                    'bg-gray-200 text-gray-500': !editing,
                    'bg-white': editing,
                }"
                class="border-2 rounded w-1/2 p-2"
                
            />
            <button
                v-if="!editing"
                @click="editing = true, successMessageName = ''"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-5"
                :class="{'transform transition duration-300 ease-in-out hover:scale-110': true,}"
            >
                Edit
            </button>
            <button
                v-if="editing"
                @click="editing = false"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-5"
                :class="{'transform transition duration-300 ease-in-out hover:scale-110': true,}"
            >
                Cancel
            </button>
            <button
                v-if="editing"
                @click="submitUsername"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-5"
                :class="{'transform transition duration-300 ease-in-out hover:scale-110': true,}"
            >
                Save
            </button>

            <div v-if="successMessageName" class="text-green-500 text-sm ml-5">
                {{ successMessageName }}
            </div>
        </div>
    </div>
</template>
