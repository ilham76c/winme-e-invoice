<script setup>
import AppIcon from './AppIcon.vue'
</script>

<template>
  <nav
    aria-label="Navigasi bawah"
    class="fixed bottom-0 left-1/2 z-20 grid h-20 w-full max-w-107.5 -translate-x-1/2 grid-cols-3 items-end border-t border-slate-200 bg-white pb-3"
  >
    <RouterLink
      to="/"
      class="flex flex-col items-center gap-1 text-sm font-medium text-slate-800"
      active-class="text-brand-600!"
    >
      <AppIcon name="home" :size="28" />
      Beranda
    </RouterLink>

    <div class="relative flex justify-center">
      <button
        type="button"
        aria-label="Katalog"
        class="absolute -top-14 flex size-16 items-center justify-center rounded-full bg-brand-600 text-white shadow-lg ring-4 ring-white transition active:scale-95"
      >
        <AppIcon name="store" :size="30" />
      </button>
      <span class="text-sm font-medium text-slate-800">Katalog</span>
    </div>

    <button
      type="button"
      class="flex flex-col items-center gap-1 text-sm font-medium text-slate-800"
    >
      <AppIcon name="sliders" :size="28" />
      Pengaturan
    </button>
  </nav>
</template>
