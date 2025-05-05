<script lang="ts" setup>
import { useModal } from 'momentum-modal';

const { close } = useModal();
</script>

<template>
    <!-- Modal Footer -->
    <div class="flex size-full items-end justify-between gap-2 rounded-b-xl bg-gray-100 px-6 pt-2 pb-5 dark:bg-gray-900">
        <div class="flex items-center justify-start">
            <slot />
            <button
                class="group relative h-10 cursor-pointer overflow-hidden rounded-md bg-gray-200 px-6 font-semibold text-gray-950 transition dark:bg-gray-900 dark:text-neutral-50"
                type="button"
                @click="close"
            >
                <span>Cancel</span>
                <span class="absolute inset-0 block h-full w-0 bg-white/40 transition-[width] group-hover:w-full dark:bg-white/10" />
            </button>
        </div>
        <div class="flex justify-center">
            <div class="bg-gradient-to-r from-lime-500 to-purple-400 bg-clip-text text-sm leading-tight text-transparent">Ihab Abou Afia</div>
        </div>
    </div>
</template>
