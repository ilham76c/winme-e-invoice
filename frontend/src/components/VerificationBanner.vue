<script setup>
import AppIcon from './AppIcon.vue'
defineEmits(['click'])
</script>

<template>
  <button
    type="button"
    class="flex w-full items-center justify-between gap-3 rounded-xl bg-accent px-4 py-3 text-left text-white transition active:scale-[0.99]"
    @click="$emit('click')"
  >
    <span class="text-[15px] leading-snug font-medium">
      Yuk, lengkapi verifikasi data untuk aktifkan beberapa fitur jualan di
      <strong class="font-bold">Winme</strong>
    </span>
    <AppIcon name="arrow-circle" :size="30" class="shrink-0" />
  </button>
</template>