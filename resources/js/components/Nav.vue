<template>
    <nav class="flex w-full shadow-md">
        <header
            class="flex justify-between space-x-10 text-bold bg-gray-200 p-3 w-full"
        >
            <h1 class="text-2xl font-bold whitespace-nowrap">Ticket Tracker</h1>
            <div class="flex justify-between items-center w-full">
                <div class="flex space-x-10">
                    <Link
                        href="/"
                        as="button"
                        :class="{
                            'font-bold underline': $page.component === 'Home',
                        }"
                    >
                        Home
                    </Link>
                    <div
                        v-if="$page.props.auth?.user.role === 'user'"
                        class="flex space-x-10"
                    >
                        <Link
                            href="/tickets"
                            as="button"
                            :class="{
                                'font-bold underline':
                                    $page.url.startsWith('/tickets'),
                            }"
                        >
                            Tickets
                        </Link>
                    </div>
                    <div
                        v-if="$page.props.auth?.user.role === 'admin'"
                        class="flex space-x-10"
                    >
                        <Link
                            href="/admin/tickets"
                            as="button"
                            :class="{
                                'font-bold underline':
                                    $page.url.startsWith('/admin/tickets'),
                            }"
                        >
                            Manage Tickets
                        </Link>
                    </div>
                    <div v-if="$page.props.auth?.user" class="flex space-x-10">
                        <Link
                            :href="isAdmin ? '/admin/settings' : '/account'"
                            as="button"
                            :class="{
                                'font-bold underline':
                                    $page.url.startsWith('/admin/settings') ||
                                    $page.url.startsWith('/account'),
                            }"
                        >
                            Settings
                        </Link>
                    </div>
                    <div v-if="!$page.props.auth?.user" class="flex space-x-10">
                        <Link
                            href="/login"
                            as="button"
                            :class="{
                                'font-bold underline':
                                    $page.url.startsWith('/login'),
                            }"
                        >
                            Login
                        </Link>
                        <Link
                            href="/register"
                            as="button"
                            :class="{
                                'font-bold underline':
                                    $page.url.startsWith('/register'),
                            }"
                        >
                            Register
                        </Link>
                    </div>
                </div>
                <div v-if="$page.props.auth?.user" class="flex space-x-10 mr-6 shadow-md">
                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        :class="{
                            'text-sm bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded': 
                            !isAdmin,
                            'text-sm bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded':
                            isAdmin,
                            'transform transition duration-300 ease-in-out hover:scale-110': true,
                        }"
                    >
                        Logout
                    </Link>
                </div>
            </div>
        </header>
    </nav>
</template>

<script>
export default {
    computed: {
        isAdmin() {
            return this.$page.props.auth?.user.role === 'admin';
        },
    },
};
</script>