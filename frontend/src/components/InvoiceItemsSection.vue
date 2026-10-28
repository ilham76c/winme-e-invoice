<script setup>
import { computed, reactive, ref } from 'vue'
import AppIcon from './AppIcon.vue'
import { rupiah } from '../utils/format'

const items = defineModel('items', { type: Array, required: true })
const mode = defineModel('mode', { type: String, default: 'custom' })
defineProps({ error: { type: String, default: '' } })

// Contoh katalog; ganti dengan data produk dari API
const catalog = [
  { name: 'Pembuatan Website', price: 600000 },
  { name: 'Fitur Menu', price: 100000 },
  { name: 'Domain & Hosting', price: 250000 },
]

const total = computed(() => items.value.reduce((sum, i) => sum + i.qty * i.price, 0))

let nextId = 1
const adding = ref(false)
const draft = reactive({ name: '', qty: 1, price: '' })
const draftError = ref('')

function pushItem(name, qty, price) {
  items.value = [...items.value, { id: nextId++, name, qty, price }]
}

function saveDraft() {
  const qty = Number(draft.qty)
  const price = Number(draft.price)
  if (!draft.name.trim()) return (draftError.value = 'Nama produk wajib diisi.')
  if (!Number.isInteger(qty) || qty < 1) return (draftError.value = 'Jumlah minimal 1.')
  if (!(price > 0)) return (draftError.value = 'Harga harus lebih dari 0.')

  pushItem(draft.name.trim(), qty, price)
  Object.assign(draft, { name: '', qty: 1, price: '' })
  draftError.value = ''
  adding.value = false
}

function remove(id) {
  items.value = items.value.filter((i) => i.id !== id)
}

const inputClass =
  'w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-200'
</script>

