<template>
    <div class="flex items-center justify-center h-screen">
        <div class="w-full max-w-md">
            <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <h2 class="text-center text-2xl font-bold mb-6">Kayıt Ol</h2>
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

                <div class="flex items-center justify-between">
                    <p class="text-gray-500 mr-3">Zaten Hesabım Var, <a @click="goToLogin" class="text-blue-800 cursor-pointer">Giriş Yap</a></p>
                    <button
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="button"
                        @click="register"
                    >
                        Kayıt Ol
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();
const user = ref({
    name: null,
    email: null,
    password: null,
});

const register = async () => {
    try {
        const response = await axios.post('/api/register', user.value);
        console.log('Registration successful:', response.data);
        router.push({ name: 'login' });
    } catch (error) {
        console.error('Error during registration:', error);
    }
};

const goToLogin = () => {
    router.push({ name: 'login' });
};
</script>

<style>
</style>
