<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { defineProps } from 'vue';

defineProps({
    active: Boolean,
    href: String,
    method: String,
});


</script>

<template>
    <Link
      :href="href"
      :method="method"
      class="text-black hover:underline"
      :class="{'font-bold underline': active }"
    >
      <slot />
    </Link>
  </template>