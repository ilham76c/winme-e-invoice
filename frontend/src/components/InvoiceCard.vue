<script setup>
import { onBeforeUnmount, ref, watch } from 'vue'
import AppIcon from './AppIcon.vue'
import { formatDate, rupiah } from '../utils/format'

const props = defineProps({
  invoice: { type: Object, required: true },
})

const statusMap = {
  active: { label: 'Aktif', class: 'bg-emerald-100 text-emerald-700' },
  paid: { label: 'Lunas', class: 'bg-brand-500 text-white' },
  expired: { label: 'Kedaluwarsa', class: 'bg-amber-100 text-amber-700' },
  cancelled: { label: 'Dibatalkan', class: 'bg-red-100 text-red-700' },
}
const status = () =>
  statusMap[props.invoice.status] ?? {
    label: props.invoice.status ?? '-',
    class: 'bg-slate-200 text-slate-600',
  }

// Menu titik tiga
const open = ref(false)
const copied = ref(false)
let copiedTimer

function close() {
  open.value = false
}
function onEsc(e) {
  if (e.key === 'Escape') close()
}
watch(open, (isOpen) => {
  const method = isOpen ? 'addEventListener' : 'removeEventListener'
  document[method]('click', close)
  document[method]('keydown', onEsc)
})
onBeforeUnmount(() => {
  document.removeEventListener('click', close)
  document.removeEventListener('keydown', onEsc)
  clearTimeout(copiedTimer)
})

async function copyLink() {
  try {
    await navigator.clipboard.writeText(props.invoice.publicUrl)
    copied.value = true
    clearTimeout(copiedTimer)
    copiedTimer = setTimeout(() => (copied.value = false), 1500)
  } catch {
    // clipboard tidak tersedia; abaikan
  }
}
</script>

<template>
  <article class="rounded-xl bg-brand-200 px-3 py-3 shadow-md shadow-slate-300/60">
    <div class="flex items-start justify-between">
      <span
        class="inline-flex items-center rounded px-1.5 py-0.5 text-[11px] font-medium"
        :class="status().class"
      >
        {{ status().label }}
      </span>

      <div class="relative">
        <button
          type="button"
          aria-label="Opsi invoice"
          aria-haspopup="menu"
          :aria-expanded="open"
          class="-mt-1 -mr-1 p-1 text-slate-900"
          @click.stop="open = !open"
        >
          <AppIcon name="dots-vertical" :size="20" :stroke="3" />
        </button>

        <ul
          v-if="open"
          role="menu"
          class="absolute right-0 z-10 mt-1 w-40 overflow-hidden rounded-lg bg-white py-1 text-sm shadow-lg ring-1 ring-slate-200"
        >
          <li role="none">
            <button
              type="button"
              role="menuitem"
              class="block w-full px-3 py-2 text-left hover:bg-brand-50"
              @click.stop="copyLink"
            >
              {{ copied ? 'Tersalin' : 'Salin link' }}
            </button>
          </li>
          <li role="none">
            <a
              :href="invoice.publicUrl"
              target="_blank"
              rel="noopener"
              role="menuitem"
              class="block px-3 py-2 hover:bg-brand-50"
            >
              Lihat invoice
            </a>
          </li>
        </ul>
      </div>
    </div>

    <p class="mt-1 text-sm font-bold text-slate-900">{{ invoice.name }}</p>

    <div class="mt-0.5 flex items-end justify-between gap-2">
      <p class="text-sm font-bold text-slate-900">{{ rupiah(invoice.amount) }}</p>
      <p class="text-xs text-slate-800">{{ invoice.number }}</p>
    </div>

    <p class="mt-1 text-right text-xs text-slate-500">
      Berlaku s/d {{ formatDate(invoice.expiresAt) }}
    </p>
  </article>
</template>