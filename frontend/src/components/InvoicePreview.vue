<script setup>
import { computed } from 'vue'
import { formatDate, rupiah } from '../utils/format'

const props = defineProps({
  customer: { type: Object, required: true },
  items: { type: Array, default: () => [] },
  expiry: { type: String, default: '' },
  channel: { type: String, default: '' },
  merchant: { type: Object, required: true },
})

const total = computed(() => props.items.reduce((sum, i) => sum + i.qty * i.price, 0))

// TODO: ambil dari backend bila ada biaya layanan per metode pembayaran
const serviceFee = 0
const grandTotal = computed(() => total.value + serviceFee)

const billTo = computed(() => {
  const c = props.customer
  const lines = [c.name || '-', c.whatsapp || '-', c.email || '-']
  if (c.address) lines.push(c.address)
  return lines
})
</script>

<template>
  <section class="rounded-2xl bg-white p-4 shadow-md shadow-slate-300/60">
    <h2 class="text-center text-lg font-bold text-brand-500">Contoh Tampilan E-invoice</h2>
    <p class="mt-0.5 text-center text-xs text-slate-400">pratinjau ini digunakan untuk referensi saja</p>

    <div class="mt-3 rounded-lg border border-slate-100 p-3 text-[11px] leading-snug text-slate-900 shadow-sm">
      <div class="flex items-center justify-between font-semibold">
        <span class="text-xs">{{ merchant.name }}</span>
        <span class="text-xs">E-Invoice</span>
      </div>

      <span class="mt-5 inline-block w-26 rounded bg-brand-500 py-1 text-center text-xs font-semibold text-white">
        Bill To
      </span>

      <div class="mt-2 flex justify-between gap-3 text-xs">
        <div class="min-w-0 space-y-0.5 break-words">
          <p v-for="(line, i) in billTo" :key="i">{{ line }}</p>
        </div>
        <div class="shrink-0 space-y-0.5 text-right">
          <p>No Invoice : ********</p>
          <p>Due Date : {{ formatDate(expiry) || '-' }}</p>
          <p>Via : {{ channel || '-' }}</p>
        </div>
      </div>

      <table class="mt-4 w-full table-fixed border-collapse text-[10px]">
        <thead>
          <tr class="bg-brand-500 text-xs text-white">
            <th class="w-[17%] border-r border-white py-2 font-semibold">Jumlah</th>
            <th class="w-[37%] border-r border-white py-2 font-semibold">Nama Produk</th>
            <th class="w-[23%] border-r border-white py-2 font-semibold">Harga</th>
            <th class="w-[23%] py-2 font-semibold">Total</th>
          </tr>
        </thead>
        <tbody class="bg-brand-50">
          <tr v-if="!items.length">
            <td colspan="4" class="py-4 text-center text-slate-400">Belum ada produk</td>
          </tr>
          <tr v-for="r in items" :key="r.id">
            <td class="py-3 text-center">{{ r.qty }}</td>
            <td class="px-2 py-3 break-words">{{ r.name }}</td>
            <td class="px-1 py-3 text-right">{{ rupiah(r.price) }}</td>
            <td class="px-1 py-3 text-right">{{ rupiah(r.qty * r.price) }}</td>
          </tr>
          <tr class="border-t border-slate-200 font-bold">
            <td colspan="2"></td>
            <td class="px-1 py-3 text-right">Total Tagihan</td>
            <td class="px-1 py-3 text-right">{{ rupiah(total) }}</td>
          </tr>
        </tbody>
      </table>

      <div class="mt-5 grid grid-cols-2 gap-3 text-xs">
        <div class="space-y-0.5">
          <p>Pembayaran Via :</p>
          <p>Channel : {{ channel || '-' }}</p>
          <p>No Va : ********</p>
          <p>Nominal : {{ rupiah(total) }}</p>
          <p>Biaya Layanan: {{ rupiah(serviceFee) }}</p>
          <p>Total : {{ rupiah(grandTotal) }}</p>
        </div>
        <div class="min-w-0 space-y-0.5 break-words">
          <p class="font-bold">{{ merchant.name }}</p>
          <p>{{ merchant.contact }}</p>
          <p>{{ merchant.phone }}</p>
          <p>{{ merchant.email }}</p>
        </div>
      </div>

      <p class="mt-6 text-center text-xs text-slate-600">Terimakasih telah menjadi kustomer kami</p>

      <p class="mt-4 flex items-center gap-1.5 text-xs text-slate-400">
        Dibuat dengan
        <svg width="20" height="14" viewBox="0 0 46 30" aria-hidden="true">
          <path d="M2 2h10l7 18 7-18h10L24 28h-9z" fill="#1f63d8" />
          <path d="M26 2h8l-9 18-4-8z" fill="#f39a1e" />
        </svg>
        <span class="font-bold text-slate-700">winme</span>
      </p>
    </div>
  </section>
</template>