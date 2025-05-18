<script lang="ts" setup>
import Modal from '@/components/Modals/Modal.vue';
import ModalBody from '@/components/Modals/ModalBody.vue';
import ModalFooter from '@/components/Modals/ModalFooter.vue';
import ModalHeader from '@/components/Modals/ModalHeader.vue';
import CancelButton from '@/components/ui/button/CancelButton.vue';
import PrimaryButton from '@/components/ui/button/PrimaryButton.vue';
import { router } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    comment: {
        type: Object,
        required: true,
    },
});

const page = computed(() => {
    const params = new URLSearchParams(window.location.search);
    return params.get('page');
});

function destroyComment() {
    router.delete(route('comments.destroy', { comment: props.comment.id, page: page.value }));
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
                    <div class="justify-sart flex size-full text-sm text-gray-800 dark:text-gray-400" v-html="comment.html"></div>
                </ModalBody>
                <!-- Modal Footer -->
                <ModalFooter>
                    <div class="flex items-center justify-start gap-4">
                        <PrimaryButton variant="danger">Delete Comment</PrimaryButton>
                        <CancelButton>Cancel</CancelButton>
                    </div>
                </ModalFooter>
            </div>
        </form>
    </Modal>
</template>
