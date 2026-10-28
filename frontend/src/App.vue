<script setup>
import BottomNav from './components/BottomNav.vue'
</script>

<template>
  <div class="relative mx-auto min-h-screen w-full max-w-107.5 bg-brand-50 shadow-xl">
    <RouterView />
    <BottomNav />
  </div>
</template>