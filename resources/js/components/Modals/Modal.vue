<script lang="ts" setup>
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { X } from 'lucide-vue-next';
import { useModal } from 'momentum-modal';

const { show, close, redirect } = useModal();
defineProps({
    size: {
        type: String,
        required: false,
        default: '2xl',
        validator: (value: string) => {
            return ['sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl'].includes(value);
        },
    },
    title: {
        type: String,
        required: false,
        default: ' ',
    },
});
</script>

<template>
    <TransitionRoot :show="show" appear as="template">
        <Dialog as="div" class="relative z-50" @close="close">
            <TransitionChild
                as="template"
                enter="ease-out duration-200"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-out duration-300"
                leave-from="opacity-100"
                leave-to="opacity-0"
                @after-leave="redirect"
            >
                <div class="fixed inset-0 bg-black/60 backdrop-blur-[1.5px] dark:bg-black/75" />
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
                            class="w-full text-left align-middle shadow-xl"
                        >
                            <div class="flex flex-col items-start">
                                <div
                                    class="flex w-full items-center justify-between rounded-t-xl border-b-2 border-gray-100 bg-gray-200 px-6 pt-5 pb-3 dark:border-gray-700 dark:bg-gray-900"
                                >
                                    <slot class="text-lg font-bold" name="title">
                                        <div v-if="title" class="text-lg font-bold">{{ title }}</div>
                                    </slot>
                                    <X class="cursor-pointer" @click="close" />
                                </div>
                                <div class="size-full max-h-[calc(100vh-25rem)] min-h-32 overflow-y-auto bg-gray-100 px-6 py-4 dark:bg-gray-800">
                                    <slot />
                                </div>
                                <div class="flex size-full items-center justify-start gap-2 rounded-b-xl bg-gray-200 px-6 py-5 dark:bg-gray-900">
                                    <!--                                    <slot name="buttons">
                                                                            <Button class="mb-5 border-t-2 border-gray-100 px-5 pb-3" variant="default" @click="close">Save </Button>
                                                                        </slot>-->
                                    <button
                                        class="group relative h-10 cursor-pointer overflow-hidden rounded-md bg-pink-600 px-6 font-semibold text-neutral-50 transition"
                                        @click="close"
                                    >
                                        <span>Save</span>
                                        <span class="absolute inset-0 block h-full w-0 bg-white/20 transition-[width] group-hover:w-full"></span>
                                    </button>
                                    <button
                                        class="group relative h-10 cursor-pointer overflow-hidden rounded-md bg-gray-200 px-6 font-semibold text-gray-950 transition dark:text-neutral-50"
                                        @click="close"
                                    >
                                        <span>Cancel</span>
                                        <span class="absolute inset-0 block h-full w-0 bg-white/40 transition-[width] group-hover:w-full" />
                                    </button>
                                </div>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<style scoped></style>
