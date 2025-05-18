<template>
    <Head title="Posts" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Container>
            <PageHeading v-if="selectedTopic">
                {{ selectedTopic.name }}
                <p class="mb-3 text-sm text-gray-400">{{ selectedTopic.description }}</p>
            </PageHeading>
            <PageHeading v-else>All Posts</PageHeading>
            <menu class="mt-2 mb-5 flex space-x-1 overflow-x-auto">
                <li>
                    <TopicPill :href="route('posts.index', { query: searchForm.query })" :selected="!selectedTopic"> All Posts </TopicPill>
                </li>
                <li v-for="topic in topics" :key="topic.id">
                    <TopicPill :href="route('posts.index', { topic: topic, query: searchForm.query })" :selected="selectedTopic?.id === topic.id">
                        {{ topic.name }}
                    </TopicPill>
                </li>
            </menu>
            <form class="mt-4" @submit.prevent="search">
                <div>
                    <Label for="search">Search</Label>
                    <div class="mt-2 flex space-x-2">
                        <Input
                            id="search"
                            v-model="searchForm.query"
                            class="block w-full border-2 border-pink-500 outline-none dark:focus:border-pink-500"
                            placeholder="Search posts..."
                            type="text"
                        />
                        <SecondaryButton type="submit">Search</SecondaryButton>
                        <PrimaryButton v-if="searchForm.query" type="button" variant="danger" @click="clearSearch"> Clear </PrimaryButton>
                    </div>
                </div>
            </form>
            <div v-if="$page.props.auth.permissions.post.create" class="fixed top-6 right-6 text-right">
                <PrimaryLink :href="route('posts.create')"> Create Post</PrimaryLink>
            </div>
            <ul class="divide-y">
                <li v-for="post in posts.data" :key="post.id" class="flex flex-col items-baseline justify-between md:flex-row">
                    <Link :href="post.route.show" class="group block py-4">
                        <span class="text-lg font-bold group-hover:text-pink-500">{{ post.title }}</span>
                        <span class="mt-1 block text-sm text-gray-500">
                            Created <span class="font-bold">{{ formatedDate(post) }}</span> by <span class="font-bold">{{ post.user.name }}</span
                            >.
                        </span>
                    </Link>
                    <TopicPill :href="route('posts.index', { topic: post.topic })">
                        {{ post.topic.name }}
                    </TopicPill>
                </li>
            </ul>
            <Pagination :meta="posts.meta" :only="['posts']" />
        </Container>
    </AppLayout>
</template>
<script lang="ts" setup>
import Container from '@/components/Container.vue';
import PageHeading from '@/components/PageHeading.vue';
import Pagination from '@/components/Pagination.vue';
import TopicPill from '@/components/TopicPill.vue';
import PrimaryButton from '@/components/ui/button/PrimaryButton.vue';
import PrimaryLink from '@/components/ui/button/PrimaryLink.vue';
import SecondaryButton from '@/components/ui/button/SecondaryButton.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { relativeDate } from '@/Utilities/date';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps(['posts', 'topics', 'selectedTopic', 'query']);
const searchForm = useForm({
    query: props.query,
    page: 1,
});

const page = usePage();
const search = () => searchForm.get(page.url);
const clearSearch = () => {
    searchForm.query = '';
    searchForm.get(route('posts.index', { topic: props.selectedTopic }));
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: '/posts',
    },
    {
        title: props.selectedTopic?.name,
        href: route('posts.index', { topic: props.selectedTopic }),
    },
];

const formatedDate = (post: object) => relativeDate(post.created_at);
</script>
