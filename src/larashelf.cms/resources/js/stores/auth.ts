import http from "../lib/axios";

export async function getCsrf() {
    const res = await http.get("/sanctum/csrf-cookie"); // should be 204
    console.debug("csrf-cookie status", res.status, "cookies now:", document.cookie);
}

export async function login(identifier: string, password: string) {
    await getCsrf();

    return http.post("/login", { identifier, password });
}

export async function me() {
    return http.get("/api/me");
}

export async function adminPing() {
    return http.get('/api/admin/ping');
}

export async function logout() {
    await http.post("/logout");
}
