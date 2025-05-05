<script lang="ts" setup>
import Modal from '@/components/Modals/Modal.vue';
import ModalBody from '@/components/Modals/ModalBody.vue';
import ModalFooter from '@/components/Modals/ModalFooter.vue';
import ModalHeader from '@/components/Modals/ModalHeader.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    comment: {
        type: Object,
        required: true,
    },
});

function destroyComment() {
    router.delete(route('comments.destroy', { comment: props.comment.id }));
}
</script>

<template>
    <Modal>
        <form v-if="$page.props.auth.user" @submit.prevent="destroyComment">
            <div class="flex flex-col items-start">
                <!-- MOdal Header -->
                <ModalHeader variant="destroy">
                    <div class="text-lg font-bold">Are you sure?</div>
                </ModalHeader>
                <!-- Modal Body -->
                <ModalBody>
                    <div class="text-md mb-4 font-semibold text-red-600 dark:text-red-500">You are about to delete the comment:</div>
                    <blockquote class="justify-sart flex size-full text-sm text-gray-800 dark:text-gray-400">"{{ comment.body }}"</blockquote>
                </ModalBody>
                <!-- Modal Footer -->
                <ModalFooter>
                    <button
                        class="group relative h-10 cursor-pointer overflow-hidden rounded-md bg-pink-600 px-6 font-semibold text-neutral-50 transition disabled:bg-pink-300"
                        type="submit"
                    >
                        <span>Delete</span>
                        <span class="absolute inset-0 block h-full w-0 bg-white/20 transition-[width] group-hover:w-full"></span>
                    </button>
                </ModalFooter>
            </div>
        </form>
    </Modal>
</template>
