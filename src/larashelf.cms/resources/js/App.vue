<script setup lang="ts">
import { ref } from 'vue'
import type { User } from './types/user'
import api, { ensureCsrfCookie } from './lib/axios'

const email = ref('')
const password = ref('')

const status = ref<'idle' | 'loading' | 'success' | 'error'>('idle')
const statusText = ref<string | null>(null)
const user = ref<User | null>(null)

async function submit() {
    status.value = 'loading'
    statusText.value = null
    try {
        await ensureCsrfCookie()
        await api.post('/api/login', { email: email.value, password: password.value })
        const { data } = await api.get('/api/user')
        user.value = data
        status.value = 'success'
    } catch (e: any) {
        status.value = 'error'
        statusText.value = e?.response?.data?.message ?? 'Login failed'
    }
}

async function logout() {
    await api.post('/api/logout')
    user.value = null
    status.value = 'idle'
    statusText.value = null
}
</script>


<template>
    <v-app>
        <v-main>
            <v-container class="d-flex align-center justify-center" style="min-height: 100vh;">
                <v-card width="420" elevation="2">
                    <v-card-title class="text-h6">Sign in</v-card-title>
                    <v-card-text>
                        <v-alert v-if="status" type="success" density="compact" variant="tonal" class="mb-3">
                            {{ status }}
                        </v-alert>
                        <v-alert v-if="errors.length" type="error" density="compact" variant="tonal" class="mb-3">
                            <ul class="pl-4">
                                <li v-for="(e,i) in errors" :key="i">{{ e }}</li>
                            </ul>
                        </v-alert>

                        <v-form @submit.prevent="submit">
                            <v-text-field v-model="email" label="Email" type="email" autocomplete="username"
                                          prepend-inner-icon="mdi-email" required />
                            <v-text-field v-model="password" label="Password" type="password" autocomplete="current-password"
                                          prepend-inner-icon="mdi-lock" required />

                            <div class="d-flex justify-space-between align-center mt-2">
                                <v-btn :loading="loading" type="submit" color="primary" variant="flat">Sign in</v-btn>
                                <v-btn variant="text" density="compact">Forgot password?</v-btn>
                            </div>
                        </v-form>

                        <div v-if="user" class="mt-4 text-caption">
                            <strong>Current user:</strong> {{ displayName }}
                        </div>
                    </v-card-text>
                </v-card>
            </v-container>
        </v-main>
    </v-app>
</template>

<style scoped>
/* keep it minimal; Vuetify handles most styling */
</style>
