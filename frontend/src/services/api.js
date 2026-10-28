// Dengan proxy Vite (lihat vite.config.js), '/api' diteruskan ke http://localhost:8000/api
const BASE_URL = (import.meta.env.VITE_API_BASE_URL ?? '/api').replace(/\/$/, '')

export class ApiError extends Error {
  constructor(message, { status, errors } = {}) {
    super(message)
    this.name = 'ApiError'
    this.status = status
    this.errors = errors ?? {} // { nama_field: ['pesan', ...] }
  }
}

export async function request(path, { method = 'GET', body, signal } = {}) {
  const res = await fetch(`${BASE_URL}${path}`, {
    method,
    headers: {
      Accept: 'application/json',
      ...(body && { 'Content-Type': 'application/json' }),
    },
    body: body ? JSON.stringify(body) : undefined,
    signal,
  })

  let data = null
  try {
    data = await res.json()
  } catch {
    // respons tanpa body JSON
  }

  if (!res.ok) {
    throw new ApiError(data?.message ?? `Permintaan gagal (${res.status})`, {
      status: res.status,
      errors: data?.errors,
    })
  }
  return data
}