<script setup>
import { useRoute } from 'vue-router'
import BottomNav from './components/BottomNav.vue'

const route = useRoute()
</script>

<template>
  <div
    class="relative mx-auto min-h-screen w-full max-w-107.5 bg-brand-50 shadow-xl"
    :class="route.meta.hideNav ? '' : 'pb-[calc(6rem+env(safe-area-inset-bottom))]'"
  >
    <RouterView />
    <BottomNav v-if="!route.meta.hideNav" />
  </div>
</template>