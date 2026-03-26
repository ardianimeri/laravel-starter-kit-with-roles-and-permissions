import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';

export function useCan(){
    const { auth } = usePage<SharedData>().props;

    return(permissions: string): boolean => {
        return auth.user?.permissions?.includes(permissions) ?? false;
    }
}
