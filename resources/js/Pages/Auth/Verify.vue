<template>
    <div class=" flex justify-center items-center ">
        <Card class="w-[450px]">
            <CardHeader>
                <CardTitle>Code has been sent</CardTitle>
                <CardDescription>Request it one more time if you did not receive anything</CardDescription>
            </CardHeader>
            <CardContent>
                <PinInput id="pin-input" v-model="code" placeholder="○" @complete="handleComplete"
                    :disabled="isLoading">
                    <PinInputGroup class="gap-1">
                        <template v-for="(id, index) in 5" :key="id">
                            <PinInputInput class="rounded-md border" :index="index" />
                            <template v-if="index !== 4">
                                <PinInputSeparator />
                            </template>
                        </template>
                    </PinInputGroup>
                    <Loader2 v-if="isLoading" class="size-5 animate-spin text-center" />
                </PinInput>
                <InputError :message="errorMessage" />
            </CardContent>
            <CardFooter class="flex justify-end px-6 pb-6">
            </CardFooter>
        </Card>
    </div>
</template>

<script setup>
import Card from '@/Components/ui/card/Card.vue';
import CardContent from '@/Components/ui/card/CardContent.vue';
import CardDescription from '@/Components/ui/card/CardDescription.vue';
import CardFooter from '@/Components/ui/card/CardFooter.vue';
import CardHeader from '@/Components/ui/card/CardHeader.vue';
import CardTitle from '@/Components/ui/card/CardTitle.vue';
import {
    PinInput,
    PinInputGroup,
    PinInputInput,
    PinInputSeparator,
} from '@/components/ui/pin-input'
import MainLayout from '@/Layouts/MainLayout.vue';
import { ref } from 'vue'
import { router } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import { Loader2 } from 'lucide-vue-next';
const code = ref();
const errorMessage = ref(null);
const isLoading = ref(false);
const handleComplete = () => {
    isLoading.value = !isLoading.value;
    router.post(route('mobile.verification.verify'), { code: code.value.join('') }, {
        onError: (error) => {
            isLoading.value = false;
            errorMessage.value = error.code;
        }
    });
}

defineOptions({
    layout: MainLayout,
});
</script>
