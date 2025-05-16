<script lang="ts" setup>
import { Link } from '@tiptap/extension-link';
import { Placeholder } from '@tiptap/extension-placeholder';
import { StarterKit } from '@tiptap/starter-kit';
import { EditorContent, useEditor } from '@tiptap/vue-3';
import 'remixicon/fonts/remixicon.css';
import { Markdown } from 'tiptap-markdown';
import { onMounted, watch } from 'vue';

const props = defineProps({
    modelValue: '',
    editorClass: '',
    placeholder: null,
});

const emit = defineEmits(['update:modelValue']);

const editor = useEditor({
    extensions: [
        StarterKit.configure({
            heading: {
                levels: [2, 3, 4],
            },
            code: false,
            codeBlock: false,
        }),
        Link.configure({
            autolink: true,
            openOnClick: true,
            linkOnPaste: true,
            HTMLAttributes: {
                target: '_blank',
                rel: 'noopener noreferrer',
            },
        }),
        Markdown,
        Placeholder.configure({
            placeholder: props.placeholder,
        }),
    ],
    editorProps: {
        attributes: {
            // Editor classes
            class: `max-w-none prose prose-sm ring-0 border-0 outline-none dark:prose-invert ${props.editorClass}`,
        },
    },
    onCreate: () => {
        watch(
            () => props.modelValue,
            (value) => {
                if (value === editor.value?.storage.markdown.getMarkdown()) {
                    return;
                }

                editor.value?.commands.setContent(value);
                editor.value?.commands.focus();
            },
            { immediate: true },
        );
    },
    onUpdate: () => emit('update:modelValue', editor.value?.storage.markdown.getMarkdown()),
});

onMounted(() => {
    if (props.modelValue) {
        editor.value?.commands.focus();
    }
});

const promptUserForHref = () => {
    if (editor.value?.isActive('link')) {
        return editor.value?.chain().focus().extendMarkRange('link').unsetLink().run();
    }

    let href = window.prompt('Where do you want to link to?');
    if (!href) {
        return editor.value?.chain().focus().run();
    }

    // Ensure the link starts with https:// unless it starts with tel:
    if (!href.startsWith('https://') && !href.startsWith('tel:')) {
        if (href.startsWith('http://')) {
            href = href.replace('http://', 'https://');
        } else {
            href = `https://${href}`;
        }
    }
    editor.value?.chain().focus().extendMarkRange('link').setLink({ href }).run();
};
</script>

