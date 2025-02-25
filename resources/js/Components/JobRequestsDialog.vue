<template>
    <Toaster />
    <AlertDialog>
        <AlertDialogTrigger>
            <Button class="bg-primaryBtn w-full">
                View Requests
            </Button>
        </AlertDialogTrigger>
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>job seeker reqeusts</AlertDialogTitle>
            </AlertDialogHeader>
            <div v-for="(request, index) in jobRequestsStore.userRequests.data" :key="index" class="space-y-4">
                <Card>
                    <CardHeader>
                        <h1>{{ request.user.name }} wants to
                            <span class="uppercase font-bold">{{ request.type }}</span>
                            the job
                        </h1>
                    </CardHeader>
                    <CardContent class="space-y-3">

                        <div class="reason-message">
                            <h1 class="font-semibold">Reason for the {{ request.type }}</h1>
                            <p class="text-sm">{{ request.message }}</p>
                        </div>
                    </CardContent>
                </Card>
                <AlertDialogFooter>
                    <AlertDialogCancel @click.prevent="declineJobReqeust(request.id)" :disabled="reqeustProcessing">
                        Decline
                    </AlertDialogCancel>
                    <AlertDialogAction @click.prevent="acceptJobReqeust(request.id)" :disabled="reqeustProcessing">
                        Accept</AlertDialogAction>
                </AlertDialogFooter>
            </div>
        </AlertDialogContent>
    </AlertDialog>
</template>

<script setup>
import Button from '@/components/ui/button/Button.vue';
import AlertDialog from '@/Components/ui/alert-dialog/AlertDialog.vue';
import AlertDialogTrigger from '@/Components/ui/alert-dialog/AlertDialogTrigger.vue';
import AlertDialogContent from '@/Components/ui/alert-dialog/AlertDialogContent.vue';
import AlertDialogHeader from '@/Components/ui/alert-dialog/AlertDialogHeader.vue';
import AlertDialogTitle from '@/Components/ui/alert-dialog/AlertDialogTitle.vue';
import AlertDialogFooter from '@/Components/ui/alert-dialog/AlertDialogFooter.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import Toaster from './ui/toast/Toaster.vue';
import { ref } from 'vue';
import { useJobRequestsStore } from '@/stores/JobRequests';
import Card from './ui/card/Card.vue';
import CardContent from './ui/card/CardContent.vue';
import CardHeader from './ui/card/CardHeader.vue';
import { toast } from './ui/toast';
import AlertDialogAction from './ui/alert-dialog/AlertDialogAction.vue';
import AlertDialogCancel from './ui/alert-dialog/AlertDialogCancel.vue';
const jobRequestsStore = useJobRequestsStore();
const reqeustProcessing = ref(false);

const acceptJobReqeust = (requestID) => {
    reqeustProcessing.value = true;
    router.put(route('accept.job.request'), { jobRequestID: requestID }, {
        onSuccess: () => {
            reqeustProcessing.value = false;
            toast({
                title: 'Job Reqeust has been accepted, both of you will be notifed.'
            })
        },
        onError: () => {
            reqeustProcessing.value = false;
            toast({
                title: 'Something went wrong while trying to cancel the job.',
                description: ' Please try again, if the problem presisted contact the support as fast as possible.'
            });
        }
    })
}

const declineJobReqeust = (requestID) => {
    reqeustProcessing.value = true;
    router.put(route('decline.job.request'), { jobRequestID: requestID }, {
        onSuccess: () => {
            reqeustProcessing.value = false;
            toast({
                title: "You have declined seeker's request, both of you will be notifed."
            })
        },
        onError: () => {
            reqeustProcessing.value = false;
            toast({
                title: 'Something went wrong while trying to cancel the job.',
                description: ' Please try again, if the problem presisted contact the support as fast as possible.'
            });
        }
    })
}

</script>