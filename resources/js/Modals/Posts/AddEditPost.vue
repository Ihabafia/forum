<script lang="ts" setup>
import InputError from '@/components/InputError.vue';
import MarkdownEditor from '@/components/MarkdownEditor.vue';
import Modal from '@/components/Modals/Modal.vue';
import ModalBody from '@/components/Modals/ModalBody.vue';
import ModalFooter from '@/components/Modals/ModalFooter.vue';
import ModalHeader from '@/components/Modals/ModalHeader.vue';
import CancelButton from '@/components/ui/button/CancelButton.vue';
import { Label } from '@/components/ui/label';
import { isInProduction } from '@/Utilities/environment';
import { emit } from '@/Utilities/eventBuss';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, onMounted, ref } from 'vue';

const props = defineProps({
    post: {
        type: Object,
        required: false,
        default: null,
    },
    topics: {
        type: Array,
        required: false,
        default: () => [],
    },
});

const postTextAreaRef = ref(null);
const postAction = computed(() => (props.post?.id ? 'Update Post' : 'Create Post'));

const postForm = useForm({
    title: props.post?.title ?? '',
    body: props.post?.body ?? '',
    topic_id: props.post?.topic_id ?? '',
});

function updatePost() {
    postForm.put(
        route('posts.update', {
            post: props.post.id,
        }),
        {
            onSuccess: () => {
                emit('postUpdated', { title: postForm.title });
            },
        },
    );
}

onMounted(() => {
    if (!props.post?.id) {
        postTextAreaRef.value?.focus();
    }
});

function storePost() {
    postForm.post(route('posts.store'));
}

const autoFill = async () => {
    if (isInProduction) {
        return;
    }

    const response = await axios.get('/local/post-content');

    postForm.title = response.data.title;
    postForm.body = response.data.body;
};
</script>

<template>
    <Modal size="4xl">
        <form v-if="$page.props.auth.user" @submit.prevent="props.post?.id ? updatePost() : storePost()">
            <div class="flex flex-col items-start">
                <!-- MOdal Header -->
                <ModalHeader :title="postAction" />
                <!-- Modal Body -->
                <ModalBody>
                    <div class="grid gap-6">
                        <div class="grid gap-2">
                            <Label class="sr-only" for="title">Title</Label>
                            <input
                                id="title"
                                ref="postTextAreaRef"
                                v-model="postForm.title"
                                :tabindex="1"
                                autocomplete="title"
                                class="dark:gray-50 dark:gray-50 block w-full rounded-md bg-gray-50 p-3 py-1.5 text-gray-900 shadow-sm ring-2 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-pink-500 focus:outline-none focus:ring-inset sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-50 dark:ring-gray-600 dark:focus:ring-pink-600"
                                placeholder="Post Title"
                                type="text"
                            />
                            <InputError :message="postForm.errors.title" />
                        </div>
                        <div class="grid gap-2">
                            <Label class="font-semibold" for="topic_id">Topic</Label>
                            <select
                                id="topic_id"
                                v-model="postForm.topic_id"
                                :tabindex="2"
                                class="dark:gray-50 dark:gray-50 block w-full rounded-md bg-gray-50 px-3 py-2 text-gray-900 shadow-sm ring-2 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-pink-500 focus:outline-none focus:ring-inset sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-50 dark:ring-gray-600 dark:focus:ring-pink-600"
                                name="topic_id"
                            >
                                <option value="">Choose a Topic</option>
                                <option v-for="topic in topics" :key="topic.id" :value="topic.id">
                                    {{ topic.name }}
                                </option>
                            </select>
                            <InputError :message="postForm.errors.topic_id" />
                        </div>
                        <div class="grid gap-2">
                            <Label class="sr-only text-sm font-medium" for="body">Comment</Label>
                            <MarkdownEditor v-model="postForm.body" editorClass="min-h-[270px]">
                                <template #toolbar="{ editor }">
                                    <li v-if="!isInProduction" class="border-r border-gray-300 dark:border-gray-600">
                                        <button class="cursor-pointer px-3 py-2" title="Autofill" type="button" @click="autoFill">
                                            <i class="ri-article-line"></i>
                                        </button>
                                    </li>
                                </template>
                            </MarkdownEditor>
                            <!--                            <textarea
                                                            id="body"
                                                            v-model="postForm.body"
                                                            class="dark:gray-50 dark:gray-50 mt-2 block w-full rounded-md bg-gray-50 p-3 py-1.5 text-gray-900 shadow-sm ring-2 ring-gray-200 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-pink-500 focus:outline-none focus:ring-inset sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-50 dark:ring-gray-600 dark:focus:ring-pink-600"
                                                            placeholder="Write your post here..."
                                                            rows="10"
                                                        ></textarea>-->
                            <InputError :message="postForm.errors.body" class="mt-1" />
                        </div>
                    </div>
                </ModalBody>
                <!-- Modal Footer -->
                <ModalFooter>
                    <button
                        :disabled="postForm.processing"
                        class="group relative mr-4 h-10 cursor-pointer overflow-hidden rounded-md bg-pink-600 px-6 font-semibold text-neutral-50 transition disabled:bg-pink-300"
                        type="submit"
                    >
                        <span>{{ postAction }}</span>
                        <span
                            v-if="!postForm.processing"
                            class="absolute inset-0 block h-full w-0 bg-white/20 transition-[width] group-hover:w-full disabled:transition-none"
                        ></span>
                    </button>
                    <!--                    <PrimaryButton :disabled="postForm.processing" class="mr-4"> Create Post</PrimaryButton>-->
                    <CancelButton>Cancel</CancelButton>
                </ModalFooter>
            </div>
        </form>
    </Modal>
</template>