<template>
  <section class="rounded-2xl bg-white p-4 shadow-md shadow-slate-300/60">
    <h2 class="text-xl font-bold text-brand-500">Detail Tagihan</h2>
    <p class="mt-1 text-sm text-slate-800">Daftar produk atau jasa yang ditagihkan</p>

    <div class="mt-4 space-y-3">
      <!-- Ambil dari katalog -->
      <div class="rounded-xl border border-slate-200 p-4">
        <label class="flex cursor-pointer items-center gap-3">
          <input
            v-model="mode"
            type="radio"
            name="billing-mode"
            value="catalog"
            class="peer sr-only"
          />
          <span
            class="size-6 shrink-0 rounded-full border-2 border-slate-300 bg-white peer-checked:border-[7px] peer-checked:border-brand-500 peer-focus-visible:ring-2 peer-focus-visible:ring-brand-200"
          ></span>
          <span
            class="flex size-13 shrink-0 items-center justify-center rounded-xl bg-brand-100 text-brand-600"
          >
            <AppIcon name="book" :size="26" :stroke="1.6" />
          </span>
          <span>
            <span class="block text-base font-bold text-slate-900">Ambil dari katalog</span>
            <span class="block text-xs text-slate-400">Pilih dari daftar produk</span>
          </span>
        </label>

        <ul v-if="mode === 'catalog'" class="mt-4 space-y-2">
          <li
            v-for="p in catalog"
            :key="p.name"
            class="flex items-center justify-between gap-2 rounded-lg border border-slate-200 px-3 py-2.5"
          >
            <div class="min-w-0">
              <p class="truncate text-sm font-semibold">{{ p.name }}</p>
              <p class="text-xs text-slate-500">{{ rupiah(p.price) }}</p>
            </div>
            <button
              type="button"
              class="shrink-0 rounded-lg bg-brand-100 px-3 py-1.5 text-xs font-semibold text-brand-600 active:scale-95"
              @click="pushItem(p.name, 1, p.price)"
            >
              + Tambah
            </button>
          </li>
        </ul>
      </div>

      <!-- Kustom -->
      <div class="rounded-xl border border-slate-200 p-4">
        <label class="flex cursor-pointer items-center gap-3">
          <input
            v-model="mode"
            type="radio"
            name="billing-mode"
            value="custom"
            class="peer sr-only"
          />
          <span
            class="size-6 shrink-0 rounded-full border-2 border-slate-300 bg-white peer-checked:border-[7px] peer-checked:border-brand-500 peer-focus-visible:ring-2 peer-focus-visible:ring-brand-200"
          ></span>
          <span
            class="flex size-13 shrink-0 items-center justify-center rounded-xl bg-brand-100 text-brand-600"
          >
            <AppIcon name="edit" :size="26" :stroke="1.6" />
          </span>
          <span>
            <span class="block text-base font-bold text-slate-900">Kustom</span>
            <span class="block text-xs text-slate-400">Masukkan detail tagihan secara manual</span>
          </span>
        </label>

        <div v-if="mode === 'custom'" class="mt-4">
          <div class="flex items-center justify-between">
            <button
              type="button"
              class="inline-flex items-center gap-1.5 rounded-xl bg-brand-500 px-5 py-3 text-base font-semibold text-white transition active:scale-95"
              @click="adding = !adding"
            >
              <AppIcon name="plus" :size="20" :stroke="2.5" />
              Tambah Produk
            </button>
            <p class="text-sm text-slate-800">
              <span class="font-semibold text-brand-500">{{ items.length }}</span> Produk
            </p>
          </div>

          <!-- Form tambah produk -->
          <div v-if="adding" class="mt-3 space-y-3 rounded-xl bg-brand-50 p-3">
            <input
              v-model="draft.name"
              type="text"
              placeholder="Nama produk atau jasa"
              aria-label="Nama produk"
              :class="inputClass"
            />
            <div class="grid grid-cols-[5rem_1fr] gap-3">
              <input
                v-model="draft.qty"
                type="number"
                min="1"
                placeholder="Jumlah"
                aria-label="Jumlah"
                :class="inputClass"
              />
              <input
                v-model="draft.price"
                type="number"
                min="0"
                placeholder="Harga satuan (Rp)"
                aria-label="Harga satuan"
                :class="inputClass"
              />
            </div>
            <p v-if="draftError" class="text-xs text-red-600" role="alert">{{ draftError }}</p>
            <div class="flex justify-end gap-2">
              <button
                type="button"
                class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600"
                @click="adding = false"
              >
                Batal
              </button>
              <button
                type="button"
                class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-semibold text-white active:scale-95"
                @click="saveDraft"
              >
                Simpan produk
              </button>
            </div>
          </div>
        </div>

        <!-- Daftar produk (dipakai oleh mode katalog maupun kustom) -->
        <ul v-if="items.length" class="mt-4 space-y-3">
          <li
            v-for="item in items"
            :key="item.id"
            class="flex overflow-hidden rounded-xl border border-slate-200"
          >
            <div class="min-w-0 flex-1 px-4 py-3">
              <p class="truncate text-base font-bold text-slate-900">{{ item.name }}</p>
              <div class="mt-0.5 flex items-center justify-between gap-2 text-sm">
                <span class="text-slate-800">{{ item.qty }} x {{ rupiah(item.price) }}</span>
                <span class="font-semibold text-brand-500">{{
                  rupiah(item.qty * item.price)
                }}</span>
              </div>
            </div>
            <button
              type="button"
              :aria-label="`Hapus ${item.name}`"
              class="flex w-14 shrink-0 items-center justify-center border-l border-slate-200 bg-red-50 text-red-600 transition active:bg-red-100"
              @click="remove(item.id)"
            >
              <AppIcon name="trash" :size="22" />
            </button>
          </li>
        </ul>
        <p v-else class="mt-4 text-center text-sm text-slate-400">Belum ada produk ditambahkan.</p>

        <div class="mt-4 flex items-center justify-between rounded-xl bg-brand-100 px-4 py-3.5">
          <span class="text-base text-slate-800">Total tagihan</span>
          <span class="text-xl font-bold text-slate-900">{{ rupiah(total) }}</span>
        </div>
      </div>
    </div>

    <p v-if="error" class="mt-3 text-xs text-red-600" role="alert">{{ error }}</p>
  </section>
</template>
