<script setup lang="ts">
import AuthLayout from '@/layouts/auth/AuthSimpleLayout.vue';
import Navbar from "@/components/Navbar.vue";

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template class="gradient_01">

    <AuthLayout :title="title" :description="description">
        <Navbar/>

            <slot />

    </AuthLayout>
</template>


