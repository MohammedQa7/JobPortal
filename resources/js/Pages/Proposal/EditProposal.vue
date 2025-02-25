<template>
    <Toaster />

    <div class="proposal-create space-y-6 " :class="{ 'pb-40': isMobile }">
        <WarningProposalSection v-if="proposal.data.status == 'insufficient funds'" />
        <div v-if="!isMobile && canUserEditProposal" class="heading flex justify-between items-center">
            <h1 class="text-4xl font-semibold">Proposal details</h1>
            <div v-if="!isEditing" class="editing-proposal-btn  hidden sm:flex flex-col gap-3 w-72  ">
                <Button @click.prevent="isEditing = !isEditing" class="bg-primaryBtn w-full"
                    :disabled="!proposal.data.canEdit">
                    Edit Proposal
                </Button>
                <Button variant="outline" :disabled="!proposal.data.canEdit">
                    Withdraw Proposal
                </Button>
                <h1 class="flex items-center gap-2 text-sm text-muted-foreground">
                    <Info class="self-baseline" />
                    Editing is available for 1 hour, Or if the client did not reviewed your proposal.
                </h1>
            </div>

            <div v-else class="editing-proposal-btn  hidden sm:flex flex-col gap-3 w-72  ">
                <Button @click.prevent="updateProposal" class="bg-primaryBtn w-full" :disabled="form.processing">
                    Save changes
                </Button>
                <Button @click.prevent="isEditing = !isEditing" variant="outline">
                    Cancel
                </Button>
            </div>
        </div>

        <!-- job Description Section-->
        <Card class="space-y-3">
            <CardHeader class="text-xl font-semibold">
                Job details
            </CardHeader>

            <CardContent>
                <section class="space-y-5">
                    <div class="job-info grid grid-cols-12 gap-2">
                        <div class="job-info space-y-3 col-span-12 md:col-span-10">
                            <h5 class="job-name text-lg font-bold ">{{ proposal.data.job.title }}</h5>

                            <div class="flex items-center gap-4">
                                <Badge
                                    class="px-3.5 py-1 bg-muted-foreground/20 text-muted-foreground hover:text-muted">
                                    Category Name</Badge>

                                <span class="text-sm text-muted-foreground">Posted {{ proposal.data.job.createdAt
                                    }}</span>
                            </div>

                            <div class="job-desc">
                                <p>
                                    {{ proposal.data.job.description }}

                                </p>
                            </div>


                        </div>
                        <div class="hidden md:col-span-2 md:block  relative space-x-5">
                            <Separator orientation="vertical" class=" absolute" />
                            <div class="job-duration flex items-center gap-2 mb-5">
                                <Timer class=" self-baseline size-5" />
                                <div>
                                    <p class="text-sm font-semibold">{{ proposal.data.job.duration }} / Day</p>
                                </div>
                            </div>
                            <div class="job-budget flex items-center gap-2">
                                <Tag class=" self-baseline size-5" />
                                <div>
                                    <p class="text-sm font-semibold">$ {{ proposal.data.job.budget }} </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <Separator />

                    <div class="job-skills text-primary  space-y-4">
                        <h1 class="text-xl font-semibold">Skills and Expertise:</h1>

                        <div class="flex flex-wrap gap-3">
                            <Badge v-for="(skill, index) in proposal.data.job.skills" :key="index"
                                class="px-3.5 py-1 bg-muted-foreground/20 text-muted-foreground hover:text-muted">
                                {{ skill }}</Badge>
                        </div>
                    </div>
                </section>



            </CardContent>
        </Card>

        <form class="space-y-6" :class="{ 'pb-32': !canUserEditProposal, '!pb-0': isMobile }">
            <!-- Terms & Bid Section-->
            <Card class="space-y-3">
                <CardHeader class="text-xl font-semibold">
                    Terms
                </CardHeader>

                <CardContent>
                    <section class="space-y-5">
                        <h1 class=" font-bold">
                            What is the full amount you'd like to bid for this proposal?

                        </h1>
                        <Separator />

                        <div class="bid-secrion grid grid-cols-12 space-y-5">
                            <div class="bid col-span-12 md:col-span-8  space-y-6">
                                <div class="bid-field flex justify-between items-center">
                                    <div class="w-1/2 space-y-2">
                                        <h1 class="font-semibold">Bid</h1>
                                        <h2 class="text-muted-foreground">Total amount the client will see on your
                                            proposal</h2>
                                    </div>
                                    <div class="bid-input ">
                                        <div v-if="isEditing" class="relative  items-center">
                                            <Input id="bid" type="text" v-model="form.bid" class="pl-10"
                                                @input="CalculateBidFees" />
                                            <span
                                                class="absolute start-0 inset-y-0 flex items-center justify-center px-4">
                                                $
                                            </span>
                                        </div>
                                        <div v-else class="relative  items-center">
                                            <h1 class="font-semibold">$ {{ proposal.data.bid }} </h1>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="isEditing" class="tax flex justify-between items-center">
                                    <div class="w-1/2 space-y-2">
                                        <h1 class="font-semibold">{{ tax * 100 }}% Fee</h1>
                                    </div>
                                    <div class="tax-input ">
                                        <div class="relative  items-center">
                                            <Input id="tax" type="text" class="pl-10" v-model="deductedAmount"
                                                disabled />
                                            <span
                                                class="absolute start-0 inset-y-0 flex items-center justify-center px-4">
                                                $
                                            </span>
                                        </div>
                                    </div>
                                </div>


                                <div v-if="!canUserEditProposal"
                                    class="recieve-from-bid  flex justify-between items-center">
                                    <div class="w-1/2 space-y-2">
                                        <h1 class="font-semibold">You’ll Receive</h1>
                                        <h2 class="text-muted-foreground">The estimated amount you'll receive after
                                            service
                                            fees </h2>
                                    </div>
                                    <div class="bid-input ">
                                        <div v-if="isEditing" class="relative  items-center">
                                            <Input id="received_amount" type="text" v-model="receivedAmount" disabled
                                                class="pl-10" />
                                            <span
                                                class="absolute start-0 inset-y-0 flex items-center justify-center px-4">
                                                $
                                            </span>
                                        </div>
                                        <div v-else class="relative  items-center">
                                            <h1 class="font-semibold">$ {{ receivedAmount }} </h1>
                                        </div>
                                    </div>
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

                        <div v-if="isEditing" class="cover-letter-secrion  w-1/4">
                            <Input v-model="form.duration" placeholder="Project duration in 'Days'" />
                        </div>
                        <div v-else class="cover-letter-secrion  w-1/4">
                            <h1 class="font-semibold">{{ proposal.data.duration }} /Day</h1>
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

                        <div v-if="isEditing" class="cover-letter-secrion ">
                            <Textarea v-model="form.coverLetter" class="min-h-[200px]" />
                        </div>
                        <div v-else class="cover-letter-secrion ">
                            <p>{{ proposal.data.coverLetter }}</p>
                        </div>

                        <InputError :message="form.errors.coverLetter" ref="errorRefs" />
                    </section>

                </CardContent>
            </Card>




            <div v-if="isMobile && canUserEditProposal"
                class="edit-proposal-responsive w-full fixed bottom-0 right-0 bg-white/20 backdrop-blur-lg px-6 py-8 space-y-4 border">
                <div v-if="!isEditing" class="space-y-4">
                    <h1 class="flex items-center gap-2 text-sm text-muted-foreground">
                        <Info class="self-baseline" />
                        Editing is available for 1 hour, Or if the client did not reviewed your proposal.
                    </h1>
                    <div class="flex gap-2 items-center">
                        <Button @click.prevent="isEditing = !isEditing" class="bg-primaryBtn w-full"
                            :disabled="!proposal.data.canEdit">
                            Edit Proposal
                        </Button>
                        <Button variant="outline" class="w-full" :disabled="!proposal.data.canEdit">
                            Withdraw Proposal
                        </Button>
                    </div>
                </div>
                <div v-else class="editing-proposal-btn flex gap-2 items-center">
                    <Button @click.prevent="updateProposal" class="bg-primaryBtn w-full" :disabled="form.processing">
                        Save changes
                    </Button>
                    <Button @click.prevent=" isEditing = !isEditing" variant="outline" class="w-full">
                        Cancel
                    </Button>
                </div>
            </div>
        </form>



        <div v-if="!canUserEditProposal && proposal.data.status == 'Pending'"
            class="edit-proposal-responsive w-full fixed bottom-0 right-0 bg-white/20 backdrop-blur-lg px-6 py-8 space-y-4 border ">
            <div class="space-y-4">
                <div class="flex gap-2 items-center">

                    <Button @click.prevent="acceptProposal" class="bg-primaryBtn w-full text-primary-foreground">
                        Accept
                    </Button>
                    <Button @click.prevent="declineProposal" variant="outline"
                        class="w-full hover:bg-destructive hover:text-primary-foreground">
                        Decline
                    </Button>
                </div>
            </div>
        </div>


    </div>
