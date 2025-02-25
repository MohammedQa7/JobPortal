<template>
    <Toaster />
    <div class="proposal-create space-y-6">
        <div class="heading flex justify-between items-center">
            <h1 class="text-4xl font-semibold">My proposals</h1>

        </div>

        <JobProposal :proposals="proposals" :proposals-count="proposalsCount" />

        <JobOffer :offers="offers" :offers-count="offersCount" />

        <JobOfferSent :sent-offers="sentOffers" :sent-offer-count="sentOffersCount" />

        <OnGoingJobs :jobs="onGoingJobs" :jobs-count="onGoingJobsCount" />
    </div>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import Toaster from '@/components/ui/toast/Toaster.vue';
import { router } from '@inertiajs/vue3';
import JobProposal from '@/Components/Jobs/JobProposal.vue';
import JobOffer from '@/Components/Jobs/JobOffer.vue';
import JobOfferSent from '@/Components/Jobs/JobOfferSent.vue';
import OnGoingJobs from '@/Components/Jobs/onGoingJobs.vue';
const propsData = defineProps({
    proposals: Array,
    offers: Array,
    sentOffers: Array,
    onGoingJobs: Array,
    proposalsCount: Number,
    offersCount: Number,
    sentOffersCount: Number,
    onGoingJobsCount: Number,
});

defineOptions({
    layout: MainLayout,
})
</script>