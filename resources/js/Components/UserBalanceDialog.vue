<template>
    <Dialog>
        <DialogTrigger as-child>
            <span class="truncate text-xs underline cursor-pointer">
                Balance: ${{ page.props.auth.user?.balance }}
            </span>
        </DialogTrigger>
        <DialogContent class=" max-w-4xl grid-rows-[auto_minmax(0,1fr)_auto]  p-0 ">
            <DialogHeader class="px-6 py-8 pb-0">
                <div class="flex justify-between">
                    <DialogTitle class=" uppercase">Add funds to your account</DialogTitle>
                    <h1 class="font-bold ">Your balance: ${{ page.props.auth.user.balance }}</h1>
                </div>

                <DialogDescription>
                    Funds in your Account may be used for the publishing a job on Masar or withdraw it to your bank
                    account.
                </DialogDescription>
            </DialogHeader>

            <div class="px-6">
                <Card class="p-2 rounded-2xl grid grid-cols-12 gap-3 ">
                    <Card
                        class="col-span-6 md:col-span-4 flex flex-col justify-around items-center bg-primary-foreground rounded-lg px-2 py-14 space-y-2 ">
                        <h1 class="font-bold text-3xl">$25</h1>

                        <Button @click.prevent="sumbitFund(2500)" class="w-2/3" :disabled="isLoading">
                            Add funds
                        </Button>
                    </Card>

                    <Card
                        class="col-span-6 md:col-span-4 flex flex-col justify-around items-center bg-primary-foreground shadow-sm rounded-lg px-2 py-14 space-y-2">
                        <h1 class="font-bold text-3xl">$50</h1>
                        <Button @click.prevent="sumbitFund(5000)" class="w-2/3" :disabled="isLoading">
                            Add funds
                        </Button>
                    </Card>
                    <Card
                        class="relative  col-span-full md:col-span-4 flex flex-col justify-around items-center bg-primary-foreground shadow-sm rounded-lg px-2 py-14 space-y-2 overflow-hidden">
                        <h1 class="font-bold text-3xl z-20">$100</h1>
                        <Button @click.prevent="sumbitFund(10000)" class="z-[5] w-2/3" :disabled="isLoading">
                            Add funds

                        </Button>
                        <svg class="absolute -left-[72%] -bottom-5 aspect-[2/1] w-[250%] translate-y-[calc(50%_-122px_+_25vw)] sm:translate-y-1/2 blur-[calc(12vw)] sm:blur-[56px] saturate-150 transform-[translate3d(0, 0, 0)] transform-gpu [will-change:transform,filter]"
                            fill="none" viewBox="0 0 701 467" xmlns="http://www.w3.org/2000/svg">
                            <g class="transform-gpu">
                                <path class="will-change-transform"
                                    d="M407.64 132.674 511.646 108l81.075 17.082V326.27H419.103l-31.111-98.696 19.648-94.9Z"
                                    fill="#5DA200" data-svg-origin="387.99200439453125 108"
                                    transform="matrix(1,0,0,1,-28.60011,-15.55138)"></path>
                                <path class="will-change-transform"
                                    d="m357.667 186.498 72.448-19.143 56.475 13.253v156.09H365.651l-21.671-76.572 13.687-73.628Z"
                                    fill="#FFC700" data-svg-origin="343.9800109863281 167.35499572753906"
                                    transform="matrix(1,0,0,1,30.4114,7.62687)"></path>
                                <path class="will-change-transform"
                                    d="m471.471 217.57 58.714-9.647 45.77 6.679v78.666h-98.013l-17.563-38.591 11.092-37.107Z"
                                    fill="#FFC700" data-svg-origin="460.3790283203125 207.92300415039062"
                                    transform="matrix(1,0,0,1,-28.59165,-5.37883)"></path>
                                <path class="will-change-transform"
                                    d="m160.042 229.351 80.266-16.432 62.569 11.376v133.988H168.888l-24.009-65.73 15.163-63.202Z"
                                    fill="#FFC700" data-svg-origin="144.87899780273438 212.91900634765625"
                                    transform="matrix(1,0,0,1,-27.36346,-4.72614)"></path>
                                <path class="will-change-transform"
                                    d="m290.066 207.03 73.697-19.143 57.449 13.253v156.091H298.188l-22.044-76.573 13.922-73.628Z"
                                    fill="#FFC700" data-svg-origin="276.14398193359375 187.88699340820312"
                                    transform="matrix(1,0,0,1,-7.68604,21.74123)"></path>
                                <path class="will-change-transform"
                                    d="m136.004 208.083 73.697-19.143 57.449 13.253v156.09H144.127l-22.045-76.572 13.922-73.628Z"
                                    fill="#FFC700" data-svg-origin="122.08200073242188 188.9399871826172"
                                    transform="matrix(1,0,0,1,27.80585,23.83451)"></path>
                                <path class="will-change-transform"
                                    d="m243.752 200.799 86.172-18.321 67.174 12.683v149.388H253.249l-25.776-73.284 16.279-70.466Z"
                                    fill="#7C5FE4" data-svg-origin="227.47299194335938 182.47799682617188"
                                    transform="matrix(1,0,0,1,28.57272,14.433)"></path>
                                <path class="will-change-transform"
                                    d="m417.299 212.382 39.278-7.668 30.618 5.309v62.518h-65.567l-11.749-30.669 7.42-29.49Z"
                                    fill="#A40099" data-svg-origin="409.8790588378906 204.71400451660156"
                                    transform="matrix(1,0,0,1,3.95081,27.31399)"></path>
                                <path class="will-change-transform"
                                    d="m145.653 194.349 93.505-19.143 72.89 13.253v156.09h-156.09l-27.97-76.573 17.665-73.627Z"
                                    fill="#FF69C4" data-svg-origin="127.98797607421875 175.20599365234375"
                                    transform="matrix(1,0,0,1,31.10635,-18.55688)"></path>
                                <path class="will-change-transform"
                                    d="m117.665 194.349 93.505-19.143 72.89 13.253v156.09H127.97L100 267.976l17.665-73.627Z"
                                    fill="#C91884" data-svg-origin="100 175.20599365234375"
                                    transform="matrix(1,0,0,1,-15.29236,22.23421)"></path>
                            </g>
                        </svg>
                    </Card>

                    <Card class="col-span-full  bg-primary-foreground shadow-sm rounded-lg px-2 py-6 space-y-2">
                        <Label for="custom-fund">Custom Fund</Label>
                        <Input v-model="customFund" name="custom-fund" placeholder="Your custom fun" />
                        <p class="text-sm font-medium text-muted-foreground">Minimum fund level is $5</p>
                        <Button @click.prevent="sumbitFund()" :disabled="isLoading">
                            Add funds
                        </Button>

                    </Card>
                </Card>
            </div>
        </DialogContent>
    </Dialog>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button'
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog'
import { usePage } from '@inertiajs/vue3';
import Card from './ui/card/Card.vue';
import { Input } from './ui/input';
import Label from './ui/label/Label.vue';
import { ref } from 'vue';
import axios from 'axios';
import eventBus from '@/Composable/eventBus';
const page = usePage();
const customFund = ref(5);
const isLoading = ref(false);

const sumbitFund = (fixedFund = null) => {
    fixedFund instanceof Event
        ? fixedFund = null
        : '';

    isLoading.value = !isLoading.value

    axios.post(route('checkout'), { price: fixedFund ? fixedFund : customFund.value * 100 })
        .then((response) => {
            isLoading.value = false;
            window.location.href = response.data.checkoutUrl
        })
        .catch((error) => {
            isLoading.value = false;
            eventBus.emit('errorMessage');
        })
}
</script>