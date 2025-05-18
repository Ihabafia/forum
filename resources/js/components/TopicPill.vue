<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
    selected: {
        type: Boolean,
        required: false,
        default: false,
    },
});
</script>

<template>
    <Link
        :class="{
            'bg-pink-500 text-gray-50': selected,
            'text-pink-500': !selected,
        }"
        :href="href"
        class="rounded-full border border-pink-500 px-2 py-0.5 text-xs font-semibold hover:bg-pink-500 hover:text-gray-50"
    >
        <slot />
    </Link>
</template>
