<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import AppIcon from '../components/AppIcon.vue'
import FormField from '../components/FormField.vue'

const router = useRouter()

const form = reactive({
  name: '',
  whatsapp: '',
  email: '',
  address: '',
})
const errors = reactive({ name: '', whatsapp: '', email: '' })
const submitting = ref(false)

const inputClass =
  'w-full rounded-xl border border-slate-300 bg-white px-4 py-3.5 text-[15px] text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 aria-invalid:border-red-500 aria-invalid:ring-red-100'

function validate() {
  errors.name = form.name.trim() ? '' : 'Nama wajib diisi.'

  const phone = form.whatsapp.replace(/[\s-]/g, '')
  if (!phone) errors.whatsapp = 'Nomor WhatsApp wajib diisi.'
  else if (!/^(\+62|62|0)8\d{7,12}$/.test(phone))
    errors.whatsapp = 'Gunakan nomor Indonesia yang valid, contoh 081234567890.'
  else errors.whatsapp = ''

  if (!form.email.trim()) errors.email = 'Email wajib diisi.'
  else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email.trim()))
    errors.email = 'Format email belum benar, contoh nama@email.com.'
  else errors.email = ''

  return !errors.name && !errors.whatsapp && !errors.email
}

function pickCustomer() {
  // TODO: buka daftar pelanggan, lalu isi form.name / whatsapp / email / address
}

async function submit() {
  if (!validate()) return
  submitting.value = true
  try {
    // TODO: kirim form ke API, atau lanjut ke langkah detail tagihan
    router.push({ name: 'invoices' })
  } finally {
    submitting.value = false
  }
}
</script>

<template>
  <div class="min-h-screen pb-10">
    <header class="flex items-center gap-3 border-b border-slate-200 bg-white px-4 py-5">
      <RouterLink to="/invoices" aria-label="Kembali" class="text-slate-900">
        <AppIcon name="chevron-left" :size="28" :stroke="2.5" />
      </RouterLink>
      <h1 class="text-xl font-bold text-slate-900">Buat E-Invoice</h1>
    </header>

    <main class="space-y-5 px-4 pt-6">
      <!-- Info panduan -->
      <div
        class="flex items-center gap-2.5 rounded-full bg-brand-100 px-4 py-3 text-sm text-slate-800"
      >
        <AppIcon name="info" :size="20" class="shrink-0 text-brand-500" />
        <p>
          Lihat panduan E-Invoice
          <a href="#" class="text-brand-600 underline underline-offset-2">disini</a>
        </p>
      </div>

      <!-- Informasi Tujuan -->
      <form
        class="space-y-5 rounded-2xl bg-white p-4 shadow-md shadow-slate-300/60"
        novalidate
        @submit.prevent="submit"
      >
        <div>
          <h2 class="text-xl font-bold text-brand-500">Informasi Tujuan</h2>
          <p class="mt-1 text-sm text-slate-800">Data penerima E-Invoice</p>

          <button
            type="button"
            class="mt-4 inline-flex items-center gap-2 rounded-lg border border-brand-500 px-3 py-2 text-xs font-medium text-brand-500 transition active:scale-95"
            @click="pickCustomer"
          >
            <AppIcon name="users" :size="16" />
            Pilih dari Pelanggan
          </button>
        </div>

        <FormField id="name" label="Nama" required :error="errors.name">
          <input
            id="name"
            v-model="form.name"
            type="text"
            autocomplete="name"
            placeholder="Nama penerima"
            :class="inputClass"
            :aria-invalid="!!errors.name"
            :aria-describedby="errors.name ? 'name-error' : undefined"
          />
        </FormField>

        <FormField id="whatsapp" label="No. WhatsApp" required :error="errors.whatsapp">
          <input
            id="whatsapp"
            v-model="form.whatsapp"
            type="tel"
            inputmode="tel"
            autocomplete="tel"
            placeholder="081234567890"
            :class="inputClass"
            :aria-invalid="!!errors.whatsapp"
            :aria-describedby="errors.whatsapp ? 'whatsapp-error' : undefined"
          />
        </FormField>

        <FormField id="email" label="Email" required :error="errors.email">
          <input
            id="email"
            v-model="form.email"
            type="email"
            autocomplete="email"
            placeholder="nama@email.com"
            :class="inputClass"
            :aria-invalid="!!errors.email"
            :aria-describedby="errors.email ? 'email-error' : undefined"
          />
        </FormField>

        <FormField id="address" label="Alamat" optional>
          <input
            id="address"
            v-model="form.address"
            type="text"
            autocomplete="street-address"
            placeholder="Jl. Raden Wijaya 110, Bekasi"
            :class="inputClass"
          />
        </FormField>

        <button
          type="submit"
          :disabled="submitting"
          class="w-full rounded-xl bg-brand-500 py-3.5 text-[15px] font-semibold text-white shadow-sm transition active:scale-[0.99] disabled:opacity-60"
        >
          Lanjutkan
        </button>
      </form>
    </main>
  </div>
</template>
