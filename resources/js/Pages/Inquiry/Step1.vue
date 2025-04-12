<template>
    <AuthLayout class="gradient_01">
        <div class="min-h-[calc(100vh-64px)]">
            <div class="bg-white px-10 py-10 shadow-md sm:rounded-lg max-w-2xl mt-6 mx-auto">

            <div class="mb-5 text-center">
                <h1 class="text-3xl font-bold">Start Inquiry</h1>
                <p>Find the best offers from companies within your region</p>
            </div>

            <Transition name="fade-slide" mode="out-in">
                <div :key="step">
            <form @submit.prevent="submit">
                <div class="mt-4">
                    <label class="label">
                        <span class="label-text font-medium text-lg">Choose Service</span>
                    </label>
                    <select v-model="form.service_type" class="select border-2 border-[#ededed] focus:border-gray-900  !outline-0 !shadow-none  w-full">
                        <option value="moving">Only Moving</option>
                        <option value="cleaning">Only Handover Cleaning</option>
                        <option value="both">Moving and Cleaning</option>
                    </select>
                </div>

                <h2 class="text-lg mt-5 mb-4 font-medium p-2 shadow text-center rounded-full bg-gray-100">
                    Where should your Service take place?
                </h2>

                <!-- Moving From -->
                <div class="mt-4">
                    <div class="mb-2 flex items-end justify-between">
                        <h3 class="font-medium text-sm">Moving From</h3>
                        <div class="relative">
                            <CountryDropdown
                                :selectedCountry="form.current_country"
                                :onCountryChange="(countryCode) => form.current_country = countryCode"
                                :disabled="true"
                            />
                        </div>
                    </div>
                    <div class="flex gap-2 w-full">
                        <input v-model="form.current_zip" class="input border-2 border-[#ededed] focus:border-gray-900  !outline-0 !shadow-none  join-item w-2/4" type="text" placeholder="ZIP" />
                        <input v-model="form.current_city" class="input  border-2 border-[#ededed] focus:border-gray-900 !outline-0 !shadow-none  join-item w-full" type="text" placeholder="CITY" />
                    </div>
                </div>

                <!-- Moving To -->
                <div class="mt-4">
                    <div class="mb-2 flex items-end justify-between">
                        <h3 class="font-medium text-sm flex-1">Moving To</h3>
                        <div class="relative flex-1 flex justify-end">
                            <CountryDropdown
                                :selectedCountry="form.destination_country"
                                :onCountryChange="(val) => form.destination_country = val"
                                :disabled="true"
                            />
                        </div>
                    </div>
                    <div class="flex gap-2 w-full">
                        <input v-model="form.destination_zip" class="input border-2 border-[#ededed] focus:border-gray-900  !outline-0 !shadow-none  join-item w-2/4" type="text" placeholder="ZIP" />
                        <input v-model="form.destination_city" class="input  border-2 border-[#ededed] focus:border-gray-900 !outline-0 !shadow-none  join-item w-full" type="text" placeholder="CITY" />
                    </div>

                </div>

                <!-- Email -->
                <div class="mt-4">
                    <div class="font-medium text-sm mb-2">
                        <h3>Email Address</h3>
                    </div>
                    <input v-model="form.email" class="input border-2 border-[#ededed] focus:border-gray-900  !outline-0 !shadow-none  block w-full" type="email" />
                </div>

                <div class="text-sm mt-5 mb-4 text-gray-600">
                    We use your contact information to send you notifications about your offers.
                    <Link href="#" class="ml-1 text-blue-400">Privacy policy</Link>
                </div>
                <p class="text-xs mt-2 text-gray-500">Editing Inquiry ID: {{ form.inquiry_id }}</p>

                <input type="hidden" v-model="form.inquiry_id" />
                <button class="btn btn-neutral mt-4 w-full text-lg" type="submit" :disabled="form.processing">
                    Start Inquiry
                </button>
            </form>

                </div>
            </Transition>
        </div></div>
    </AuthLayout>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'

import CountryDropdown from '@/components/CountryDropdown.vue'
import AuthLayout from "@/layouts/AuthLayout.vue";

const queryParams = new URLSearchParams(window.location.search)
const serviceTypeFromQuery = queryParams.get('service_type')

const savedData = JSON.parse(localStorage.getItem("inquiry_form_step_1") || "{}")

// const props = defineProps({
//     inquiry: Object,
// })

const props = defineProps<{
    inquiry?: {
        id?: number;
        service_type?: string;
        email?: string;
        [key: string]: any;
    };
    auth?: {
        user?: {
            email?: string;
        };
    };
}>();

const form = useForm({
    inquiry_id: props.inquiry?.id || null,
    service_type: props.inquiry?.service_type || savedData.service_type || serviceTypeFromQuery || "",
    current_country: savedData.current_country || "",
    current_zip: savedData.current_zip || "",
    current_city: savedData.current_city || "",
    destination_country: savedData.destination_country || "",
    destination_zip: savedData.destination_zip || "",
    destination_city: savedData.destination_city || "",
    //email: savedData.email || "",
    email: savedData.email || props.inquiry?.email || (props.auth?.user?.email ?? "")

})


console.log("User email from props:", props.auth?.user?.email)
console.log("Props:", props)
console.log("Saved data:", savedData)


onMounted(() => {
    if (!form.inquiry_id) {
        const fromQuery = queryParams.get('inquiry')
        if (fromQuery) {
            form.inquiry_id = parseInt(fromQuery)
        }
    }
})

watch(form, () => {
    localStorage.setItem("inquiry_form_step_1", JSON.stringify(form))
}, { deep: true })


const step = ref(1);
const submit = () => {
    form.post(route("inquiry.store"))
}
</script>


<style scoped>
.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.4s ease;
}
.fade-slide-enter-from,
.fade-slide-leave-to {
    opacity: 0;
    transform: translateY(10px);
}
.fade-slide-enter-to,
.fade-slide-leave-from {
    opacity: 1;
    transform: translateY(0);
}
</style>
