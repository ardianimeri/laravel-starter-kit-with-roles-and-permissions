<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronDown } from 'lucide-vue-next';
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSubButton,
    SidebarMenuSub,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import type { NavItem } from '@/types';

defineProps<{
    items: NavItem[][];
}>();

const { isCurrentUrl } = useCurrentUrl();
</script>

<template>
    <SidebarGroup
        class="px-2 py-0"
        v-for="(groupedItems, index) in items"
        :key="index"
    >
        <SidebarMenu>
            <template v-for="item in groupedItems" :key="item.title">

                <template v-if="item.show !== false">

                    <Collapsible
                        v-if="item.subItems && item.subItems.length > 0"
                        as-child
                        :default-open="item.isActive"
                        class="group/collapsible"
                    >
                        <SidebarMenuItem>
                            <CollapsibleTrigger as-child>
                                <SidebarMenuButton :tooltip="item.title">
                                    <component :is="item.icon" v-if="item.icon" />
                                    <span>{{ item.title }}</span>
                                    <ChevronDown
                                        class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90"
                                    />
                                </SidebarMenuButton>
                            </CollapsibleTrigger>

                            <CollapsibleContent>
                                <SidebarMenuSub>
                                    <SidebarMenuSubItem
                                        v-for="subItem in item.subItems"
                                        :key="subItem.title"
                                    >
                                        <SidebarMenuSubButton as-child>
                                            <Link
                                                :href="subItem.href"
                                                :class="{ 'is-active': isCurrentUrl(subItem.href) }"
                                            >
                                                <component
                                                    :is="subItem.icon"
                                                    v-if="subItem.icon"
                                                />
                                                <span>{{ subItem.title }}</span>
                                            </Link>
                                        </SidebarMenuSubButton>
                                    </SidebarMenuSubItem>
                                </SidebarMenuSub>
                            </CollapsibleContent>
                        </SidebarMenuItem>
                    </Collapsible>

                    <SidebarMenuItem v-else>
                        <SidebarMenuButton
                            as-child
                            :tooltip="item.title"
                            :is-active="isCurrentUrl(item.href)"
                            v-if="item.href"
                        >
                            <Link :href="item.href">
                                <component :is="item.icon" v-if="item.icon" />
                                <span>{{ item.title }}</span>
                            </Link>
                        </SidebarMenuButton>
                        <div v-else>
                        </div>
                    </SidebarMenuItem>
                </template>
            </template>
        </SidebarMenu>
        <Separator v-if="items[index + 1]" class="my-2" />
    </SidebarGroup>
</template>
