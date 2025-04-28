<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface Props {
    href: string;
    tabindex?: number;
    method?: string;
    active?: boolean;
    as?: string;
    when?: () => boolean;
}

defineProps<Props>();
</script>

<template>
    <Link
        :href="href"
        :tabindex="tabindex"
        :method="method"
        :as="as"
        class="ml-4 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]"
        :class="{
             'border rounded bg-gray-700 py-1 px-2':
                active,
        }"
    >
        <slot />
    </Link>
</template>
