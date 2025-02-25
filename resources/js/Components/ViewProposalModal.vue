<template>
    <Dialog :open="isOpen">
        <DialogContent :hideCloseButton="true" class="max-w-5xl grid-rows-[auto_minmax(0,1fr)_auto] p-0 max-h-[90dvh]">
            <DialogHeader class="flex-row justify-between p-6">
                <div>
                    <DialogTitle>Proposal details</DialogTitle>
                </div>
            </DialogHeader>
            <div v-if="proposalStore.isLoading && !proposalStore.proposal"
                class="loading-indicator flex justify-center items-center">
                <Loader2 class="size-9 animate-spin text-center" />
            </div>

            <div v-else class="proposal-details px-6 pb-0  overflow-y-auto">
                <div class="flex justify-between">
                    <div class="user-details flex gap-4">
                        <div class="user-avatar ">
                            <Avatar class="h-14 w-14 rounded-full">
                                <AvatarImage />
                                <AvatarFallback class="rounded-lg">
                                    {{ proposalStore.proposal.jobSeeker.name.slice(0, 2).toUpperCase() }}
                                </AvatarFallback>
                            </Avatar>
                        </div>
                        <div class="user-info space-y-1 font-semibold ">
                            <h1 class="username font-bold text-primaryBtn underline">{{
                                proposalStore.proposal.jobSeeker.name
                            }}
                            </h1>
                            <h1 class="speciality ">Fullstack Developer</h1>
                            <h1 class="location text-sm text-muted-foreground ">Palestine</h1>
                            <!-- Rating -->
                            <div class="flex items-center gap-2">
                                <Rating :disable-rating="true" :rate="proposalStore.proposal.jobSeeker.rate" />
                                <span class="underline">{{ proposalStore.proposal.jobSeeker.rate.toFixed(1) }}</span>
                            </div>
                            <div class="proposal-info space-y-3 pt-2">
                                <div class="flex items-center gap-12">
                                    <span class="offer-price flex items-center gap-2">
                                        <Tag class="size-4" />
                                        $ {{ proposalStore.proposal.bid }}
                                    </span>
                                    <span class="offer-price flex items-center gap-2">
                                        <Calendar class="size-4" />
                                        {{ proposalStore.proposal.duration }} /Day
                                    </span>
                                </div>

                                <p class=" font-medium"><span class="font-bold">Cover letter -</span>
                                    {{ proposalStore.proposal.coverLetter }}

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <DialogFooter class="flex !flex-col  p-6 border-t bg-primary-foreground">
                <div class="w-full text-end space-y-4">
                    <div class="w-full">
                        <Textarea />
                    </div>
                    <Button type="submit" class="w-full md:w-auto bg-primaryBtn">
                        Send Message
                    </Button>
                </div>
            </DialogFooter>
            <DialogClose @click.prevent="CloseProposalDialog"
                class="absolute right-4 top-4 rounded-sm opacity-70 ring-offset-background transition-opacity hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:pointer-events-none data-[state=open]:bg-accent data-[state=open]:text-muted-foreground">
                <X class="w-4 h-4" />
                <span class="sr-only">Close</span>
            </DialogClose>
        </DialogContent>
    </Dialog>
</template>

<script setup>
import { Calendar, Loader2, Tag, X } from 'lucide-vue-next';
import Dialog from './ui/dialog/Dialog.vue';
import DialogContent from './ui/dialog/DialogContent.vue';
import DialogHeader from './ui/dialog/DialogHeader.vue';
import DialogTitle from './ui/dialog/DialogTitle.vue';
import DialogFooter from './ui/dialog/DialogFooter.vue';
import DialogClose from './ui/dialog/DialogClose.vue';
import Button from './ui/button/Button.vue';
import { closeDialog } from '@/Composable/closeDialog';
import { onMounted, onUnmounted, ref } from 'vue';
import Avatar from './ui/avatar/Avatar.vue';
import AvatarImage from './ui/avatar/AvatarImage.vue';
import AvatarFallback from './ui/avatar/AvatarFallback.vue';
import { useProposalStore } from '@/stores/ProposalStore';
import { Textarea } from './ui/textarea';
import Rating from './Rating.vue';
const emit = defineEmits();
const proposalStore = useProposalStore();
const propsData = defineProps({
    isOpen: Boolean,
})


const CloseProposalDialog = () => {
    closeDialog(emit);
}

const CloseOnKeydown = (event) => {
    if (event.key == 'Escape') {
        CloseProposalDialog();
    }
}

onMounted(() => {
    document.addEventListener('keydown', CloseOnKeydown)
})

onUnmounted(() => {
    document.removeEventListener('keydown', CloseOnKeydown);
})

</script>