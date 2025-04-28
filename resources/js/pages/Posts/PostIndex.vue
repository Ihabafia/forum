<script lang="ts" setup>
import Container from '@/components/Container.vue';
import Pagination from '@/components/Pagination.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: '/posts',
    },
];

defineProps(['posts']);
</script>

<template>
    <Head title="Posts" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Container>
            <ul class="divide-y">
                <li v-for="post in posts.data" :key="post.id" class="px-2 py-4">
                    <span class="text-lg font-bold">{{ post.title }}</span>
                </li>
            </ul>
            <Pagination :meta="posts.meta" />
        </Container>
    </AppLayout>
</template>
