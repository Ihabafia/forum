<script lang="ts" setup>
import InputError from '@/components/InputError.vue';
import Modal from '@/components/Modals/Modal.vue';
import ModalBody from '@/components/Modals/ModalBody.vue';
import ModalFooter from '@/components/Modals/ModalFooter.vue';
import ModalHeader from '@/components/Modals/ModalHeader.vue';
import { Label } from '@/components/ui/label';
import { useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    post: {
        type: Object,
        required: false,
        default: null,
    },
    comment: {
        type: Object,
        required: false,
        default: null,
    },
});

const commentTextAreaRef = ref(null);
const commentAction = computed(() => (props.comment ? 'Update Comment' : 'Create Comment'));

const commentForm = useForm({
    body: props.comment?.body ?? '',
});

function updateComment() {
    commentForm.put(
        route('comments.update', {
            comment: props.comment.id,
        }),
    );
}

function storeComment() {
    commentForm.post(route('posts.comments.store', props.post.id));
}
</script>

<template>
    <Modal>
        <form v-if="$page.props.auth.user" @submit.prevent="props.post ? storeComment() : updateComment()">
            <div class="flex flex-col items-start">
                <!-- MOdal Header -->
                <ModalHeader :title="commentAction" />
                <!-- Modal Body -->
                <ModalBody>
                    <Label class="sr-only text-sm font-medium" for="body">Comment</Label>
                    <textarea
                        id="body"
                        ref="commentTextAreaRef"
                        v-model="commentForm.body"
                        class="dark:gray-50 dark:gray-50 block w-full rounded-md bg-gray-50 p-3 py-1.5 text-gray-900 shadow-sm ring-2 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-pink-500 focus:outline-none focus:ring-inset sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-50 dark:ring-gray-600 dark:focus:ring-pink-600"
                        placeholder="Write your comment here..."
                        rows="7"
                    ></textarea>
                    <InputError :message="commentForm.errors.body" class="mt-1" />
                </ModalBody>
                <!-- Modal Footer -->
                <ModalFooter>
                    <button
                        :disabled="commentForm.processing"
                        class="group relative h-10 cursor-pointer overflow-hidden rounded-md bg-pink-600 px-6 font-semibold text-neutral-50 transition disabled:bg-pink-300"
                        type="submit"
                    >
                        <span>Save</span>
                        <span
                            v-if="!commentForm.processing"
                            class="absolute inset-0 block h-full w-0 bg-white/20 transition-[width] group-hover:w-full disabled:transition-none"
                        ></span>
                    </button>
                </ModalFooter>
            </div>
        </form>
    </Modal>
</template>
