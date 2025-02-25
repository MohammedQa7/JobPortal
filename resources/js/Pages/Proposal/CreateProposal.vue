<template>
    <Toaster />
    <div class="proposal-create space-y-6">
        <div class="heading flex justify-between items-center">
            <h1 class="text-4xl font-semibold">Submit a proposal</h1>

        </div>
        <!-- Job Description Section-->
        <Card class="space-y-3">
            <CardHeader class="text-xl font-semibold">
                Job Details
            </CardHeader>

            <CardContent>
                <section class="space-y-5">
                    <div class="job-info grid grid-cols-12 gap-2">
                        <div class="job-info space-y-3 col-span-12 md:col-span-10">
                            <h5 class="job-name text-lg font-bold ">{{ job.data.title }}</h5>

                            <div class="flex items-center gap-4">
                                <Badge
                                    class="px-3.5 py-1 bg-muted-foreground/20 text-muted-foreground hover:text-muted">
                                    Category Name</Badge>

                                <span class="text-sm text-muted-foreground">Posted {{ job.data.createdAt }}</span>
                            </div>

                            <div class="job-desc">
                                <p>
                                    {{ job.data.description }}
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet, soluta velit,
                                    laborum
                                    repellendus placeat, minus eius molestias id ipsa temporibus rem. Consequuntur
                                    libero
                                    dolor
                                    illum quae repudiandae nobis culpa eaque!
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. A doloremque cumque qui
                                    doloribus
                                    nisi perferendis consequuntur, reiciendis, distinctio quod expedita quo sequi harum
                                    nam
                                    praesentium commodi inventore corrupti atque. Aliquid?
                                </p>
                            </div>


                        </div>
                        <div class="hidden md:col-span-2 md:block  relative space-x-5">
                            <Separator orientation="vertical" class=" absolute" />
                            <div class="job-duration flex items-center gap-2 mb-5">
                                <Timer class=" self-baseline size-5" />
                                <div>
                                    <p class="text-sm font-semibold">{{ job.data.duration }} / Day</p>
                                </div>
                            </div>
                            <div class="job-budget flex items-center gap-2">
                                <Tag class=" self-baseline size-5" />
                                <div>
                                    <p class="text-sm font-semibold">$ {{ job.data.budget }} </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <Separator />

                    <div class="job-skills text-primary  space-y-4">
                        <h1 class="text-xl font-semibold">Skills and Expertise:</h1>

                        <div class="flex flex-wrap gap-3">
                            <Badge v-for="(skill, index) in job.data.skills" :key="index"
                                class="px-3.5 py-1 bg-muted-foreground/20 text-muted-foreground hover:text-muted">
                                {{ skill }}</Badge>
                        </div>
                    </div>
                </section>



            </CardContent>
        </Card>

        <form @submit.prevent="createProposal" class="space-y-6">
            <!-- Terms & Bid Section-->
            <Card class="space-y-3">
                <CardHeader class="text-xl font-semibold">
                    Terms
                </CardHeader>

                <CardContent>
                    <section class="space-y-5">
                        <h1 class=" font-bold">
                            What is the full amount you'd like to bid for this job?

                        </h1>
                        <Separator />

                        <div class="bid-secrion grid grid-cols-12 space-y-5">
                            <div class="bid col-span-12 md:col-span-8  space-y-6">
                                <div class="bid-field flex justify-between items-center">
                                    <div class="w-1/2 space-y-2">
                                        <h1 class="font-semibold">Bid</h1>
                                        <h2 class="text-muted-foreground">Total amount the client will see on your
                                            proposal</h2>
                                    </div>
                                    <div class="bid-input ">
                                        <div class="relative  items-center">
                                            <Input id="bid" type="text" v-model="form.bid" class="pl-10"
                                                @input="CalculateBidFees" />
                                            <span
                                                class="absolute start-0 inset-y-0 flex items-center justify-center px-4">
                                                $
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="tax flex justify-between items-center">
                                    <div class="w-1/2 space-y-2">
                                        <h1 class="font-semibold">{{ tax * 100 }}% Fee</h1>
                                    </div>
                                    <div class="tax-input ">
                                        <div class="relative  items-center">
                                            <Input id="tax" type="text" class="pl-10" v-model="deductedAmount"
                                                disabled />
                                            <span
                                                class="absolute start-0 inset-y-0 flex items-center justify-center px-4">
                                                $
                                            </span>
                                        </div>
                                    </div>
                                </div>


                                <div class="recieve-from-bid  flex justify-between items-center">
                                    <div class="w-1/2 space-y-2">
                                        <h1 class="font-semibold">You’ll Receive</h1>
                                        <h2 class="text-muted-foreground">The estimated amount you'll receive after
                                            service
                                            fees </h2>
                                    </div>
                                    <div class="bid-input ">
                                        <div class="relative  items-center">
                                            <Input id="received_amount" type="text" v-model="receivedAmount" disabled
                                                class="pl-10" />
                                            <span
                                                class="absolute start-0 inset-y-0 flex items-center justify-center px-4">
                                                $
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div
                                class="no-profit-icon col-span-12 md:col-span-4 flex items-center gap-2 md:flex-col   md:justify-center ">
                                <BadgePercent class="size-12 md:size-24 text-primaryBtn" />
                                <h1 class="text-muted-foreground">Lowest fees, best value.</h1>
                            </div>
                        </div>

                        <InputError :message="form.errors.bid" />
                    </section>

                </CardContent>
            </Card>

            <!-- Project Duration input section -->
            <Card class="space-y-3 pt-4">
                <CardContent>
                    <section class="space-y-3">
                        <h1 class="font-bold">
                            How long will this project take?
                        </h1>

                        <div class="cover-letter-secrion  w-1/4">
                            <Input v-model="form.duration" placeholder="Project duration in 'Days'" />
                        </div>
                        <InputError :message="form.errors.duration" />
                    </section>
                </CardContent>
            </Card>

            <!-- Cover Letter Section -->
            <Card class="space-y-3">
                <CardHeader class="text-xl font-semibold">
                    Additional details
                </CardHeader>

                <CardContent>
                    <section class="space-y-3">
                        <h1 class="font-bold">
                            Cover Letter
                        </h1>

                        <div class="cover-letter-secrion ">
                            <Textarea v-model="form.coverLetter" class="min-h-[200px]" />
                        </div>

                        <InputError :message="form.errors.coverLetter" />
                    </section>

                </CardContent>
            </Card>

            <div v-if="page.props.auth.user" class="submit-btn flex items-center gap-4">
                <Button type="submit" class="bg-primaryBtn" :disabled="form.processing">
                    Submit proposal
                </Button>

                <Link :href="route('job.index')" class="text-primaryBtn font-semibold">
                Cancel
                </Link>
            </div>
        </form>
    </div>
