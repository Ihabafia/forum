<script lang="ts" setup>
import InputError from '@/components/InputError.vue';
import MarkdownEditor from '@/components/MarkdownEditor.vue';
import Modal from '@/components/Modals/Modal.vue';
import ModalBody from '@/components/Modals/ModalBody.vue';
import ModalFooter from '@/components/Modals/ModalFooter.vue';
import ModalHeader from '@/components/Modals/ModalHeader.vue';
import CancelButton from '@/components/ui/button/CancelButton.vue';
import PrimaryButton from '@/components/ui/button/PrimaryButton.vue';
import { Label } from '@/components/ui/label';
import { useForm } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';

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
const page = computed(() => {
    const params = new URLSearchParams(window.location.search);
    return params.get('page');
});

onMounted(() => {
    commentTextAreaRef.value?.focus();
});

const commentForm = useForm({
    body: props.comment?.body ?? '',
});

function updateComment() {
    commentForm.put(
        route('comments.update', {
            comment: props.comment.id,
            page: page.value,
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
                    <MarkdownEditor
                        id="body"
                        ref="commentTextAreaRef"
                        v-model="commentForm.body"
                        editorClass="min-h-[160px]"
                        placeholder="Speak your mind Spoke..."
                    />
                    <InputError :message="commentForm.errors.body" class="mt-1" />
                </ModalBody>
                <!-- Modal Footer -->
                <ModalFooter>
                    <div class="flex items-center justify-start gap-4">
                        <PrimaryButton :disabled="commentForm.processing">{{ commentAction }}</PrimaryButton>
                        <CancelButton>Cancel</CancelButton>
                    </div>
                </ModalFooter>
            </div>
        </form>
    </Modal>
</template>
