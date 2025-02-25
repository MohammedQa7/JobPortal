<template>
    <Card class="border-yellow-300">
        <CardContent class="p-6 bg-yellow-100/30 ">
            <div class="flex items-center gap-2 ">
                <Info class="self-baseline" />
                <h1 class="text-sm font-medium ">
                    <span class="uppercase font-bold">The job has been held</span> due to issues with payment on the job
                    owner's side.
                    If the problem is not resolved within 42 hours, the job will be canceled.
                </h1>
            </div>
        </CardContent>
    </Card>
</template>

<script setup>
import { Info } from 'lucide-vue-next';
import Card from './ui/card/Card.vue';
import CardContent from './ui/card/CardContent.vue';
</script>