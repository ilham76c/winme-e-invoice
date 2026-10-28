<script setup>
import { ref } from 'vue'
import AppHeader from '../components/AppHeader.vue'
import BalanceCard from '../components/BalanceCard.vue'
import VerificationBanner from '../components/VerificationBanner.vue'
import PromoBanner from '../components/PromoBanner.vue'
import MenuGrid from '../components/MenuGrid.vue'
import SalesActivity from '../components/SalesActivity.vue'

const balance = ref({ available: 0, processing: 0 })

const stats = ref([
  { label: 'Pesanan Baru', icon: 'tray', count: 0 },
  { label: 'Pesanan Diproses', icon: 'package', count: 0 },
  { label: 'Pesanan Selesai', icon: 'check-circle', count: 0 },
])

function withdraw() {
  // TODO: arahkan ke halaman tarik saldo
}

function openVerification() {
  // TODO: arahkan ke halaman verifikasi data
}
</script>

<template>
  <main>
    <AppHeader />

    <div class="space-y-4 px-4">
      <BalanceCard
        :available="balance.available"
        :processing="balance.processing"
        @withdraw="withdraw"
      />
      <VerificationBanner @click="openVerification" />
      <PromoBanner />
      <MenuGrid />
      <SalesActivity :stats="stats" />
    </div>
  </main>
</template>