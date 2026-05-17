<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { BookOpen, FolderGit2, LayoutGrid, UserRoundCogIcon } from 'lucide-vue-next';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { useCan } from '@/composables/useCan';
import { dashboard } from '@/routes';
import permissionsRoutes from '@/routes/admin/permissions';
import roles from '@/routes/admin/roles';
import users from '@/routes/admin/users';
import type { NavItem } from '@/types';


const can = useCan();


const mainNavItems: NavItem[][] = [
    [
        {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
            show: true
        },
    ],
    [
        {
            title: 'Manage Users',
            href: '#',
            icon: UserRoundCogIcon,
            show:
                can('read-users') ||
                can('read-roles') ||
                can('read-permissions'),
            subItems: [
                ...(can('read-users')
                    ? [
                        {
                            title: 'Users',
                            href: users.index(),
                        },
                    ]
                    : []),
                ...(can('read-roles')
                    ? [{ title: 'Roles', href: roles.index() }]
                    : []),
                ...(can('read-permissions')
                    ? [
                        {
                            title: 'Permissions',
                            href: permissionsRoutes.index(),
                        },
                    ]
                    : []),
            ],
        },
    ]
];

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: FolderGit2,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
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
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
