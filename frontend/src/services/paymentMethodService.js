import { request } from './api'

/**
 * GET /api/payment-methods
 * Respons: { message, data: { items: [{ id, code, name }] } }
 */
export async function getPaymentMethods() {
  const res = await request('/payment-methods')
  return (res?.data?.items ?? []).map(({ id, code, name }) => ({ id, code, name }))
}
