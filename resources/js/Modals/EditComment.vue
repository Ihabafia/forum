<script lang="ts" setup>
import Modal from '@/components/Modals/Modal.vue';
import { Button } from '@/components/ui/button';

defineProps(['comment']);
</script>

<template>
    <Modal title="Edit Comment">
        <!--        <template #title>
                    <span class="text-4xl text-red-500">Test Title</span>
                </template>-->
        <div class="flex">
            <div>{{ comment.body }}</div>
        </div>
        <template #buttons>
            <Button class="mb-5 border-t-2 border-gray-100 px-5 pb-3" variant="destructive" @click="$emit('cancel')"> Delete </Button>
        </template>
    </Modal>
</template>
