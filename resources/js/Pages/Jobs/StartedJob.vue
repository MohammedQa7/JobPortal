<template>
    <div class="heading flex justify-end items-center mb-4">
        <div class="editing-proposal-btn flex items-center gap-3">
            <JobAlertDialog v-if="jobRequestsStore.job && !isJobCanceled" />
        </div>
    </div>


    <!-- If job is canceled -->
    <CanceledJobSecion v-if="isJobCanceled" class="mb-10" />

    <div v-if="!hasUserRatedJob && canUserRateJob" class="user-rating-section mb-5">
        <Card>
            <CardHeader>
                Rate the person based on his performance overall.
            </CardHeader>
            <CardContent class="space-y-3">
                <div class="flex items-center gap-2">
                    <Rating @bindUserRating="userRatingEvent" />
                    <span>{{ userRating }}</span>
                </div>
                <InputError :message="form.errors.rate" />
                <Textarea v-model="form.description" />
                <InputError :message="form.errors.description" />
                <Button @click.prevent="submitRating">
                    Rate
                </Button>
            </CardContent>
        </Card>
    </div>
    <div v-if="hasUserRatedJob" class="mb-5">
        <Card>
            <CardHeader>
                You have rated this user.
            </CardHeader>
        </Card>
    </div>


    <div class="proposal-create grid grid-cols-12 gap-5">
        <!-- job Description Section-->
        <Card class=" col-span-12 order-1 md:order-2 md:col-span-8  space-y-3">
            <CardHeader class="text-xl font-semibold">
                Messages
            </CardHeader>
            <CardContent>
                <main class="w-full overflow-auto ">
                    <div class="relative flex h-full min-h-[50vh] flex-col rounded-xl bg-muted/50 p-4 ">
                        <div class="message flex items-center gap-3">
                            <Avatar class="bg-muted-foreground self-baseline">
                                <AvatarImage />
                                <AvatarFallback>
                                    <User2 class="text-muted" />
                                </AvatarFallback>
                            </Avatar>
                            <div>
                                <h1 class="font-medium">askdjnaskjdn</h1>
                                <h6 class="text-sm">3:20 PM</h6>
                            </div>
                        </div>
                        <div class="flex-1" />
                        <form v-if="!isJobCanceled"
                            class="relative overflow-hidden rounded-lg border bg-background focus-within:ring-1 focus-within:ring-ring">
                            <Label for="message" class="sr-only">
                                Message
                            </Label>
                            <Textarea id="message" placeholder="Type your message here..."
                                class="min-h-12 resize-none border-0 p-3 shadow-none focus-visible:ring-0" />
                            <div class="flex items-center p-3 pt-0">
                                <TooltipProvider>
                                    <Tooltip>
                                        <TooltipTrigger as-child>
                                            <Button variant="ghost" size="icon">
                                                <Paperclip class="size-4" />
                                                <span class="sr-only">Attach file</span>
                                            </Button>
                                        </TooltipTrigger>
                                        <TooltipContent side="top">
                                            Attach File
                                        </TooltipContent>
                                    </Tooltip>
                                </TooltipProvider>
                                <AudioRecording />
                                <Button type="submit" size="sm" class="ml-auto gap-1.5">
                                    Send Message
                                    <CornerDownLeft class="size-3.5" />
                                </Button>
                            </div>
                        </form>
                        <div v-else class="w-full">
                            <Card>
                                <CardContent class="p-6">
                                    <div class="flex items-center gap-2 ">
                                        <MailWarningIcon class="self-baseline" />
                                        <h1 class="font-medium ">
                                            <span>Sending messages is not available due to the cancellation of the
                                                job.</span>
                                        </h1>
                                    </div>
                                </CardContent>
                            </Card>
                        </div>
                    </div>
                </main>
            </CardContent>
        </Card>

        <!-- Messages Secion -->
        <Card class="col-span-12 md:col-span-4 md:order-2">
            <CardHeader class="text-xl font-semibold">
                Offer details
            </CardHeader>
            <CardContent>
                <section class="space-y-5">
                    <div class="job-info space-y-2">
                        <div>
                            <h5 class="job-name text-lg font-bold ">{{ job.data.title }}</h5>
                            <div class="flex items-center gap-4">
                                <span class="text-sm text-muted-foreground">Posted {{ job.data.createdAt }}</span>
                            </div>
                        </div>
                        <div>
                            <div class="job-duration flex items-center gap-2 mb-5">
                                <Timer class=" self-baseline size-5" />
                                <div>
                                    <p class="text-sm font-semibold">{{ job.data.proposal.duration }} / Day</p>
                                </div>
                            </div>
                            <div class="job-budget flex items-center gap-2">
                                <Tag class=" self-baseline size-5" />
                                <div>
                                    <p class="text-sm font-semibold">$ {{ job.data.proposal.bid }} </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <Separator />
                    <div class="job-skills text-primary  space-y-4">
                        <h1 class="text-xl font-semibold">Client</h1>
                        <div class="flex flex-wrap items-center gap-4 text-sm">
                            <div class="client-name">
                                <h1 class="text-lg font-medium">{{ job.data.user.name }}</h1>
                            </div>
                            <div class="flex items-center gap-2">
                                <Rating :disable-rating="true" :rate="job.data.user.rate" />
                                <span class="underline">{{ job.data.user.rate.toFixed(1) }}</span>
                            </div>
                            <div class="total-jobs">
                                <h1>{{ job.data.user.jobsCount }} jobs posted <span>
                                        ({{ job.data.user.jobsCompletionRate }}% hire rate) </span></h1>
                            </div>
                            <div class="location">
                                <h1>Canada</h1>
                            </div>
                            <div class="spent-amount">
                                <h1>$3.1K total spent</h1>
                            </div>
                            <div class="member-since">
                                <h1 class="text-sm">Member since <span class="memeber-since">{{
                                    job.data.user.createdAt
                                        }}</span>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <Separator />
                    <div class="project-deadline text-primary  space-y-4">
                        <h1 class="text-xl font-semibold">Job Deadline</h1>
                        <h1 class="flex items-center ">{{ deadline }}
                            <Dot /> {{ deadlineForHumans }}
                        </h1>
                    </div>
                </section>



            </CardContent>
        </Card>
    </div>
