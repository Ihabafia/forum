<script lang="ts" setup>
import { X } from 'lucide-vue-next';
import { useModal } from 'momentum-modal';

defineProps({
    title: {
        type: String,
        required: false,
        default: ' ',
    },
    variant: {
        type: String,
        required: false,
        validator: (value: string) => {
            return ['destroy'].includes(value);
        },
    },
});
const { close } = useModal();
</script>

<template>
    <!-- MOdal Header -->
    <div
        :class="{
            'bg-gray-200 dark:bg-gray-900': variant !== 'destroy',
            'bg-red-500 text-gray-100 dark:bg-red-600': variant === 'destroy',
        }"
        class="flex w-full items-center justify-between rounded-t-xl border-b-2 border-gray-100 px-6 pt-4 pb-4 dark:border-gray-700"
    >
        <slot>
            <div v-if="title" class="text-lg font-bold">{{ title }}</div>
        </slot>
        <X class="cursor-pointer" @click="close" />
    </div>
</template>
