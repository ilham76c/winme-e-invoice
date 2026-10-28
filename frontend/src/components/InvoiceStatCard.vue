<script setup>
defineProps({
  title: { type: String, required: true },
  value: { type: String, default: '–' },
  prefix: { type: String, default: '' },
})
</script>

<template>
  <div
    class="relative h-25 overflow-hidden rounded-xl bg-linear-to-b from-brand-500 to-brand-900 px-4 py-3 text-white shadow-sm"
  >
    <p class="text-[15px] font-medium text-white/85">{{ title }}</p>
    <p class="mt-2 text-sm font-bold">{{ prefix }} {{ value }}</p>
    <div class="absolute right-3 bottom-3 text-white/90">
      <slot name="icon" />
    </div>
  </div>
</template>
