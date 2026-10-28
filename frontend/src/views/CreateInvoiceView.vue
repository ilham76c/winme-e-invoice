<script setup>
import { computed, nextTick, onMounted, reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import AppIcon from '../components/AppIcon.vue'
import FormField from '../components/FormField.vue'
import InvoiceItemsSection from '../components/InvoiceItemsSection.vue'
import InvoiceSettingsSection from '../components/InvoiceSettingsSection.vue'
import InvoicePreview from '../components/InvoicePreview.vue'
import InvoiceSuccessModal from '../components/InvoiceSuccessModal.vue'
import { addDays, toISODate } from '../utils/format'
import { createInvoice } from '../services/invoiceService'
import { getPaymentMethods } from '../services/paymentMethodService'
import { ApiError } from '../services/api'

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
const methodId = ref(null)
const paymentMethods = ref([])
const methodsLoading = ref(true)
const methodsLoadError = ref('')

async function loadPaymentMethods() {
  methodsLoading.value = true
  methodsLoadError.value = ''
  try {
    paymentMethods.value = await getPaymentMethods()
  } catch {
    methodsLoadError.value = 'Gagal memuat metode pembayaran.'
  } finally {
    methodsLoading.value = false
  }
}

onMounted(loadPaymentMethods)

const errors = reactive({
  name: '',
  whatsapp: '',
  email: '',
  address: '',
  items: '',
  expiry: '',
  methods: '',
})
const submitting = ref(false)
const submitError = ref('')
const showSuccess = ref(false)
const invoiceUrl = ref('')

const channel = computed(() => paymentMethods.value.find((m) => m.id === methodId.value)?.name ?? '')

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

  errors.methods = methodId.value ? '' : 'Pilih metode pembayaran.'

  return Object.values(errors).every((e) => !e)
}

function pickCustomer() {
  // TODO: buka daftar pelanggan, lalu isi form.name / whatsapp / email / address
}

const serverFieldMap = {
  recipient_name: 'name',
  whatsapp: 'whatsapp',
  email: 'email',
  address: 'address',
  payment_method_id: 'methods',
  expires_at: 'expiry',
}

// Menampilkan error validasi dari backend ({ field: ['pesan'] }) di field terkait
function applyServerErrors(fieldErrors) {
  let applied = false
  for (const [field, raw] of Object.entries(fieldErrors)) {
    const text = Array.isArray(raw) ? raw[0] : String(raw)
    if (serverFieldMap[field]) errors[serverFieldMap[field]] = text
    else if (field === 'items' || field.startsWith('items.')) errors.items ||= text
    else continue
    applied = true
  }
  return applied
}

function scrollToFirstError() {
  nextTick(() => {
    document.querySelector('[role="alert"]')?.scrollIntoView({ behavior: 'smooth', block: 'center' })
  })
}

async function submit() {
  submitError.value = ''
  errors.address = ''

  if (!validate()) {
    scrollToFirstError()
    return
  }

  submitting.value = true
  try {
    const invoice = await createInvoice({
      form,
      items: items.value,
      expiresAt: expiry.value,
      paymentMethodId: methodId.value,
    })
    invoiceUrl.value = invoice.url
    showSuccess.value = true // tombol tetap nonaktif; halaman berpindah saat modal ditutup
  } catch (err) {
    submitting.value = false
    if (err instanceof ApiError) {
      submitError.value = applyServerErrors(err.errors)
        ? 'Periksa kembali isian yang ditandai.'
        : err.message
    } else {
      submitError.value = 'Tidak dapat terhubung ke server. Periksa koneksi lalu coba lagi.'
    }
    scrollToFirstError()
  }
}

function closeSuccess() {
  showSuccess.value = false
  router.push({ name: 'invoices' })
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

        <FormField id="address" label="Alamat" optional :error="errors.address">
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
        v-model:method-id="methodId"
        :min="minExpiry"
        :max="maxExpiry"
        :expiry-error="errors.expiry"
        :methods-error="errors.methods"
        :options="paymentMethods"
        :loading="methodsLoading"
        :load-error="methodsLoadError"
        @retry="loadPaymentMethods"
      />

      <!-- Pratinjau -->
      <InvoicePreview
        :customer="form"
        :items="items"
        :expiry="expiry"
        :channel="channel"
        :merchant="merchant"
      />

      <p
        v-if="submitError"
        class="rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700"
        role="alert"
      >
        {{ submitError }}
      </p>

      <button
        type="submit"
        :disabled="submitting"
        class="w-full rounded-2xl bg-brand-500 py-5 text-xl font-semibold text-white shadow-sm transition active:scale-[0.99] disabled:opacity-60"
      >
        Buat E-Invoice
      </button>
    </form>

    <InvoiceSuccessModal v-if="showSuccess" :url="invoiceUrl" @close="closeSuccess" />
  </div>
</template>