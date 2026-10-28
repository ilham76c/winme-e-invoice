import { request } from './api'

/**
 * POST /api/invoices
 * Mengubah state form menjadi body yang diminta backend.
 */
export async function createInvoice({ form, items, expiresAt, paymentMethodId }) {
  const address = form.address.trim()

  const body = {
    recipient_name: form.name.trim(),
    whatsapp: form.whatsapp.replace(/[\s-]/g, ''),
    email: form.email.trim(),
    ...(address && { address }),
    payment_method_id: paymentMethodId,
    expires_at: expiresAt, // 'YYYY-MM-DD'
    items: items.map((i) => ({
      product_name: i.name,
      quantity: Number(i.qty),
      unit_price: Number(i.price),
    })),
  }

  const res = await request('/invoices', { method: 'POST', body })

  // TODO: sesuaikan dengan bentuk respons backend yang sebenarnya
  const invoice = res?.data ?? res ?? {}
  const url =
    invoice.public_url ??
    invoice.url ??
    invoice.link ??
    invoice.invoice_url ??
    (invoice.id ? `${window.location.origin}/invoice/${invoice.id}` : '')

  return { ...invoice, url }
}

function mapInvoice(i) {
  return {
    id: i.id,
    number: i.invoice_number,
    name: i.recipient?.name ?? '-',
    amount: Number(i.total_amount),
    status: i.status,
    expiresAt: i.expires_at,
    createdAt: i.created_at,
    paymentMethod: i.payment_method?.name ?? '',
    publicUrl: i.public_url,
  }
}

/**
 * GET /api/invoices?search=&page=
 * Respons: { data: { items: [...], pagination: { current_page, per_page, total, last_page } } }
 */
export async function getInvoices({ search = '', page = 1, signal } = {}) {
  const params = new URLSearchParams()
  if (search) params.set('search', search)
  if (page > 1) params.set('page', String(page))
  const query = params.toString()

  const res = await request(`/invoices${query ? `?${query}` : ''}`, { signal })
  const data = res?.data ?? {}
  const pg = data.pagination ?? {}

  return {
    items: (data.items ?? []).map(mapInvoice),
    pagination: {
      page: pg.current_page ?? 1,
      lastPage: pg.last_page ?? 1,
      total: pg.total ?? 0,
    },
  }
}