</template>

<script setup>
import InputError from '@/Components/InputError.vue';
import { Badge } from '@/Components/ui/badge';
import Button from '@/components/ui/button/Button.vue';
import { Card } from '@/Components/ui/card';
import CardContent from '@/Components/ui/card/CardContent.vue';
import CardHeader from '@/Components/ui/card/CardHeader.vue';
import { Input } from '@/components/ui/input';
import Separator from '@/components/ui/separator/Separator.vue';
import { Textarea } from '@/components/ui/textarea';
import MainLayout from '@/Layouts/MainLayout.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { BadgePercent, Info, Tag, Timer } from 'lucide-vue-next';
import { computed, nextTick, onMounted, ref, watch } from 'vue';
import Toaster from '@/components/ui/toast/Toaster.vue';
import { useToast } from '@/components/ui/toast/use-toast'
import { useMediaQuery } from '@vueuse/core';
import { calculateBidFees } from '@/Composable/caculateFees';
import WarningProposalSection from '@/Components/WarningProposalSection.vue';
const { toast } = useToast();
const page = usePage();
const errorRefs = ref([]);
const deductedAmount = ref(null);
const receivedAmount = ref(null);
const isEditing = ref(false);
const isMobile = useMediaQuery('(max-width: 767px)');
const propsData = defineProps({
    proposal: Object,
    proposalTypes: Object,
    successSession: Boolean,
    tax: Number,
});
const form = useForm({
    job: null,
    bid: null,
    duration: null,
    coverLetter: '',
})
watch(form, () => {
    getBidFeed();
})

