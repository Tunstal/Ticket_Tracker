<script setup>
import { defineProps } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import Layout from '@/Layouts/AppLayout.vue';

// Define the layout for this component
defineOptions({
    layout: Layout,
});

// Define props
const props = defineProps({
    ticket: Object,
    tickets: Array,
    errors: Object,
    auth: Object,
    categories: Array,
});

// Create form data using Inertia's useForm
const form = useForm({
    idUser: props.auth.user.idUser,
    title: '',
    idCategory: '',
    description: '',
});

// Function to submit the form data to the database for ticket creation
function submit() {
    form.post('/tickets/create');
}
</script>

<template>
    <head>
        <title>Ticket Tracker - Create Ticket</title>
    </head>

    <section class="flex justify-center mt-10">
        <div class="w-1/2">
            <form @submit.prevent="submit" class="text-center">
                <div class="font-bold text-2xl m-6">
                    <h1>Create Ticket</h1>
                </div>
                <div class="mb-6">
                    <label
                        for="title"
                        class="block text-sm font-bold text-gray-700 mb-3"
                        >Title</label
                    >
                    <input
                        type="text"
                        v-model="form.title"
                        required
                        class="border-2 rounded w-full p-2"
                    />
                    <div
                        v-if="props.errors.title"
                        v-text="props.errors.title"
                        class="text-red-500 text-sm"
                    ></div>
                </div>
                <div class="mb-6">
                    <label
                        for="idCategory"
                        class="block text-sm font-bold text-gray-700 mb-3"
                        >Category</label
                    >
                    <select
                        v-model="form.idCategory"
                        required
                        class="border-2 rounded w-full p-2"
                    >
                        <option value="" disabled selected>Select a category</option>
                        <option
                            v-for="category in props.categories"
                            :key="category.idCategory"
                            :value="category.idCategory"
                            >{{ category.name }}</option
                        >
                    </select>
                    <div
                        v-if="props.errors.idCategory"
                        v-text="props.errors.idCategory"
                        class="text-red-500 text-sm"
                    ></div>
                </div>
                <div class="mb-6">
                    <label
                        for="description"
                        class="block text-sm font-bold text-gray-700 mb-3"
                        >Description</label
                    >
                    <textarea
                        v-model="form.description"
                        required
                        class="border-2 rounded w-full p-2"
                    ></textarea>
                    <div
                        v-if="props.errors.description"
                        v-text="props.errors.description"
                        class="text-red-500 text-sm"
                    ></div>
                </div>
                <div class="mb-6">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="bg-blue-500 text-white rounded p-2 w-full"
                    >
                        Create Ticket
                    </button>
                </div>
            </form>
        </div>
    </section>
</template>