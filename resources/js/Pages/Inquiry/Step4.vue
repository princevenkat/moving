<script setup lang="ts">
import { SquareX,Boxes } from 'lucide-vue-next';
import {ref, computed, watch} from 'vue';
import {router, useForm} from '@inertiajs/vue3';
import AuthLayout from "@/layouts/AuthLayout.vue";
import NumberInput from "@/components/NumberInput.vue";


import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert'

import {data} from "autoprefixer";
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion'


// const form = ref({
//     length_of_residence: '', // This will store the selected value
// });

const radioOptions = [
    {
        name: 'length_of_residence',
        value: 'Less than 5 years',
        label: 'Less than 5 years'
    },
    {
        name: 'length_of_residence',
        value: '5 to 10 years',
        label: '5 to 10 years'
    },
    {
        name: 'length_of_residence',
        value: 'more than 10 years',
        label: 'More than 10 years'
    }
];

const additional_service_items = [
    {
        key: 'furniture_assembly',
        title: 'Sturdy and straight furniture even after moving?',
        subtitle: 'Disassembly / Assembly of your furniture',
        description: 'Disassembling of the furniture mentioned and assembling in the new home. Important: If the service is not selected, the furniture needs to be disassembled on the moving day',
        icon: '/assets/additional_icon/01.svg',
    },
    {
        key: 'furniture_lift',
        title: 'Furniture too big for the stairwell?',
        subtitle: 'Order a furniture lift',
        description: 'May be necessary if some furniture does not fit through the stairwell (large sofa, piano). <b>Important: in some situations, it is not possible to install the furniture lift.</b>',
        icon: '/assets/additional_icon/02.svg',
    },
    {
        key: 'wardrobe_boxes',
        title: 'Wrinkle-free shirts in your new home?',
        subtitle: 'Rent wardrobe boxes (delivery and pick-up included)',
        description: 'Your clothes will be transported wrinkle-free with hangers in a horizontal rail. The boxes will be delivered on the day of the move and picked up once you packed everything. One wardrobe box is designed to fit approx. 10-15 pieces of clothing.',
        icon: '/assets/additional_icon/03.svg',
    },
    {
        key: 'box_packing',
        title: 'Have your boxes packed by experts?',
        subtitle: 'Box packing service (boxes included)',
        description: 'The service of protecting and packing your objects in boxes. The boxes are brought by the moving company. <b>Important: This service can only be selected if you have boxes to transport.</b>',
        icon: '/assets/additional_icon/04.svg',
    },
    {
        key: 'lamp_dismantling',
        title: 'Easy and secure lamp dismantling?',
        subtitle: 'Lamps to dismantle',
        description: 'Dismounting lamps in your current home. For insurance reasons we cannot offer you a service of reassembling the lamps in your new Home.',
        icon: '/assets/additional_icon/05.svg',
    },
    {
        key: 'item_disposal',
        title: 'Get rid of items in poor condition?',
        subtitle: 'Disposal per m³',
        description: 'The company will take your waste to the waste disposal center for you. <b>The quote for this option will be for one cubic meter. If you have more than one cubic meter, the quote you receive will not be fixed.</b>',
        icon: '/assets/additional_icon/06.svg',
    },
    {
        key: 'floor_protection',
        title: 'Protect the new parquet?',
        subtitle: 'Order floorliner',
        description: 'The moving company protects floors, especially new and/or fragile ones to minimize the risk of scratches during the move.',
        icon: '/assets/additional_icon/07.svg',
    },
];


const props = defineProps({
    inquiry: Object,
    categories: Array,
    inventoryItems: Array
});

const showPopup = ref(null);
const selectedCategory = ref<number|null>(null);
const selectedProduct = ref<string|null>(null);

const searchTerm = ref('');

const savedData = JSON.parse(localStorage.getItem("inquiry_form_step_4") || "{}");

