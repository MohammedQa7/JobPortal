<template>
    <div class="proposal-create space-y-6">
        <Collapsible v-model:open="isSentOfferProposalOpen">
            <Card class="space-y-3">
                <CollapsibleTrigger class="w-full">
                    <CardHeader class="text-left text-xl font-semibold ">
                        Sent offers ({{ sentOfferCount }})
                    </CardHeader>
                </CollapsibleTrigger>

                <CollapsibleContent>
                    <CardContent>
                        <div v-for="(offer, index) in sentOffers.data" :key="index"
                            class="offer-info flex flex-col justify-between gap-5 md:flex-row md:items-center md:gap-28 mb-10">
                            <div class="flex flex-col gap-5 md:flex-row md:items-center md:gap-28">
                                <div class="offer-dates  order-2 md:order-1">
                                    <h1 class="">{{ offer.createdAt }}</h1>
                                    <h2 class="text-sm text-muted-forground">{{ offer.formatedCreatedAt }}</h2>
                                </div>

                                <div
                                    class="offer-job-name text-lg font-semibold !text-primaryBtn underline order-1 md:order-1">
                                    <Link :href="route('proposal.show', { proposal: offer.uuid })">

                                    <h1>{{ offer.job.title }}</h1>
                                    </Link>
                                </div>
                            </div>
                            <div class=" font-semibold !text-primaryBtn order-3 md:order-3">
                                <span class="text-semibold">{{ offer.status }}</span>
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
import Button from '@/components/ui/button/Button.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
const isSentOfferProposalOpen = ref(true);
const propsData = defineProps({
    sentOffers: Array,
    sentOfferCount: Number,
});


</script>