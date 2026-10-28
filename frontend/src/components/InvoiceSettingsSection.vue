<script setup>
import { computed, ref } from 'vue'
import AppIcon from './AppIcon.vue'
import { formatDate } from '../utils/format'

const expiry = defineModel('expiry', { type: String, default: '' })
const methodId = defineModel('methodId', { type: Number, default: null })

const props = defineProps({
  min: { type: String, required: true },
  max: { type: String, required: true },
  expiryError: { type: String, default: '' },
  methodsError: { type: String, default: '' },
  options: { type: Array, default: () => [] }, // [{ id, code, name }]
  loading: { type: Boolean, default: false },
  loadError: { type: String, default: '' },
})
const emit = defineEmits(['retry'])

function openPicker(e) {
  try {
    e.target.showPicker?.()
  } catch {
    // picker sudah terbuka atau tidak didukung; abaikan
  }
}

// Tombol "Lihat Semua Metode" hanya relevan bila metode lebih banyak dari yang ditampilkan awal
const PREVIEW_COUNT = 8
const showAll = ref(false)
const canExpand = computed(() => props.options.length > PREVIEW_COUNT)
const visibleOptions = computed(() =>
  showAll.value || !canExpand.value ? props.options : props.options.slice(0, PREVIEW_COUNT),
)
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
      <p class="mt-0.5 mb-2 text-xs text-slate-400">Pilih batas waktu invoice aktif (maks. 30 hari)</p>

      <div
        class="relative flex items-center justify-between rounded-xl border bg-white px-4 py-3.5 text-[15px] focus-within:border-brand-500 focus-within:ring-2 focus-within:ring-brand-200"
        :class="expiryError ? 'border-red-500' : 'border-slate-300'"
      >
        <!-- Tampilan DD-MM-YYYY; input tanggal asli ada di atasnya (transparan) untuk membuka kalender -->
        <span :class="expiry ? 'text-slate-900' : 'text-slate-400'" aria-hidden="true">
          {{ formatDate(expiry) || 'DD-MM-YYYY' }}
        </span>
        <AppIcon name="calendar" :size="24" class="text-slate-900" />
        <input
          id="expiry"
          v-model="expiry"
          type="date"
          :min="min"
          :max="max"
          :aria-invalid="!!expiryError"
          class="absolute inset-0 h-full w-full cursor-pointer opacity-0 [&::-webkit-calendar-picker-indicator]:absolute [&::-webkit-calendar-picker-indicator]:inset-0 [&::-webkit-calendar-picker-indicator]:h-full [&::-webkit-calendar-picker-indicator]:w-full [&::-webkit-calendar-picker-indicator]:cursor-pointer"
          @click="openPicker"
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
        v-if="canExpand"
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

      <!-- Memuat -->
      <div v-if="loading" class="mt-3 grid grid-cols-3 gap-2" aria-busy="true" aria-label="Memuat metode pembayaran">
        <span v-for="n in 3" :key="n" class="h-10 animate-pulse rounded-lg bg-brand-100"></span>
      </div>

      <!-- Gagal memuat -->
      <div
        v-else-if="loadError"
        class="mt-3 flex items-center justify-between gap-3 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700"
        role="alert"
      >
        <span>{{ loadError }}</span>
        <button type="button" class="shrink-0 font-semibold underline" @click="emit('retry')">
          Coba lagi
        </button>
      </div>

      <p v-else-if="!options.length" class="mt-3 text-sm text-slate-400">
        Belum ada metode pembayaran yang tersedia.
      </p>

      <div v-else class="mt-3 grid grid-cols-3 gap-2" role="group" aria-label="Metode pembayaran">
        <button
          v-for="m in visibleOptions"
          :key="m.id"
          type="button"
          :aria-pressed="methodId === m.id"
          class="rounded-lg px-2 py-2.5 text-center text-sm leading-tight font-medium break-words transition active:scale-95"
          :class="
            methodId === m.id
              ? 'bg-brand-500 text-white'
              : 'bg-brand-100 text-brand-600 hover:bg-brand-200'
          "
          @click="methodId = m.id"
        >
          {{ m.name }}
        </button>
      </div>

      <p v-if="methodsError" class="mt-2 text-xs text-red-600" role="alert">{{ methodsError }}</p>
    </div>
  </section>
</template>