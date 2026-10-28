<script setup>
import AppIcon from './AppIcon.vue'

defineProps({
  available: { type: Number, default: 0 },
  processing: { type: Number, default: 0 },
})
defineEmits(['withdraw'])

const rupiah = (n) => new Intl.NumberFormat('id-ID').format(n)
</script>

<template>
  <section class="overflow-hidden rounded-2xl shadow-sm">
    <div class="relative bg-linear-to-br from-brand-600 to-brand-500 px-4 pt-4 pb-5 text-white">
      <svg
        class="pointer-events-none absolute -right-6 -bottom-6 opacity-10"
        width="200"
        height="160"
        viewBox="0 0 200 160"
        aria-hidden="true"
      >
        <path d="M10 10h50l40 100 40-100h50L120 150H80z" fill="#fff" />
      </svg>

      <div class="relative flex items-start justify-between">
        <div>
          <p class="flex items-center gap-1.5 text-[15px] font-medium">
            Saldo Siap Ditarik
            <AppIcon name="info" :size="16" />
          </p>
          <p class="mt-2 flex items-start gap-1 font-bold">
            <span class="mt-1 text-sm">Rp</span>
            <span class="text-3xl leading-none">{{ rupiah(available) }}</span>
          </p>
        </div>

        <button
          type="button"
          class="flex items-center gap-1.5 rounded-lg bg-white px-3 py-2 text-xs font-semibold text-slate-800 shadow-sm transition active:scale-95"
          @click="$emit('withdraw')"
        >
          <AppIcon name="wallet" :size="18" class="text-brand-600" />
          Tarik Saldo
        </button>
      </div>
    </div>

    <div class="flex items-center justify-between bg-brand-200 px-4 py-3 text-sm text-slate-800">
      <p class="flex items-center gap-1.5">
        Saldo Diproses
        <AppIcon name="info" :size="16" />
      </p>
      <p class="font-semibold">Rp{{ rupiah(processing) }}</p>
    </div>
  </section>
</template>
