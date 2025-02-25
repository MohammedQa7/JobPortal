<template>
    <Sheet :open="isOpen">
        <SheetContent :hide-close-button="true" @Close="CloseDrawer"
            class="!w-full sm:!max-w-3xl space-y-4 overflow-y-auto overflow-x-hidden pt-10 pb-20"
            :class="{ 'pb-44': isMobile }">
            <!-- Title + User Location + Favorite -->
            <div v-if="!JobsStore.isLoading && singleJob">
                <SheetHeader class="flex flex-row justify-between items-center py-2 space-y-2">
                    <div class="space-y-2">
                        <SheetTitle class="font-bold text-xl">{{ singleJob.title }}</SheetTitle>
                        <SheetDescription>
                            <div class="user-info flex items-center">

                                <span class="font-semibold">Posted {{ singleJob.createdAt }}</span>
                            </div>
                        </SheetDescription>
                    </div>
                    <div v-if="singleJob.user.id != page.props.auth?.user?.id" class="favorite">
                        <Button variant="outline" class=" size-12">
                            <Bookmark class="!size-6" />
                        </Button>

                    </div>
                </SheetHeader>
                <SheetDescription class="space-y-8 relative">
                    <!-- Price And Duration -->
                    <div class="project-specific-info grid grid-cols-12 gap-4 md:gap-8">
                        <!-- Price -->
                        <Card class="project-price flex col-span-12 md:col-span-6 xl:col-span-6">
                            <CardContent class="flex items-center gap-3 pt-6">
                                <div class="price-icon">
                                    <Avatar class="h-12 w-12 rounded-full">
                                        <CircleDollarSign />
                                    </Avatar>
                                </div>

                                <div class="price-info space-y-1">
                                    <div class="flex items-center gap-1">
                                        <h1 class="font-bold text-lg">
                                            {{ singleJob.budget }}
                                        </h1>
                                        <DollarSign class="size-5" />
                                    </div>
                                    <h1 class="text-sm text-muted-foreground">Project Budget</h1>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Duration -->
                        <Card class="project-duration flex col-span-12 md:col-span-6 xl:col-span-6">
                            <CardContent class="flex items-center gap-3 pt-6">
                                <div class="duration-icon">
                                    <Avatar class="h-12 w-12 rounded-full">
                                        <CalendarClock />
                                    </Avatar>
                                </div>

                                <div class="duration-info space-y-1">
                                    <div class="flex items-center gap-1">
                                        <h1 class="font-bold text-lg">
                                            {{ singleJob.duration }}
                                            <span class="text-sm text-muted-foreground">/ Day</span>
                                        </h1>
                                    </div>
                                    <h1 class="text-sm text-muted-foreground">Project Duration</h1>
                                </div>
                            </CardContent>
                        </Card>
                    </div>

                    <!-- Project descriotion -->
                    <div class="project-desc text-primary space-y-4">
                        <h1 class="text-2xl font-bold">About the project</h1>

                        <p class=" leading-6">{{ singleJob.description }}</p>
                    </div>

                    <!-- Project / Job Attachmets -->
                    <div v-if="singleJob.attachments.length > 0" class="project-desc text-primary space-y-4">
                        <h1 class="text-2xl font-bold">Attachments</h1>

                        <Collapsible v-model:open="isAttachmentCollapsed" class="w-full space-y-2">
                            <div class="flex items-center justify-between space-x-4 ">
                                <h4 v-if="!isAttachmentCollapsed" class="text-sm font-semibold">* Attachments are
                                    collapsed</h4>
                                <h4 v-else class="text-sm font-semibold"></h4>
                                <CollapsibleTrigger as-child>
                                    <Button variant="ghost" size="sm" class="w-9 p-0">
                                        <ChevronsUpDownIcon class="h-4 w-4" />
                                        <span class="sr-only">Toggle</span>
                                    </Button>
                                </CollapsibleTrigger>
                            </div>
                            <CollapsibleContent class="space-y-2">
                                <div v-for="(attachment, index) in singleJob.attachments" :key="index"
                                    class=" rounded-md border px-4 py-3 font-mono text-sm underline cursor-pointer"
                                    @click.prevent="download(attachment)">

                                    {{ attachment.name }}
                                </div>
                            </CollapsibleContent>
                        </Collapsible>

                    </div>

                    <!-- Project skills-->
                    <div class="project-skills text-primary  space-y-4">
                        <h1 class="text-2xl font-bold">Skills and Expertise:</h1>

                        <div class="flex flex-wrap gap-3">
                            <Badge v-for="(skill, index) in singleJob.skills" :key="index"
                                class="px-3.5 py-1 bg-muted-foreground/20 text-muted-foreground hover:text-muted">
                                {{ skill }}</Badge>
                        </div>
                    </div>


                    <!-- About the client-->
                    <div class="client-info text-primary space-y-4">
                        <h1 class="text-2xl font-bold">About the client:</h1>
                        <div class="flex flex-wrap h-5 items-center gap-4 text-sm">
                            <div class="flex items-center gap-2">
                                <Rating :disable-rating="true" :rate="singleJob.user.rate" />
                                <UserRatingsDialog :rate="singleJob.user.rate" />
                            </div>
                            <Separator orientation="vertical" />
                            <div class="">
                                <h1>{{ singleJob.user.jobsCount }} jobs posted <span>
                                        ({{ singleJob.user.jobsCompletionRate }}% hire rate) </span></h1>
                            </div>
                            <Separator orientation="vertical" />
                            <div class="flex items-center gap-1">
                                <h1>Canada</h1>
                            </div>
                            <Separator orientation="vertical" />
                            <div class="flex items-center gap-1">
                                <h1>$3.1K total spent</h1>
                            </div>
                            <Separator orientation="vertical" />
                            <div class="flex items-center gap-1">
                                <h1 class="text-sm">Member since <span class="memeber-since">{{
                                    singleJob.user.createdAt
                                        }}</span>
                                </h1>
                            </div>
                        </div>
                    </div>


                    <div v-if="page.props.auth?.user?.id != singleJob.user.id && page.props.auth?.user"
                        class="send-proposal-btn w-96 md:w-[720px] fixed bottom-10"
                        :class="{ 'left-1/2 transform -translate-x-1/2 !bottom-5': isMobile }">

                        <a v-if="!singleJob.hasUserSubmitProposal"
                            :href="route('proposal.create', { job: singleJob.slug })" target="_blank">
                            <Button class="w-full text-lg font-semibold !p-6 bg-primaryBtn">
                                Apply now
                            </Button>
                        </a>

                        <Button v-else class="w-full text-lg font-semibold !p-6  bg-primaryBtn" disabled>
                            Submited
                        </Button>
                    </div>
                </SheetDescription>

                <DialogClose @click.prevent="CloseDrawer"
                    class="absolute right-4 top-4 rounded-sm opacity-70 ring-offset-background transition-opacity hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:pointer-events-none data-[state=open]:bg-accent data-[state=open]:text-muted-foreground">
                    <X class="w-4 h-4" />
                    <span class="sr-only">Close</span>
                </DialogClose>
            </div>
            <JobsLoadingSkeleton v-else />
        </SheetContent>


    </Sheet>
