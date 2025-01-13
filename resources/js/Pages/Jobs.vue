<template>
    <!-- Drawer from right side to show a single job -->

    <div class="grid grid-cols-12">
        <div class="filter-section col-span-3">

        </div>

        <div class="jobs-section col-span-9 space-y-4">
            <div class="search-field flex justify-center items-center gap-2 border rounded-xl p-4 ">
                <Input placeholder="Search By: Title or any job keyword" />

                <Button class="w-32 bg-primaryBtn">
                    Find
                    <Search />
                </Button>
            </div>


            <div v-if="jobs && jobPagination" class="jobs-list border rounded-xl p-4 space-y-5">
                <Card @click.prevent="OpenJobDrawer(job.slug)" v-for="(job, index) in jobs" :key="index"
                    class="bg-primary-foreground cursor-pointer transition-all hover:shadow-lg ">
                    <CardContent class="py-4 space-y-4">
                        <div class="job-header space-y-2">
                            <div class="flex items-center ">
                                <h1 class="text-xl font-semibold">{{ job.title }}</h1>
                                <Dot />
                                <span class="text-sm  font-medium text-muted-foreground">{{ job.createdAt }}</span>
                            </div>
                            <div class="job-header flex items-center ">
                                <h1 class="text-sm font-medium text-muted-foreground">Budget: <span class="font-bold">
                                        {{ job.budget }}$
                                    </span> - Est: {{ job.duration }} Days
                                </h1>
                            </div>
                        </div>

                        <div class="job-pref ">
                            <h1 class="font-semibold text-sm ">{{ job.description }}
                            </h1>
                        </div>

                        <div class="skills flex flex-wrap gap-2">
                            <Badge v-for="(skill, index) in job.skills" :key="index"
                                class="px-3.5 py-1 bg-muted-foreground/20 text-muted-foreground hover:text-muted">
                                {{ skill }}</Badge>
                            <Badge v-if="job.skillCount"
                                class="px-3.5 py-1 bg-muted-foreground/20 text-muted-foreground hover:text-muted">
                                +{{ job.skillCount }} more</Badge>

                        </div>

                        <div class="rating flex flex-wrap gap-2">
                            <h1 class="text-sm font-medium text-muted-foreground">Proposals: <span
                                    class="font-bold">20</span> </h1>
                        </div>

                        <div class="proposals">
                            <h1 class="text-sm font-medium text-muted-foreground">Proposals: <span
                                    class="font-bold">20</span> </h1>
                        </div>
                    </CardContent>
                </Card>

                <WhenVisible v-if="jobPagination.current_page <= jobPagination.last_page" :always="true" :params="{
                    data: {
                        page: jobPagination.current_page + 1
                    },
                    only: ['jobs', 'jobPagination'],
                }">
                    <div>
                        <JobsLoadingSkeleton />
                    </div>
                </WhenVisible>
            </div>
        </div>
    </div>

    <SingleJobDrawer v-if="isJobDrawerOpend" :singleJob="singleJob?.data" :is-open="isJobDrawerOpend"
        @Close="CloseJobDrawer" />
</template>

<script setup>
import JobsLoadingSkeleton from '@/Components/JobsLoadingSkeleton.vue';
import SingleJobDrawer from '@/Components/SingleJobDrawer.vue';
import { Badge } from '@/Components/ui/badge';
import Button from '@/components/ui/button/Button.vue';
import { Card } from '@/Components/ui/card';
import CardContent from '@/Components/ui/card/CardContent.vue';
import Input from '@/components/ui/input/Input.vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { useJobsStore } from '@/stores/JobStore';
import { router, WhenVisible } from '@inertiajs/vue3';
import { Dot, Search } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
const isJobDrawerOpend = ref(false);
const queryParameter = ref('');
const JobsStore = useJobsStore();
const propsData = defineProps({
    jobs: Array,
    singleJob: Object,
    isDrawerOpend: Boolean,
    jobPagination: Object,
})


const OpenJobDrawer = (jobSlug) => {
    // open drawer
    isJobDrawerOpend.value = !isJobDrawerOpend.value
    // set loading indecator
    JobsStore.isLoading = !JobsStore.isLoading;
    // get pervious Query String
    queryParameter.value = window.location.search;
    router.get(route('job.index.modal', { 'job': jobSlug }), {}, {
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
    history.pushState({}, '', `${route('job.index')}${queryParameter.value}`);
}

onMounted(() => {
    isJobDrawerOpend.value = propsData.isDrawerOpend;
})

defineOptions({
    layout: MainLayout
})
</script>