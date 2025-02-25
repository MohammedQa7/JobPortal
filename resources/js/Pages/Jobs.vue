<template>
    <!-- Drawer from right side to show a single job -->
    <div class="grid grid-cols-12 gap-5">
        <div class="filter-section col-span-3">
            <Collapsible v-model:open="isCollapsibleOpened" class=" space-y-2">
                <div class="flex items-center justify-between space-x-4">
                    <h4 class="text-lg font-semibold">
                        Category
                    </h4>
                    <CollapsibleTrigger as-child>
                        <Button variant="ghost" size="sm" class="w-9 p-0">
                            <ChevronDown class="size-8  transition-all"
                                :class="{ 'rotate-180': isCollapsibleOpened }" />
                            <span class="sr-only">Toggle</span>
                        </Button>
                    </CollapsibleTrigger>
                </div>
                <CollapsibleContent class="space-y-2">
                    <CategoryFilterMultiSelect :categories="categories.data"
                        :pre-selected-categories="searchAndFilter.category"
                        @bindSelectedCategories="bindCategoryItems" />
                </CollapsibleContent>
            </Collapsible>
        </div>

        <div class="jobs-section col-span-9 space-y-4">
            <div class="search-field flex justify-center items-center gap-2 border rounded-xl p-4 ">
                <Input @keyup.enter="filterAndSearchJobs" v-model="search"
                    placeholder="Search By: Title or any job keyword" />

                <Button @click.prevent="filterAndSearchJobs" class="w-32 bg-primaryBtn">
                    Find
                    <Search />
                </Button>
            </div>


            <div v-if="jobs.length > 0 && jobPagination" class="jobs-list border rounded-xl p-4 space-y-5">
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
                            <h1 class="text-sm font-medium text-muted-foreground">Proposals: <span class="font-bold">{{
                                job.proposalsCount }}</span> </h1>
                        </div>
                    </CardContent>
                </Card>

                <WhenVisible v-if="jobs.length > 4 && jobPagination.current_page <= jobPagination.last_page"
                    :always="true" :params="{
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

            <div v-else>
                <Card class="bg-primary-foreground ">
                    <CardContent class="pt-6 font-semibold">
                        No jobs were found
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>

    <SingleJobDrawer v-if="isJobDrawerOpened" :singleJob="singleJob?.data" :is-open="isJobDrawerOpened"
        @Close="CloseJobDrawer" />
</template>

<script setup>
import CategoryFilterMultiSelect from '@/Components/CategoryFilterMultiSelect.vue';
import JobsLoadingSkeleton from '@/Components/JobsLoadingSkeleton.vue';
import SingleJobDrawer from '@/Components/SingleJobDrawer.vue';
import { Badge } from '@/Components/ui/badge';
import Button from '@/components/ui/button/Button.vue';
import { Card } from '@/Components/ui/card';
import CardContent from '@/Components/ui/card/CardContent.vue';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/Components/ui/collapsible';
import Input from '@/components/ui/input/Input.vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { useJobsStore } from '@/stores/JobStore';
import { router, WhenVisible } from '@inertiajs/vue3';
import { ChevronDown, ChevronsUpDown, ChevronUp, Dot, Search } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
const isJobDrawerOpened = ref(false);
const isCollapsibleOpened = ref(true)
const queryParameter = ref('');
const JobsStore = useJobsStore();
const search = ref('');
const categoryFilterItems = ref([]);
const params = new URLSearchParams(window.location.search);

// Data from server
const propsData = defineProps({
    jobs: Array,
    jobPagination: Object,
    singleJob: Object,
    isDrawerOpend: Boolean,
    categories: Array,
    searchAndFilter: Array,
})


const OpenJobDrawer = (jobSlug) => {
    // open drawer
    isJobDrawerOpened.value = !isJobDrawerOpened.value
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
    isJobDrawerOpened.value = false
    history.pushState({}, '', `${route('job.index')}${queryParameter.value}`);
}

const filterAndSearchJobs = () => {
    router.get(route('job.index', { category: categoryFilterItems.value.join(' '), search: search.value }), {},
        {
            preserveState: true,
        });
}

const bindCategoryItems = (category) => {
    categoryFilterItems.value = category;
    filterAndSearchJobs();
}




onMounted(() => {
    isJobDrawerOpened.value = propsData.isDrawerOpend;
    categoryFilterItems.value = propsData.searchAndFilter.category.length > 0
        ? propsData.searchAndFilter.category
        : []
    search.value = propsData.searchAndFilter.search;

})

defineOptions({
    layout: MainLayout
})
</script>