<template>
    <Toaster />
    <div class="job-create space-y-6">
        <div class="heading flex justify-between items-center">
            <h1 class="text-4xl "><span class="font-medium">{{ job.title }}</span> (proposals)</h1>
        </div>

        <Card class="job-tabs">
            <Tabs v-model="selectedTab" :default-value="selectedTab">
                <TabsList class="w-full justify-start items-center bg-transparent p-4">
                    <TabsTrigger @mousedown.prevent="switchTabs('all_proposals')" value="all_proposals"
                        class="relative text-xl data-[state=active]:text-primaryBtn">
                        All proposals ({{ jobProposalsCount }})

                        <div class="absolute  -bottom-1.5 left-0 border rounded-xl w-full  h-1 bg-primaryBtn  border-primaryBtn data-[state=active]:text-primaryBtn transition-all"
                            :class="{ 'opacity-0': selectedTab != 'all_proposals' }">
                        </div>
                    </TabsTrigger>
                    <TabsTrigger @mousedown.prevent="switchTabs('invites')" value="invites"
                        class="relative text-xl data-[state=active]:text-primaryBtn">
                        Sent invites

                        <div class="absolute  -bottom-1.5 left-0 border rounded-xl w-full h-1 bg-primaryBtn  border-primaryBtn data-[state=active]:text-primaryBtn transition-all"
                            :class="{ 'opacity-0': selectedTab != 'invites' }">
                        </div>
                    </TabsTrigger>
                </TabsList>



                <!-- Loading Indicator -->

                <Deferred data="jobProposals">
                    <template #fallback>
                        <div class="flex items-center justify-center p-6">
                            <Loader2 class="animate-spin size-8" />
                        </div>
                    </template>

                    <div v-if="!proposalStore.isOuterLoading">
                        <!-- Proposals -->
                        <TabsContent value="all_proposals">
                            <div v-if="jobProposals.data.length > 0" v-for="(proposal, index) in jobProposals.data"
                                :key="index" class="proposal-user-info px-4 py-8 hover:bg-primaryBtn/5  transition-all">
                                <div class="flex justify-between">
                                    <div class="user-details flex gap-4">
                                        <div class="user-avatar ">
                                            <Avatar class="h-14 w-14 rounded-full">
                                                <AvatarImage />
                                                <AvatarFallback class="rounded-lg">
                                                    {{ proposal.jobSeeker.name.slice(0, 2).toUpperCase() }}
                                                </AvatarFallback>
                                            </Avatar>
                                            <!-- Rating -->
                                            <!-- <Rating :disable-rating="true" :rate="2" /> -->
                                        </div>
                                        <div class="user-info space-y-1 font-semibold ">
                                            <h1 class="username font-bold text-primaryBtn underline">{{
                                                proposal.jobSeeker.name
                                                }}
                                            </h1>
                                            <h1 class="job-title ">{{ proposal.jobSeeker.jobTitle }}</h1>
                                            <h1 class="location text-sm text-muted-foreground ">Palestine</h1>

                                            <div class="proposal-info space-y-3 pt-2">
                                                <span class="offer-price">$ {{ proposal.bid }}</span>

                                                <p class=" font-medium"><span class="font-bold">Cover letter -</span>
                                                    {{ proposal.coverLetter }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="intercation flex justify-center items-center h-fit space-x-5">
                                        <Button @click.prevent="OpenProposalModal(proposal)" variant="outline"
                                            class="border border-primaryBtn px-8 ">
                                            View / Send Message
                                        </Button>

                                        <Button v-if="proposal.canInvite" @click.prevent="OpenInviteModal(proposal)"
                                            class="bg-primaryBtn">
                                            Invite to job
                                        </Button>
                                        <h1 v-else class="font-semibold text-primaryBtn h-fit">Already invited</h1>
                                    </div>

                                </div>


                            </div>

                            <div v-else>
                                <h1 class="flex justify-center items-center gap-2 font-bold p-32">
                                    <XCircle />
                                    No Proposals
                                </h1>
                            </div>
                        </TabsContent>


                        <!-- Invites -->
                        <TabsContent value="invites">
                            <div v-if="jobProposals.data.length > 0" v-for="(proposal, index) in jobProposals.data"
                                :key="index" class="proposal-user-info px-4 py-8 hover:bg-primaryBtn/5  transition-all">
                                <div class="flex justify-between">
                                    <div class="user-details flex gap-4">
                                        <div class="user-avatar ">
                                            <Avatar class="h-14 w-14 rounded-full">
                                                <AvatarImage />
                                                <AvatarFallback class="rounded-lg">
                                                    {{ proposal.jobSeeker.name.slice(0, 2).toUpperCase() }}
                                                </AvatarFallback>
                                            </Avatar>
                                            <!-- Rating -->
                                            <!-- <Rating :disable-rating="true" :rate="2" /> -->
                                        </div>
                                        <div class="user-info space-y-1 font-semibold ">
                                            <h1 class="username font-bold text-primaryBtn underline">{{
                                                proposal.jobSeeker.name
                                                }}
                                            </h1>
                                            <h1 class="speciality ">Fullstack Developer</h1>
                                            <h1 class="location text-sm text-muted-foreground ">Palestine</h1>

                                            <div class="proposal-info space-y-3 pt-2">
                                                <span class="offer-price">$ {{ proposal.bid }}</span>

                                                <p class=" font-medium"><span class="font-bold">Cover letter -</span>
                                                    {{ proposal.coverLetter }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <h1 class="font-semibold text-primaryBtn">{{ proposal.Status }}</h1>
                                    </div>
                                </div>


                            </div>

                            <div v-else>
                                <h1 class="flex justify-center items-center gap-2 font-bold p-32">
                                    <XCircle />
                                    No Proposals
                                </h1>
                            </div>
                        </TabsContent>
                    </div>
                    <!-- Loading indicator for switching tabs -->
                    <div v-if="proposalStore.isOuterLoading" class="flex justify-center items-center p-6">
                        <Loader2 class="animate-spin size-8" />
                    </div>
                </Deferred>
            </Tabs>


        </Card>
    </div>
    <ViewProposalModal v-if="isProposalModalOpened" :is-open="isProposalModalOpened" @Close="CloseProposalModal" />
    <InviteToJob v-if="isInviteModalOpened" :types="proposalTypes" :is-open="isInviteModalOpened"
        @Close="CloseProposalModal" />
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Button } from '@/components/ui/button';
import Toaster from '@/components/ui/toast/Toaster.vue';
import { onMounted, ref } from 'vue';
import { Deferred, router, usePage } from '@inertiajs/vue3';
import Tabs from '@/Components/ui/tabs/Tabs.vue';
import TabsList from '@/Components/ui/tabs/TabsList.vue';
import TabsTrigger from '@/Components/ui/tabs/TabsTrigger.vue';
import TabsContent from '@/Components/ui/tabs/TabsContent.vue';
import Avatar from '@/Components/ui/avatar/Avatar.vue';
import AvatarImage from '@/Components/ui/avatar/AvatarImage.vue';
import AvatarFallback from '@/Components/ui/avatar/AvatarFallback.vue';
import { Loader2, XCircle } from 'lucide-vue-next';
import ViewProposalModal from '@/Components/ViewProposalModal.vue';
import { useProposalStore } from '@/stores/ProposalStore';
import { toast } from '@/Components/ui/toast';
import { Card } from '@/Components/ui/card';
import InviteToJob from '@/Components/InviteToJob.vue';
const isProposalModalOpened = ref(false);
const isInviteModalOpened = ref(false);
const proposalStore = useProposalStore();
const page = usePage();
const selectedTab = ref('all_proposals');
const propsData = defineProps({
    job: Object,
    jobProposals: Array,
    proposalTypes: Object,
    selectedTabView: String,
    jobProposalsCount: Number,
})

const OpenProposalModal = (proposal) => {
    isProposalModalOpened.value = !isProposalModalOpened.value

    if (proposal.isProposalReviewed) {
        proposalStore.proposal = proposal;

    } else {
        proposalStore.isLoading = !proposalStore.isLoading;
        router.put(route('proposal.review.update', { proposal: proposal.uuid }), {}, {
            onSuccess: () => {
                proposalStore.isLoading = !proposalStore.isLoading;
                proposalStore.proposal = proposal;

            },
            onError: () => {
                proposalStore.isLoading = !proposalStore.isLoading;
                proposalStore.proposal = proposal;
                toast({
                    title: 'something went wrong while trying to view the proposal',
                    variant: 'destructive',
                });
            }
        })

    }
}

const switchTabs = (tab) => {
    selectedTab.value = tab
    proposalStore.isOuterLoading = true;
    router.visit(route('job.proposals', { job: propsData.job, type: selectedTab.value }), {
        preserveScroll: true,
        preserveState: true,
        only: ['jobProposals', 'selectedTabView'],
        onSuccess: () => {
            proposalStore.isOuterLoading = !proposalStore.isOuterLoading;
        }
    })
};


const OpenInviteModal = (proposal) => {
    isInviteModalOpened.value = !isInviteModalOpened.value
    proposalStore.proposal = proposal
}
const CloseProposalModal = () => {
    isProposalModalOpened.value = false;
    isInviteModalOpened.value = false;
    proposalStore.proposal = null;
}

onMounted(() => {
    propsData.selectedTabView ? selectedTab.value = propsData.selectedTabView : '';
})

defineOptions({
    layout: MainLayout,
})
</script>