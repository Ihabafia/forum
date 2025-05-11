<script lang="ts" setup>
import Modal from '@/components/Modals/Modal.vue';
import ModalBody from '@/components/Modals/ModalBody.vue';
import ModalFooter from '@/components/Modals/ModalFooter.vue';
import ModalHeader from '@/components/Modals/ModalHeader.vue';
import CancelButton from '@/components/ui/button/CancelButton.vue';
import PrimaryButton from '@/components/ui/button/PrimaryButton.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    post: {
        type: Object,
        required: true,
    },
});

function destroyPost() {
    router.delete(route('posts.destroy', { post: props.post.id }));
}
</script>

<template>
    <Modal>
        <form v-if="$page.props.auth.user" @submit.prevent="destroyPost">
            <div class="flex flex-col items-start">
                <!-- MOdal Header -->
                <ModalHeader variant="destroy">
                    <div class="text-lg font-bold">Are you sure?</div>
                </ModalHeader>
                <!-- Modal Body -->
                <ModalBody>
                    <div class="text-md mb-4 font-semibold text-red-600 dark:text-red-500">You are about to delete the post:</div>
                    <blockquote class="justify-sart flex size-full text-sm text-gray-800 dark:text-gray-400">"{{ post.body }}"</blockquote>
                </ModalBody>
                <!-- Modal Footer -->
                <ModalFooter>
                    <div class="flex items-center justify-start gap-4">
                        <PrimaryButton variant="destroy">Delete Post</PrimaryButton>
                        <CancelButton>Cancel</CancelButton>
                    </div>
                </ModalFooter>
            </div>
        </form>
    </Modal>
</template>
