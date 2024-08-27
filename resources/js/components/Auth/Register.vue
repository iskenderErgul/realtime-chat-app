<template>
    <div class="flex items-center justify-center h-screen">
        <div class="w-full max-w-md">
            <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 shadow-custom">
                <div class="text-center mb-10">
                    <div class="text-gray-900 text-3xl font-medium">
                        <img src="../../../../public/image/0a29b111-f86f-4c98-a56e-1c0c6cc2881f.png" class="mx-auto">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        İsim
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        type="text"
                        placeholder="İsim"
                        v-model="user.name"
                    />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        type="email"
                        placeholder="Email"
                        v-model="user.email"
                    />
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Şifre
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                        type="password"
                        placeholder="******************"
                        v-model="user.password"
                    />
                </div>

                <div class="mb-6 block">
                    <Recaptcha :siteKey="recaptchaSiteKey" />
                </div>

                <div class="flex items-center justify-between">
                    <p class="text-gray-500 mr-3">Zaten Hesabım Var, <a @click="goToLogin" class="text-[#00B3D7] hover:text-[#0095B0] cursor-pointer">Giriş Yap</a></p>
                    <button
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="button"
                        @click="register"
                        v-tooltip="'Kayıt Ol'"
                    >
                        Kayıt Ol
                    </button>
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

<style>
.shadow-custom {
    box-shadow: 0 -4px 6px -1px rgba(0, 179, 215, 0.5), 4px 0 6px -1px rgba(0, 179, 215, 0.5), -4px 0 6px -1px rgba(0, 179, 215, 0.5);
}

</style>
