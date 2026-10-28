<script setup>
defineProps({
  name: { type: String, required: true },
  size: { type: [Number, String], default: 24 },
  stroke: { type: Number, default: 2 },
})

const icons = {
  menu: ['M4 6h16', 'M4 12h16', 'M4 18h16'],
  bell: ['M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9', 'M10.3 21a1.94 1.94 0 0 0 3.4 0'],
  wallet: [
    'M19 7V5a2 2 0 0 0-2-2H5a2 2 0 0 0 0 4h14a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5',
    'M16 13h.01',
  ],
  info: ['M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20z', 'M12 16v-4', 'M12 8h.01'],
  'arrow-circle': ['M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20z', 'M8 12h8', 'M12 8l4 4-4 4'],
  link: [
    'M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71',
    'M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71',
  ],
  receipt: [
    'M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z',
    'M9 8h6',
    'M9 12h6',
    'M9 16h4',
  ],
  package: [
    'M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z',
    'M12 22V12',
    'M3.29 7 12 12l8.71-5',
    'M7.5 4.27l9 5.15',
  ],
  layout: ['M3 3h18v18H3z', 'M3 9h18', 'M9 21V9'],
  clipboard: [
    'M8 2h8v4H8z',
    'M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2',
    'M9 12h6',
    'M9 16h6',
  ],
  chart: ['M3 3v18h18', 'M7 14l4-4 4 4 5-6'],
  'user-plus': [
    'M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2',
    'M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z',
    'M19 8v6',
    'M22 11h-6',
  ],
  'grid-plus': ['M3 3h7v7H3z', 'M14 3h7v7h-7z', 'M3 14h7v7H3z', 'M14 17.5h7', 'M17.5 14v7'],
  tray: ['M12 3v12', 'M7 10l5 5 5-5', 'M4 17v3h16v-3'],
  'check-circle': ['M22 11.08V12a10 10 0 1 1-5.93-9.14', 'M22 4 12 14.01l-3-3'],
  home: ['M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z'],
  store: [
    'M3 9l1.5-5h15L21 9',
    'M3 9v1a3 3 0 0 0 6 0 3 3 0 0 0 6 0 3 3 0 0 0 6 0V9',
    'M5 21v-8.5',
    'M19 21v-8.5',
    'M3 21h18',
  ],
  sliders: [
    'M4 21v-7', 'M4 10V3', 'M12 21v-9', 'M12 8V3', 'M20 21v-5', 'M20 12V3',
    'M1 14h6', 'M9 8h6', 'M17 16h6',
  ],
  'arrow-right': ['M5 12h14', 'M12 5l7 7-7 7'],
  'arrow-left': ['M19 12H5', 'M12 19l-7-7 7-7'],
  plus: ['M12 5v14', 'M5 12h14'],
}
</script>

<template>
  <svg
    :width="size"
    :height="size"
    viewBox="0 0 24 24"
    fill="none"
    stroke="currentColor"
    :stroke-width="stroke"
    stroke-linecap="round"
    stroke-linejoin="round"
    aria-hidden="true"
  >
    <path v-for="d in icons[name]" :key="d" :d="d" />
  </svg>
</template>