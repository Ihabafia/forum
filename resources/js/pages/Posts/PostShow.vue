<script lang="ts" setup>
import Container from '@/components/Container.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: '/posts',
    },
    {
        title: 'Post',
        href: '/posts/1',
    },
];

defineProps(['post']);
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Container>
            <div class="text-2xl font-bold">
                {{ post.title }}
            </div>
            <div class="mt-4">
                {{ post.body }}
            </div>
        </Container>
    </AppLayout>
</template>
