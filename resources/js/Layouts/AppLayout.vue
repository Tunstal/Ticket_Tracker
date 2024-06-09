<script setup>
import Nav from '@/components/Nav.vue'
</script>

<template class="overflow-x-auto">
    <header class="flex space-x-4 bg-gray-200">
        <Nav />
    </header>
    <section class="w-full max-w-6xl mx-auto">
            <slot />
    </section>
</template>