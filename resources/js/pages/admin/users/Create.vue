<script setup lang="ts">
import InputError from '@/components/InputError.vue';
// import PhoneInput from '@/components/PhoneInput.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Role } from '@/types';
import { Form, Head } from '@inertiajs/vue3';
import { ref } from "vue";
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
const { setBreadcrumbs } = useBreadcrumbs();

interface Props {
    roles: Role[];
}

const props = defineProps<Props>();

const selectedRole = ref("0");

setBreadcrumbs([
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Users', href: '/admin/users' },
    { title: 'Create', href: '#' },
]);
</script>

<template>
    <Head title="Create User" />
    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <h1 class="text-xl font-semibold">Create User</h1>

        <Form
            action="/admin/users"
            method="post"
            v-slot="{ errors, processing }"
            class="max-w-xl space-y-6"
        >
            <div class="grid gap-2">
                <Label for="name">Name</Label>
                <Input
                    id="name"
                    name="name"
                    required
                    placeholder="Enter full name"
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
                    placeholder="email@example.com"
                />
                <InputError :message="errors.email" />
            </div>

            <!--                <div class="grid gap-2">-->
            <!--                    <Label for="phone">Phone</Label>-->
            <!--                    <PhoneInput-->
            <!--                        required-->
            <!--                        placeholder="Enter phone number"-->
            <!--                    />-->
            <!--                    <InputError :message="errors.phone" />-->
            <!--                    <InputError :message="errors.country_code" />-->
            <!--                </div>-->

            <div class="grid gap-2">
                <Label for="role">Role</Label>
                <select
                    v-model="selectedRole"
                    name="role"
                    id="role"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                >
                    <option selected value="0">Choose a role</option>
                    <option
                        v-for="role in props.roles"
                        :value="role.id"
                        :key="role.id"
                    >
                        {{ role.name }}
                    </option>
                </select>
                <InputError :message="errors.role" />
            </div>

            <div class="grid gap-2">
                <Label for="password">Password</Label>
                <Input
                    id="password"
                    name="password"
                    type="password"
                    required
                    placeholder="********"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="grid gap-2">
                <Label for="password_confirmation">Confirm Password</Label>
                <Input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    required
                    placeholder="********"
                />
            </div>

            <div class="flex items-center gap-2">
                <Button type="submit" :disabled="processing">Create User</Button>
            </div>
        </Form>
    </div>
</template>
