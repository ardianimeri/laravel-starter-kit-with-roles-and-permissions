<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Role, User } from '@/types';
import { Form, Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
const { setBreadcrumbs } = useBreadcrumbs();

interface Props {
    user: User;
    roles: Role[];
}

const props = defineProps<Props>();

setBreadcrumbs( [
    { title: 'Dashboard', href: '/admin/dashboard' },
    { title: 'Users', href: '/admin/users' },
    { title: 'Edit', href: `/admin/users/${props.user.id}/edit` },
]);

const selectedRole = ref(0);

onMounted(() => {
    if (props.user?.role) {
        selectedRole.value = props.user.role.id ?? 0;
    }
});
</script>

<template>
    <Head :title="`Edit User: ${user.name}`" />
    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <h1 class="text-xl font-semibold">Edit User</h1>

        <Form
            :action="`/admin/users/${user.id}`"
            method="post"
            v-slot="{ errors, processing }"
            class="max-w-xl space-y-6"
        >
            <input type="hidden" name="_method" value="PUT" />

            <div class="grid-cols grid gap-2">
                <Label for="name">Name</Label>
                <Input
                    id="name"
                    name="name"
                    required
                    :default-value="user.name"
                />
                <InputError :message="errors.name" />
            </div>

            <div class="grid gap-2">
                <Label for="email">Email</Label>
                <Input
                    id="email"
                    name="email"
                    type="email"
                    required
                    :default-value="user.email"
                />
                <InputError :message="errors.email" />
            </div>

            <!--                <div class="grid gap-2">-->
            <!--                    <Label for="phone">Phone</Label>-->
            <!--                    <PhoneInput-->
            <!--                        required-->
            <!--                        :country-code-value="user.country_code || '+355'"-->
            <!--                        :phone-value="user.phone"-->
            <!--                    />-->
            <!--                    <InputError :message="errors.phone" />-->
            <!--                    <InputError :message="errors.country_code" />-->
            <!--                </div>-->

            <div class="grid gap-2">
                <Label for="role">Role</Label>
                <select
                    name="role"
                    id="role"
                    v-model="selectedRole"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                >
                    <option selected value="0">Choose a role</option>
                    <option
                        v-for="role in roles"
                        :value="role.id"
                        :key="role.id"
                    >
                        {{ role.name }}
                    </option>
                </select>
                <InputError :message="errors.role" />
            </div>

            <div class="grid gap-2">
                <Label for="password">New Password</Label>
                <Input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Leave blank to keep current password"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="grid gap-2">
                <Label for="password_confirmation">Confirm New Password</Label>
                <Input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                />
            </div>

            <div class="flex items-center gap-2">
                <Button type="submit" :disabled="processing">Save Changes</Button>
            </div>
        </Form>
    </div>
</template>
