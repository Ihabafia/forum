<script lang="ts" setup xmlns:flux="http://www.w3.org/1999/html">
import Comment from '@/components/Comment.vue';
import Container from '@/components/Container.vue';
import InputError from '@/components/InputError.vue';
import Pagination from '@/components/Pagination.vue';
import { Button } from '@/components/ui/button';
import TextArea from '@/components/ui/input/TextArea.vue';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { relativeDate } from '@/Utilities/date';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

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
const formatedDate = computed(() => relativeDate(props.post.created_at));

const commentForm = useForm({
    body: '',
});

const addComment = () => {
    commentForm.post(route('posts.comments.store', props.post.id), {
        preserveScroll: true,
        onSuccess: () => commentForm.reset(),
    });
};
</script>

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

                <form v-if="$page.props.auth.user" class="mt-3" @submit.prevent="addComment">
                    <div>
                        <Label class="sr-only text-sm font-medium" for="body">Comment</Label>
                        <TextArea
                            id="body"
                            v-model="commentForm.body"
                            class="dark:gray-50 bg-gray-50 dark:bg-gray-800"
                            placeholder="Write your comment here..."
                            rows="7"
                        ></TextArea>
                        <InputError :message="commentForm.errors.body" class="mt-1" />
                        <Button
                            :disabled="commentForm.processing"
                            class="mt-5 cursor-pointer border-2 border-pink-500 bg-pink-500 hover:bg-pink-600 dark:bg-pink-600 dark:text-white dark:hover:bg-pink-700"
                            type="submit"
                            >Add Comment
                        </Button>
                    </div>
                </form>

                <div v-if="comments.meta.total > 0">
                    <ul class="divide-y">
                        <li v-for="comment in comments.data" :key="comment.id" class="px-0 py-4">
                            <Comment :comment="comment" />
                        </li>
                    </ul>
                    <Pagination :meta="comments.meta" :only="['comments']" />
                </div>
                <div v-else class="mt-4 text-gray-500">No comments yet.</div>
            </div>
        </Container>
    </AppLayout>
</template>
