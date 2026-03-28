<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, router, Form } from '@inertiajs/vue3';
import { confirmDelete } from '@/lib/alert';
import { Plus, Trash2, Shield } from 'lucide-vue-next';
import { ref } from 'vue';
import admin from '@/routes/admin';
import permissionsRoutes from '@/routes/admin/permissions';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
const { setBreadcrumbs } = useBreadcrumbs();

interface Permission {
    id: number;
    name: string;
    guard_name: string;
    created_at: string;
}

interface Props {
    permissions: {
        data: Permission[];
        links: { url: string | null; label: string; active: boolean }[];
    };
}

defineProps<Props>();

setBreadcrumbs([
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Permissions', href: permissionsRoutes.index().url },
]);

const showCreateForm = ref(false);

async function destroyPermission(id: number) {
    const result = await confirmDelete('A jeni të sigurt që doni të fshini këtë leje?');

    if (result.isConfirmed) {
        router.delete(permissionsRoutes.destroy(id).url);
    }
}

function formatDate(dateString: string): string {
    return new Date(dateString).toLocaleDateString('sq-AL', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
}
</script>

<template>
    <Head title="Lejet" />
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-semibold">Lejet</h1>
                    <p class="text-sm text-muted-foreground">Menaxho lejet e sistemit</p>
                </div>
                <Button @click="showCreateForm = !showCreateForm" size="sm">
                    <Plus class="mr-2 h-4 w-4" />
                    {{ showCreateForm ? 'Anulo' : 'Krijo Leje' }}
                </Button>
            </div>

            <!-- Create Form -->
            <div
                v-if="showCreateForm"
                class="rounded-lg border bg-card p-4 shadow-sm transition-all duration-300 animate-in fade-in slide-in-from-top-2"
            >
                <div class="mb-4">
                    <h3 class="text-sm font-semibold">Krijo Leje të Re</h3>
                    <p class="text-xs text-muted-foreground">Shto një leje të re në sistem</p>
                </div>

                <Form
                    :action="permissionsRoutes.store().url"
                    method="post"
                    v-slot="{ errors, processing }"
                    class="space-y-4"
                >
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="name">Emri i Lejes</Label>
                            <Input
                                id="name"
                                name="name"
                                placeholder="e.g. edit-users"
                                required
                            />
                            <InputError :message="errors.name" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="guard_name">Guard Name</Label>
                            <Input
                                id="guard_name"
                                name="guard_name"
                                placeholder="web (default)"
                                value="web"
                            />
                            <InputError :message="errors.guard_name" />
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <Button type="submit" :disabled="processing">
                            <span v-if="processing">Duke krijuar...</span>
                            <span v-else>Krijo Lejen</span>
                        </Button>
                    </div>
                </Form>
            </div>

            <!-- Permissions Table -->
            <div v-if="permissions.data.length > 0" class="overflow-hidden rounded-lg border">
                <table class="w-full text-left text-sm">
                    <thead class="bg-muted/50">
                    <tr>
                        <th class="px-4 py-3">Emri</th>
                        <th class="px-4 py-3">Guard Name</th>
                        <th class="px-4 py-3">Krijuar më</th>
                        <th class="px-4 py-3 text-right">Veprimet</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                        v-for="permission in permissions.data"
                        :key="permission.id"
                        class="border-t hover:bg-muted/20"
                    >
                        <td class="px-4 py-3 font-medium">{{ permission.name }}</td>
                        <td class="px-4 py-3 text-muted-foreground">
                            {{ permission.guard_name }}
                        </td>
                        <td class="px-4 py-3 text-muted-foreground">
                            {{ formatDate(permission.created_at) }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <Button
                                variant="ghost"
                                size="sm"
                                class="text-destructive hover:bg-destructive/10"
                                @click="destroyPermission(permission.id)"
                            >
                                <Trash2 class="h-4 w-4" />
                            </Button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div
                v-else
                class="flex flex-col items-center justify-center rounded-lg border bg-card py-12 text-center"
            >
                <Shield class="mb-3 h-12 w-12 text-muted-foreground/30" />
                <p class="text-sm font-medium text-muted-foreground">
                    Nuk ka të dhëna
                </p>
                <p class="mt-1 text-xs text-muted-foreground">
                    Kliko butonin "Krijo Leje" për të filluar
                </p>
            </div>

            <!-- Pagination -->
            <div v-if="permissions.links.length > 3" class="flex justify-center gap-2">
                <Link
                    v-for="link in permissions.links"
                    :key="link.label"
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
</template>