</template>

<script setup>
import InputError from '@/Components/InputError.vue';
import { Badge } from '@/Components/ui/badge';
import Button from '@/components/ui/button/Button.vue';
import { Card } from '@/Components/ui/card';
import CardContent from '@/Components/ui/card/CardContent.vue';
import CardHeader from '@/Components/ui/card/CardHeader.vue';
import { Input } from '@/components/ui/input';
import Separator from '@/components/ui/separator/Separator.vue';
import { Textarea } from '@/components/ui/textarea';
import MainLayout from '@/Layouts/MainLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { BadgePercent, Info, Tag, Timer } from 'lucide-vue-next';
import { onMounted, ref, watch } from 'vue';
import Toaster from '@/components/ui/toast/Toaster.vue';
import { useToast } from '@/components/ui/toast/use-toast'
import { calculateBidFees } from '@/Composable/caculateFees';
const { toast } = useToast();
const page = usePage();
const deductedAmount = ref();
const receivedAmount = ref();
const propsData = defineProps({
    job: Object,
    proposalTypes: Object,
    tax: Number,
});
const form = useForm({
    job: null,
    bid: 5,
    duration: null,
    coverLetter: '',
    type: propsData.proposalTypes.proposal,
})
watch(form, () => {
    CalculateBidFees2();
})

const CalculateBidFees2 = () => {
    if (!isNaN(form.bid)) {
        // receivedAmount.value = (form.bid - (form.bid * propsData.tax)).toFixed(2);
        // deductedAmount.value = (receivedAmount.value - form.bid).toFixed(2);
        const fees = calculateBidFees(form.bid, propsData.tax, receivedAmount.value, deductedAmount.value);
        receivedAmount.value = fees.receivedAmount;
        deductedAmount.value = fees.deductedAmount;

    }


}

const createProposal = () => {
    //  set project and user value to the form
    form.job = propsData.job.data.id;

    form.post(route('proposal.store'), {
        preserveScroll: true,
        onError: () => {
            toast({
                title: 'Something went wrong, please try again',
                variant: 'destructive',
            });
        }

    });
}

onMounted(() => {
    CalculateBidFees2();
})



defineOptions({
    layout: MainLayout,
})
</script>