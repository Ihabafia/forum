<script lang="ts" setup>
import ModalHeader from '@/components/Modals/ModalHeader.vue';
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { useModal } from 'momentum-modal';

const { show, close, redirect } = useModal();
defineProps({
    title: {
        type: String,
        required: false,
        default: null,
    },
    size: {
        type: String,
        required: false,
        default: '3xl',
        validator: (value: string) => {
            return ['sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl'].includes(value);
        },
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
                            class="w-full px-4 text-left align-middle shadow-xl"
                        >
                            <template v-if="title">
                                <ModalHeader :title="title" />
                            </template>
                            <slot :close="close" />
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<style scoped></style>
