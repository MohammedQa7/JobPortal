<template>
    <div class="max-w-screen-xl mx-auto w-full ">
        <!-- Navbar -->
        <div class="w-full  mb-36">
            <Navbar />
        </div>
        <div class="w-full  max-w-screen-xl mx-auto p-6">
            <slot />
        </div>
    </div>
</template>

<script setup>
import Navbar from '@/Shared/Navbar.vue';

</script>