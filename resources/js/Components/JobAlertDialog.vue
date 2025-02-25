<template>
    <Toaster />
    <AlertDialog v-if="jobRequestsStore.canSendRequest">
        <AlertDialogTrigger>
            <Button class="bg-primaryBtn w-full">
                Submit job completion
            </Button>
        </AlertDialogTrigger>
        <AlertDialogContent v-if="!isJobSubmitted">
            <AlertDialogHeader>
                <AlertDialogTitle>Did you provide all the needed files?</AlertDialogTitle>
                <AlertDialogDescription>
                    This setup cannot be undone.
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel>Cancel</AlertDialogCancel>
                <Button @click.prevent="submit" :disabled="form.processing">Procced</Button>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>

    <!-- Seeker Reqeusts  ()  -->
    <JobRequestsDialog v-else-if="jobRequestsStore.job.user.id == page.props.auth.user.id" />
    <!--  -->
    <AlertDialog v-if="jobRequestsStore.canSendRequest || jobRequestsStore.job.user.id == page.props.auth.user.id">
        <AlertDialogTrigger>
            <Button variant="outline">
                Cancel Job
            </Button>
        </AlertDialogTrigger>
        <AlertDialogContent v-if="!isJobSubmitted">
            <AlertDialogHeader>
                <AlertDialogTitle>Are you absolutely sure you want to cancel the job?</AlertDialogTitle>
                <AlertDialogDescription>
                    Reasons why you need to cancel the job
                    <Textarea class="mt-3" v-model="form.message" />
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel>Cancel</AlertDialogCancel>
                <Button @click.prevent="submitCancelRequest" :disabled="form.processing">Continue</Button>
            </AlertDialogFooter>
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
import AlertDialogDescription from '@/Components/ui/alert-dialog/AlertDialogDescription.vue';
import AlertDialogFooter from '@/Components/ui/alert-dialog/AlertDialogFooter.vue';
import AlertDialogCancel from '@/Components/ui/alert-dialog/AlertDialogCancel.vue';
import AlertDialogAction from '@/Components/ui/alert-dialog/AlertDialogAction.vue';
import Textarea from './ui/textarea/Textarea.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import Toaster from './ui/toast/Toaster.vue';
import { toast } from './ui/toast';
import { nextTick, onMounted, ref } from 'vue';
import JobRequestsDialog from './JobRequestsDialog.vue';
import { useJobRequestsStore } from '@/stores/JobRequests';
const page = usePage();
const jobRequestsStore = useJobRequestsStore();
const isJobSubmitted = ref(false);



const form = useForm({
    job: jobRequestsStore.job.slug,
    proposal: jobRequestsStore.job.proposal.uuid,
    message: null,
});



const submit = () => {
    form.post(route('submit.job.request'), {
        preserveState: true,
        onSuccess: () => {
            isJobSubmitted.value = true;
            toast({
                title: 'Job was completed, both of you will be notifed.'
            })
        },
        onError: () => {
            toast({
                title: 'Something went wrong while trying to sumbit the job.',
                description: ' Please try again, if the problem presisted contact the support as fast as possible.'
            });
        },
    })
}


const submitCancelRequest = () => {

    form.post(route('cancel.job.request'), {
        preserveState: true,
        onSuccess: () => {
            isJobSubmitted.value = true;
            toast({
                title: 'Job has been canceled, both of you will be notifed.'
            })
        },
        onError: () => {
            toast({
                title: 'Something went wrong while trying to cancel the job.',
                description: ' Please try again, if the problem presisted contact the support as fast as possible.'
            });
        }
    })
}
</script>