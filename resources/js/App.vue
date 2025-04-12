<script setup lang="ts">
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
</script>

<template>
    <Transition name="fade" mode="out-in">
        <component :is="page.component" :key="page.url" v-bind="page.props" />
    </Transition>
</template>

<style>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
