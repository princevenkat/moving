<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItemType } from '@/types';
import NavUser from "@/components/NavUser.vue";
import {Link} from "@inertiajs/vue3";
import {House} from 'lucide-vue-next';
defineProps<{
    breadcrumbs?: BreadcrumbItemType[];
}>();
</script>

<template>
    <header
        class="flex h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4 w-full justify-between"
    >
        <div class="flex items-center gap-2 flex-initial flex-1">
            <SidebarTrigger class="-ml-1"  />
            <template v-if="breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>

        </div>
<!--        <Link :href="route('/')" method="post" as="button">Back to Home</Link>-->
        <div class="flex flex-row items-center justify-end gap-3">
            <Link :href="route('home')" class="btn btn-ghost p-3 hover:border-transparent hover:bg-transparent hover:text-gray-500 uppercase text-xs hover:shadow-none" as="a"><House class="w- h-5"/> go to Home</Link>
            <NavUser  />
        </div>

    </header>
</template>
