<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Edit, Trash2 } from 'lucide-vue-next';
import rolesRoutes from "@/routes/admin/roles";
import { confirmDelete } from '@/lib/alert';

interface Permission {
    id: number;
    name: string;
}

interface RoleItem {
    id: number;
    name: string;
    permissions: Permission[];
    created_at: string;
}

interface Props {
    roles: {
        data: RoleItem[];
        links: { url: string | null; label: string; active: boolean }[];
    };
}

defineProps<Props>();


const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Ballina', href: '/admin/dashboard' },
    { title: 'Rolet', href: '/admin/roles' },
];

async function destroyRole(id: number) {
    const result = await confirmDelete('roles.confirm_delete');

    if (result.isConfirmed) {
        router.delete(`/admin/roles/${id}`);
    }
}
</script>

<template>
    <Head :title="'roles.title'" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold">{{ 'roles.title' }}</h1>
                <Button as-child>
                    <Link href="/admin/roles/create">{{ 'roles.create' }}</Link>
                </Button>
            </div>

            <div class="overflow-hidden rounded-lg border">
                <table class="w-full text-left text-sm">
                    <thead class="bg-muted/50">
                    <tr>
                        <th class="px-4 py-3">{{ 'common.name' }}</th>
                        <th class="px-4 py-3">Permissions</th>
                        <th class="px-4 py-3 text-right">{{ 'common.actions' }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                        v-for="role in roles.data"
                        :key="role.id"
                        class="border-t"
                    >
                        <td class="px-4 py-3">{{ role.name }}</td>
                        <td class="px-4 py-3">
                                <span
                                    v-if="role.permissions.length === 0"
                                    class="text-muted-foreground"
                                >—</span
                                >
                            <span v-else>{{
                                    role.permissions
                                        .map((permission) => permission.name)
                                        .join(', ')
                                }}</span>
                        </td>
                        <td class="space-x-2 px-4 py-3 text-right">
                            <div
                                class="flex items-center justify-end gap-1"
                            >
                                <Button
                                    as-child
                                    variant="ghost"
                                    size="sm"
                                    class="h-8 w-8 p-0"
                                >
                                    <Link
                                        :href="
                                                rolesRoutes.edit(
                                                    role.id,
                                                ).url
                                            "
                                    >
                                        <Edit class="h-4 w-4" />
                                    </Link>
                                </Button>
                                <Button
                                    variant="ghost"
                                    size="sm"
                                    class="h-8 w-8 p-0 text-destructive hover:text-destructive"
                                    @click="destroyRole(role.id)"
                                >
                                    <Trash2 class="h-4 w-4" />
                                </Button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="roles.data.length === 0">
                        <td
                            colspan="4"
                            class="px-4 py-8 text-center text-muted-foreground"
                        >
                            {{ 'roles.no_results' }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-center gap-2">
                <Link
                    v-for="link in roles.links"
                    :key="link.label + link.url"
                    :href="link.url || '#'"
                    class="rounded-md px-3 py-1 text-sm"
                    :class="[
                        link.active
                            ? 'bg-primary text-primary-foreground'
                            : 'bg-muted hover:bg-muted/80',
                        !link.url && 'pointer-events-none opacity-50',
                    ]"
                >
                    <span v-html="link.label"></span>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
