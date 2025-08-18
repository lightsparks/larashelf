import axios from 'axios';

const api = axios.create({
    baseURL: 'http://127.0.0.1:8001',
    withCredentials: true,
});

// CSRF helper:
export async function ensureCsrfCookie() {
    await api.get('/sanctum/csrf-cookie');
}

export default api;
