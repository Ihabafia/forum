<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Container>
            <div class="text-2xl font-bold">
                {{ post.title }}
            </div>
            <div class="mt-1 text-sm text-gray-500">
                Created <span class="font-bold">{{ formatedDate }}</span> by <span class="font-bold">{{ post.user.name }}</span
                >.
            </div>
            <article class="mt-6">
                <pre class="font-sans whitespace-pre-wrap">{{ post.body }}</pre>
            </article>

            <div>
                <div class="mt-8 text-xl font-semibold">Comments</div>

                <form v-if="$page.props.auth.user" class="mt-3" @submit.prevent="() => (commentIdBeingEdited ? updateComment() : addComment())">
                    <div>
                        <Label class="sr-only text-sm font-medium" for="body">Comment</Label>
                        <textarea
                            id="body"
                            ref="commentTextAreaRef"
                            v-model="commentForm.body"
                            class="dark:gray-50 dark:gray-50 block w-full rounded-md border-0 bg-gray-50 p-3 py-1.5 text-gray-900 shadow-sm ring-1 ring-gray-300 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-pink-600 focus:ring-inset sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-50"
                            placeholder="Write your comment here..."
                            rows="7"
                        ></textarea>
                        <InputError :message="commentForm.errors.body" class="mt-1" />
                        <Button
                            :disabled="commentForm.processing"
                            class="mt-5 cursor-pointer border-2 border-pink-500 bg-pink-500 hover:bg-pink-600 dark:bg-pink-600 dark:text-white dark:hover:bg-pink-700"
                            type="submit"
                            >{{ commentAction }}
                        </Button>
                        <Button v-if="commentIdBeingEdited" class="ml-3" type="button" variant="link" @click="cancelEditComment"> Cancel </Button>
                    </div>
                </form>

                <div v-if="comments.meta.total > 0">
                    <ul class="divide-y">
                        <li v-for="comment in comments.data" :key="comment.id" class="px-0 py-4">
                            <Comment :comment="comment" @deleteComment="deleteComment" @editComment="editComment" />
                        </li>
                    </ul>
                    <Pagination :meta="comments.meta" :only="['comments']" />
                </div>
                <div v-else class="mt-4 text-gray-500">No comments yet.</div>
            </div>
        </Container>
    </AppLayout>
</template>

<script lang="ts" setup xmlns:flux="http://www.w3.org/1999/html">
import Comment from '@/components/Comment.vue';
import Container from '@/components/Container.vue';
import InputError from '@/components/InputError.vue';
import Pagination from '@/components/Pagination.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { relativeDate } from '@/Utilities/date';
import { router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps(['post', 'comments']);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: '/posts',
    },
    {
        title: props.post.title,
        href: '/posts/1',
    },
];

const commentIdBeingEdited = ref(null);
const commentTextAreaRef = ref(null);

const formatedDate = computed(() => relativeDate(props.post.created_at));
const commentBeingEdit = computed(() => {
    return props.comments.data.find((comment) => comment.id === commentIdBeingEdited.value);
});

const commentForm = useForm({
    body: '',
});

const cancelEditComment = () => {
    commentIdBeingEdited.value = null;
    commentForm.reset();
};
const commentAction = computed(() => {
    return commentIdBeingEdited.value ? 'Update Comment' : 'Add Comment';
});

const editComment = (commentId) => {
    commentIdBeingEdited.value = commentId;
    commentForm.body = commentBeingEdit.value?.body;
    commentTextAreaRef.value?.focus();
};

const updateComment = () => {
    commentForm.put(
        route('comments.update', {
            comment: commentIdBeingEdited.value,
            page: props.comments.meta.current_page,
        }),
        {
            preserveScroll: true,
            onSuccess: () => {
                cancelEditComment();
            },
        },
    );
};

const addComment = () => {
    commentForm.post(route('posts.comments.store', props.post.id), {
        preserveScroll: true,
        onSuccess: () => commentForm.reset(),
    });
};

const deleteComment = (commentId) => {
    router.delete(route('comments.destroy', { comment: commentId, page: props.comments.meta.current_page }), {
        preserveScroll: true,
        onSuccess: () => {
            // Optionally, you can add a success message or perform any other action after deletion
        },
    });
};
</script>
