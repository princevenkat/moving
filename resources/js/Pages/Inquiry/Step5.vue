<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';

import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

import AuthLayout from "@/layouts/AuthLayout.vue";
import { Label } from '@/components/ui/label'
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group'
import {Checkbox} from "@/components/ui/checkbox";

// Retrieve saved data from localStorage
const savedData = JSON.parse(localStorage.getItem("inquiry_form_step_5") || "{}");

// Define props for inquiry and authentication
const props = defineProps<{
    inquiry?: {
        id?: number;
        email?: string;
        gender?: string;
        name?: string;
        last_name?: string;
        [key: string]: any;
    };
    auth?: {
        user?: {
            email?: string;
            gender?: string;
            name?: string;
            last_name?: string;
        };
    };
}>();

// Set up the form using useForm() hook
const form = useForm({
    inquiry_id: props.inquiry?.id || null,
    moving_date: savedData.moving_date || "",
    // salutation: savedData.salutation || "Mr", // Default to Mr
    // first_name: savedData.first_name || "",
    // last_name: savedData.last_name || "",
    phone_number: savedData.phone_number || "",
    email: savedData.email || props.inqufirstiry?.email || (props.auth?.user?.email ?? ""),
    gender: savedData.gender || props.inquiry?.gender || (props.auth?.user?.gender ?? ""),
    name: savedData.name || props.inquiry?.name || (props.auth?.user?.name ?? ""),
    last_name: savedData.last_name || props.inquiry?.last_name || (props.auth?.user?.last_name ?? ""),
    thirdParty_broker: false,
});

// Set up a ref for the selected date
const date = ref<Date | null>(null);

// On mounted, check for a query string to populate inquiry_id if needed
onMounted(() => {
    if (!form.inquiry_id) {
        const fromQuery = new URLSearchParams(window.location.search).get("inquiry");
        if (fromQuery) {
            form.inquiry_id = parseInt(fromQuery);
        }
    }
});

// Watch the form data and store it in localStorage
watch(form, () => {
    localStorage.setItem("inquiry_form_step_5", JSON.stringify(form));
}, { deep: true });

// Watch for changes in the selected date
watch(date, () => {
    if (date.value) {
        form.moving_date = date.value.toISOString().split("T")[0]; // Ensure ISO string format (yyyy-MM-dd)
    }
});

// Define a step for multi-step form functionality
const step = ref(1);

// Submit the form


function submit() {
    form.post(route('inquiry.step5.store', { inquiry: props.inquiry.id }));
}


const disablePastDates = (date: Date) => {
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Set time to 00:00:00 to compare just the date part
    return date < today; // Disable past dates
};
</script>

