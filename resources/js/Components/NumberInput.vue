<script setup lang="ts">
import { ref, watch, onMounted, computed } from 'vue'
import { Minus, Plus } from 'lucide-vue-next'

interface Props {
    modelValue?: number | string
    min?: number
    max?: number
    step?: number
    symbol?: string
}

const props = defineProps<Props>()
const emit = defineEmits(['update:modelValue', 'change'])

const inputRef = ref<HTMLInputElement | null>(null)
const hitMin = ref(false)
const hitMax = ref(false)

const internalValue = ref(props.modelValue ?? '')

watch(() => props.modelValue, (val) => {
    internalValue.value = val ?? ''
    checkBounds()
})

const checkBounds = () => {
    const val = parseFloat(internalValue.value as string)
    hitMin.value = props.min !== undefined && val <= props.min
    hitMax.value = props.max !== undefined && val >= props.max
}

const updateValue = (val: number | string) => {
    internalValue.value = val
    emit('update:modelValue', val)
    emit('change', val)
    checkBounds()
}

const increment = () => {
    if (inputRef.value) {
        inputRef.value.stepUp()
        updateValue(inputRef.value.value)
    }
}

const decrement = () => {
    if (inputRef.value) {
        inputRef.value.stepDown()
        updateValue(inputRef.value.value)
    }
}

onMounted(() => {
    checkBounds()
})
</script>

<template>
    <div class="flex items-center justify-between rounded-lg border p-2.5">
        <button
            type="button"
            :disabled="hitMin"
            @click="decrement"
            aria-label="decrease"
            class="group mr-2 text-gray-500 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer"
        >
            <Minus class="w-4" />
        </button>

        <div class="relative flex-grow">
            <input
                ref="inputRef"
                type="number"
                class="no-steps w-fit border-0 bg-transparent p-0 text-center"
                :value="internalValue"
                :min="props.min"
                :max="props.max"
                :step="props.step || 1"
                @input="updateValue($event.target.value)"
            />
            <span v-if="props.symbol" class="absolute right-4 top-0">{{ props.symbol }}</span>
        </div>

        <button
            type="button"
            :disabled="hitMax"
            @click="increment"
            aria-label="increase"
            class="group ml-2 text-gray-500 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer"
        >
            <Plus class="w-4" />
        </button>
    </div>
</template>
