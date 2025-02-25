<template>
    <Toaster />
    <div class="job-create space-y-6">
        <div class="heading flex justify-between items-center">
            <h1 class="text-4xl font-semibold">My jobs</h1>

        </div>

        <!-- Job Description Section-->
        <Collapsible v-model:open="isSubmitedjobOpend">
            <Card class="space-y-3">
                <CollapsibleTrigger class="w-full">
                    <CardHeader class="text-left text-xl font-semibold ">
                        Submited jobs ({{ jobsCount }})
                    </CardHeader>
                </CollapsibleTrigger>

                <CollapsibleContent>
                    <CardContent>
                        <div v-for="(job, index) in jobs.data" :key="index"
                            class="job-info flex flex-col justify-between gap-5 md:flex-row md:items-center md:gap-28 mb-8">
                            <div class="flex flex-col gap-5 md:flex-row   items-center ">
                                <div class="job-dates order-2 md:order-1 ">
                                    <h1 class="">{{ job.createdAt }}</h1>
                                    <h2 class="text-sm text-muted-forground">{{ job.formatedCreatedAt }}</h2>
                                </div>

                                <div
                                    class="job-project-name text-lg font-semibold !text-primaryBtn underline order-1 md:order-1">
                                    <Link @click.prevent="OpenJobDrawer(job.slug)">
                                    <h1 class="items-baseline">{{ job.title }}</h1>
                                    </Link>
                                </div>
                            </div>
                            <div
                                class="job-project-actions flex items-center gap-4 font-semibold !text-primaryBtn order-3 md:order-3">
                                <Link :href="route('job.proposals', { job: job.slug })">
                                <Button variant="outline"> Proposals</Button>
                                </Link>

                                <div class="dropdown">
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <Button variant="outline">
                                                <MoreVertical />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent>
                                            <DropdownMenuLabel>My Account</DropdownMenuLabel>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuItem>Profile</DropdownMenuItem>
                                            <DropdownMenuItem>Billing</DropdownMenuItem>
                                            <DropdownMenuItem>Team</DropdownMenuItem>
                                            <DropdownMenuItem>Subscription</DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </div>
                            </div>

                        </div>
                    </CardContent>
                </CollapsibleContent>
            </Card>
        </Collapsible>
    </div>
    <SingleJobDrawer v-if="isJobDrawerOpend" :singleJob="singleJob?.data" :is-open="isJobDrawerOpend"
        @Close="CloseJobDrawer" />
</template>

<script setup>
import Card from '@/Components/ui/card/Card.vue';
import CardContent from '@/Components/ui/card/CardContent.vue';
import CardHeader from '@/Components/ui/card/CardHeader.vue';
import Collapsible from '@/Components/ui/collapsible/Collapsible.vue';
import CollapsibleContent from '@/Components/ui/collapsible/CollapsibleContent.vue';
import CollapsibleTrigger from '@/Components/ui/collapsible/CollapsibleTrigger.vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { Button } from '@/components/ui/button';
import Toaster from '@/components/ui/toast/Toaster.vue';
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import SingleJobDrawer from '@/Components/SingleJobDrawer.vue';
import { useJobsStore } from '@/stores/JobStore';
import DropdownMenu from '@/Components/ui/dropdown-menu/DropdownMenu.vue';
import DropdownMenuTrigger from '@/Components/ui/dropdown-menu/DropdownMenuTrigger.vue';
import DropdownMenuContent from '@/Components/ui/dropdown-menu/DropdownMenuContent.vue';
import DropdownMenuLabel from '@/Components/ui/dropdown-menu/DropdownMenuLabel.vue';
import DropdownMenuSeparator from '@/Components/ui/dropdown-menu/DropdownMenuSeparator.vue';
import DropdownMenuItem from '@/Components/ui/dropdown-menu/DropdownMenuItem.vue';
import { MoreVertical } from 'lucide-vue-next';
const isSubmitedjobOpend = ref(true);
const isJobDrawerOpend = ref(false);
const queryParameter = ref('');
const JobsStore = useJobsStore();
const propsData = defineProps({
    jobs: Array,
    singleJob: Object,
    isDrawerOpend: Boolean,
    jobsCount: Number,
})

const OpenJobDrawer = (jobSlug) => {
    // open drawer
    isJobDrawerOpend.value = !isJobDrawerOpend.value
    // set loading indecator
    JobsStore.isLoading = !JobsStore.isLoading;
    // get pervious Query String
    queryParameter.value = window.location.search;
    router.get(route('user.job.show', { 'job': jobSlug }), {}, {
        preserveState: true,
        preserveScroll: true,
        only: ['singleJob', 'isDrawerOpend'],
        onSuccess: (page) => {
            JobsStore.isLoading = !JobsStore.isLoading;
        },
        onError: () => {
            JobsStore.isLoading = !JobsStore.isLoading;
        }
    })
}
const CloseJobDrawer = () => {
    isJobDrawerOpend.value = false
    history.pushState({}, '', `${route('user.jobs')}${queryParameter.value}`);
}


onMounted(() => {
    isJobDrawerOpend.value = propsData.isDrawerOpend;
})

defineOptions({
    layout: MainLayout,
})
</script>