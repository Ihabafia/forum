<template>
    <div class="mt-3 flex items-center justify-between rounded-xl border border-gray-400 px-4 py-3 sm:px-6 dark:border-gray-500">
        <div class="flex flex-1 justify-between md:hidden">
            <Link
                :href="previousUrl"
                :only="only"
                class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:border-gray-500 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
                preserve-scroll
            >
                Previous
            </Link>
            <Link
                :href="nextUrl"
                :only="only"
                class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:border-gray-500 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
                preserve-scroll
            >
                Next
            </Link>
        </div>
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700 dark:text-gray-400">
                    Showing
                    {{ ' ' }}
                    <span class="font-medium">{{ meta.from }}</span>
                    {{ ' ' }}
                    to
                    {{ ' ' }}
                    <span class="font-medium">{{ meta.to }}</span>
                    {{ ' ' }}
                    of
                    {{ ' ' }}
                    <span class="font-medium">{{ meta.total }}</span>
                    {{ ' ' }}
                    results
                </p>
            </div>
            <div>
                <nav aria-label="Pagination" class="isolate inline-flex -space-x-px rounded-md bg-white shadow-xs dark:bg-gray-700">
                    <Link
                        v-for="link in meta.links"
                        :key="link.label"
                        :class="{
                            'z-10 bg-pink-600 text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-pink-600 dark:text-gray-100':
                                link.active,
                            'text-gray-900 ring-1 ring-gray-300 ring-inset hover:bg-gray-200 focus:outline-offset-0 dark:text-gray-300 dark:ring-gray-600 dark:hover:bg-gray-800':
                                !link.active,
                        }"
                        :href="link.url ?? ''"
                        :only="only"
                        class="relative inline-flex items-center px-4 py-2"
                        preserve-scroll
                    >
                        {{ link.label }}
                    </Link>
                </nav>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    meta: {
        type: Object,
        required: true,
    },
    only: {
        type: Array as () => string[],
        default: () => [],
    },
});

const only = computed(() => (props.only.length === 0 ? [] : [...props.only, 'notification']));
const previousUrl = computed(() => props.meta?.links?.[0]?.url || '');
const nextUrl = computed(() => (props.meta?.links ? [...props.meta.links].reverse()[0]?.url || '' : ''));
</script>
