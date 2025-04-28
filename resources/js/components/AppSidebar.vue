<script lang="ts" setup>
import AppFullLogo from '@/components/AppFullLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { StickyNote } from 'lucide';
import { BookOpen, Folder, HomeIcon, LayoutGrid } from 'lucide-vue-next';

const mainNavItems: NavItem[] = [
    {
        title: 'Home',
        href: route('home'),
        icon: HomeIcon,
        when: false,
    },
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
        when: usePage().props.auth.user,
    },
    {
        title: 'Posts',
        href: '/posts',
        icon: StickyNote,
        when: true,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton as-child class="h-16" size="lg">
                        <Link :href="route('dashboard')">
                            <AppFullLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser v-if="usePage().props.auth.user" />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