<template>
    <div
        class="group flex flex-col items-start rounded-md shadow-sm ring-2 ring-gray-200 focus-within:ring-pink-500 dark:ring-gray-600 dark:focus-within:ring-pink-500"
    >
        <menu
            class="flex w-full divide-x divide-gray-300 border-b border-gray-300 ring-inset focus:outline-none dark:divide-gray-600 dark:border-b-gray-600"
        >
            <li>
                <button
                    :class="[editor?.isActive('bold') ? 'bg-pink-500 text-gray-50 ring-2 ring-pink-500' : 'hover:bg-gray-300 dark:hover:bg-gray-600']"
                    class="cursor-pointer rounded-tl-md px-3 py-2"
                    title="Bold"
                    type="button"
                    @click="() => editor?.chain().focus().toggleBold().run()"
                >
                    <i class="ri-bold"></i>
                </button>
            </li>
            <li>
                <button
                    :class="[
                        editor?.isActive('italic') ? 'bg-pink-500 text-gray-50 ring-2 ring-pink-500' : 'hover:bg-gray-300 dark:hover:bg-gray-600',
                    ]"
                    class="cursor-pointer px-3 py-2"
                    title="Italic"
                    type="button"
                    @click="() => editor?.chain().focus().toggleItalic().run()"
                >
                    <i class="ri-italic"></i>
                </button>
            </li>
            <li>
                <button
                    :class="[
                        editor?.isActive('strike') ? 'bg-pink-500 text-gray-50 ring-2 ring-pink-500' : 'hover:bg-gray-300 dark:hover:bg-gray-600',
                    ]"
                    class="cursor-pointer px-3 py-2"
                    title="Strikethrough"
                    type="button"
                    @click="() => editor?.chain().focus().toggleStrike().run()"
                >
                    <i class="ri-strikethrough"></i>
                </button>
            </li>
            <li>
                <button
                    :class="[
                        editor?.isActive('blockquote') ? 'bg-pink-500 text-gray-50 ring-2 ring-pink-500' : 'hover:bg-gray-300 dark:hover:bg-gray-600',
                    ]"
                    class="cursor-pointer px-3 py-2"
                    title="Blockquote"
                    type="button"
                    @click="() => editor?.chain().focus().toggleBlockquote().run()"
                >
                    <i class="ri-double-quotes-l"></i>
                </button>
            </li>
            <li>
                <button
                    :class="[
                        editor?.isActive('bulletList') ? 'bg-pink-500 text-gray-50 ring-2 ring-pink-500' : 'hover:bg-gray-300 dark:hover:bg-gray-600',
                    ]"
                    class="cursor-pointer px-3 py-2"
                    title="Bullet List"
                    type="button"
                    @click="() => editor?.chain().focus().toggleBulletList().run()"
                >
                    <i class="ri-list-unordered"></i>
                </button>
            </li>
            <li>
                <button
                    :class="[
                        editor?.isActive('orderedList')
                            ? 'bg-pink-500 text-gray-50 ring-2 ring-pink-500'
                            : 'hover:bg-gray-300 dark:hover:bg-gray-600',
                    ]"
                    class="cursor-pointer px-3 py-2"
                    title="Numeric List"
                    type="button"
                    @click="() => editor?.chain().focus().toggleOrderedList().run()"
                >
                    <i class="ri-list-ordered-2"></i>
                </button>
            </li>
            <li>
                <button
                    :class="[editor?.isActive('link') ? 'bg-pink-500 text-gray-50 ring-2 ring-pink-500' : 'hover:bg-gray-300 dark:hover:bg-gray-600']"
                    class="cursor-pointer px-3 py-2"
                    title="Add Link"
                    type="button"
                    @click="promptUserForHref"
                >
                    <i class="ri-link"></i>
                </button>
            </li>
            <li>
                <button
                    :class="[
                        editor?.isActive('heading', { level: 2 })
                            ? 'bg-pink-500 text-gray-50 ring-2 ring-pink-500'
                            : 'hover:bg-gray-300 dark:hover:bg-gray-600',
                    ]"
                    class="cursor-pointer px-3 py-2"
                    title="Heading 1"
                    type="button"
                    @click="() => editor?.chain().focus().toggleHeading({ level: 2 }).run()"
                >
                    <i class="ri-h-1"></i>
                </button>
            </li>
            <li>
                <button
                    :class="[
                        editor?.isActive('heading', { level: 3 })
                            ? 'bg-pink-500 text-gray-50 ring-2 ring-pink-500'
                            : 'hover:bg-gray-300 dark:hover:bg-gray-600',
                    ]"
                    class="cursor-pointer px-3 py-2"
                    title="Heading 2"
                    type="button"
                    @click="() => editor?.chain().focus().toggleHeading({ level: 3 }).run()"
                >
                    <i class="ri-h-2"></i>
                </button>
            </li>
            <li class="border-r border-gray-300 dark:border-gray-600">
                <button
                    :class="[
                        editor?.isActive('heading', { level: 4 })
                            ? 'bg-pink-500 text-gray-50 ring-2 ring-pink-500'
                            : 'hover:bg-gray-300 dark:hover:bg-gray-600',
                    ]"
                    class="cursor-pointer px-3 py-2"
                    title="Heading 3"
                    type="button"
                    @click="() => editor?.chain().focus().toggleHeading({ level: 4 }).run()"
                >
                    <i class="ri-h-3"></i>
                </button>
            </li>
            <slot :editor="editor" name="toolbar" />
        </menu>
        <div class="block w-full rounded-br-md rounded-bl-md bg-gray-50 p-2 dark:bg-gray-800">
            <EditorContent :editor="editor" />
        </div>
    </div>
</template>

<style scoped>
:deep(.tiptap p.is-editor-empty:first-child::before) {
    @apply pointer-events-none float-left;
    content: attr(data-placeholder);
}
</style>
