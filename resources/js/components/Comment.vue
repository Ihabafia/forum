<template>
    <div class="mt-4 sm:flex">
        <div class="flex items-start justify-between sm:mr-4 sm:mb-0">
            <img :src="comment.user.avatar" alt="User Avatar" class="mr-3 h-10 w-10 rounded-full md:h-12 md:w-12" />
            <div class="flex-1">
                <span class="text-md break-all">{{ comment.body }}</span>
                <span class="block pt-1 text-xs text-gray-500 first-letter:uppercase"
                    >By <span class="font-bold dark:text-gray-400"> {{ comment.user.name }} </span>,
                    <span class="font-bold dark:text-gray-400"> {{ formatedDate(comment.created_at) }} </span>.
                </span>
                <div>
                    <form v-if="canDelete" @submit.prevent="deleteComment">
                        <Button class="pl-0 text-red-500" type="submit" variant="link">Delete</Button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { relativeDate } from '@/Utilities/date';
import { Button } from '@/components/ui/button';
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps(['comment']);

const deleteComment = () =>
    router.delete(route('comments.destroy', props.comment.id), {
        preserveScroll: true,
        onSuccess: () => {
            // Optionally, you can add a success message or perform any other action after deletion
        },
    });

const canDelete = computed(() => props.comment.user.id === usePage().props.auth.user?.id);
const formatedDate = (date) => relativeDate(date);
</script>
