<template>
    <Card class="border-destructive/30 bg-destructive/20 ">
        <CardContent class="p-6 ">
            <div class="flex items-center gap-2 ">
                <Info class="self-baseline" />
                <h1 class="text-sm font-medium ">
                    <span class="uppercase font-bold">The job has been Canceled</span>
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