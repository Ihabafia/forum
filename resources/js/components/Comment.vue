<template>
    <div class="mt-4 items-start justify-between sm:flex">
        <div class="mb-4 sm:mr-4 sm:mb-0">
            <img :src="comment.user.avatar" alt="User Avatar" class="mr-3 h-10 w-10 rounded-full md:h-12 md:w-12" />
        </div>
        <div :id="comment.id" class="flex-1">
            <div class="prose prose-md dark:prose-invert max-w-none break-all" v-html="comment.html" />
            <span class="block pt-1 text-xs text-gray-500 first-letter:uppercase"
                >By <span class="font-bold dark:text-gray-400"> {{ comment.user.name }} </span>,
                <span class="font-bold dark:text-gray-400"> {{ formatedDate(comment.created_at) }} </span>
                <span class="font-semibold text-gray-800 dark:text-gray-100"> | {{ comment.likes_count }}</span
                >.
            </span>
            <div class="flex items-center justify-end space-x-3 text-right empty:hidden">
                <div v-if="$page.props.auth.user">
                    <Link
                        v-if="comment.can.like"
                        :href="route('likes.store', ['comment', comment.id])"
                        class="inline-block cursor-pointer text-gray-500 hover:text-pink-500"
                        method="post"
                        preserve-scroll
                    >
                        <HandThumbUpIcon class="mr-1 inline-block size-5" />
                        Like
                    </Link>
                    <Link
                        v-else
                        :href="route('likes.destroy', ['comment', comment.id])"
                        class="inline-block cursor-pointer text-green-500 hover:text-pink-500"
                        method="delete"
                        preserve-scroll
                    >
                        <HandThumbUpIcon class="mr-1 inline-block size-5" />
                        Liked
                    </Link>
                </div>
                <Link
                    v-if="comment.can?.update"
                    :href="route('comments.edit', { comment: comment.id, page: page })"
                    class="p-0 font-bold text-indigo-500"
                >
                    Edit
                </Link>
                <Link
                    v-if="comment.can?.delete"
                    :href="route('comments.delete', { comment: comment.id, page: lastPage })"
                    class="p-0 font-bold text-red-500"
                >
                    Delete
                </Link>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { relativeDate } from '@/Utilities/date';
import { HandThumbUpIcon } from '@heroicons/vue/20/solid';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = computed(() => {
    const params = new URLSearchParams(window.location.search);
    return params.get('page') ?? 1;
});

const lastPage = computed(() => {
    return props.length > 1 ? props.current : Math.max(props.current - 1, 1);
});

const props = defineProps(['comment', 'length', 'current']);

const formatedDate = (date) => relativeDate(date);
</script>
