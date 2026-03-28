import { ref } from 'vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs = ref<BreadcrumbItem[]>([]);

export function useBreadcrumbs() {
    return {
        breadcrumbs,
        setBreadcrumbs: (items: BreadcrumbItem[]) => {
            breadcrumbs.value = items;
        },
    };
}
