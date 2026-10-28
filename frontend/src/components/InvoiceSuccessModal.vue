<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue'
import AppIcon from './AppIcon.vue'

const props = defineProps({
  url: { type: String, required: true },
})
const emit = defineEmits(['close'])

const dialogRef = ref(null)
const copied = ref(false)
const copyFailed = ref(false)
let copiedTimer

async function copyLink() {
  try {
    await navigator.clipboard.writeText(props.url)
    copyFailed.value = false
    copied.value = true
    clearTimeout(copiedTimer)
    copiedTimer = setTimeout(() => (copied.value = false), 2000)
  } catch {
    copyFailed.value = true
  }
}

async function share() {
  if (navigator.share) {
    try {
      await navigator.share({
        title: 'E-Invoice',
        text: 'Berikut link E-Invoice Anda:',
        url: props.url,
      })
    } catch (err) {
      // pengguna menutup menu bagikan: bukan error
      if (err?.name !== 'AbortError') await copyLink()
    }
  } else {
    // browser tanpa Web Share API (umumnya desktop): salin link saja
    await copyLink()
  }
}

function onKeydown(e) {
  if (e.key === 'Escape') emit('close')
}

onMounted(() => {
  document.body.style.overflow = 'hidden'
  document.addEventListener('keydown', onKeydown)
  dialogRef.value?.focus()
})

onBeforeUnmount(() => {
  document.body.style.overflow = ''
  document.removeEventListener('keydown', onKeydown)
  clearTimeout(copiedTimer)
})
</script>

<template>
  <Teleport to="body">
    <div
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-5"
      @click.self="emit('close')"
    >
      <div
        ref="dialogRef"
        role="dialog"
        aria-modal="true"
        aria-labelledby="invoice-success-title"
        aria-describedby="invoice-success-desc"
        tabindex="-1"
        class="relative w-full max-w-sm rounded-3xl bg-white px-6 pt-6 pb-6 text-center shadow-xl outline-none"
      >
        <button
          type="button"
          aria-label="Tutup"
          class="absolute top-5 right-5 text-slate-900"
          @click="emit('close')"
        >
          <AppIcon name="x" :size="28" :stroke="2.5" />
        </button>

        <div
          class="mx-auto mt-6 flex size-18 items-center justify-center rounded-full bg-brand-500 text-white"
        >
          <AppIcon name="check" :size="40" :stroke="3.5" />
        </div>

        <h2 id="invoice-success-title" class="mt-6 text-lg font-bold text-slate-900">
          E-Invoice Berhasil Dibuat!
        </h2>
        <p id="invoice-success-desc" class="mt-1 text-sm leading-snug text-slate-800">
          Invoice berhasil ditambahkan. Bagikan link E-invoice kepada pelanggan
        </p>

        <div class="mt-4 rounded-xl bg-brand-100 p-3">
          <p class="text-base font-bold text-slate-900">E-Invoice</p>
          <div
            class="mt-2 flex items-center gap-2 rounded-lg bg-white py-1.5 pr-1.5 pl-3 shadow-sm"
          >
            <p class="min-w-0 flex-1 truncate text-left text-xs text-slate-800" :title="url">
              {{ url }}
            </p>
            <button
              type="button"
              class="inline-flex shrink-0 items-center gap-1 rounded-md bg-brand-100 px-2.5 py-1.5 text-xs font-semibold text-brand-600 transition active:scale-95"
              @click="copyLink"
            >
              <AppIcon :name="copied ? 'check' : 'copy'" :size="14" :stroke="2.5" />
              {{ copied ? 'Tersalin' : 'Salin' }}
            </button>
          </div>
          <p v-if="copyFailed" class="mt-2 text-left text-xs text-red-600" role="alert">
            Gagal menyalin. Tekan lama pada link untuk menyalin manual.
          </p>
        </div>

        <button
          type="button"
          class="mt-6 flex w-full items-center justify-center gap-2 rounded-2xl bg-brand-500 py-4 text-lg font-semibold text-white shadow-sm transition active:scale-[0.99]"
          @click="share"
        >
          <AppIcon name="share" :size="24" />
          Bagikan
        </button>
      </div>
    </div>
  </Teleport>
</template>
