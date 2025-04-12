<script setup lang="ts">
import { ref, reactive, watch, onMounted } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AuthLayout from "@/layouts/AuthLayout.vue";
import NumberInput from "@/components/NumberInput.vue";

interface Inquiry {
    id: string;
}

const props = defineProps<{
    inquiry: Inquiry;
}>();

const step = ref(1);

// Load saved data
const savedData = JSON.parse(localStorage.getItem("inquiry_form_step_2") || "{}");

const form = useForm({
    current_home_type: savedData.current_home_type || "",
    floor: savedData.floor || "",
    rooms: savedData.rooms || "",
    square_meters: savedData.square_meters || "0",
    has_elevator: savedData.has_elevator || "No",
    long_distance: savedData.long_distance || false,
    distance_meters: savedData.distance_meters || "",
    has_steps: savedData.has_steps || false,
    num_steps: savedData.num_steps || "",
    impeded: savedData.impeded || false,
    impeded_details: savedData.impeded_details || "",
});

// Auto-save to localStorage
watch(form, (val) => {
    localStorage.setItem("inquiry_form_step_2", JSON.stringify(val));
}, { deep: true });

// Submit the form
function submit() {
    form.post(route("inquiry.step2.store", { inquiry: props.inquiry.id }));
}

const goBack = () => {
    if (step.value > 1) step.value--;
};
const goNext = () => {
    if (step.value < 5) step.value++;
};

const selectHomeType = (type: string) => {
    form.current_home_type = type;
};

const selectFloor = (floor: string) => {
    form.floor = floor;
};

const selectElevator = (option: string) => {
    form.has_elevator = option;
};



</script>

