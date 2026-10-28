<template>
  <section class="relative flex h-30 overflow-hidden rounded-xl bg-linear-to-r from-brand-100 to-brand-200">
    <div class="relative z-10 flex w-[62%] flex-col justify-center bg-brand-600 py-4 pr-6 pl-4 text-white [clip-path:polygon(0_0,100%_0,88%_100%,0_100%)]">
      <p class="text-[15px] font-medium">Dulu ribet jualan manual</p>
      <p class="mt-1 text-[21px] leading-[1.15] font-bold">
        sekarang semua serba otomatis!
      </p>
    </div>

    <div class="absolute inset-y-0 right-0 flex w-[45%] items-end justify-center">
      <!-- Ilustrasi sederhana; ganti dengan <img> foto promo bila sudah ada -->
      <svg width="110" height="110" viewBox="0 0 110 110" aria-hidden="true">
        <circle cx="62" cy="40" r="22" fill="#f1c9a5" />
        <path d="M38 36c0-16 12-24 26-22 12 2 18 12 16 24-6-12-14-14-22-14s-14 4-20 12z" fill="#2a1b17" />
        <path d="M18 110c0-24 16-34 44-34s44 10 44 34z" fill="#8fb4e6" />
        <rect x="14" y="58" width="26" height="46" rx="5" fill="#1f2937" />
        <rect x="17" y="62" width="20" height="36" rx="3" fill="#2f7cf0" />
      </svg>
    </div>
  </section>
</template>