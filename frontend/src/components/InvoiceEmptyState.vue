<script setup>
defineEmits(['create'])
</script>

<template>
  <section class="flex flex-col items-center px-8 pt-16 text-center">
    <svg width="120" height="130" viewBox="0 0 120 130" aria-hidden="true">
      <rect x="18" y="10" width="62" height="108" rx="10" fill="#2f7cf0" />
      <rect x="24" y="20" width="50" height="88" rx="4" fill="#fff" />
      <g fill="#2f7cf0">
        <rect x="30" y="34" width="38" height="4" rx="2" />
        <rect x="30" y="44" width="38" height="4" rx="2" />
        <rect x="30" y="54" width="38" height="4" rx="2" />
        <rect x="30" y="64" width="26" height="4" rx="2" />
      </g>
      <path d="M60 98h46v8H64a12 12 0 0 1-4-8z" fill="#e5e7eb" />
      <circle cx="30" cy="92" r="9" fill="#1f63d8" />
      <text x="30" y="96" text-anchor="middle" font-size="11" font-weight="700" fill="#fff">%</text>
      <circle cx="90" cy="24" r="16" fill="#f39a1e" />
      <text x="90" y="28" text-anchor="middle" font-size="10" font-weight="800" fill="#fff">
        TAX
      </text>
    </svg>

    <p class="mt-6 text-[13px] leading-relaxed text-slate-400">
      Kelola invoice dan pembayaran pelanggan secara profesional. Kirim tagihan kapan saja, di mana
      saja.
    </p>

    <button
      type="button"
      class="mt-3 text-sm font-semibold text-brand-500 underline underline-offset-4"
      @click="$emit('create')"
    >
      + Buat E-Invoice
    </button>
  </section>
</template>
