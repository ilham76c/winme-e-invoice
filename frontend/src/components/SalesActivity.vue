<script setup>
import AppIcon from './AppIcon.vue'

defineProps({
  stats: { type: Array, required: true },
})
</script>

<template>
  <section>
    <h2 class="mb-3 text-[15px] font-semibold text-slate-900">Aktivitas Penjualanmu</h2>
    <div class="grid grid-cols-3 gap-3">
      <button
        v-for="s in stats"
        :key="s.label"
        type="button"
        class="rounded-xl bg-white p-3 text-left shadow-sm ring-1 ring-slate-100 transition active:scale-[0.98]"
      >
        <div class="flex items-start justify-between gap-1">
          <p class="text-[13px] leading-tight font-semibold text-slate-900">{{ s.label }}</p>
          <AppIcon name="arrow-circle" :size="16" class="shrink-0 text-brand-600" />
        </div>
        <div class="mt-3 flex items-center justify-between rounded-lg bg-brand-50 px-2 py-2">
          <AppIcon :name="s.icon" :size="26" :stroke="1.6" class="text-brand-600" />
          <span class="text-xl font-semibold text-brand-600">{{ s.count }}</span>
        </div>
      </button>
    </div>
  </section>
</template>