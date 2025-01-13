<script setup lang="ts">
import { Button } from '@/components/ui/button';

</script>

<template>
    <div class="w-full h-screen flex flex-col justify-center items-center p-12 gap-4">
        <h1 class="text-4xl text-center font-bold">
            You have already submited a proposal for this project
        </h1>

        <Link :href="route('job.index')">
        <Button class="px-12 py-6 text-xl">Back to homepage</Button>
        </Link>
    </div>
</template>