<template>
    <AuthLayout class="gradient_01">
        <div class="min-h-[calc(100vh-64px)]">
            <div class="bg-white px-10 py-10 shadow-md sm:rounded-lg max-w-2xl mt-6 mx-auto">
                <form @submit.prevent="submit">
                    <div class="mt-4">
                        <label class="label flex flex-col items-start gap-0 mb-3">
                            <span class="label-text font-medium text-lg text-black">Moving Date</span>
                            <small>You can change the date later.</small>
                        </label>
                        <!-- Vue DatePicker Component -->
                        <VueDatePicker
                            :auto-apply="true"
                            :enable-time-picker="false"
                            :disabled-dates="disablePastDates"
                            week-start="0"
                            :disabled-week-days="[7, 0]"
                            :year-range="[2025, 2040]"
                            v-model="form.moving_date"
                            format="dd.MM.yyyy"
                            placeholder="Select a date" />
                    </div>

                    <div class="my-8">
                        <h2 class="text-2xl font-semibold text-center">To personalize your quotes:</h2>
                    </div>

                    <div class="mt-4">
                        <RadioGroup v-model="form.gender"  class="flex">
                            <div class="flex items-center space-x-2">
                                <RadioGroupItem id="r1" value="Mr" class="cursor-pointer" />
                                <Label for="r1" class="text-md font-medium cursor-pointer">Mr</Label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <RadioGroupItem id="r2" value="Ms" class="cursor-pointer" />
                                <Label for="r2" class="text-md font-medium cursor-pointer">Ms</Label>
                            </div>
                        </RadioGroup>

                    </div>
                    <div class="mt-4 flex gap-4">
                        <!-- First Name -->
                        <div class="flex-1">
                            <div class="font-medium text-sm mb-2">
                                <h3>First name</h3>
                            </div>
                            <input v-model="form.name" class="input border-2 border-[#ededed] focus:border-gray-900  !outline-0 !shadow-none  block w-full" type="text" />
                        </div>
                        <!-- Last name -->
                        <div class="flex-1">
                            <div class="font-medium text-sm mb-2">
                                <h3>Last name</h3>
                            </div>
                            <input v-model="form.last_name" class="input border-2 border-[#ededed] focus:border-gray-900  !outline-0 !shadow-none  block w-full" type="text" />
                        </div>

                    </div>



                    <!-- Phone -->
                    <div class="mt-4">
                        <div class="font-medium text-sm mb-2">
                            <h3>Phone</h3>
                        </div>
                        <div class="input border-2 border-[#ededed] focus:border-gray-900  !outline-0 !shadow-none   w-full">
                            <svg class="h-[20px] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><g fill="none"><path d="M7.25 11.5C6.83579 11.5 6.5 11.8358 6.5 12.25C6.5 12.6642 6.83579 13 7.25 13H8.75C9.16421 13 9.5 12.6642 9.5 12.25C9.5 11.8358 9.16421 11.5 8.75 11.5H7.25Z" fill="currentColor"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M6 1C4.61929 1 3.5 2.11929 3.5 3.5V12.5C3.5 13.8807 4.61929 15 6 15H10C11.3807 15 12.5 13.8807 12.5 12.5V3.5C12.5 2.11929 11.3807 1 10 1H6ZM10 2.5H9.5V3C9.5 3.27614 9.27614 3.5 9 3.5H7C6.72386 3.5 6.5 3.27614 6.5 3V2.5H6C5.44771 2.5 5 2.94772 5 3.5V12.5C5 13.0523 5.44772 13.5 6 13.5H10C10.5523 13.5 11 13.0523 11 12.5V3.5C11 2.94772 10.5523 2.5 10 2.5Z" fill="currentColor"></path></g></svg>
                            <input v-model="form.phone_number" type="tel" class="" required placeholder="Phone" pattern="[0-9]*" minlength="10" maxlength="10" title="Must be 10 digits" />
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mt-4">
                        <div class="font-medium text-sm mb-2">
                            <h3>Email</h3>
                        </div>
                        <div class="input border-2 border-[#ededed] focus:border-gray-900  !outline-0 !shadow-none   w-full">
                            <svg class="h-[16px] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none" stroke="currentColor"><rect width="20" height="16" x="2" y="4" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></g></svg>
                            <input v-model="form.email" type="email" class="" required placeholder="Email"  />
                        </div>
                    </div>

                    <!-- Email
                    <div class="mt-4">
                        <div class="font-medium text-sm mb-2">
                            <h3>Email Address</h3>
                        </div>
                        <input v-model="form.email" class="input border-2 border-[#ededed] focus:border-gray-900  !outline-0 !shadow-none  block w-full" type="email" />
                    </div>
                    -->

                    <div class="flex items-start space-x-2 mt-4 cursor-pointer p-5 bg-gray-50 border-1 border-gray-200 rounded-lg">
                        <Checkbox
                            id="terms"
                            class="w-5 h-5 bg-white border-1 border-gray-600"
                            v-model="form.thirdParty_broker"
                        />
                        <label for="terms" class="text-sm font-normal leading-5 cursor-pointer">
                            I would like to receive a free insurance advice from Combinvest.ch AG - including a 15% discount on insurances.
                        </label>
                    </div>


                    <div class="flex justify-between mt-12">
                        <button type="button" @click="router.visit(route('inquiry.step4',{ inquiry: props.inquiry.id }))" class="btn btn-soft border-2">Back</button>
                        <button type="submit" :disabled="form.processing" class="btn btn-neutral">
                            {{ form.processing ? "Saving..." : "Continue" }}
                        </button>
                    </div>



                </form>
            </div>
        </div>
    </AuthLayout>
</template>

<style scoped>
/* Optional custom styles */
</style>
