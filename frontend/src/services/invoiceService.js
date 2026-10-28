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
    invoice.url ??
    invoice.link ??
    invoice.invoice_url ??
    invoice.public_url ??
    (invoice.id ? `${window.location.origin}/invoice/${invoice.id}` : '')

  return { ...invoice, url }
}
