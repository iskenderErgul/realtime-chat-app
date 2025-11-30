<template>
    <div class="min-h-screen flex items-center justify-center p-4 bg-gradient-animated">
        <div class="w-full max-w-md animate-fade-in">
            <form class="glass-card rounded-3xl px-8 sm:px-10 pt-8 pb-10 shadow-2xl" @submit.prevent="register">
                <!-- Logo/Header -->
                <div class="text-center mb-8">
                    <div class="mb-6">
                        <img src="../../../../public/image/0a29b111-f86f-4c98-a56e-1c0c6cc2881f.png" class="mx-auto max-w-[180px] hover:scale-105 transition-transform duration-300">
                    </div>
                    <h1 class="text-3xl font-bold text-gradient mb-2">Kayıt Ol</h1>
                    <p class="text-gray-600">Yeni hesap oluşturun</p>
                </div>

                <!-- Name Input -->
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="name">
                        İsim
                    </label>
                    <input
                        id="name"
                        class="input-modern w-full px-4 py-3 rounded-xl focus:outline-none transition-all"
                        type="text"
                        placeholder="Adınız"
                        v-model="user.name"
                        required
                    />
                </div>

                <!-- Email Input -->
                <div class="mb-5">
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
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="password">
                        Şifre
                    </label>
                    <input
                        id="password"
                        class="input-modern w-full px-4 py-3 rounded-xl focus:outline-none transition-all"
                        type="password"
                        placeholder="••••••••••••"
                        v-model="user.password"
                        required
                    />
                </div>

                <!-- Recaptcha -->
                <div class="mb-6">
                    <Recaptcha :siteKey="recaptchaSiteKey" />
                </div>

                <!-- Actions -->
                <div class="space-y-4">
                    <button
                        class="btn-modern w-full bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-bold py-3 px-6 rounded-xl focus:outline-none shadow-lg hover:shadow-xl"
                        type="submit"
                        v-tooltip="'Kayıt Ol'"
                    >
                        Kayıt Ol
                    </button>

                    <div class="text-center">
                        <p class="text-gray-600">
                            Zaten hesabınız var mı? 
                            <a @click="goToLogin" class="text-indigo-600 hover:text-indigo-800 font-semibold cursor-pointer transition-colors">
                                Giriş Yap
                            </a>
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref} from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';
import Recaptcha from './Recaptcha.vue';

const toast = useToast();
const router = useRouter();
const user = ref({
    name: null,
    email: null,
    password: null,
});
const recaptchaSiteKey = ref('6LdCIzAqAAAAAA8lWudpGTbzkYfb7a5G93kkeD28');


const register = async () => {
    const recaptchaResponse  = grecaptcha.getResponse();
    if (!recaptchaResponse) {
        toast.error('Lütfen reCAPTCHA doğrulamasını tamamlayın.');
        return;
    }
    try {
        const response = await axios.post('/api/register', {
            ...user.value,
            'g-recaptcha-response': recaptchaResponse ,
        });
        toast.success(response.data.message);
        await router.push({ name: 'login' });
    } catch (error) {
        toast.error(error.response.data.message || 'Bir hata oluştu.');
    }
};

const goToLogin = () => {
    router.push({ name: 'login' });
};
</script>

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
