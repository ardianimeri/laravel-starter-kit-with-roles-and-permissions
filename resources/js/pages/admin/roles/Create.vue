<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Form, Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
const { setBreadcrumbs } = useBreadcrumbs();

interface Permission {
    id: number;
    name: string;
}

interface Props {
    permissions: Permission[];
}

const props = defineProps<Props>();

const selected = ref<number[]>([]);

const allChecked = computed({
    get: () =>
        props.permissions.length > 0 &&
        selected.value.length === props.permissions.length,
    set: (val: boolean) => {
        selected.value = val ? props.permissions.map((p) => p.id) : [];
    },
});

function onTogglePermission(id: number, value: boolean | 'indeterminate') {
    if (value === true) {
        if (!selected.value.includes(id)) {
            selected.value.push(id);
        }
    } else {
        selected.value = selected.value.filter((x) => x !== id);
    }
}

setBreadcrumbs([
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Roles', href: '/admin/roles' },
    { title: 'Create', href: '/admin/roles/create' },
]);
</script>

<template>
    <Head title="Create Role" />
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <h1 class="text-xl font-semibold">Create role</h1>

            <Form
                action="/admin/roles"
                method="post"
                v-slot="{ errors, processing }"
                class="grid max-w-3xl gap-6"
            >
                <div class="grid max-w-xl gap-2">
                    <Label for="name">Name</Label>
                    <Input
                        id="name"
                        name="name"
                        required
                        placeholder="Role name"
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="grid max-w-xl gap-2">
                    <Label for="guard_name">Guard name</Label>
                    <Input
                        id="guard_name"
                        name="guard_name"
                        placeholder="web"
                    />
                    <InputError :message="errors.guard_name" />
                </div>

                <div class="grid gap-2">
                    <Label>Permissions</Label>

                    <label
                        class="flex items-center gap-2 rounded-md border p-2"
                    >
                        <Checkbox v-model="allChecked" id="perm-select-all" />
                        <span class="text-sm">Select all permissions</span>
                    </label>

                    <div
                        class="grid grid-cols-1 gap-2 sm:grid-cols-2 md:grid-cols-3"
                    >
                        <label
                            v-for="perm in props.permissions"
                            :key="perm.id"
                            class="flex items-center gap-2 rounded-md border p-2"
                        >
                            <Checkbox
                                :model-value="selected.includes(perm.id)"
                                @update:model-value="
                                    (val) => onTogglePermission(perm.id, val)
                                "
                                :value="perm.id"
                                :id="`perm-${perm.id}`"
                                name="permissions[]"
                            />
                            <span class="text-sm">{{ perm.name }}</span>
                        </label>
                    </div>
                    <InputError :message="errors.permissions" />
                </div>

                <div class="flex items-center gap-2">
                    <Button type="submit" :disabled="processing">Create</Button>
                </div>
            </Form>
        </div>
</template>
