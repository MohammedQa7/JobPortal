<template>
    <div>
        <Label for="job-title">Job title</Label>
        <Input v-model="form.jobTitle" placeholder="Job title" name="job-title" />
        <InputError :message="form.errors.jobTitle" />
    </div>
    <div>
        <Label for="bio">Bio</Label>
        <Textarea v-model="form.bio" placeholder="Job title" name="job-title" />
        <InputError :message="form.errors.bio" />
    </div>
</template>


<script setup>
import { Input } from '../ui/input';
import InputError from '../InputError.vue';
import Label from '../ui/label/Label.vue';
import { ref, watch } from 'vue';
import { Textarea } from '../ui/textarea';
const propsData = defineProps({
    form: Object,
})
</script>