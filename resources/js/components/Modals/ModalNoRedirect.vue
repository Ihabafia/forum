<script lang="ts" setup>
import { useConfirm } from '@/Utilities/Composables/useConfirm';
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { X } from 'lucide-vue-next';

const { cancel } = useConfirm();
defineProps({
    show: {
        type: Boolean,
        required: true,
        default: false,
    },
    title: {
        type: String,
        required: false,
        default: ' ',
    },
    size: {
        type: String,
        required: false,
        default: '3xl',
        validator: (value: string) => {
            return ['sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl'].includes(value);
        },
    },
    variant: {
        type: String,
        required: false,
        validator: (value: string) => {
            return ['destroy'].includes(value);
        },
    },
});
</script>

<template>
    <TransitionRoot :show="show" appear as="template">
        <Dialog as="div" class="relative z-50">
            <TransitionChild
                as="template"
                enter="ease-out duration-200"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-out duration-300"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/60 backdrop-blur-[1.5px] dark:bg-black/75" @click="cancel" />
            </TransitionChild>
            <div class="fixed inset-0 text-gray-950 dark:text-gray-300">
                <div class="flex min-h-full items-center justify-center">
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="ease-out duration-300"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel
                            :class="{
                                'max-w-sm': size === 'sm',
                                'max-w-md': size === 'md',
                                'max-w-lg': size === 'lg',
                                'max-w-xl': size === 'xl',
                                'max-w-2xl': size === '2xl',
                                'max-w-3xl': size === '3xl',
                                'max-w-4xl': size === '4xl',
                            }"
                            class="w-full px-4 text-left align-middle shadow-xl"
                        >
                            <!-- MOdal Header -->
                            <div
                                :class="{
                                    'bg-gray-200 dark:bg-gray-900': variant !== 'destroy',
                                    'bg-red-500 text-gray-100 dark:bg-red-600': variant === 'destroy',
                                }"
                                class="flex w-full items-center justify-between rounded-t-xl border-b-2 border-gray-100 px-6 pt-4 pb-4 dark:border-gray-700"
                            >
                                <slot name="title">
                                    <span class="dark:text-2xl">{{ title }}</span>
                                </slot>
                                <X class="cursor-pointer" @click="cancel" />
                            </div>
                            <div class="size-full max-h-[calc(100vh-10rem)] min-h-24 overflow-y-auto bg-gray-100 p-6 text-lg dark:bg-gray-900">
                                <slot name="content" />
                            </div>
                            <!-- Modal Footer -->
                            <div class="flex size-full items-end justify-between gap-2 rounded-b-xl bg-gray-100 px-6 pt-2 pb-5 dark:bg-gray-900">
                                <div class="flex items-center justify-start">
                                    <slot name="buttons" />
                                </div>
                                <div class="flex justify-center">
                                    <div class="bg-gradient-to-r from-lime-500 to-purple-400 bg-clip-text text-sm leading-tight text-transparent">
                                        Ihab Abou Afia
                                    </div>
                                </div>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
