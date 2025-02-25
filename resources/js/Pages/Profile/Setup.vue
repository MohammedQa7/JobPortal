<template>
    <Stepper v-if="!isFormSubmited" class="block w-full">
        <div class="flex w-full flex-start gap-2">
            <StepperItem v-for="step in steps" :key="step.step" v-slot="{ state }"
                class="relative flex w-full flex-col items-center justify-center" :step="step.step">
                <StepperSeparator v-if="step.step !== steps[steps.length - 1].step" class="absolute left-[calc(50%+20px)] right-[calc(-50%+10px)] top-5 block h-0.5 shrink-0 rounded-full bg-muted 
                    " :class="{ 'bg-primary': stepIndex > step.step }" />

                <StepperTrigger as-child>
                    <Button :variant="stepIndex > step.step || step.step == stepIndex ? 'default' : 'outline'"
                        size="icon" class="z-10 rounded-full shrink-0"
                        :class="[step.step == stepIndex && 'ring-2 ring-ring ring-offset-2 ring-offset-background']"
                        :disabled="step.step != stepIndex && stepIndex < step.step">
                        <Check v-if="stepIndex > step.step" class="size-5" />
                        <Circle v-if="step.step == stepIndex" />
                        <Dot v-if="step.step != stepIndex && stepIndex < step.step" />
                    </Button>
                </StepperTrigger>

                <div class="mt-5 flex flex-col items-center text-center">
                    <StepperTitle :class="[state === 'active' && 'text-primary']"
                        class="text-sm font-semibold transition lg:text-base">
                        {{ step.title }}
                    </StepperTitle>
                    <StepperDescription :class="[state === 'active' && 'text-primary']"
                        class="sr-only text-xs text-muted-foreground transition md:not-sr-only lg:text-sm">
                        {{ step.description }}
                    </StepperDescription>
                </div>
            </StepperItem>
        </div>
        <div class="flex flex-col gap-4 mt-4">
            <template v-if="stepIndex === 1">
                <Step1 @stepOneEvent="bindStepOneData" :form="form" />
            </template>

            <template v-if="stepIndex === 2">
                <Step2 :form="form" :specialities="specialities" />
            </template>
        </div>

        <div class="flex items-center justify-between mt-4">
            <Button v-if="stepIndex > 1" @click="previousStep" variant="outline" size="sm" :disabled="stepIndex == 1">
                Back
            </Button>
            <div class="flex items-center gap-3">
                <Button v-if="stepIndex == steps.length - 1" @click="nextStep()" size="sm" :disabled="form.processing">
                    Next
                </Button>
                <Button v-if="stepIndex == steps.length" @click="sumbit()" size="sm" type="submit"
                    :disabled="form.processing">
                    Submit
                </Button>
            </div>
        </div>
    </Stepper>

    <div v-else class="flex flex-col justify-center items-center gap-8">
        <h1 class="text-4xl font-bold flex items-center justify-center gap-2 ">
            Congratulations
            <PartyPopper size="52" /> for setting up your profile
        </h1>
        <Button> View your profile </Button>
    </div>
</template>

<script setup>
import Step1 from '@/Components/ProfileWizard/Step1.vue';
import Step2 from '@/Components/ProfileWizard/Step2.vue';
import { Button } from '@/components/ui/button'
import { Stepper, StepperDescription, StepperItem, StepperSeparator, StepperTitle, StepperTrigger } from '@/components/ui/stepper'
import MainLayout from '@/Layouts/MainLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { Check, Circle, Dot, PartyPopper } from 'lucide-vue-next'
import { ref } from 'vue';
const stepIndex = ref(1);
const isFormSubmited = ref(false);
const steps = [
    {
        step: 1,
        title: 'Public information',
        description: 'Provide your infomation',
    },
    {
        step: 2,
        title: 'Choose your speciality',
        description: 'You can only choose one',
    },
]
const propsData = defineProps({
    specialities: Array,
});

const form = useForm({
    jobTitle: '',
    bio: '',
    speciality: null,
    stepIndex: stepIndex.value
})


const nextStep = () => {
    if (stepIndex.value != steps.length) {
        form.post(route('validate-step-data'), {
            onSuccess: () => {
                stepIndex.value++;
            }
        })
    };
}

const previousStep = () => {
    stepIndex.value > 1
        ? stepIndex.value--
        : ''
}

const sumbit = () => {
    form.post(route('profile.setup.store'), {
        onSuccess: () => {
            isFormSubmited.value = true;
        }
    });
};

defineOptions({
    layout: MainLayout,
});
</script>
