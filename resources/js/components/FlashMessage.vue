<template>
</template>

<script lang="ts">
// GLOBAL STATE:
// Defined outside <script setup> so it persists across page navigations/component re-renders.
// This prevents the message from showing again when you hit "Back".
const seenFlashIds = new Set<string>();
</script>

<script setup lang="ts">
import { watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { notifySuccess, notifyError } from '@/lib/alert';

const page = usePage();

watch(
    () => page.props.flash,
    (flash: any) => {
        // 1. If there is no flash data or no UUID, do nothing
        if (!flash || !flash.uuid) return;

        // 2. Check the global Set: Have we seen this ID before?
        if (seenFlashIds.has(flash.uuid)) {
            return; // Stop here, don't show it again
        }

        // 3. Add to global Set so we don't show it again later
        seenFlashIds.add(flash.uuid);

        // 4. Display the Alert
        if (flash.success) {
            notifySuccess(flash.success);
        }
        if (flash.error) {
            notifyError(flash.error);
        }
        if (flash.warning) {
            notifyError(flash.warning);
        }
    },
    { deep: true, immediate: true }
);
</script>
