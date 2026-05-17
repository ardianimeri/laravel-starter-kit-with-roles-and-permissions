<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, User } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Edit, Plus, Trash2, Search, X } from 'lucide-vue-next';
import { confirmDelete } from '@/lib/alert';
import { ref } from 'vue';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
import { useCan } from '@/composables/useCan';
const { setBreadcrumbs } = useBreadcrumbs();

interface Props {
    users: {
        data: User[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    filters: {
        search?: string;
    };
}

const props = defineProps<Props>();


const search = ref(props.filters.search || '');
const can = useCan();

function performSearch() {
    router.get(
        '/admin/users',
        { search: search.value },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
}

function handleKeydown(event: KeyboardEvent) {
    if (event.key === 'Enter') {
        performSearch();
    }
}

function clearSearch() {
    search.value = '';
    router.get(
        '/admin/users',
        {},
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
}

setBreadcrumbs([
    { title: 'Dashboard', href: '/dashboard' },
    {  title: 'Users', href: '/admin/users' },
]);

async function destroyUser(id: number) {
    const result = await confirmDelete('Are you sure you want to delete this user?');

    if (result.isConfirmed) {
        router.delete(`/admin/users/${id}`);
    }
}

function getUserRole(user: User) {
    if (user.roles.length === 0) {
        return '';
    }

    return user.roles.map((role) => role.name).join(', ');
}
</script>

<template>
    <Head title="Users" />
    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-xl font-semibold">Users</h1>
            <Button v-if="can('create-user')" as-child class="w-full sm:w-auto">
                <Link href="/admin/users/create">
                    <Plus class="mr-2 h-4 w-4" />
                    <span class="sm:hidden">Create User</span>
                    <span class="hidden sm:inline">Create User</span>
                </Link>
            </Button>
        </div>

        <!-- Search Input -->
        <div class="flex gap-2">
            <div class="relative flex-1">
                <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                <Input
                    v-model="search"
                    type="text"
                    placeholder="Search..."
                    class="pl-9"
                    @keydown="handleKeydown"
                />
            </div>
            <Button @click="performSearch">
                Search
            </Button>
            <Button
                v-if="search"
                variant="outline"
                @click="clearSearch"
            >
                <X class="h-4 w-4" />
            </Button>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden overflow-hidden rounded-lg border md:block">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-muted/50">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Email</th>
                        <!--                            <th class="px-4 py-3">Phone</th>-->
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">Created At</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                        v-for="user in users.data"
                        :key="user.id"
                        class="border-t"
                    >
                        <td class="px-4 py-3 font-medium">{{ user.name }}</td>
                        <td class="px-4 py-3 text-muted-foreground">{{ user.email }}</td>
                        <!--                            <td class="px-4 py-3 text-muted-foreground">{{ user.country_code }} {{ user.phone }}</td>-->
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                <Badge
                                    v-for="role in user.roles"
                                    :key="role.id"
                                    variant="default"
                                    class="text-xs"
                                >
                                    {{ role.name }}
                                </Badge>
                                <span
                                    v-if="user.roles.length === 0"
                                    class="text-muted-foreground"
                                >-</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-muted-foreground text-xs">{{ user.created_at }}</td>
                        <td class="px-4 py-3 text-right">
                            <div
                                class="flex items-center justify-end gap-1"
                            >
                                <Button v-if="can('edit-user')"
                                    as-child
                                    variant="ghost"
                                    size="sm"
                                    class="h-8 w-8 p-0"
                                >
                                    <Link
                                        :href="`/admin/users/${user.id}/edit`"
                                    >
                                        <Edit class="h-4 w-4" />
                                    </Link>
                                </Button>
                                <Button v-if="can('delete-user')"
                                    variant="ghost"
                                    size="sm"
                                    class="h-8 w-8 p-0 text-destructive hover:text-destructive"
                                    @click="destroyUser(user.id)"
                                >
                                    <Trash2 class="h-4 w-4" />
                                </Button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="users.data.length === 0">
                        <td
                            colspan="6"
                            class="px-4 py-8 text-center text-muted-foreground"
                        >
                            No users found.
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="space-y-3 md:hidden">
            <div
                v-for="user in users.data"
                :key="user.id"
                class="rounded-lg border bg-card p-4"
            >
                <div class="mb-3 flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="font-semibold">{{ user.name }}</h3>
                        <p v-if="user.email" class="text-sm text-muted-foreground">{{ user.email }}</p>
                    </div>
                </div>

                <div class="space-y-2 text-sm">
                    <div v-if="user.phone" class="flex justify-between">
                        <span class="text-muted-foreground">Phone:</span>
                        <span class="font-medium">{{ user.country_code }} {{ user.phone }}</span>
                    </div>
                    <div v-if="user.roles.length > 0" class="border-t pt-2">
                        <p class="text-xs text-muted-foreground mb-1">Role:</p>
                        <div class="flex flex-wrap gap-1">
                            <Badge
                                v-for="role in user.roles"
                                :key="role.id"
                                variant="default"
                                class="text-xs"
                            >
                                {{ role.name }}
                            </Badge>
                        </div>
                    </div>
                    <div class="border-t pt-2">
                        <span class="text-xs text-muted-foreground">Created At: {{ user.created_at }}</span>
                    </div>
                </div>

                <div class="mt-4 flex gap-2">
                    <Button
                        as-child
                        variant="outline"
                        size="sm"
                        class="flex-1"
                    >
                        <Link :href="`/admin/users/${user.id}/edit`">
                            <Edit class="mr-2 h-4 w-4" />
                            Edit
                        </Link>
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        class="shrink-0 text-destructive hover:bg-destructive/10 hover:text-destructive"
                        @click="destroyUser(user.id)"
                    >
                        <Trash2 class="h-4 w-4" />
                    </Button>
                </div>
            </div>

            <div v-if="users.data.length === 0" class="rounded-lg border border-dashed bg-muted/20 p-8 text-center">
                <p class="text-muted-foreground">No users found.</p>
            </div>
        </div>

        <div class="flex flex-wrap justify-center gap-2">
            <Link
                v-for="link in users.links"
                :key="link.label + link.url"
                :href="link.url || '#'"
                class="rounded-md px-3 py-1.5 text-sm"
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
