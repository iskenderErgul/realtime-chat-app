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

    <div class="flex items-center justify-center h-screen">

        <div class="w-full max-w-md">
            <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 shadow-custom">
                <div class="text-center mb-10">
                    <div class="text-gray-900 text-3xl font-medium ">
                        <img src="../../../../public/image/0a29b111-f86f-4c98-a56e-1c0c6cc2881f.png" class="mx-auto">
                    </div>
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
                        placeholder="******************"
                        type="password"
                        v-model="user.password"

                    />
                </div>
                <div class="">

                    <button
                        class="bg-[#00B3D7] hover:bg-[#0095B0] text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline  block w-full mb-2"
                        type="button"
                        @click="login"
                        v-tooltip="'Giriş Yap'"
                    >
                            Giriş Yap
                    </button>

                    <button
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline   block w-full mb-2"
                        type="button"
                        @click="goToRegister"
                        v-tooltip="'Kayıt Ol'"
                    >
                        Kayıt Ol
                    </button>

                </div>
            </form>

        </div>
    </div>
</template>
<style>
.shadow-custom {
    box-shadow: 0 -4px 6px -1px rgba(0, 179, 215, 0.5), 4px 0 6px -1px rgba(0, 179, 215, 0.5), -4px 0 6px -1px rgba(0, 179, 215, 0.5);
}
</style>