</template>

<script setup>
import Avatar from '@/Components/ui/avatar/Avatar.vue';
import { Separator } from '@/components/ui/separator';
import Sheet from '@/Components/ui/sheet/Sheet.vue';
import SheetContent from '@/Components/ui/sheet/SheetContent.vue';
import SheetDescription from '@/Components/ui/sheet/SheetDescription.vue';
import SheetHeader from '@/Components/ui/sheet/SheetHeader.vue';
import SheetTitle from '@/Components/ui/sheet/SheetTitle.vue';
import { Bookmark, CalendarClock, ChevronsUpDownIcon, CircleDollarSign, DollarSign, FileQuestion, X } from 'lucide-vue-next';
import Button from './ui/button/Button.vue';
import { DialogClose } from './ui/dialog';
import Card from './ui/card/Card.vue';
import CardContent from './ui/card/CardContent.vue';
import Badge from './ui/badge/Badge.vue';
import { closeDialog } from '@/Composable/closeDialog';
import JobsLoadingSkeleton from './JobsLoadingSkeleton.vue';
import { useJobsStore } from '@/stores/JobStore';
import { onMounted, onUnmounted, ref } from 'vue';
import { useMediaQuery } from '@vueuse/core';
import { Link, usePage } from '@inertiajs/vue3';
import Rating from './Rating.vue';
import Collapsible from './ui/collapsible/Collapsible.vue';
import CollapsibleTrigger from './ui/collapsible/CollapsibleTrigger.vue';
import CollapsibleContent from './ui/collapsible/CollapsibleContent.vue';
import UserRatingsDialog from './Rating/UserRatingsDialog.vue';
const emit = defineEmits();
const page = usePage();
const JobsStore = useJobsStore();
const isAttachmentCollapsed = ref(true);
const isMobile = useMediaQuery('(max-width: 767px)')
const propsData = defineProps({
    isOpen: Boolean,
    singleJob: Object,
})

const download = (attachment) => {
    const url = route('download', { attachmentPath: attachment['path'] });
    window.location.href = url
}

const CloseDrawer = () => {
    closeDialog(emit);
}

const CloseOnKeydown = (event) => {
    if (event.key == 'Escape') {
        CloseDrawer();
    }
}

onMounted(() => {
    document.addEventListener('keydown', CloseOnKeydown)
})

onUnmounted(() => {
    document.removeEventListener('keydown', CloseOnKeydown);
})

</script>