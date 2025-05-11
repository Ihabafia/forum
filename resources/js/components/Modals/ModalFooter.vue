<script lang="ts" setup></script>

<template>
    <!-- Modal Footer -->
    <div class="flex size-full items-end justify-between gap-2 rounded-b-xl bg-gray-100 px-6 pb-5 dark:bg-gray-900">
        <div class="flex items-center justify-start">
            <slot />
        </div>
        <div class="flex justify-center">
            <div class="bg-gradient-to-r from-lime-500 to-purple-400 bg-clip-text text-sm leading-tight text-transparent">Ihab Abou Afia</div>
        </div>
    </div>
</template>
