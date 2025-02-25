<template>
    <div class="proposal-create space-y-6">
        <Collapsible v-model:open="isSubmitedProposalOpend">
            <Card class="space-y-3">
                <CollapsibleTrigger class="w-full">
                    <CardHeader class="text-left text-xl font-semibold ">
                        Submited proposals ({{ proposalsCount }})
                    </CardHeader>
                </CollapsibleTrigger>

                <CollapsibleContent>
                    <CardContent>
                        <div v-for="(proposal, index) in proposals.data" :key="index"
                            class="proposal-info flex flex-col justify-between gap-5 md:flex-row md:items-center md:gap-28 mb-10">
                            <div class="flex flex-col gap-5 md:flex-row md:items-center md:gap-28">
                                <div class="proposal-dates  order-2 md:order-1">
                                    <h1 class="">{{ proposal.createdAt }}</h1>
                                    <h2 class="text-sm text-muted-forground">{{ proposal.formatedCreatedAt }}</h2>
                                </div>

                                <div
                                    class="proposal-job-name text-lg font-semibold !text-primaryBtn underline order-1 md:order-1">
                                    <Link :href="route('proposal.show', { proposal: proposal.uuid })">

                                    <h1>{{ proposal.job.title }}</h1>
                                    </Link>
                                </div>
                            </div>
                            <div class="proposal-job-name font-semibold !text-primaryBtn order-3 md:order-3">
                                <span v-if="!proposal.isProposalReviewed" class="text-semibold">Not reviewd</span>
                                <span v-else class="text-semibold">Reviewed by client</span>
                            </div>
                        </div>
                    </CardContent>
                </CollapsibleContent>
            </Card>
        </Collapsible>
    </div>
</template>

<script setup>
import Card from '@/Components/ui/card/Card.vue';
import CardContent from '@/Components/ui/card/CardContent.vue';
import CardHeader from '@/Components/ui/card/CardHeader.vue';
import Collapsible from '@/Components/ui/collapsible/Collapsible.vue';
import CollapsibleContent from '@/Components/ui/collapsible/CollapsibleContent.vue';
import CollapsibleTrigger from '@/Components/ui/collapsible/CollapsibleTrigger.vue';
import { ref } from 'vue';
const isSubmitedProposalOpend = ref(true);
const propsData = defineProps({
    proposals: Array,
    proposalsCount: Number,
});

</script>