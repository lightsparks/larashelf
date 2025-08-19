import axios from "axios";

function getCookie(name: string): string | null {
    const m = document.cookie.match(new RegExp("(^|;\\s*)" + name + "=([^;]*)"));
    return m ? decodeURIComponent(m[2]) : null;
}

const http = axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL, // http://127.0.0.1:8001
    withCredentials: true,
});

http.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
http.defaults.xsrfCookieName = "XSRF-TOKEN";
http.defaults.xsrfHeaderName = "X-XSRF-TOKEN";

http.interceptors.request.use((config) => {
    const token = getCookie("XSRF-TOKEN");
    if (token) {
        (config.headers ||= {})["X-XSRF-TOKEN"] = token;
    }
    return config;
});

export default http;
