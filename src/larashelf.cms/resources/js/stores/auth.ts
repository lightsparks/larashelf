import http from "../lib/axios";

export async function getCsrf() {
    const res = await http.get("/sanctum/csrf-cookie"); // should be 204
    console.debug("csrf-cookie status", res.status, "cookies now:", document.cookie);
}

export async function login(identifier: string, password: string) {
    await getCsrf();

    // Debug headers we send:
    console.debug("XSRF header", (http.defaults as any).xsrfHeaderName);

    const resp = await http.post("/login", { email: identifier, password });
    console.debug("login status", resp.status);
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
