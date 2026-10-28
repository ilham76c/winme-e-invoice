<script setup>
import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'
import AppIcon from '../components/AppIcon.vue'
import InvoiceStatCard from '../components/InvoiceStatCard.vue'
import InvoiceCard from '../components/InvoiceCard.vue'
import InvoiceEmptyState from '../components/InvoiceEmptyState.vue'

const router = useRouter()

const invoices = ref([
  {
    id: 1,
    name: 'INV-NamaTujuan',
    amount: 250000,
    number: 'INV-20260424-2414',
    status: 'unpaid',
    date: '2026-04-24',
    demo: true,
  },
])

const search = ref('')
const sort = ref('newest')
const dateFilter = ref('')
const dateType = ref('text')

const rupiah = (n) => new Intl.NumberFormat('id-ID').format(n)

const visibleInvoices = computed(() => {
  const q = search.value.trim().toLowerCase()
  return invoices.value
    .filter((i) => !q || i.name.toLowerCase().includes(q) || i.number.toLowerCase().includes(q))
    .filter((i) => !dateFilter.value || i.date === dateFilter.value)
    .toSorted((a, b) =>
      sort.value === 'newest' ? b.date.localeCompare(a.date) : a.date.localeCompare(b.date),
    )
})

const paid = computed(() => invoices.value.filter((i) => i.status === 'paid'))
const paidCount = computed(() => (paid.value.length ? String(paid.value.length) : '–'))
const paidTotal = computed(() =>
  paid.value.length ? rupiah(paid.value.reduce((sum, i) => sum + i.amount, 0)) : '–',
)

const hasRealInvoices = computed(() => invoices.value.some((i) => !i.demo))

function createInvoice() {
  router.push({ name: 'invoice-create' })
}

function showDatePicker(e) {
  dateType.value = 'date'
  // beri waktu browser mengganti tipe input sebelum membuka picker
  requestAnimationFrame(() => e.target.showPicker?.())
}

function hideDatePicker() {
  if (!dateFilter.value) dateType.value = 'text'
}
</script>

<template>
  <div class="min-h-screen pb-10">
    <!-- Header -->
    <header class="flex items-center gap-3 border-b border-slate-200 bg-white px-4 py-5">
      <RouterLink to="/" aria-label="Kembali" class="text-slate-900">
        <AppIcon name="chevron-left" :size="28" :stroke="2.5" />
      </RouterLink>
      <h1 class="text-xl font-bold text-slate-900">E-Invoice</h1>
    </header>

    <main class="space-y-5 px-4 pt-5">
      <!-- Ringkasan -->
      <section class="space-y-4">
        <div class="flex items-center justify-between gap-3">
          <h2 class="text-base font-bold text-slate-900">Ringkasan E-Invoice</h2>
          <label
            class="flex w-47.5 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2.5 shadow-sm focus-within:border-brand-500"
          >
            <AppIcon name="calendar" :size="20" class="shrink-0 text-slate-800" />
            <input
              v-model="dateFilter"
              :type="dateType"
              placeholder="Pilih tanggal"
              aria-label="Pilih tanggal"
              class="w-full min-w-0 bg-transparent text-sm text-slate-700 outline-none placeholder:text-slate-400"
              @focus="showDatePicker"
              @blur="hideDatePicker"
            />
          </label>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <InvoiceStatCard title="Terbayar" :value="paidCount">
            <template #icon><AppIcon name="cart" :size="20" /></template>
          </InvoiceStatCard>

          <InvoiceStatCard title="Total Terbayar" prefix="Rp" :value="paidTotal">
            <template #icon>
              <span
                class="flex size-5 items-center justify-center rounded-full border-2 border-white/90 text-[8px] font-bold"
              >
                Rp
              </span>
            </template>
          </InvoiceStatCard>
        </div>
      </section>

      <!-- Daftar -->
      <section class="space-y-4">
        <div class="flex items-center justify-between">
          <h2 class="text-base font-bold text-slate-900">Daftar E-Invoice</h2>
          <div class="relative">
            <select
              v-model="sort"
              aria-label="Urutkan"
              class="appearance-none rounded-lg border border-slate-300 bg-white py-1.5 pr-8 pl-3 text-sm text-slate-800 outline-none focus:border-brand-500"
            >
              <option value="newest">Terbaru</option>
              <option value="oldest">Terlama</option>
            </select>
            <AppIcon
              name="chevron-down"
              :size="16"
              :stroke="3"
              class="pointer-events-none absolute top-1/2 right-2.5 -translate-y-1/2 text-slate-900"
            />
          </div>
        </div>

        <div class="flex items-center gap-3">
          <label
            class="flex min-w-0 flex-1 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-3 shadow-sm focus-within:border-brand-500"
          >
            <AppIcon name="search" :size="20" class="shrink-0 text-slate-500" />
            <input
              v-model="search"
              type="search"
              placeholder="Cari Produk"
              aria-label="Cari invoice"
              class="w-full min-w-0 bg-transparent text-sm outline-none placeholder:text-slate-400"
            />
          </label>

          <button
            type="button"
            class="shrink-0 rounded-xl bg-brand-500 px-4 py-3 text-sm font-semibold text-white shadow-sm transition active:scale-95"
            @click="createInvoice"
          >
            + Tambah E-Invoice
          </button>
        </div>

        <ul class="space-y-3">
          <li v-for="invoice in visibleInvoices" :key="invoice.id">
            <InvoiceCard :invoice="invoice" />
          </li>
        </ul>

        <p v-if="!visibleInvoices.length" class="py-6 text-center text-sm text-slate-500">
          Tidak ada invoice yang cocok dengan pencarianmu.
        </p>
      </section>

      <InvoiceEmptyState v-if="!hasRealInvoices" @create="createInvoice" />
    </main>
  </div>
</template>
