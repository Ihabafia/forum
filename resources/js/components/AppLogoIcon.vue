<script lang="ts" setup>
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <img alt="Digital Outline Logo" src="/imgs/logo.svg" />
</template>
