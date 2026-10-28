<script setup>
import { computed, reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import AppIcon from '../components/AppIcon.vue'
import FormField from '../components/FormField.vue'
import InvoiceItemsSection from '../components/InvoiceItemsSection.vue'
import InvoiceSettingsSection from '../components/InvoiceSettingsSection.vue'
import InvoicePreview from '../components/InvoicePreview.vue'
import { paymentMethods } from '../constants/paymentMethods'
import { addDays, toISODate } from '../utils/format'

const router = useRouter()

// TODO: ambil dari profil merchant yang sedang login
const merchant = {
  name: 'Dhanidan',
  contact: 'Yusuf Adhani',
  phone: '6281375198725',
  email: 'Yusufadhani.bs@gmail.com',
}

// --- Informasi tujuan
const form = reactive({ name: '', whatsapp: '', email: '', address: '' })

// --- Detail tagihan
const billingMode = ref('custom')
const items = ref([])

// --- Pengaturan
const today = new Date()
const minExpiry = toISODate(today)
const maxExpiry = toISODate(addDays(today, 30))
const expiry = ref(toISODate(addDays(today, 7)))
const methods = ref([])

const errors = reactive({
  name: '',
  whatsapp: '',
  email: '',
  items: '',
  expiry: '',
  methods: '',
})
const submitting = ref(false)

const firstChannel = computed(
  () => paymentMethods.find((m) => methods.value.includes(m.id))?.label ?? '',
)

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

  errors.items = items.value.length ? '' : 'Tambahkan minimal satu produk atau jasa.'

  if (!expiry.value) errors.expiry = 'Masa berlaku wajib diisi.'
  else if (expiry.value < minExpiry || expiry.value > maxExpiry)
    errors.expiry = 'Masa berlaku maksimal 30 hari dari hari ini.'
  else errors.expiry = ''

  errors.methods = methods.value.length ? '' : 'Pilih minimal satu metode pembayaran.'

  return Object.values(errors).every((e) => !e)
}

function pickCustomer() {
  // TODO: buka daftar pelanggan, lalu isi form.name / whatsapp / email / address
}

async function submit() {
  if (!validate()) {
    // gulir ke pesan error pertama
    await Promise.resolve()
    document.querySelector('[role="alert"]')?.scrollIntoView({ behavior: 'smooth', block: 'center' })
    return
  }
  submitting.value = true
  try {
    const payload = {
      customer: { ...form },
      mode: billingMode.value,
      items: items.value,
      expiry: expiry.value,
      methods: methods.value,
    }
    console.log('Buat E-Invoice', payload) // TODO: kirim ke API
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

    <form class="space-y-5 px-4 pt-6" novalidate @submit.prevent="submit">
      <!-- Info panduan -->
      <div class="flex items-center gap-2.5 rounded-full bg-brand-100 px-4 py-3 text-sm text-slate-800">
        <AppIcon name="info" :size="20" class="shrink-0 text-brand-500" />
        <p>
          Lihat panduan E-Invoice
          <a href="#" class="text-brand-600 underline underline-offset-2">disini</a>
        </p>
      </div>

      <!-- Informasi Tujuan -->
      <section class="space-y-5 rounded-2xl bg-white p-4 shadow-md shadow-slate-300/60">
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
      </section>

      <!-- Detail Tagihan -->
      <InvoiceItemsSection v-model:items="items" v-model:mode="billingMode" :error="errors.items" />

      <!-- Pengaturan E-Invoice -->
      <InvoiceSettingsSection
        v-model:expiry="expiry"
        v-model:methods="methods"
        :min="minExpiry"
        :max="maxExpiry"
        :expiry-error="errors.expiry"
        :methods-error="errors.methods"
      />

      <!-- Pratinjau -->
      <InvoicePreview
        :customer="form"
        :items="items"
        :expiry="expiry"
        :channel="firstChannel"
        :merchant="merchant"
      />

      <button
        type="submit"
        :disabled="submitting"
        class="w-full rounded-2xl bg-brand-500 py-5 text-xl font-semibold text-white shadow-sm transition active:scale-[0.99] disabled:opacity-60"
      >
        Buat E-Invoice
      </button>
    </form>
  </div>
</template>