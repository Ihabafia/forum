<script lang="ts" setup>
import ModalNoRedirect from '@/components/Modals/ModalNoRedirect.vue';
import { useConfirm } from '@/Utilities/Composables/useConfirm';
import { nextTick, ref, watchEffect } from 'vue';

const { state, confirm, cancel } = useConfirm();

const cancelButtonRef = ref(null);

watchEffect(async () => {
    if (state.show) {
        await nextTick();
        cancelButtonRef.value?.focus();
    }
});
</script>

<template>
    <ModalNoRedirect :show="state.show">
        <template #title
            ><span class="text-xl">{{ state.title }}</span></template
        >
        <template #content
            ><span class="font-semibold">{{ state.message }}</span></template
        >
        <template #buttons>
            <!--                :disabled="commentForm.processing"-->
            <button
                class="group relative mr-6 h-10 cursor-pointer overflow-hidden rounded-md bg-pink-600 px-6 font-semibold text-neutral-50 transition disabled:bg-pink-300"
                type="submit"
                @click="confirm"
            >
                <span>Confirm</span>
                <span class="absolute inset-0 block h-full w-0 bg-white/20 transition-[width] group-hover:w-full disabled:transition-none"></span>
            </button>
            <button
                ref="cancelButtonRef"
                class="group relative h-10 cursor-pointer overflow-hidden rounded-md bg-gray-200 px-6 font-semibold text-gray-950 transition dark:bg-gray-900 dark:text-neutral-50"
                type="button"
                @click="cancel"
            >
                <span>Cancel</span>
                <span class="absolute inset-0 block h-full w-0 bg-white/40 transition-[width] group-hover:w-full dark:bg-white/10" />
            </button>
        </template>
        <!--                    v-if="!commentForm.processing"-->
    </ModalNoRedirect>
</template>
