export const rupiah = (n) => 'Rp' + new Intl.NumberFormat('id-ID').format(n || 0)

// 'YYYY-MM-DD' -> 'DD-MM-YYYY'
export function formatDate(iso) {
  if (!iso) return ''
  const [y, m, d] = iso.split('-')
  return `${d}-${m}-${y}`
}

// Date -> 'YYYY-MM-DD' (zona waktu lokal)
export function toISODate(date) {
  const pad = (n) => String(n).padStart(2, '0')
  return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`
}

export function addDays(date, days) {
  const d = new Date(date)
  d.setDate(d.getDate() + days)
  return d
}