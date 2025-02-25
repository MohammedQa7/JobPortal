<template>
    <Dialog>
        <DialogTrigger>
            <span @click.prevent="getRatings()" class="underline cursor-pointer">{{ rate?.toFixed(1) }}</span>
        </DialogTrigger>
        <DialogContent class="max-w-5xl grid-rows-[auto_minmax(0,1fr)_auto] p-0 max-h-[90dvh]">
            <DialogHeader class="flex-row justify-between p-6">
                <div>
                    <DialogTitle>Ratings</DialogTitle>
                </div>
            </DialogHeader>
            <div v-if="isLoading" class="loading-indicator flex justify-center items-center">
                <Loader2 class="size-9 animate-spin text-center" />
            </div>

            <div class="proposal-details px-6 pb-0  overflow-y-auto">
                <div class="flex justify-between">
                    <div class="user-details flex flex-col gap-4">
                        <div v-for="(singleRate, index) in userRatings" :key="index"
                            class="user-info space-y-1 font-semibold ">
                            <!-- Rating  + Heading-->
                            <div class="flex items-center gap-5">
                                <h1 class="username font-bold text-primaryBtn underline">{{
                                    singleRate.job.title
                                    }}
                                </h1>
                                <Rating :disable-rating="true" :rate="singleRate.rate" />
                            </div>
                            <h1 class="speciality ">{{ singleRate.user.name }}</h1>
                            <p class="text-primary"> {{ singleRate.description }}</p>
                            <h1 class="location text-sm text-muted-foreground ">{{ singleRate.createdAt }}</h1>

                        </div>

                        <Separator />

                    </div>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>

<script setup>
import Dialog from '../ui/dialog/Dialog.vue';
import DialogContent from '../ui/dialog/DialogContent.vue';
import DialogHeader from '../ui/dialog/DialogHeader.vue';
import Avatar from '../ui/avatar/Avatar.vue';
import AvatarImage from '../ui/avatar/AvatarImage.vue';
import AvatarFallback from '../ui/avatar/AvatarFallback.vue';
import Rating from '../Rating.vue';
import DialogClose from '../ui/dialog/DialogClose.vue';
import DialogTitle from '../ui/dialog/DialogTitle.vue';
import DialogTrigger from '../ui/dialog/DialogTrigger.vue';
import { ref } from 'vue';
import { Loader2 } from 'lucide-vue-next';
import Separator from '../ui/separator/Separator.vue';
const userRatings = ref(null);
const isLoading = ref(true);
const propsData = defineProps({
    isOpen: Boolean,
    rate: Number,
})

const getRatings = () => {
    isLoading.value = true;
    axios.get(route('rate.index', { username: 'Mohammed2' }))
        .then((response) => {
            isLoading.value = false;
            userRatings.value = response.data.ratings
            console.log(response.data);

        })
        .catch((error) => {
            isLoading.value = false;
            console.log(error);
        })
}

</script>