const form = useForm({
    inventory: savedData.inventory || [],
    number_of_people: savedData.number_of_people || 1,
    length_of_residence: savedData.length_of_residence,
    number_of_boxes: savedData.number_of_boxes,
    furniture_assembly: savedData.furniture_assembly ?? false,
    furniture_lift: savedData.furniture_lift ?? false,
    wardrobe_boxes: savedData.wardrobe_boxes ?? false,
    wardrobe_boxes_count: savedData.wardrobe_boxes_count ?? null,
    box_packing: savedData.box_packing ?? false,
    lamp_dismantling: savedData.lamp_dismantling ?? false,
    lamp_dismantling_count: savedData.lamp_dismantling_count ?? null,
    item_disposal: savedData.item_disposal ?? false,
    floor_protection: savedData.floor_protection ?? false,
    floor_protection_count: savedData.floor_protection_count ?? null,
});

// watch(() => form.inventory, (newVal) => {
//     localStorage.setItem("inquiry_form_step_4", JSON.stringify({ inventory: newVal }));
// }, { deep: true });

watch(form, (val) => {
    localStorage.setItem("inquiry_form_step_4", JSON.stringify(val));
}, { deep: true });

const newItem = ref({
    category: '',
    item: '',
    itemImage: '',
    quantity: 1,
    size: '',
    weight: '',
    type: '',
    doors: '',
    'rear-walls': ''
});

const openCategoryPopup = () => showPopup.value = 'category';

function selectCategory(categoryId: number) {
    selectedCategory.value = categoryId;
    const categoryName = props.categories.find((cat: any) => cat.id === categoryId)?.name || '';
    newItem.value.category = categoryName;
    showPopup.value = 'product';
}

function selectProduct(itemName: string) {
    selectedProduct.value = itemName;
    showPopup.value = 'details';
}

function addItem() {
    if (selectedProduct.value && newItem.value.quantity > 0) {
        const foundItem = props.inventoryItems.find((item: any) => item.name === selectedProduct.value);

        newItem.value.item = selectedProduct.value;
        newItem.value.itemImage = foundItem?.image || null; // <-- attach image here

        form.inventory.push({ ...newItem.value });

        Object.assign(newItem.value, {
            category: '',
            item: '',
            itemImage: '',
            quantity: 1,
            size: '',
            weight: '',
            type: '',
            doors: '',
            'rear-walls': ''
        });

        showPopup.value = null;
        selectedCategory.value = null;
        selectedProduct.value = null;
    }
}

function removeItem(index: number) {
    form.inventory.splice(index, 1);
}

function submit() {
    form.post(route('inquiry.step4.store', { inquiry: props.inquiry.id }));
}

const groupedInventory = computed(() => {
    return form.inventory.reduce((acc: any, item: any) => {
        if (!acc[item.category]) acc[item.category] = [];
        acc[item.category].push(item);
        return acc;
    }, {});
});

const selectedItem = computed(() => props.inventoryItems.find((item: any) => item.name === selectedProduct.value));
const optionValues = computed(() => selectedItem.value?.option_values || {});
const productOptions = computed(() => Object.keys(optionValues.value));

function getCustomLabel(key: string, value: string | number) {
    const options = optionValues.value?.[key] || [];
    const match = options.find(opt => opt.value == value);
    return match?.custom_value || null;
}

