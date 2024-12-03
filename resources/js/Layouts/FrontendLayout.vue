<template>
    <!-- <div :style="{ backgroundImage: `url(${image})`, backgroundSize: 'cover', backgroundPosition: 'center' }"> -->
    <div>
        <Navbar/>
        <slot/>
    </div>
</template>

<script setup>
// import image from '@/assets/bg-1.jpg';
import Navbar from '@/Components/Navbar.vue'
import { Link } from '@inertiajs/vue3';
</script>