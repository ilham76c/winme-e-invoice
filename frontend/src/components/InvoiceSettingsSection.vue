<script setup>
import { computed, ref } from 'vue'
import AppIcon from './AppIcon.vue'
import { paymentMethods } from '../constants/paymentMethods'

const expiry = defineModel('expiry', { type: String, default: '' })
const methods = defineModel('methods', { type: Array, default: () => [] })

defineProps({
  min: { type: String, required: true },
  max: { type: String, required: true },
  expiryError: { type: String, default: '' },
  methodsError: { type: String, default: '' },
})

const PREVIEW_COUNT = 8
const showAll = ref(false)
const visibleMethods = computed(() =>
  showAll.value ? paymentMethods : paymentMethods.slice(0, PREVIEW_COUNT),
)

function toggle(id) {
  methods.value = methods.value.includes(id)
    ? methods.value.filter((m) => m !== id)
    : [...methods.value, id]
}
</script>

<template>
  <section class="rounded-2xl bg-white p-4 shadow-md shadow-slate-300/60">
    <h2 class="text-xl font-bold text-brand-500">Pengaturan E-Invoice</h2>
    <p class="mt-1 text-sm text-slate-800">Daftar produk atau jasa yang ditagihkan</p>

    <!-- Masa berlaku -->
    <div class="mt-4">
      <label for="expiry" class="block text-base font-bold text-slate-900">
        Masa Berlaku Invoice <span class="text-red-500" aria-hidden="true">*</span>
      </label>
      <p class="mt-0.5 mb-2 text-xs text-slate-400">
        Pilih batas waktu invoice aktif (maks. 30 hari)
      </p>

      <div class="relative">
        <input
          id="expiry"
          v-model="expiry"
          type="date"
          :min="min"
          :max="max"
          :aria-invalid="!!expiryError"
          class="w-full appearance-none rounded-xl border border-slate-300 bg-white px-4 py-3.5 text-[15px] text-slate-900 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-200 aria-invalid:border-red-500 [&::-webkit-calendar-picker-indicator]:absolute [&::-webkit-calendar-picker-indicator]:inset-0 [&::-webkit-calendar-picker-indicator]:h-full [&::-webkit-calendar-picker-indicator]:w-full [&::-webkit-calendar-picker-indicator]:cursor-pointer [&::-webkit-calendar-picker-indicator]:opacity-0"
        />
        <AppIcon
          name="calendar"
          :size="24"
          class="pointer-events-none absolute top-1/2 right-4 -translate-y-1/2 text-slate-900"
        />
      </div>
      <p v-if="expiryError" class="mt-1.5 text-xs text-red-600" role="alert">{{ expiryError }}</p>
    </div>

    <!-- Metode pembayaran -->
    <div class="mt-5">
      <p class="text-base font-bold text-slate-900">
        Metode Pembayaran <span class="text-red-500" aria-hidden="true">*</span>
      </p>
      <p class="mt-0.5 mb-2 text-xs text-slate-400">Pilih metode pembayaran yang tersedia</p>

      <button
        type="button"
        class="flex w-full items-center justify-between rounded-xl border border-slate-300 bg-white px-5 py-4 text-left transition active:scale-[0.99]"
        :aria-expanded="showAll"
        @click="showAll = !showAll"
      >
        <span class="text-base font-bold text-slate-900">
          {{ showAll ? 'Tampilkan Lebih Sedikit' : 'Lihat Semua Metode' }}
        </span>
        <AppIcon
          name="chevron-right"
          :size="22"
          :stroke="2.5"
          class="transition-transform"
          :class="showAll ? 'rotate-90' : ''"
        />
      </button>

      <div class="mt-3 grid grid-cols-4 gap-2">
        <button
          v-for="m in visibleMethods"
          :key="m.id"
          type="button"
          :aria-pressed="methods.includes(m.id)"
          class="truncate rounded-lg px-1 py-2.5 text-sm font-medium transition active:scale-95"
          :class="
            methods.includes(m.id)
              ? 'bg-brand-500 text-white'
              : 'bg-brand-100 text-brand-600 hover:bg-brand-200'
          "
          @click="toggle(m.id)"
        >
          {{ m.label }}
        </button>
      </div>
      <p v-if="methodsError" class="mt-2 text-xs text-red-600" role="alert">{{ methodsError }}</p>
    </div>
  </section>
</template>