function formatOptionKey(key: string) {
    return key.replace(/^option[_-]/, '').replace(/[_-]/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
}

const filteredInventoryItems = computed(() =>
    props.inventoryItems.filter((item: any) =>
        item.inventory_id === selectedCategory.value &&
        item.name.toLowerCase().includes(searchTerm.value.toLowerCase())
    )
);

const step = ref(1);



const goBack = () => {
    if (step.value > 1) step.value--;
};
const goNext = () => {
    if (step.value < 5) step.value++;
};


function handleAccordionClick(key: string) {
    // Toggle the corresponding form property.
    // This will check it if it’s unchecked or uncheck if it’s checked.
    form[key] = !form[key];
}

</script>
<template>
    <AuthLayout class="gradient_01">
        <div class="min-h-[calc(100vh-64px)]">
            <div class="bg-white px-10 py-10 shadow-md sm:rounded-lg max-w-2xl mt-6 mx-auto">

                <Transition name="fade-slide" mode="out-in">
                    <div :key="step">

                        <div v-if="step === 1" class="text-center">
                            <div class="text-center">
                                <h1 class="text-2xl font-bold">Furniture to be transported</h1>
                                <p>Specify your furniture and large items that do not fit into boxes</p>
                            </div>
                        </div>
                        <div v-if="step === 2" class="text-center">
                            <div class="text-center">
                                <h1 class="text-2xl font-bold">Inventory</h1>
                            </div>
                        </div>

                        <div v-if="step === 3" class="text-center">
                            <div class="text-center">
                                <h1 class="text-2xl font-bold">Additional Services</h1>
                            </div>
                        </div>

                <form @submit.prevent="submit">
                    <div v-if="step === 1" class="mt-6">
                        <h1 class="text-lg font-semibold mt-6 mb-2  text-gray-800 p-2 text-center uppercase border-t-1 border-b-1 border-gray-500">Inventory Management</h1>

                        <ul class="mt-4 space-y-4">
                            <li v-for="(items, category) in groupedInventory" :key="category">
                                <h4 class="font-semibold">{{ category }}</h4>
                                <ul class="list">
                                    <li class="list-row px-0" v-for="(inv, index) in items" :key="index">

                                        <div>
                                            <img
                                                v-if="inv.itemImage"
                                                :src="inv.itemImage"
                                                class="size-10"
                                                :alt="inv.itemImage"
                                            />
                                        </div>
                                        <div class="flex justify-start flex-col items-start">
                                            <div class="mb-0 font-semibold">{{ inv.item }} (x{{ inv.quantity }})</div>
                                            <div class="flex flex-col items-start justify-start">
                                            <span v-for="(value, key) in inv" :key="key">
                                                  <template v-if="key.startsWith('option_') && value" >
                                                      <div class="text-[13px] font-normal mr-2"> <span class="text-gray-700">{{ formatOptionKey(key) }}:</span> {{ getCustomLabel(key, value) || value }}</div>
                                                  </template>
                                            </span>
                                            </div>
    <!--                                        <div className="text-xs uppercase font-semibold opacity-60">Bears of a fever</div>-->
                                        </div>

                                        <button @click="removeItem(index)" class="cursor-pointer">
                                            <SquareX class="hover:text-red-700 text-black w-5" />
                                        </button>
                                    </li>
                                </ul>
                            </li>
                        </ul>

                        <button type="button" class="btn btn-neutral btn-wide" @click="openCategoryPopup">
                            Add Room
                        </button>

                        <div class="flex justify-between mt-6">
<!--                            <button class="btn btn-neutral" type="submit" :disabled="form.processing">-->
<!--                                Continue-->
<!--                            </button>-->
                            <button type="button" @click="router.visit(route('inquiry.step3',{ inquiry: props.inquiry.id }))" class="btn btn-soft border-2">Back</button>
                            <button type="button" @click="step = 2" class="btn btn-neutral">Continue</button>
                        </div>

                        <!-- CATEGORY MODAL -->
                        <dialog v-if="showPopup === 'category'" class="modal modal-open">
                            <div class="modal-box max-w-2xl">
                                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" @click="showPopup = null">X</button>
                                <h2 class="text-md lg:text-xl mb-3 lg:mb-0 font-bold">Select Room</h2>
                                <div class="grid grid-cols-2 lg:grid-cols-4 -gap-0 mt-4 join">

                                    <button v-for="category in categories" :key="category.id" class="join-item btn px-4 py-6 h-auto flex flex-col items-center gap-1 bg-white hover:bg-gray-200 border-1 -mt-1 -mr-1 rounded-none transition-colors duration-700 ease-in-out hover:bg-radial-[at_50%_50%] hover:from-white  hover:via-gray-100 hover:to-gray-200 hover:to-100%" @click="selectCategory(category.id)">
                                        <img
                                            v-if="category.image"
                                            :src="category.image"
                                            alt="Category Image"
                                            class="w-14 h-14"
                                        />

                                        <span class="text-xs uppercase font-medium">{{ category.name }}</span>
                                    </button>
                                </div>
                            </div>
                        </dialog>

                        <!-- PRODUCT MODAL -->
                        <dialog v-if="showPopup === 'product' && selectedCategory !== null" class="modal modal-open">
                            <div class="modal-box max-w-3xl h-[600px] overflow-y-auto p-5 lg:p-6">
                                <div class="flex justify-center lg:justify-between items-center flex-wrap ">
                                    <h2 class="text-md lg:text-xl mb-3 lg:mb-0 font-bold">Select Product</h2>
                                    <div class="flex gap-2 items-center">
                                        <input
                                            v-model="searchTerm"
                                            type="text"
                                            placeholder="Search product..."
                                            class="input-bordered w-full input border-2 border-[#ededed] focus:border-gray-900  !outline-0 !shadow-none h-10"
                                        />
                                        <button class="btn btn-sm text-xs uppercase btn-ghost" @click="showPopup = 'category'">Back</button>
                                        <button class="btn btn-sm btn-circle btn-ghost" @click="showPopup = null">X</button>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 lg:grid-cols-4 mt-4 join">
                                    <button v-for="item in filteredInventoryItems" :key="item.id" class="join-item btn px-4 py-6 h-auto flex flex-col items-center gap-1 bg-white hover:bg-gray-200 border-1 -mt-1 -mr-1 rounded-none transition-colors duration-700 ease-in-out hover:bg-radial-[at_50%_50%] hover:from-white  hover:via-gray-100 hover:to-gray-200 hover:to-100%" @click="selectProduct(item.name)">
                                        <!--                                {{ item.name }}-->
                                        <span class="text-xs uppercase font-medium">{{ item.name }}</span>
                                    </button>
                                </div>
                            </div>
                        </dialog>

                        <!-- DETAILS MODAL -->
                        <dialog v-if="showPopup === 'details' && selectedProduct && selectedItem" class="modal modal-open">
                            <div class="modal-box max-w-2xl">
                                <div class="absolute right-2 top-2 flex gap-2">
                                    <button class="btn btn-sm text-xs uppercase btn-ghost" @click="showPopup = 'product'">Back</button>
                                    <button class="btn btn-sm btn-circle btn-ghost" @click="showPopup = null">X</button>
                                </div>

                                <h2 class="text-sm font-semibold capitalize mb-1">Quantity</h2>
                                <div class="w-32">

                                    <NumberInput v-model.number="newItem.quantity" :min="1"  />

                                </div>

                                <div class="grid grid-cols-2 gap-4 mt-4">
                                    <fieldset v-for="optionKey in productOptions" :key="optionKey">
                                        <legend class="text-sm font-semibold capitalize">
                                            {{ optionKey.replace('option_', '').replace('-', ' ') }}
                                        </legend>
                                        <label v-for="(val, idx) in optionValues[optionKey]" :key="idx" class="flex items-center gap-2 mt-1 mb-2 cursor-pointer">
                                            <input
                                                type="radio"
                                                class="radio radio-xs border-1"
                                                :name="optionKey"
                                                :value="val.value"
                                                v-model="newItem[optionKey]"
                                            />
                                            <span class="text-sm text-gray-700">{{ val.value }}</span>
                                            <span v-if="val.custom_value" class="text-xs text-gray-500">({{ val.custom_value }})</span>
                                        </label>
                                    </fieldset>
                                </div>

                                <div class="mt-6 flex justify-between">
                                    <button class="btn btn-neutral" @click="addItem">
                                        Add Item
                                    </button>
                                </div>
                            </div>
                        </dialog>

                    </div>

                    <div v-if="step === 2" class="mt-6">
                        <div class="flex flex-col">
                            <h2 class="text-lg font-semibold mt-6 mb-2">Number of people that move</h2>
                            <select v-model="form.number_of_people" class="select border-2 border-[#ededed] focus:border-gray-900  !outline-0 !shadow-none  w-full">
                                <option value="1">1 person</option>
                                <option value="2">2 people</option>
                                <option value="3">3 people</option>
                                <option value="4">4 people</option>
                                <option value="5">5 people</option>
                                <option value="6">6 people</option>
                                <option value="7">7 people</option>
                                <option value="8">8 people</option>
                                <option value="9">9 people</option>
                                <option value="10">10 people</option>
                            </select>
                        </div>
                        <div class="flex flex-col">
                            <h2 class="text-lg font-semibold mt-6 mb-2">Length of residence</h2>
                            <div class="grid grid-cols-2">
                                <fieldset>
                                    <div v-for="(option, index) in radioOptions" :key="index">
                                        <label class="flex items-center gap-2 mt-1 mb-2 cursor-pointer">
                                            <input
                                                type="radio"
                                                class="radio radio-xs border-1"
                                                :name="option.name"
                                                :value="option.value"
                                                v-model="form.length_of_residence"
                                            />
                                            <span class="text-sm text-gray-700">{{ option.label }}</span>
                                        </label>
                                    </div>
                                </fieldset>

                            </div>
                            </div>
                        <div class="flex flex-col">
                            <h2 class="text-lg font-semibold mt-6 mb-2">Number of boxes to be transported</h2>
                            <NumberInput v-model="form.number_of_boxes" :min="10" :max="400" :step="10" />

                            <div class="mt-6 flex flex-col md:flex-row p-4 gap-5 bg-amber-50  border-1 border-amber-100 rounded-lg">
                                <Boxes strokeWidth={1} class="h-18 w-18" />
                                <div class="flex-1 flex flex-col gap-1">
                                    <p class="text-xs">For 1 person with 5 to 10 years of residence, we recommend</p>
                                    <h1 class="text-2xl font-extrabold tracking-tight lg:text-4xl text-black flex items-center gap-2">
                                        40 <span class="text-lg font-normal tracking-wide">moving boxes.</span>
                                    </h1>

                                </div>

                            </div>
                        </div>
                        <div class="flex justify-between mt-12">
                            <button type="button" @click="step = 1" class="btn btn-soft border-2">Back</button>
                            <button type="button" @click="step = 3" class="btn btn-neutral">Continue</button>
                        </div>
                    </div>


                    <div v-if="step === 3" class="mt-6">

                        <Accordion type="multiple" class="w-full" collapsible :default-value="defaultValue">
                            <AccordionItem
                                v-for="item in additional_service_items"
                                :key="item.key"
                                :value="item.key"

                            >
                                <AccordionTrigger @click="handleAccordionClick(item.key)" class="cursor-pointer hover:no-underline">
                                    <div class="w-full flex gap-4 items-start">
                                        <div class="w-8"><img :src="item.icon" alt="" class="w-full h-full object-contain" /></div>
                                        <div class="flex-1">
                                            <h4 class="text-[15px] font-semibold text-black mb-0 leading-[20px]">{{ item.title }} </h4>
                                            <span class="text-sm font-normal text-gray-700 block">{{ item.subtitle }}</span>
                                        </div>
                                        <div class=""><input type="checkbox" class="checkbox checkbox-sm border-1 relative z-50" v-model="form[item.key]" @click.stop  /></div>
                                    </div>
                                </AccordionTrigger>
                                <AccordionContent>
                                    <p v-if="item.description" class="text-sm text-gray-600 mt-2" v-html="item.description"></p>

                                    <!-- Conditionally show the count input for Wardrobe boxes -->
                                    <div v-if="item.key === 'wardrobe_boxes' && form.wardrobe_boxes"  class="mt-4 flex justify-between items-center" >
                                        <label for="wardrobeBoxesCount" class="block text-sm font-medium text-gray-700">Number of Wardrobe Boxes:</label>
                                        <NumberInput id="wardrobeBoxesCount" v-model="form.wardrobe_boxes_count" :min="1" class="mt-1 w-26 py-2 border-gray-500"/>
                                    </div>
                                    <!-- Conditionally show the count input for Lamps to dismantle -->
                                    <div v-if="item.key === 'lamp_dismantling' && form.lamp_dismantling"  class="mt-4 flex justify-between items-center" >
                                        <label for="lampDismantling" class="block text-sm font-medium text-gray-700">Lamps to dismantle:</label>
                                        <NumberInput id="lampDismantling" v-model="form.lamp_dismantling_count" :min="1" class="mt-1 w-26 py-2 border-gray-500"/>
                                    </div>
                                    <!-- Conditionally show the count input for Lamps to dismantle -->
                                    <div v-if="item.key === 'floor_protection' && form.floor_protection"  class="mt-4 flex justify-between items-center" >
                                        <label for="floorProtection" class="block text-sm font-medium text-gray-700">Required floorliner in metres</label>
                                        <NumberInput id="floorProtection" v-model="form.floor_protection_count" :min="0" :step="10" class="mt-1 w-26 py-2 border-gray-500"/>
                                    </div>

                                </AccordionContent>
                            </AccordionItem>
                        </Accordion>


                        <div class="flex justify-between mt-12">
                            <button type="button" @click="step = 2" class="btn btn-soft border-2">Back</button>
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
.strong-txt{
    font-weight:bold;
}
</style>


