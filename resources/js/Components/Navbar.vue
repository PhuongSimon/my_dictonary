<template>
    <nav class="flex justify-between bg-neutral-300 p-4 px-5">
        <div class="flex justify-between items-center ps-5">
            <a :href="route('home')" class="text-dark text-2xl font-bold">Brand</a>
        </div>
        <div class="flex pe-5">
            <ul class="flex gap-x-5 me-auto mb-2">
                <li class="nav-item">
                    <NavLink class="text-dark" :href="route('home')" :active="route().current('home')">Home</NavLink>
                </li>
                <li class="nav-item">
                    <NavLink class="text-dark" :href="route('vocabulary.index')" :active="route().current().startsWith('vocabulary.')">
                        My Vocabulary
                    </NavLink>                    
                </li>
                <li class="nav-item">
                    <NavLink class="text-dark" :href="route('about')" :active="route().current('about')">About</NavLink>
                </li>
                <li class="nav-item">
                    <NavLink class="text-dark" :href="route('contact')" :active="route().current('contact')">Contact</NavLink>
                </li>
            </ul>
      </div>
      <div>
            <ul class="flex gap-x-5 me-auto mb-2">
                <li class="nav-item">
                    <NavLink class="text-dark" :href="route('login')" :active="route().current('login')">Sign in</NavLink>
                </li>
            </ul>
      </div>
    </nav>
</template>
  
<script setup>
  import NavLink from '@/Components/NavLink.vue'
import { Link } from '@inertiajs/vue3';
</script>
  