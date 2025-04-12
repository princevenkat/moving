<script setup lang="ts">
import { Trophy } from 'lucide-vue-next';
import AuthLayout from "@/layouts/AuthLayout.vue"

defineProps({
    inquiry: Object
})
</script>

<template>
        <AuthLayout class="gradient_01 w-full">
            <div class="min-h-[calc(100vh-64px)] max-w-7xl">
                <div class="bg-white px-10 py-10 shadow-md sm:rounded-lg  mt-6 mx-auto">
                    <div class="relative isolate px-6 pt-14 lg:px-8">
                        <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
                            <div class="relative left-[calc(50%-11rem)] aspect-1155/678 w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
                        </div>
                        <div class="mx-auto max-w-2xl">

                            <div class="text-center flex flex-col items-center">
                                <Trophy strokeWidth={4} class="w-30 h-30 mb-5" />
                                <h1 class="text-5xl font-semibold tracking-tight text-balance text-gray-900 sm:text-4xl">Thank you for your inquiry!</h1>
                                <p class="mt-4 text-md font-normal text-pretty text-gray-500">A confirmation email containing all neccesary information has been sent to test@gmail.com. Our Move Captains will call you after you have received your 5 offers.
                                </p>
                                <div class="mt-10 flex items-center justify-center ">
                                    <div class="text-lg font-semibold text-gray-900">Your inquiry ID is <span aria-hidden="true" class="text-xl text-black">→</span></div>
                                    <div class="rounded-md bg-indigo-600 px-2.5 py-1.5 text-lg font-semibold text-white shadow-xs ">
                                        {{inquiry.id}}
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]" aria-hidden="true">
                            <div class="relative left-[calc(50%+3rem)] aspect-1155/678 w-[36.125rem] -translate-x-1/2 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthLayout>
</template>

<style scoped>

</style>
