<template>
    <Dialog :open="isOpen">
        <DialogContent :hide-close-button="true">
            <DialogHeader>
                <DialogTitle>Send offer as the job seeker requested ?</DialogTitle>
                <DialogDescription>
                    You can edit the proposal unless the job seeker reviews your proposal or the time limit of the
                    Editing has reached (which is 1 hour after submitting the offer/proposal).
                </DialogDescription>
            </DialogHeader>

            <DialogFooter>
                <Button @click.prevent="createProposal" variant="outline">
                    Confirm and Send
                </Button>
                <Dialog>
                    <DialogTrigger>
                        <Button @click.prevent="form.reset()" class="bg-primaryBtn">
                            Make new offer
                        </Button>
                    </DialogTrigger>
                    <DialogContent>
                        <DialogHeader>
                            <DialogTitle>Send invite offer</DialogTitle>
                        </DialogHeader>
                        <div class="h-96 overflow-y-auto ">
                            <form @submit.prevent="createProposal" class="space-y-6">
                                <!-- Terms & Bid Section-->
                                <Card>
                                    <CardHeader class="text-xl font-semibold">
                                        Terms
                                    </CardHeader>

                                    <CardContent>
                                        <section class="space-y-5">
                                            <h1 class=" font-bold">
                                                What is the full amount you'd like to bid for this job?
                                            </h1>
                                            <Separator />
                                            <div class="bid-secrion space-y-5">
                                                <div class="w-full space-y-2">
                                                    <h1 class="font-semibold">Bid</h1>
                                                    <h2 class="text-muted-foreground">Total amount the client
                                                        will
                                                        see on your
                                                        proposal</h2>
                                                </div>
                                                <div class="bid-input ">
                                                    <div class="relative  items-center">
                                                        <Input id="bid" type="text" v-model="form.bid" class="pl-10" />
                                                        <span
                                                            class="absolute start-0 inset-y-0 flex items-center justify-center px-4">
                                                            $
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <InputError :message="form.errors.bid" />
                                        </section>

                                    </CardContent>
                                </Card>

                                <!-- Project Duration input section -->
                                <Card class="space-y-3 pt-4">
                                    <CardContent>
                                        <section class="space-y-3">
                                            <h1 class="font-bold">
                                                How long will this project take?
                                            </h1>

                                            <div class="cover-letter-secrion w-full ">
                                                <Input v-model="form.duration"
                                                    placeholder="Project duration in 'Days'" />
                                            </div>
                                            <InputError :message="form.errors.duration" />
                                        </section>
                                    </CardContent>
                                </Card>

                                <!-- Cover Letter Section -->
                                <Card class="space-y-3">
                                    <CardHeader class="text-xl font-semibold">
                                        Additional details
                                    </CardHeader>

                                    <CardContent>
                                        <section class="space-y-3">
                                            <h1 class="font-bold">
                                                Cover Letter
                                            </h1>

                                            <div class="cover-letter-secrion ">
                                                <Textarea v-model="form.coverLetter" class="min-h-[200px]" />
                                            </div>

                                            <InputError :message="form.errors.coverLetter" />
                                        </section>

                                    </CardContent>
                                </Card>

                                <div class="submit-btn ">
                                    <DialogFooter>
                                        <Button type="submit" class="bg-primaryBtn" :disabled="form.processing">
                                            Submit proposal
                                        </Button>
                                    </DialogFooter>
                                </div>
                            </form>
                        </div>

                    </DialogContent>
                </Dialog>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

<script setup>
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import Toaster from '@/components/ui/toast/Toaster.vue';
import { toast } from '@/Components/ui/toast';
import InputError from './InputError.vue';
import Card from './ui/card/Card.vue';
import CardHeader from './ui/card/CardHeader.vue';
import CardContent from './ui/card/CardContent.vue';
import Textarea from './ui/textarea/Textarea.vue';
import Separator from './ui/separator/Separator.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import Input from './ui/input/Input.vue';
import { onMounted, onUnmounted } from 'vue';
import { closeDialog } from '@/Composable/closeDialog';
import { useProposalStore } from '@/stores/ProposalStore';
const emit = defineEmits();
const page = usePage();
const proposalStore = useProposalStore();
const propsData = defineProps({
    isOpen: Boolean,
    types: Object,
});
const form = useForm({
    job: null,
    bid: 5,
    duration: null,
    coverLetter: '',
    type: propsData.types.offer,
    jobSeekerID: null,
})

const createProposal = (e, isNew = false) => {
    isNew
        ? form.reset()
        : bindProposalData();

    form.post(route('proposal.store'), {
        onError: () => {
            toast({
                title: 'Something went wrong, please try again',
                variant: 'destructive',
            });
        }
    });
}


const bindProposalData = () => {
    Object.assign(form, proposalStore.proposal);
    // passing the jobID not the whole job data
    form.job = proposalStore.proposal.job.id;
    form.type = propsData.types.offer;
    form.jobSeekerID = proposalStore.proposal.jobSeeker.id;
}

const CloseProposalDialog = () => {
    closeDialog(emit);
}
const CloseOnKeydown = (event) => {
    if (event.key == 'Escape') {
        CloseProposalDialog();
    }
}

onMounted(() => {
    bindProposalData();
    document.addEventListener('keydown', CloseOnKeydown)
})

onUnmounted(() => {
    document.removeEventListener('keydown', CloseOnKeydown);
})
</script>
