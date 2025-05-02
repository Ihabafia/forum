<script lang="ts" setup>
import AppFullLogoIcon from '@/components/AppFullLogoIcon.vue';
import NavLink from '@/components/NavLink.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

const menu = [
    {
        title: 'Home',
        url: route('home'),
        route: 'home',
    },
    {
        title: 'Dashboard',
        url: route('dashboard'),
        route: 'dashboard',
        when: () => usePage().props.auth.user,
    },
];

defineProps(['posts']);
</script>

<template>
    <Head title="Welcome">
        <link href="https://rsms.me/" rel="preconnect" />
        <link href="https://rsms.me/inter/inter.css" rel="stylesheet" />
    </Head>
    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-start lg:p-8 dark:bg-[#0a0a0a]">
        <header class="mb-6 flex w-full max-w-[335px] items-center justify-between text-sm not-has-[nav]:hidden lg:max-w-5xl">
            <div class="flex items-center justify-start">
                <AppFullLogoIcon class="size-8 fill-current text-[var(--foreground)] dark:text-white" class-name="w-[150px] mr-3" />
                <template v-for="item in menu" :key="item.title">
                    <NavLink v-if="item.when ? item.when() : true" :active="route().current(item.route)" :href="item.url">
                        {{ item.title }}
                    </NavLink>
                </template>
            </div>

            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Log in
                    </Link>
                    <Link
                        :href="route('register')"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Register
                    </Link>
                </template>
            </nav>
        </header>
        <div class="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 starting:opacity-0">
            <main class="flex h-lvh w-full max-w-[535px] flex-col-reverse overflow-hidden rounded-lg lg:max-w-5xl lg:flex-row">
                <!--                <div
                                    class="flex-1 rounded-bl-lg rounded-br-lg bg-white p-4 pb-6 text-[13px] leading-[20px]
                                    shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:text-[#EDEDEC]
                                    dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] lg:rounded-br-none lg:rounded-tl-lg lg:p-10"
                                >
                                    Hello world!
                                </div>-->
            </main>
        </div>
        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>
