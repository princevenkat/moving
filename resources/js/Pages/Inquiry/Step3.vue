<script setup>
import { ref, watch } from "vue";
import { useForm, router, Link } from "@inertiajs/vue3";
import AuthLayout from "@/layouts/AuthLayout.vue";
import NumberInput from "@/components/NumberInput.vue";

const props = defineProps({
    inquiry: Object,
});

const step = ref(1);

const homeTypes = ["House", "Apartment", "Shared Flat", "Storage", "Office"];
const floorOptions = ["Basement", "Ground Floor", "Mezzanine Floor", "1", "2", "3", "4", "5+"];
const elevatorOptions = ["No", "Yes, for 2-3 people", "Yes, for 4-5 people", "Yes, for 6+ people", "Yes, goods lift for 10+ people"];

const saved = JSON.parse(localStorage.getItem("inquiry_form_step_3") || "{}");

const form = useForm({
    new_home_type: saved.new_home_type || "",
    new_home_floor: saved.new_home_floor || "",
    new_home_rooms: saved.new_home_rooms || "",
    new_home_square_meters: saved.new_home_square_meters || "0",
    new_home_has_elevator: saved.new_home_has_elevator || "No",
    new_home_long_distance: saved.new_home_long_distance || false,
    new_home_distance_meters: saved.new_home_distance_meters || "",
    new_home_has_steps: saved.new_home_has_steps || false,
    new_home_num_steps: saved.new_home_num_steps || "",
    new_home_impeded: saved.new_home_impeded || false,
    new_home_impeded_details: saved.new_home_impeded_details || "",
});

watch(form, (val) => {
    localStorage.setItem("inquiry_form_step_3", JSON.stringify(val));
}, { deep: true });

const submit = () => {
    form.post(route("inquiry.step3.store", { inquiry: props.inquiry.id }));
};

const goBack = () => {
    router.visit(route("inquiry.step2"));
};



</script>

