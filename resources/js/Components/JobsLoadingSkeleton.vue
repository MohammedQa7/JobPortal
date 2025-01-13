<template>
    <Card class="bg-primary-foreground ">
        <CardContent class="py-4 space-y-4">
            <div class="job-header space-y-2">
                <div class="flex items-center ">
                    <Skeleton class="h-4 w-[250px]" />
                    <Skeleton class="h-4 w-[250px]" />
                    <Skeleton class="h-4 " />
                    <Skeleton class="h-4 w-[250px]" />
                </div>
                <div class="job-header flex items-center ">

                    <Skeleton class="h-4 w-[250px]">
                        <Skeleton class="h-4 w-[250px]" />
                    </Skeleton>

                </div>
            </div>

            <div class="job-pref ">
                <Skeleton class="h-4 w-[250px]" />
            </div>

            <div class="skills flex flex-wrap gap-2">
                <Skeleton class="h-4" />

            </div>

            <div class="rating flex flex-wrap gap-2">
                <Skeleton class="h-4 w-[250px]" />
            </div>

            <div class="proposals">
                <Skeleton class="h-4 w-[250px]" />
            </div>
        </CardContent>
    </Card>
</template>

<script setup>
import { Card } from '@/Components/ui/card';
import CardContent from '@/Components/ui/card/CardContent.vue';
import { Skeleton } from '@/Components/ui/skeleton';
</script>