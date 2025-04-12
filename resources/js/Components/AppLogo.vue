<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import {usePage} from "@inertiajs/vue3";
const { appName } = usePage().props as { appName: string }
</script>

<template>
    <div class="flex aspect-square size-8 items-center justify-center rounded-md bg-sidebar-primary text-sidebar-primary-foreground">
        <AppLogoIcon class="size-5 fill-current text-white dark:text-black" />
    </div>
    <div class="ml-1 grid flex-1 text-left text-md uppercase">
        <span class="mb-0.5 truncate font-semibold leading-none">{{ appName }}</span>
    </div>
</template>
