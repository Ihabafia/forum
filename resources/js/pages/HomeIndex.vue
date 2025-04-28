<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import AppFullLogoIcon from '@/components/AppFullLogoIcon.vue';
import NavLink from '@/components/NavLink.vue';
import AppLayout from '@/layouts/AppLayout.vue';

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

defineProps([
    'posts',
])
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-start lg:p-8">
        <header class="flex justify-between items-center not-has-[nav]:hidden mb-6 w-full max-w-[335px] text-sm lg:max-w-5xl">
            <div class="flex justify-start items-center">
                <AppFullLogoIcon class-name="w-[150px] mr-3" class="size-8 fill-current text-[var(--foreground)] dark:text-white" />
                <template v-for="item in menu" :key="item.title">
                    <NavLink v-if="item.when ? item.when() : true"
                          :href="item.url"
                          :active="route().current(item.route)"
                    >
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
        <div class="duration-750 starting:opacity-0 flex w-full items-center justify-center opacity-100 transition-opacity">
            <main class="flex w-full max-w-[535px] h-lvh flex-col-reverse overflow-hidden rounded-lg lg:max-w-5xl lg:flex-row">
<!--                <div
                    class="flex-1 rounded-bl-lg rounded-br-lg bg-white p-4 pb-6 text-[13px] leading-[20px]
                    shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:text-[#EDEDEC]
                    dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] lg:rounded-br-none lg:rounded-tl-lg lg:p-10"
                >
                    Hello world!
                </div>-->
            </main>
        </div>
        <div class="h-14.5 hidden lg:block"></div>
    </div>
</template>
