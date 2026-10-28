<script setup>
defineProps({
  id: { type: String, required: true },
  label: { type: String, required: true },
  required: { type: Boolean, default: false },
  optional: { type: Boolean, default: false },
  error: { type: String, default: '' },
})
</script>

<template>
  <div>
    <label :for="id" class="mb-2 block text-[15px] font-bold text-slate-900">
      {{ label }}
      <span v-if="required" class="text-red-500" aria-hidden="true">*</span>
      <span v-if="optional" class="font-normal text-slate-700">(Opsional)</span>
    </label>

    <slot />

    <p v-if="error" :id="`${id}-error`" class="mt-1.5 text-xs text-red-600" role="alert">
      {{ error }}
    </p>
  </div>
</template>
