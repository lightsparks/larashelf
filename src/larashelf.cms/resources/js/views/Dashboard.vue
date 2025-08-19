<script setup lang="ts">
import { onMounted, computed, ref } from "vue";
import { me, logout } from "../stores/auth";
import CmsShell from "../components/CmsShell.vue";
import { adminPing } from '../stores/auth';

onMounted(async () => {
    try {
        const { data } = await adminPing();
        console.log('Admin ping:', data); // admin → { ok: true }
    } catch (e: any) {
        console.error('Admin ping failed', e?.response?.status, e?.response?.data); // non-admin → 403
    }
});

const user = ref<any>(null);
const loading = ref(true);
const error = ref<string | null>(null);

onMounted(async () => {
    try { const { data } = await me(); user.value = data; }
    catch { error.value = "Could not load your session."; }
    finally { loading.value = false; }
});

async function signOut() {
    await logout();
    localStorage.removeItem('ls_drawer_open');
    localStorage.removeItem('ls_drawer_rail');
    window.location.assign('/login');
}

const displayGreeting = computed(() => {
    if (!user.value) return "Welcome!";

    const fn = user.value.first_name?.trim();
    const ln = user.value.last_name?.trim();

    if (fn && ln) return `Welcome, ${fn} ${ln}`;
    if (fn) return `Welcome, ${fn}`;
    if (user.value.username) return `Welcome, ${user.value.username}`;
    if (user.value.email) return `Welcome, ${user.value.email}`;

    return "Welcome!";
});
</script>

<template>
    <CmsShell :show-logout="true" @logout="signOut">
        <v-container class="py-8">
            <v-card elevation="4" rounded="lg">
                <v-card-title class="text-h6">Dashboard</v-card-title>
                <v-divider />
                <v-card-text>
                    <div class="mb-2">
                        <span>{{ displayGreeting }}</span>
                    </div>
                    <div class="text-body-2 text-medium-emphasis">
                        You're signed in to LaraShelf CMS.
                    </div>
                </v-card-text>
            </v-card>
        </v-container>
    </CmsShell>
</template>

