<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectLabel from '@/components/ui/select/SelectLabel.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },

    specialities: Array,
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
    jobTitle: user.job_title,
    bio: user.bio,
    speciality: String(user.specialty_id),
});
</script>

<template>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Profile Information
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Update your account's profile information and email address.
        </p>
    </header>

    <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-6 w-full">
        <div class="grid grid-cols-12 gap-5 ">
            <div class="col-span-6 space-y-5">
                <div>
                    <Label for="name">Name</Label>

                    <Input id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus
                        autocomplete="name" />

                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div>
                    <Label for="email">Email</Label>

                    <Input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                        autocomplete="username" />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
            </div>
            <div class="col-span-6  space-y-5">
                <div>
                    <Label for="job-title">Job title</Label>

                    <Input id="job-title" type="text" class="mt-1 block w-full" v-model="form.jobTitle" required
                        autofocus autocomplete="job-title" />

                    <InputError class="mt-2" :message="form.errors.jobTitle" />
                </div>

                <div>
                    <Label for="email">Speciality</Label>

                    <Select v-model="form.speciality">
                        <SelectTrigger class="w-full mt-1">
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
                </div>
            </div>
            <div class="col-span-full  space-y-5">
                <div>
                    <Label for="bio">Job title</Label>

                    <Textarea id="bio" type="text" class="mt-1 block w-full" v-model="form.bio" required autofocus
                        autocomplete="bio" />

                    <InputError class="mt-2" :message="form.errors.bio" />
                </div>
            </div>
        </div>



        <div v-if="mustVerifyEmail && user.email_verified_at === null">
            <p class="mt-2 text-sm text-gray-800">
                Your email address is unverified.
                <Link :href="route('verification.send')" method="post" as="button"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Click here to re-send the verification email.
                </Link>
            </p>

            <div v-show="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                A new verification link has been sent to your email address.
            </div>
        </div>

        <div class="flex items-center gap-4">
            <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

            <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
                    Saved.
                </p>
            </Transition>
        </div>
    </form>
</template>
