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
import rolesRoutes from '@/routes/admin/roles';
import permissionsRoutes from '@/routes/admin/permissions';
import { dashboard } from '@/routes';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
const { setBreadcrumbs } = useBreadcrumbs();

interface Permission {
    id: number;
    name: string;
    guard_name: string;
}
interface RoleItem {
    id: number;
    name: string;
    guard_name: string;
    permissions: Permission[];
}
interface Props {
    role: RoleItem;
    permissions: Permission[];
}

const props = defineProps<Props>();

setBreadcrumbs([
    { title: 'Dashboard', href: dashboard() },
    { title: 'Roles', href: rolesRoutes.index().url },
    { title: 'Edit', href: rolesRoutes.edit(props.role.id).url },
]);

const selected = ref<number[]>(props.role.permissions.map((p) => p.id));

const allChecked = computed({
    get: () =>
        props.permissions.length > 0 &&
        selected.value.length === props.permissions.length,
    set: (val: boolean) => {
        selected.value = val
            ? props.permissions.map((permission) => permission.id)
            : [];
    },
});

function onTogglePermission(id: number, value: boolean | 'indeterminate') {
    if (value === true) {
        if (!selected.value.includes(id)) {
            selected.value.push(id);
        }
    } else {
        selected.value = selected.value.filter((val) => val !== id);
    }
}

</script>

<template>
    <Head :title="`Edit Role: ${props.role.name}`" />
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <h1 class="text-xl font-semibold">Edit role</h1>

            <Form
                :action="`/admin/roles/${props.role.id}`"
                method="put"
                v-slot="{ errors, processing }"
                class="grid max-w-4xl gap-6"
            >
                <div class="grid max-w-xl gap-2">
                    <Label for="name">Name</Label>
                    <Input
                        id="name"
                        name="name"
                        required
                        placeholder="Role name"
                        :model-value="props.role.name"
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="grid max-w-xl gap-2">
                    <Label for="guard_name">Guard name</Label>
                    <Input
                        id="guard_name"
                        name="guard_name"
                        :model-value="props.role.guard_name"
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
                            v-for="permission in props.permissions"
                            :key="permission.id"
                            class="flex items-center gap-2 rounded-md border p-2"
                        >
                            <Checkbox
                                :model-value="selected.includes(permission.id)"
                                @update:model-value="
                                    (val) =>
                                        onTogglePermission(permission.id, val)
                                "
                                :value="permission.id"
                                :id="`perm-${permission.id}`"
                                name="permissions[]"
                            />
                            <span class="text-sm">{{ permission.name }}</span>
                        </label>
                    </div>
                    <InputError :message="errors.permissions" />
                </div>

                <div class="flex items-center gap-2">
                    <Button type="submit" :disabled="processing"
                    >Save Changes</Button
                    >
                </div>
            </Form>
        </div>
</template>
