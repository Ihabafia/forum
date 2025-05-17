<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Container>
            <div class="fixed top-6 right-6 flex items-center justify-end gap-4 text-right">
                <div v-if="post.can?.delete">
                    <PrimaryLink :href="route('posts.delete', post.id)" variant="delete">
                        <div>Delete Post</div>
                    </PrimaryLink>
                </div>
                <div v-if="post.can?.update">
                    <PrimaryLink :href="route('posts.edit', post.id)"> Edit Post</PrimaryLink>
                </div>
            </div>
            <div>
                <TopicPill :href="route('posts.index', { topic: post.topic.slug })">
                    {{ post.topic.name }}
                </TopicPill>
                <page-heading class="mt-3">{{ post.title }}</page-heading>
            </div>
            <div class="mt-1 text-sm text-gray-500">
                Created <span class="font-bold">{{ formatedDate }}</span> by <span class="font-bold">{{ post.user.name }}</span
                >.
            </div>
            <article class="prose prose-sm dark:prose-invert mt-6 max-w-none" v-html="post.html" />

            <div>
                <div class="mt-8 text-xl font-semibold">Comments</div>
                <Link
                    :href="route('posts.comments.create', post.id)"
                    class="mt-5 inline-block cursor-pointer rounded-md border-2 border-pink-500 bg-pink-500 p-2 text-sm font-semibold text-gray-50 hover:bg-pink-600 dark:bg-pink-600 dark:text-white dark:hover:bg-pink-700"
                    >Add Comment
                </Link>

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

<script lang="ts" setup xmlns:flux="http://www.w3.org/1999/html">
import Comment from '@/components/Comment.vue';
import Container from '@/components/Container.vue';
import PageHeading from '@/components/PageHeading.vue';
import Pagination from '@/components/Pagination.vue';
import TopicPill from '@/components/TopicPill.vue';
import PrimaryLink from '@/components/ui/button/PrimaryLink.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { relativeDate } from '@/Utilities/date';
import { off, on } from '@/Utilities/eventBuss';
import { Link } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, ref } from 'vue';

const props = defineProps(['post', 'comments']);

const breadcrumbs = ref<BreadcrumbItem[]>([
    {
        title: 'Posts',
        href: '/posts',
    },
    {
        title: props.post.title,
        href: `/posts/${props.post.id}`,
    },
]);

onMounted(() => {
    const updateBreadcrumbs = (updatedPost: { title: string }) => {
        breadcrumbs.value = [
            breadcrumbs.value[0],
            {
                ...breadcrumbs.value[1],
                title: updatedPost.title,
            },
        ];
    };

    on('postUpdated', updateBreadcrumbs);

    onUnmounted(() => {
        off('postUpdated', updateBreadcrumbs);
    });
});

const formatedDate = computed(() => relativeDate(props.post.created_at));
</script>