</template>

<script setup>
import Button from '@/components/ui/button/Button.vue';
import { Card } from '@/Components/ui/card';
import CardContent from '@/Components/ui/card/CardContent.vue';
import CardHeader from '@/Components/ui/card/CardHeader.vue';
import Separator from '@/components/ui/separator/Separator.vue';
import { Textarea } from '@/components/ui/textarea';
import MainLayout from '@/Layouts/MainLayout.vue';
import { CornerDownLeft, Dot, MailWarningIcon, Mic, Paperclip, Tag, Timer, User2 } from 'lucide-vue-next';
import Rating from '@/Components/Rating.vue';
import Label from '@/components/ui/label/Label.vue';
import TooltipProvider from '@/Components/ui/tooltip/TooltipProvider.vue';
import Tooltip from '@/Components/ui/tooltip/Tooltip.vue';
import TooltipTrigger from '@/Components/ui/tooltip/TooltipTrigger.vue';
import TooltipContent from '@/Components/ui/tooltip/TooltipContent.vue';
import { Avatar, AvatarImage } from '@/Components/ui/avatar';
import AvatarFallback from '@/Components/ui/avatar/AvatarFallback.vue';
import JobAlertDialog from '@/Components/JobAlertDialog.vue';
import { nextTick, onMounted, ref } from 'vue';
import { useJobRequestsStore } from '@/stores/JobRequests';
import CanceledJobSecion from '@/Components/CanceledJobSecion.vue';
import { router, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import { toast } from '@/Components/ui/toast';
import AudioRecording from '@/Components/AudioRecording.vue';
const jobRequestsStore = useJobRequestsStore();
const propsData = defineProps({
    job: Object,
    userReqeusts: Array,
    canSendRequest: Boolean,
    isJobCanceled: Boolean,
    hasUserRatedJob: Boolean,
    canUserRateJob: Boolean,
    deadline: String,
    deadlineForHumans: String,
});


const form = useForm({
    jobID: propsData.job.data.id,
    rate: null,
    description: '',
})

const userRatingEvent = (rate) => {
    form.rate = rate;
}

const submitRating = () => {
    form.post(route('rate.store'), {
        preserveScroll: true,
        onSuccess: () => {
            toast({
                title: 'user has been rated successfully.'
            });
        },
        onError: () => {
            toast({
                title: 'Something went wrong while trying to rate the user',
                variant: 'destructive',
            });
        },
    });
}



onMounted(() => {
    nextTick(() => {
        jobRequestsStore.job = propsData.job.data;
        jobRequestsStore.canSendRequest = propsData.canSendRequest;
        jobRequestsStore.userRequests = propsData.userReqeusts
    });
})

defineOptions({
    layout: MainLayout,
})
</script>