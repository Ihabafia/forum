<template>
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="grid auto-rows-min grid-cols-1 gap-4">
            <div
                class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video rounded-xl border-1 bg-gray-100 px-10 py-6 dark:bg-gray-900"
            >
                <slot />
            </div>
        </div>
    </div>
</template>
