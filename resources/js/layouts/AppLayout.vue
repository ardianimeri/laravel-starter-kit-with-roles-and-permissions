<script setup lang="ts">
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItem } from '@/types';

const { breadcrumbs: globalBreadcrumbs } = useBreadcrumbs();


const props = defineProps<{
    breadcrumbs?: BreadcrumbItem[];
}>();
</script>

<template>
    <AppLayout :breadcrumbs="props.breadcrumbs ?? globalBreadcrumbs">
        <slot />
    </AppLayout>
</template>
