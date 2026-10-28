<script setup>
import AppIcon from './AppIcon.vue'

defineProps({
  invoice: { type: Object, required: true },
})
defineEmits(['menu'])

const rupiah = (n) => 'Rp' + new Intl.NumberFormat('id-ID').format(n)
</script>

<template>
  <article class="rounded-xl bg-brand-200 px-3 py-3 shadow-md shadow-slate-300/60">
    <div class="flex items-start justify-between">
      <span
        v-if="invoice.demo"
        class="inline-flex items-center gap-1 rounded bg-slate-300/70 px-1.5 py-0.5 text-[11px] text-slate-500"
      >
        Demo <span class="size-1.5 rounded-full bg-slate-500"></span>
      </span>
      <span v-else></span>
      <button
        type="button"
        aria-label="Opsi invoice"
        class="-mt-1 -mr-1 p-1 text-slate-900"
        @click="$emit('menu', invoice)"
      >
        <AppIcon name="dots-vertical" :size="20" :stroke="3" />
      </button>
    </div>

    <p class="mt-1 text-sm font-bold text-slate-900">{{ invoice.name }}</p>

    <div class="mt-0.5 flex items-end justify-between">
      <p class="text-sm font-bold text-slate-900">{{ rupiah(invoice.amount) }}</p>
      <p class="text-xs text-slate-800">{{ invoice.number }}</p>
    </div>

    <p v-if="invoice.demo" class="mt-1 text-right text-xs text-slate-400">Demo</p>
  </article>
</template>
