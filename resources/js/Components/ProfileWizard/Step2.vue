<template>

    <Select v-model="form.speciality">
        <SelectTrigger class="w-full">
            <SelectValue placeholder="Select a fruit" />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectLabel>Specialities</SelectLabel>
                <SelectItem v-for="(speciality, key) in specialities" :key="key" :value="key">
                    {{ speciality }}
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>

    <InputError :message="form.errors.speciality" />
</template>


<script setup>
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'
import InputError from '../InputError.vue';

const propsData = defineProps({
    form: Object,
    specialities: Array,
})
</script>