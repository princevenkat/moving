<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from "@/components/PlaceholderPattern.vue";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Your Inquiries',
        href: '/inquiries',
    },
];
defineProps({
    inquiries: Array
})
</script>

<template>
    <Head title="Your Inquiries" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="">
                <div v-if="inquiries.length">
                    <div class="grid auto-rows-min gap-4 md:grid-cols-4">
                    <div
                        v-for="inquiry in inquiries"
                        :key="inquiry.id"
                        class="p-4 mb-4 border rounded shadow"
                    >
                        <h2 class="font-semibold text-lg">Inquiry #{{ inquiry.id }}</h2>
                        <p><strong>Service:</strong> {{ inquiry.service }}</p>
                        <p><strong>From:</strong> {{ inquiry.moving_from_city }}, {{ inquiry.moving_from_country }}</p>
                        <p><strong>To:</strong> {{ inquiry.moving_to_city }}, {{ inquiry.moving_to_country }}</p>
                        <p><strong>Status:</strong> {{ inquiry.status }}</p>
                    </div>
                    </div>

                </div>
                <div v-else>
                    <p>No inquiries found.</p>
                </div>
            </div>
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>
