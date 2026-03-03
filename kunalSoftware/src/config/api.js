const rawBase = (import.meta.env.VITE_API_BASE_URL || '/api').trim();
const normalizedBase = rawBase.endsWith('/') ? rawBase.slice(0, -1) : rawBase;

export function buildApiUrl(path) {
  const cleanPath = path.startsWith('/') ? path : `/${path}`;
  return `${normalizedBase}${cleanPath}`;
}
