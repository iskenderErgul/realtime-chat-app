<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';

const user = ref({
    email: '',
    password: ''
});

const router = useRouter();
const store = useStore();


const login = async () => {
    try {
        await store.dispatch('login', user.value);
    } catch (error) {
        console.error('Login error:', error);
    }
};

const goToRegister = () => {
    router.push('/register');
};
</script>

<template>
    <div class="min-h-screen flex items-center justify-center p-4 bg-gradient-animated">
        <div class="w-full max-w-md animate-fade-in">
            <form class="glass-card rounded-3xl px-8 sm:px-10 pt-8 pb-10 shadow-2xl" @submit.prevent="login">
                <!-- Logo/Header -->
                <div class="text-center mb-8">
                    <div class="mb-6">
                        <img src="../../../../public/image/0a29b111-f86f-4c98-a56e-1c0c6cc2881f.png" class="mx-auto max-w-[180px] hover:scale-105 transition-transform duration-300">
                    </div>
                    <h1 class="text-3xl font-bold text-gradient mb-2">Hoş Geldiniz</h1>
                    <p class="text-gray-600">Hesabınıza giriş yapın</p>
                </div>

                <!-- Email Input -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="email">
                        Email
                    </label>
                    <input
                        id="email"
                        class="input-modern w-full px-4 py-3 rounded-xl focus:outline-none transition-all"
                        type="email"
                        placeholder="ornek@email.com"
                        v-model="user.email"
                        required
                    />
                </div>

                <!-- Password Input -->
                <div class="mb-8">
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="password">
                        Şifre
                    </label>
                    <input
                        id="password"
                        class="input-modern w-full px-4 py-3 rounded-xl focus:outline-none transition-all"
                        placeholder="••••••••••••"
                        type="password"
                        v-model="user.password"
                        required
                    />
                </div>

                <!-- Buttons -->
                <div class="space-y-3">
                    <button
                        class="btn-modern w-full bg-gradient-primary text-white font-bold py-3 px-6 rounded-xl focus:outline-none shadow-lg hover:shadow-xl"
                        type="submit"
                        v-tooltip="'Giriş Yap'"
                    >
                        Giriş Yap
                    </button>

                    <button
                        class="btn-modern w-full bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-bold py-3 px-6 rounded-xl focus:outline-none shadow-lg hover:shadow-xl"
                        type="button"
                        @click="goToRegister"
                        v-tooltip="'Kayıt Ol'"
                    >
                        Kayıt Ol
                    </button>
                </div>

                <!-- Demo Credentials -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h2 class="text-center font-semibold text-gray-700 mb-3">Demo Hesaplar</h2>
                    <div class="space-y-2 text-sm">
                        <p class="text-center text-gray-600 bg-gray-50 py-2 px-3 rounded-lg">
                            <span class="font-medium">Email:</span> iskender1@gmail.com / <span class="font-medium">Şifre:</span> 12345
                        </p>
                        <p class="text-center text-gray-600 bg-gray-50 py-2 px-3 rounded-lg">
                            <span class="font-medium">Email:</span> mahmut@gmail.com / <span class="font-medium">Şifre:</span> 12345
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
.bg-gradient-animated {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    background-size: 200% 200%;
    animation: gradientShift 15s ease infinite;
}

@keyframes gradientShift {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}
</style>
