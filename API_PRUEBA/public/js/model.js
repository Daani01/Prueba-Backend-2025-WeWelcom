const API_URL = '/api/restaurantes';
let apiKey = '';

export function setApiKey(key) {
  apiKey = key;
}

function getHeaders(contentType = 'application/json') {
  const headers = {
    'Authorization': `ApiKey ${apiKey}`
  };
  if (contentType) headers['Content-Type'] = contentType;
  return headers;
}

export async function getRestaurantes() {
  const res = await fetch(API_URL, {
    headers: getHeaders(null),
  });
  if (!res.ok) throw new Error('Error al obtener restaurantes');
  return await res.json();
}

export async function createRestaurante(data) {
  const res = await fetch(API_URL, {
    method: 'POST',
    headers: getHeaders(),
    body: JSON.stringify(data),
  });
  if (!res.ok) throw new Error('Error al crear restaurante');
  return await res.json();
}

export async function updateRestaurante(id, data) {
  const res = await fetch(`${API_URL}/${id}`, {
    method: 'PUT',
    headers: getHeaders(),
    body: JSON.stringify(data),
  });
  if (!res.ok) throw new Error('Error al actualizar restaurante');
  return await res.json();
}

export async function deleteRestaurante(id) {
  const res = await fetch(`${API_URL}/${id}`, {
    method: 'DELETE',
    headers: getHeaders(null),
  });
  if (!res.ok) throw new Error('Error al eliminar restaurante');
  return await res.json();
}