const canUserEditProposal = computed(() => {

    if (propsData.proposal.data.type == propsData.proposalTypes.offer) {

        return propsData.proposal.data.client.id == page.props.auth.user.id;

    } else {

        return propsData.proposal.data.jobSeeker.id == page.props.auth.user.id;
    }
})

const getBidFeed = () => {
    if (!isNaN(form.bid)) {
        const fees = calculateBidFees(form.bid, propsData.tax, receivedAmount.value, deductedAmount.value);
        receivedAmount.value = fees.receivedAmount;
        deductedAmount.value = fees.deductedAmount;
    }
}


const bindProposalData = () => {
    Object.assign(form, propsData.proposal.data);
    // passing the projectID not the whole project data
    form.job = propsData.proposal.data.job.id;
}

const updateProposal = () => {
    if (propsData.proposal.data.canEdit) {
        form.put(route('proposal.update', { proposal: propsData.proposal.data.uuid }), {
            onSuccess: () => {
                isEditing.value = !isEditing.value
                toast({
                    title: 'Changes saved successfully',
                })
            },
            onError: () => {
                toast({
                    title: 'Something went wrong while trying to update proposal',
                    variant: 'destructive',
                })
            }
        })
    }
}


const acceptProposal = () => {
    router.put(route('proposal.accept', { proposal: propsData.proposal.data.uuid }), {}, {
        onSuccess: () => {
            toast({
                title: 'Offer has been accepted, You can now start working on the job/project'
            })
        },
        onError: () => {
            toast({
                title: 'Something went wrong while trying to accept the offer',
                variant: 'destructive'
            })
        }
    })
}
const declineProposal = () => {
    router.put(route('proposal.decline', { proposal: propsData.proposal.data.uuid }), {}, {
        onSuccess: () => {
            toast({
                title: 'Offer has been declined.'
            })
        },
        onError: () => {
            toast({
                title: 'Something went wrong while trying to decline the offer',
                variant: 'destructive'
            })
        }
    })
}

onMounted(() => {
    bindProposalData();
    getBidFeed();
    if (propsData.successSession) {
        toast({
            title: 'Proposal has been submited',
            description: 'Go to your proposals page in order to edit or cancel the proposal',
        });
    }
})
defineOptions({
    layout: MainLayout,
})
</script>