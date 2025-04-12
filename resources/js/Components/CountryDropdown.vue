<template>

        <button
            type="button"
            @click="toggleDropdown"
            class="!rounded-sm !px-1 !py-1 !min-h-0 !h-auto"
            :disabled="disabled"
        >
            <template v-if="selectedFlag">
                <img :src="selectedFlag" alt="" class="w-6 h-4 object-cover" />
            </template>
            <template v-else>
                <span class="mr-2">Select a Country</span>
            </template>
        </button>

        <div
            v-if="isOpen"
            class="absolute top-full mt-2 w-full bg-white shadow-lg border border-gray-300 rounded-lg max-h-60 overflow-y-auto z-10"
        >
            <div
                v-for="country in countries"
                :key="country.code"
                @click="selectCountry(country)"
                class="flex items-center p-2 cursor-pointer hover:bg-gray-100"
            >
                <img :src="country.flag" :alt="country.name" class="w-5 h-5 mr-2" />
                <span>{{ country.name }}</span>
            </div>
        </div>

</template>

<script setup lang="ts">
import { ref, watchEffect, onMounted } from 'vue'
import axios from 'axios'

interface Country {
    code: string
    name: string
    flag: string
}

const props = defineProps<{
    selectedCountry: string
    onCountryChange: (countryCode: string) => void
    disabled?: boolean
}>()

const countries = ref<Country[]>([])
const isOpen = ref(false)
const userCountryCode = ref('')
const selectedFlag = ref('')

const toggleDropdown = () => {
    isOpen.value = !isOpen.value
}

const selectCountry = (country: Country) => {
    props.onCountryChange(country.code)
    selectedFlag.value = country.flag
    isOpen.value = false
}

const getCountries = async () => {
    const response = await fetch('https://restcountries.com/v3.1/all')
    const data = await response.json()
    countries.value = data.map((country: any) => ({
        code: country.cca2,
        name: country.name.common,
        flag: country.flags.svg,
    }))
}


const getUserCountry = async () => {
    try {
        const res = await axios.get('https://ipinfo.io?token=61b212b0240679')
        userCountryCode.value = res.data.country;

    } catch (error) {
        console.error('Error fetching user country:', error)
    }
}


// watch(
//     () => props.selectedCountry,
//     (newCode) => {
//         const match = countries.value.find((c) => c.code === newCode)
//         if (match) selectedFlag.value = match.flag
//     }
// )
watchEffect(() => {
    const match = countries.value.find((c) => c.code === props.selectedCountry)
    if (match) selectedFlag.value = match.flag
})
onMounted(async () => {
    await getCountries()
    await getUserCountry()
    props.onCountryChange('CH')
    // if (!props.selectedCountry && userCountryCode.value) {
    //     props.onCountryChange(userCountryCode.value)
    // }
})
</script>
