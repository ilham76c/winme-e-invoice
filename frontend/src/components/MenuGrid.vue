<script setup>
import AppIcon from './AppIcon.vue'

const items = [
  { label: 'Link Pembayaran', icon: 'link' },
  { label: 'E-Invoice', icon: 'receipt', to: '/invoices' },
  { label: 'Jual Produk', icon: 'package' },
  { label: 'Landing Page', icon: 'layout' },
  { label: 'Pesanan', icon: 'clipboard' },
  { label: 'Laporan', icon: 'chart' },
  { label: 'Affiliate', icon: 'user-plus' },
  { label: 'Lainnya', icon: 'grid-plus' },
]
</script>

<template>
  <nav aria-label="Menu utama" class="grid grid-cols-4 gap-x-3 gap-y-4">
    <component
      :is="item.to ? 'RouterLink' : 'button'"
      v-for="item in items"
      :key="item.label"
      :to="item.to"
      :type="item.to ? undefined : 'button'"
      class="group flex flex-col items-center gap-2 text-center"
    >
      <span
        class="flex size-18 items-center justify-center rounded-2xl bg-brand-100 text-brand-600 ring-1 ring-brand-200/60 transition group-hover:bg-brand-200 group-active:scale-95"
      >
        <AppIcon :name="item.icon" :size="34" :stroke="1.6" />
      </span>
      <span class="text-[13px] leading-tight font-medium text-slate-800">{{ item.label }}</span>
    </component>
  </nav>
</template>