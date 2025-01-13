<template>
    <Toaster />
    <div class="job-create space-y-6">
        <div class="heading flex justify-between items-center">
            <h1 class="text-4xl font-semibold">Job proposals</h1>

        </div>

        <Card class="job-tabs ">
            <Tabs :default-value="selectedTab">
                <TabsList class="w-full justify-start items-center bg-transparent p-4">
                    <TabsTrigger @click.prevent="selectedTab = 'all_proposals'" value="all_proposals"
                        class="relative text-xl data-[state=active]:text-primaryBtn">
                        All proposals ({{ jobProposalsCount }})

                        <div class="absolute  -bottom-1.5 left-0 border rounded-xl w-full  h-1 bg-primaryBtn  border-primaryBtn data-[state=active]:text-primaryBtn transition-all"
                            :class="{ 'opacity-0': selectedTab != 'all_proposals' }">
                        </div>
                    </TabsTrigger>
                    <TabsTrigger @click.prevent="selectedTab = 'password'" value="password"
                        class="relative text-xl data-[state=active]:text-primaryBtn">
                        Password

                        <div class="absolute  -bottom-1.5 left-0 border rounded-xl w-full h-1 bg-primaryBtn  border-primaryBtn data-[state=active]:text-primaryBtn transition-all"
                            :class="{ 'opacity-0': selectedTab != 'password' }">
                        </div>
                    </TabsTrigger>
                </TabsList>
                <TabsContent value="all_proposals">
                    <div v-if="jobProposals.data.length > 0" v-for="(proposal, index) in jobProposals.data" :key="index"
                        class="proposal-user-info px-4 py-8 hover:bg-primaryBtn/5  transition-all">
                        <div class="flex justify-between">
                            <div class="user-details flex gap-4">
                                <div class="user-avatar ">
                                    <Avatar class="h-14 w-14 rounded-full">
                                        <AvatarImage />
                                        <AvatarFallback class="rounded-lg">
                                            {{ proposal.user.name.slice(0, 2).toUpperCase() }}
                                        </AvatarFallback>
                                    </Avatar>
                                    <!-- Rating -->
                                    <!-- <Rating :disable-rating="true" :rate="2" /> -->
                                </div>
                                <div class="user-info space-y-1 font-semibold ">
                                    <h1 class="username font-bold text-primaryBtn underline">{{ proposal.user.name }}
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
                            <div class="intercation flex space-x-5">
                                <Button @click.prevent="OpenProposalModal(proposal)" variant="outline"
                                    class="border border-primaryBtn px-8 ">
                                    View / Send Message
                                </Button>
                                <Button class="bg-primaryBtn px-8 ">
                                    Invite to job
                                </Button>
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


                <TabsContent value="password">
                    Change your password here.
                </TabsContent>
            </Tabs>
        </Card>
    </div>
    <ViewProposalModal v-if="isProposalModalOpened" :is-open="isProposalModalOpened" @Close="CloseProposalModal" />
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Button } from '@/components/ui/button';
import Toaster from '@/components/ui/toast/Toaster.vue';
import { ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import Tabs from '@/Components/ui/tabs/Tabs.vue';
import TabsList from '@/Components/ui/tabs/TabsList.vue';
import TabsTrigger from '@/Components/ui/tabs/TabsTrigger.vue';
import TabsContent from '@/Components/ui/tabs/TabsContent.vue';
import Avatar from '@/Components/ui/avatar/Avatar.vue';
import AvatarImage from '@/Components/ui/avatar/AvatarImage.vue';
import AvatarFallback from '@/Components/ui/avatar/AvatarFallback.vue';
import { XCircle } from 'lucide-vue-next';
import ViewProposalModal from '@/Components/ViewProposalModal.vue';
import { useProposalStore } from '@/stores/ProposalStore';
import { toast } from '@/Components/ui/toast';
import { Card } from '@/Components/ui/card';
const isProposalModalOpened = ref(false);
const proposalStore = useProposalStore();
const page = usePage();
const selectedTab = ref('all_proposals');
const propsData = defineProps({
    jobProposals: Array,
    jobProposalsCount: Number,
})

const OpenProposalModal = (proposal) => {
    isProposalModalOpened.value = !isProposalModalOpened.value
    proposalStore.isLoading = !proposalStore.isLoading;
    if (proposal.isProposalReviewed) {
        console.log(proposal.isProposalReviewed);

        proposalStore.proposal = proposal;
    } else {
        router.put(route('proposal.review.update', { proposal: proposal.uuid }), { isReviewed: true }, {
            onSuccess: () => {
                proposalStore.isLoading = !proposalStore.isLoading;
                proposalStore.proposal = proposal;
            },
            onError: () => {
                proposalStore.isLoading = !proposalStore.isLoading;
                toast({
                    title: 'something went wrong while trying to view the proposal',
                    variant: 'destructive',
                });
            }
        })

    }




}
const CloseProposalModal = () => {
    isProposalModalOpened.value = false
    proposalStore.proposal = null;
}



defineOptions({
    layout: MainLayout,
})
</script>