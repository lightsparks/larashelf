<script setup lang="ts">
import { ref } from "vue";
import { login, me } from "../../stores/auth"; // <-- import me
import { useRouter } from "vue-router";
import CmsShell from "../../components/CmsShell.vue";

const router = useRouter();
const identifier = ref("");
const password = ref("");
const loading = ref(false);
const error = ref<string | null>(null);

async function submit() {
    loading.value = true;
    error.value = null;

    try {
        await login(identifier.value, password.value);
        await me();
        await router.replace("/dashboard");
    } catch {
        error.value = "Invalid credentials";
    } finally {
        loading.value = false;
    }
}
</script>

<template>
    <CmsShell :show-logout="false" :show-nav="false">
        <v-container class="d-flex align-center justify-center" style="min-height: calc(100vh - 64px);">
            <v-row justify="center" class="w-100">
                <v-col cols="12" sm="8" md="5" lg="4">
                    <v-card elevation="6" rounded="lg" class="pa-6">
                        <v-card-title class="text-h5 text-center mb-6">LaraShelf CMS Login</v-card-title>
                        <v-card-text>
                            <v-alert v-if="error" type="error" variant="tonal" class="mb-4" border="start">{{ error }}</v-alert>
                            <v-form @submit.prevent="submit">
                                <v-text-field v-model="identifier" label="Email or Username" prepend-inner-icon="mdi-account"
                                              autocomplete="username" variant="outlined" density="comfortable" class="mb-4" required />
                                <v-text-field v-model="password" label="Password" type="password" prepend-inner-icon="mdi-lock"
                                              autocomplete="current-password" variant="outlined" density="comfortable" class="mb-6" required />
                                <v-btn type="submit" block size="large" color="primary" :loading="loading">Sign In</v-btn>
                            </v-form>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </CmsShell>
</template>
