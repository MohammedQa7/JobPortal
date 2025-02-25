<template>
    <div class=" flex justify-center items-center ">
        <Card class="w-[450px]">
            <CardHeader>
                <CardTitle>Verify Your Phone Number</CardTitle>
                <CardDescription>Enter your phone number to
                    receive a verification code via SMS.</CardDescription>
            </CardHeader>
            <CardContent>
                <form>
                    <div class="grid items-center w-full gap-4">
                        <div class="flex flex-col space-y-1.5">
                            <Label for="phone_number">Phone number</Label>
                            <Input v-model="form.phoneNumber" id="phone_number" placeholder="+xxx xxxx xxxx" />
                            <InputError :message="form.errors.phoneNumber" />
                        </div>
                    </div>
                </form>
            </CardContent>
            <CardFooter class="flex justify-end px-6 pb-6">
                <Button @click.prevent="submit">Send</Button>
            </CardFooter>
        </Card>
    </div>
</template>

<script setup lang='ts'>
import InputError from '@/Components/InputError.vue'
import { Button } from '@/components/ui/button'
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import MainLayout from '@/Layouts/MainLayout.vue'
import { router, useForm } from '@inertiajs/vue3'
const form = useForm({
    phoneNumber: null
})
const submit = () => {
    form.post(route('mobile.verification.send'));
}

defineOptions({
    layout: MainLayout,
})
</script>
