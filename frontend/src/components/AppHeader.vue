<script setup>
import AppIcon from './AppIcon.vue'
</script>

<template>
  <header class="flex items-center justify-between px-4 pt-5 pb-4">
    <div class="flex items-center gap-3">
      <button type="button" aria-label="Buka menu" class="text-slate-800">
        <AppIcon name="menu" :size="26" />
      </button>

      <div class="flex items-center gap-1.5">
        <svg width="46" height="30" viewBox="0 0 46 30" aria-hidden="true">
          <path d="M2 2h10l7 18 7-18h10L24 28h-9z" fill="#1f63d8" />
          <path d="M26 2h8l-9 18-4-8z" fill="#f39a1e" />
        </svg>
        <div class="leading-none">
          <p class="text-[26px] font-bold tracking-tight text-slate-700">winme</p>
          <p class="text-[7px] font-semibold tracking-wide text-slate-500">Profit Instantly</p>
        </div>
      </div>
    </div>

    <div class="flex items-center gap-4">
      <button type="button" aria-label="Notifikasi" class="text-slate-900">
        <AppIcon name="bell" :size="26" />
      </button>
      <button type="button" aria-label="Profil" class="size-10 rounded-full bg-slate-400"></button>
    </div>
  </header>
</template>