<template>
    <AuthLayout class="gradient_01">
        <div class="min-h-[calc(100vh-64px)]">
            <div class="bg-white px-10 py-10 shadow-md sm:rounded-lg max-w-2xl mt-6 mx-auto">

                <Transition name="fade-slide" mode="out-in">
                    <div :key="step">
                <!-- Header -->
                <div v-if="step === 1" class="text-center">
                    <h1 class="text-3xl font-bold">New Home</h1>
                    <p>Following we will ask you detailed questions about your current home.</p>
                    <Link href="#" class="text-sm text-gray-600">Why do we need this information?</Link>
                </div>
                <div v-else-if="step === 2" class="text-center">
                    <p class="text-sm">New Home</p>
                    <h1 class="text-3xl font-bold">Floors</h1>
                    <Link href="#" class="text-sm text-gray-600">Why do we need this information?</Link>
                </div>
                <div v-else-if="step === 3" class="text-center">
                    <p class="text-sm">New Home</p>
                    <h1 class="text-3xl font-bold">Size</h1>
                    <Link href="#" class="text-sm text-gray-600">Why do we need this information?</Link>
                </div>
                <div v-else-if="step === 4" class="text-center">
                    <p class="text-sm">New Home</p>
                    <h1 class="text-3xl font-bold">Access</h1>
                    <Link href="#" class="text-sm text-gray-600">Why do we need this information?</Link>
                </div>
                <div v-else-if="step === 5" class="text-center">
                    <p class="text-sm">Accessibility</p>
                    <h1 class="text-3xl font-bold">Access</h1>
                    <Link href="#" class="text-sm text-gray-600">Why do we need this information?</Link>
                </div>

                <form @submit.prevent="submit">
                    <!-- Step 1: Type -->
                    <div v-if="step === 1" class="mt-6">
                        <h2 class="text-lg font-semibold">Where are you moving out of?</h2>
                        <div class="grid grid-cols-2 gap-3 mt-4">
                            <button
                                v-for="type in homeTypes"
                                :key="type"
                                type="button"
                                @click="form.new_home_type = type"
                                :class="[
                  'p-3 rounded-lg border',
                  form.new_home_type === type ? 'bg-neutral text-white' : 'btn-white'
                ]"
                            >
                                {{ type }}
                            </button>
                        </div>
                        <div class="flex justify-between mt-6">
                            <button type="button" @click="router.visit(route('inquiry.step2',{ inquiry: props.inquiry.id }))" class="btn btn-soft border-2">Back</button>
                            <button type="button" @click="step = 2" class="btn btn-neutral">Continue</button>
                        </div>
                    </div>

                    <!-- Step 2: Floor -->
                    <div v-if="step === 2" class="mt-6">
                        <h2 class="text-lg font-semibold">Select Floor</h2>
                        <div class="grid grid-cols-2 gap-3 mt-4">
                            <button
                                v-for="floor in floorOptions"
                                :key="floor"
                                type="button"
                                @click="form.new_home_floor = floor"
                                :class="[
                  'p-3 rounded-lg border',
                  form.new_home_floor === floor ? 'bg-neutral text-white' : 'btn-white'
                ]"
                            >
                                {{ floor }}
                            </button>
                        </div>
                        <div class="flex justify-between mt-6">
                            <button type="button" @click="step = 1" class="btn btn-soft border-2">Back</button>
                            <button type="button" @click="step = 3" class="btn btn-neutral">Continue</button>
                        </div>
                    </div>

                    <!-- Step 3: Size -->
                    <div v-if="step === 3" class="mt-6">
                        <h2 class="text-lg font-semibold mt-6 mb-2">Home Size</h2>
                        <div class="flex gap-20">
                            <div class="flex-1">
                                <label class="label"><span class="label-text font-medium text-sm">Rooms</span></label>
                                <NumberInput v-model="form.new_home_rooms" :min="1" :max="8" :step="0.5" />
                            </div>
                            <div class="flex-1">
                                <label class="label"><span class="label-text font-medium text-sm">Square Meters</span></label>
                                <NumberInput v-model="form.new_home_square_meters" :min="0" :max="300" :step="10" />
                            </div>
                        </div>
                        <div class="flex justify-between mt-12">
                            <button type="button" @click="step = 2" class="btn btn-soft border-2">Back</button>
                            <button type="button" @click="step = 4" class="btn btn-neutral">Continue</button>
                        </div>
                    </div>

                    <!-- Step 4: Lift -->
                    <div v-if="step === 4" class="mt-6">
                        <h2 class="text-lg font-semibold">Is there a lift?</h2>
                        <div class="grid grid-cols-2 gap-3 mt-4">
                            <button
                                v-for="opt in elevatorOptions"
                                :key="opt"
                                type="button"
                                @click="form.new_home_has_elevator = opt"
                                :class="[
                  'p-3 rounded-lg border',
                  form.new_home_has_elevator === opt ? 'bg-neutral text-white' : 'btn-white'
                ]"
                            >
                                {{ opt }}
                            </button>
                        </div>
                        <div class="flex justify-between mt-6">
                            <button type="button" @click="step = 3" class="btn btn-soft border-2">Back</button>
                            <button type="button" @click="step = 5" class="btn btn-neutral">Continue</button>
                        </div>
                    </div>

                    <!-- Step 5: Final -->
                    <div v-if="step === 5" class="mt-6">
                        <h2 class="text-lg font-semibold">The path from parking to building entrance...</h2>

                        <div class="mt-6">
                            <label class="flex gap-3 items-center">
                                <input type="checkbox" v-model="form.new_home_long_distance" class="checkbox checkbox-md border-1" />
                                ... is longer than 5 meters
                            </label>
                            <div v-if="form.new_home_long_distance" class="mt-2 ml-6 w-2/4">
                                <label class="label">
                                    <span class="label-text font-medium text-sm">Distance in meters</span>
                                </label>
                                <NumberInput v-model="form.new_home_distance_meters" :min="0" :max="200" :step="10" />
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="flex gap-3 items-center">
                                <input type="checkbox" v-model="form.new_home_has_steps" class="checkbox checkbox-md border-1" />
                                ... has steps
                            </label>
                            <div v-if="form.new_home_has_steps" class="mt-2 ml-6 w-2/4">
                                <label class="label">
                                    <span class="label-text font-medium text-sm">Number of steps</span>
                                </label>
                                <NumberInput v-model="form.new_home_num_steps" :min="0" :max="40" :step="5" />
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="flex gap-3 items-center">
                                <input type="checkbox" v-model="form.new_home_impeded" class="checkbox checkbox-md border-1" />
                                ... is otherwise impeded (e.g. maisonette)
                            </label>
                            <div v-if="form.new_home_impeded" class="mt-6 ml-6">
                <textarea
                    v-model="form.new_home_impeded_details"
                    placeholder="Please describe your situation..."
                    class="textarea textarea-bordered  w-full h-32 border-1"
                ></textarea>
                            </div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <button type="button" @click="step = 4" class="btn btn-soft">Back</button>
                            <button type="submit" :disabled="form.processing" class="btn btn-neutral">
                                {{ form.processing ? "Saving..." : "Continue" }}
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
