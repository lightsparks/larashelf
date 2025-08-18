import axios from 'axios';

const api = axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL ?? 'http://127.0.0.1:8001',
    withCredentials: true,
});

export async function ensureCsrfCookie() {
    await api.get('/sanctum/csrf-cookie');
}

export default api;
