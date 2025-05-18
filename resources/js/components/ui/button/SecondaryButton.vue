<script lang="ts" setup>

import { X } from 'lucide-vue-next';

const props = defineProps({
    disabled: {
        type: Boolean,
        default: false
    },
    variant: {
        type: String,
        default: 'default',
        validator: (value: string) => {
            return ['default', 'danger', 'destroy', 'delete'].includes(value);
        }
    }
});
</script>

<template>
    <button
        :class="{
            'border-pink-600 disabled:border-pink-300': variant === 'default',
            'border-red-500 text-neutral-50 disabled:border-red-300': variant === 'danger' || variant === 'destroy' || variant === 'delete',
        }"
        class="group flex border-2 justify-center items-center relative h-9 cursor-pointer overflow-hidden rounded-md px-4 font-semibold text-white transition"
        type="submit"
    >
        <template v-if="variant === 'delete'">
            <div class="flex items-center gap-1">
                <X class="size-5" />
                <span><slot /></span>
            </div>
        </template>
        <template v-else>
            <span><slot /></span>
        </template>
        <span
            v-if="!disabled"
            class="absolute inset-0 block h-full w-0 bg-pink-200/20 transition-[width] group-hover:w-full disabled:transition-none"
        ></span>
    </button>
</template>
