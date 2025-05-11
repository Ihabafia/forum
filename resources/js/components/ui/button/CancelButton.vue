<script lang="ts" setup>
import { useModal } from 'momentum-modal';

const { close } = useModal();

const props = defineProps({
    cancel: {
        type: Boolean,
        default: false
    }
});
</script>

<template>
    <button
        class="group relative h-10 cursor-pointer overflow-hidden rounded-md bg-gray-200 px-6 font-semibold text-gray-950 transition dark:bg-gray-900 dark:text-neutral-50"
        type="button"
        @click="close"
    >
        <span><slot /></span>
        <span
            class="absolute inset-0 block h-full w-0 bg-white/40 transition-[width] group-hover:w-full dark:bg-white/10" />
    </button>
</template>
