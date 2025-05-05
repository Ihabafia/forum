<template>
    <div class="mt-4 items-start justify-between sm:flex">
        <div class="mb-4 sm:mr-4 sm:mb-0">
            <img :src="comment.user.avatar" alt="User Avatar" class="mr-3 h-10 w-10 rounded-full md:h-12 md:w-12" />
        </div>
        <div :id="comment.id" class="flex-1">
            <span class="text-md block break-all">{{ comment.body }}</span>
            <span class="block pt-1 text-xs text-gray-500 first-letter:uppercase"
                >By <span class="font-bold dark:text-gray-400"> {{ comment.user.name }} </span>,
                <span class="font-bold dark:text-gray-400"> {{ formatedDate(comment.created_at) }} </span>.
            </span>
            <div class="flex items-center justify-end space-x-3 text-right empty:hidden">
                <Link v-if="comment.can?.update" :href="route('comment-modal.edit', comment.id)" class="p-0 font-bold text-indigo-500"> Edit </Link>
                <Link v-if="comment.can?.delete" :href="route('comment-modal.destroy', comment.id)" class="p-0 font-bold text-red-500"> Delete </Link>
                <!--                <form v-if="comment.can?.delete" @submit.prevent="$emit('deleteComment', comment.id)">
                                    <Button class="cursor-pointer p-0 text-red-500" type="submit" variant="link">Delete</Button>
                                </form>-->
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { relativeDate } from '@/Utilities/date';
import { Link } from '@inertiajs/vue3';

defineProps(['comment']);
defineEmits(['deleteComment']);

const formatedDate = (date) => relativeDate(date);
</script>
