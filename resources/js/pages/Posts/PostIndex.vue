<template>
    <Head title="Posts" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Container>
            <ul class="divide-y">
                <li v-for="post in posts.data" :key="post.id">
                    <Link :href="route('posts.show', post.id)" class="group block px-2 py-4">
                        <span class="text-lg font-bold group-hover:text-pink-500">{{ post.title }}</span>
                        <span class="mt-1 block text-sm text-gray-500">
                            Created <span class="font-bold">{{ formatedDate(post) }}</span> by <span class="font-bold">{{ post.user.name }}</span
                            >.
                        </span>
                    </Link>
                </li>
            </ul>
            <Pagination :meta="posts.meta" :only="['posts']" />
        </Container>
    </AppLayout>
</template>
<script lang="ts" setup>
import Container from '@/components/Container.vue';
import Pagination from '@/components/Pagination.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { relativeDate } from '@/Utilities/date';
import { Head, Link } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: '/posts',
    },
];

defineProps(['posts']);
const formatedDate = (post) => relativeDate(post.created_at);
</script>
