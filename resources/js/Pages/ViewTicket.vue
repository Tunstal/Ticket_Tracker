<script setup>
import Layout from "@/Layouts/AppLayout.vue";
import { lastUpdate, formatDate } from "../utils.js";
import { defineProps, ref, reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";

// Loads the layout component
defineOptions({
    layout: Layout,
});

// Define the prop for the user to be stored in the page
const props = defineProps({
    errors: Object,
    auth: Object,
    ticket: Object,
    category: Object,
    comment: Object,
    comments: Array,
});

// Defined reactive variables
const editMode = reactive({});
const editedComment = reactive({});
const newComment = ref("");
const editingComment = ref(null);

// Function to add a comment to the ticket
function addComment() {
    const comment = {
        idUser: props.auth.user.idUser,
        idTicket: props.ticket.idTicket,
        content: newComment.value,
    };

    Inertia.post(`/tickets/${props.ticket.idTicket}/comments`, comment, {
        preserveScroll: true,
    });

    newComment.value = "";
}

// Function to edit a comment
function editComment(idComment) {
    editingComment.value = idComment;
    editMode[idComment] = true;
    const commentToEdit = props.ticket.comments.find(
        (comment) => comment.idComment === idComment
    );
    editedComment[idComment] = commentToEdit ? commentToEdit.content : "";
}

// Function to cancel editing a comment
function cancelEdit(idComment) {
    editMode[idComment] = false;
    editingComment.value = null;
}

// Function to save a comment
function saveComment(idComment) {
    editMode[idComment] = false;
    editingComment.value = null;

    const comment = {
        idComment: idComment,
        content: editedComment[idComment],
    };

    Inertia.put(
        `/tickets/${props.ticket.idTicket}/comments/${idComment}`,
        comment,
        {
            preserveScroll: true,
        }
    );
}

// Function to confirm an action with a dialog box
function confirm(message) {
    if (window.confirm(message)) {
        Inertia.put(`/admin/tickets/${props.ticket.idTicket}`);
    }
}

// Function to delete a ticket with a confirmation dialog box
function deleteTicket(idTicket) {
    if (confirm("Are you sure you want to delete this ticket?")) {
        Inertia.delete(`/admin/tickets/${idTicket}`);
    }
}
</script>

<template>
    <div class="grid grid-cols-1 gap-4 mt-10">
        <div v-if="auth.user.role === 'user'">
            <Link :href="'/tickets'" class="text-blue-500"
                >« Back to Tickets</Link
            >
        </div>
        <div v-else class="flex justify-between">
            <Link :href="'/admin/tickets'" class="text-red-500"
                >« Back to Tickets</Link
            >
            <div v-if="ticket.status === 'open'">
                <button
                    @click="
                        confirm('Are you sure you want to close this ticket?')
                    "
                    class="flex justify-center items-center text-sm bg-red-500 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded"
                >
                    Close Ticket
                </button>
            </div>
            <div v-else class="flex">
                <div class="mr-2">
                    <button
                        @click="
                            confirm(
                                'Are you sure you want to reopen this ticket?'
                            )
                        "
                        class="flex justify-center items-center text-sm bg-green-500 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded"
                    >
                        Reopen Ticket
                    </button>
                </div>
                <button
                    @click="deleteTicket(ticket.idTicket)"
                    class="flex justify-center items-center text-sm bg-red-500 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded"
                >
                    Delete Ticket
                </button>
            </div>
        </div>
        <div>
            <h1 class="text-4xl font-bold">{{ ticket.title }}</h1>
        </div>
        <div class="grid grid-cols-3 gap-4 mt-3">
            <div class="flex items-center">
                <h2 class="text-lg">Status:</h2>
                <span
                    v-if="ticket.status === 'open'"
                    class="ml-2 text-green-500"
                    >Open</span
                >
                <span v-else class="ml-2 text-red-500">Closed</span>
            </div>
            <div class="flex items-center">
                <h2 class="text-lg">Category:</h2>
                <span class="ml-2">{{ category.name }}</span>
            </div>
            <div class="flex items-center justify-end">
                <h2 class="text-lg">Last Updated:</h2>
                <span v-if="ticket.updated_at" class="ml-2">{{
                    lastUpdate(ticket.updated_at)
                }}</span>
                <span v-else class="ml-2">N/A</span>
            </div>
        </div>
        <div class="flex flex-col border-2 p-5 mb-10">
            <div class="flex justify-between mb-5">
                <h3 class="text-lg font-bold text-blue-500">
                    {{
                        ticket.idUser === auth.user.idUser
                            ? auth.user.username
                            : ticket.user.username
                    }}
                </h3>
                <span class="text-sm">{{ lastUpdate(ticket.created_at) }}</span>
            </div>
            <div class="whitespace-pre-line" v-html="ticket.description"></div>
        </div>
    </div>
    <ul class="grid grid-cols-1 gap-4">
        <li
            class="border-2 p-5"
            v-for="comment in ticket.comments"
            :key="comment.idComment"
        >
            <div class="whitespace-pre-line">
                <div
                    v-if="
                        editMode[comment.idComment] &&
                        comment.idUser === auth.user.idUser
                    "
                >
                    <textarea
                        v-model="editedComment[comment.idComment]"
                        class="w-full"
                    ></textarea>
                    <div class="flex justify-end">
                        <button
                            @click="saveComment(comment.idComment)"
                            class="flex justify-center items-center text-sm bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded w-13 h-6 mr-2"
                        >
                            Save
                        </button>
                        <button
                            @click="cancelEdit(comment.idComment)"
                            class="flex justify-center items-center text-sm bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded w-13 h-6"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
                <div v-else class="grid grid-cols-1 gap-4">
                    <span class="flex justify-between">
                        <p
                            class="text-normal font-bold"
                            :class="{
                                'text-red-500': comment.user.role === 'admin',
                                'text-blue-500': comment.user.role === 'user',
                            }"
                        >
                            {{ comment.user.username }}
                        </p>
                        <p class="text-sm">
                            {{ lastUpdate(comment.created_at) }}
                        </p>
                    </span>
                    <div>{{ comment.content }}</div>
                    <div class="flex justify-between">
                        <p class="text-sm">
                            {{
                                comment.edited_at
                                    ? `Edited: ${formatDate(comment.edited_at)}`
                                    : ""
                            }}
                        </p>
                        <button
                            v-if="
                                comment.idUser === auth.user.idUser &&
                                comment.idComment !== editingComment?.value
                            "
                            @click="editComment(comment.idComment)"
                            class="flex justify-center items-center text-sm text-white font-semibold py-2 px-4 rounded w-12 h-6"
                            :class="{
                                'bg-red-500 hover:bg-red-700':
                                    comment.user.role === 'admin',
                                'bg-blue-500 hover:bg-blue-700':
                                    comment.user.role === 'user',
                            }"
                        >
                            Edit
                        </button>
                    </div>
                </div>
            </div>
        </li>
    </ul>
    <div class="mb-10 mt-5">
        <textarea
            v-model="newComment"
            placeholder="Enter Reply Here..."
            class="border-2 w-full h-48"
        ></textarea>
        <button
            @click="addComment"
            class="text-sm text-white font-semibold py-2 px-4 rounded float-right mb-5"
            :class="{
                'bg-red-500 hover:bg-red-700': auth.user.role === 'admin',
                'bg-blue-500 hover:bg-blue-700': auth.user.role === 'user',
            }"
        >
            Add Reply
        </button>
    </div>
</template>