<template>
    <AuthLayout  class="gradient_01">
        <div class="min-h-[calc(100vh-64px)]">
            <div class="bg-white px-10 py-10 shadow-md sm:rounded-lg max-w-2xl mt-6 mx-auto">

                <Transition name="fade-slide" mode="out-in">
                    <div :key="step">

                <div v-if="step === 1" class="text-center">
                    <h1 class="text-3xl font-bold">Current Home</h1>
                    <p>Following we will ask you detailed questions about your current home.</p>
                    <a href="#" class="text-sm text-gray-600">Why do we need this information?</a>
                </div>

                <div v-else-if="step === 2" class="text-center">
                    <p class="text-sm">Current Home</p>
                    <h1 class="text-3xl font-bold">Floors</h1>
                    <a href="#" class="text-sm text-gray-600">Why do we need this information?</a>
                </div>

                <div v-else-if="step === 3" class="text-center">
                    <p class="text-sm">Current Home</p>
                    <h1 class="text-3xl font-bold">Size</h1>
                    <a href="#" class="text-sm text-gray-600">Why do we need this information?</a>
                </div>

                <div v-else-if="step === 4" class="text-center">
                    <p class="text-sm">Current Home</p>
                    <h1 class="text-3xl font-bold">Access</h1>
                    <a href="#" class="text-sm text-gray-600">Why do we need this information?</a>
                </div>

                <div v-else-if="step === 5" class="text-center">
                    <p class="text-sm">Accessibility</p>
                    <h1 class="text-3xl font-bold">Access</h1>
                    <a href="#" class="text-sm text-gray-600">Why do we need this information?</a>
                </div>

                <form @submit.prevent="submit">

                    <!-- Step 1: Home Type -->
                    <div v-if="step === 1" class="mt-6">
                        <h2 class="text-lg font-semibold">Where are you moving out of?</h2>
                        <div class="grid grid-cols-2 gap-3 mt-4">
                            <button
                                v-for="type in ['House', 'Apartment', 'Shared Flat', 'Storage', 'Office']"
                                :key="type"
                                type="button"
                                class="p-3 rounded-lg border"
                                :class="form.current_home_type === type ? 'bg-neutral text-white' : 'btn-white'"
                                @click="selectHomeType(type)"
                            >
                                {{ type }}
                            </button>
                        </div>
                        <div class="flex justify-between mt-6">
                            <button type="button" @click="router.visit(route('inquiry.start',{ inquiry: props.inquiry.id }))" class="btn">Back</button>
                            <button type="button" @click="goNext" class="btn btn-neutral">Continue</button>
                        </div>
                    </div>

                    <!-- Step 2: Floor -->
                    <div v-else-if="step === 2" class="mt-6">
                        <h2 class="text-lg font-semibold">Select Floor</h2>
                        <div class="grid grid-cols-2 gap-3 mt-4">
                            <button
                                v-for="floor in ['Basement', 'Ground Floor', 'Mezzanine Floor', '1', '2', '3', '4', '5+']"
                                :key="floor"
                                type="button"
                                class="p-3 rounded-lg border"
                                :class="form.floor === floor ? 'bg-neutral text-white' : 'btn-white'"
                                @click="selectFloor(floor)"
                            >
                                {{ floor }}
                            </button>
                        </div>
                        <div class="flex justify-between mt-6">
                            <button type="button" @click="goBack" class="btn">Back</button>
                            <button type="button" @click="goNext" class="btn btn-neutral">Continue</button>
                        </div>
                    </div>

                    <!-- Step 3: Size -->
                    <div v-else-if="step === 3" class="mt-6">
                        <h2 class="text-lg font-semibold mt-6 mb-2">Home Size</h2>
                        <div class="flex gap-20">
                            <div class="flex-1">
                                <label class="label"><span class="label-text font-medium text-sm">Rooms</span></label>
                                <NumberInput v-model="form.rooms" :min="1" :max="10" :step="0.5" />
                            </div>
                            <div class="flex-1">
                                <label class="label"><span class="label-text font-medium text-sm">Square Meters</span></label>
                                <NumberInput v-model="form.square_meters" :min="0" :max="300" :step="10" />
                            </div>
                        </div>
                        <div class="flex justify-between mt-12">
                            <button type="button" @click="goBack" class="btn btn-soft border-2">Back</button>
                            <button type="button" @click="goNext" class="btn btn-neutral">Continue</button>
                        </div>
                    </div>

                    <!-- Step 4: Elevator -->
                    <div v-else-if="step === 4" class="mt-6">
                        <h2 class="text-lg font-semibold">Is there a lift?</h2>
                        <div class="grid grid-cols-2 gap-3 mt-4">
                            <button
                                v-for="option in ['No', 'Yes, for 2-3 people', 'Yes, for 4-5 people', 'Yes, for 6+ people', 'Yes, goods lift for 10+ people']"
                                :key="option"
                                type="button"
                                class="p-3 rounded-lg border"
                                :class="form.has_elevator === option ? 'bg-neutral text-white' : 'btn-white'"
                                @click="selectElevator(option)"
                            >
                                {{ option }}
                            </button>
                        </div>
                        <div class="flex justify-between mt-6">
                            <button type="button" @click="goBack" class="btn btn-soft">Back</button>
                            <button type="button" @click="goNext" class="btn btn-neutral">Continue</button>
                        </div>
                    </div>

                    <!-- Step 5: Access Details -->
                    <div v-else-if="step === 5" class="mt-6">
                        <h2 class="text-lg font-semibold">The path from parking to building entrance...</h2>

                        <!-- Long Distance -->
                        <div class="mt-6">
                            <label class="flex gap-3 items-center">
                                <input type="checkbox" class="checkbox checkbox-md border-1" v-model="form.long_distance" />
                                ... is longer than 5 meters
                            </label>
                            <div v-if="form.long_distance" class="mt-2 ml-6 w-2/4">
                                <label class="label">
                                    <span class="label-text font-medium text-sm">Distance in meters</span>
                                </label>
                                <NumberInput v-model="form.distance_meters" :min="0" :max="200" :step="10" />
                            </div>
                        </div>

                        <!-- Steps -->
                        <div class="mt-6">
                            <label class="flex gap-3 items-center">
                                <input type="checkbox" class="checkbox checkbox-md border-1" v-model="form.has_steps" />
                                ... has steps
                            </label>
                            <div v-if="form.has_steps" class="mt-2 ml-6 w-2/4">
                                <label class="label">
                                    <span class="label-text font-medium text-sm">Number of steps</span>
                                </label>
                                <NumberInput v-model="form.num_steps" :min="0" :max="40" :step="5" />
                            </div>
                        </div>

                        <!-- Impeded Access -->
                        <div class="mt-6">
                            <label class="flex gap-3 items-center">
                                <input type="checkbox" class="checkbox checkbox-md border-1" v-model="form.impeded" />
                                ... is otherwise impeded (e.g. maisonette)
                            </label>
                            <div v-if="form.impeded" class="mt-6 ml-6">
                <textarea
                    placeholder="Please describe your situation..."
                    class="textarea textarea-bordered w-full h-32 border-1"
                    v-model="form.impeded_details"
                ></textarea>
                            </div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <button type="button" @click="goBack" class="btn btn-soft">Back</button>
                            <button type="submit" :disabled="form.processing" class="btn btn-neutral">
                                {{ form.processing ? 'Saving...' : 'Continue' }}
                            </button>
                        </div>
                    </div>

                </form>

                    </div>
                </Transition>
            </div>
        </div>
    </AuthLayout>
</template>


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
