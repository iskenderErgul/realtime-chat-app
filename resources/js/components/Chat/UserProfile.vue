<template>
    <div>
        <Header></Header>
        <div class="mt-[120px]">
            <div class="w-4/5 mx-auto mt-8 flex space-x-4">
                <!-- Kullanıcı Bilgileri -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg w-full md:w-1/2">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Kullanıcı Bilgileri</h3>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Kullanıcı Adı</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ user ? user.name : '' }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Kullanıcı Soyadı</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ user ? user.surname : '' }}</dd>
                            </div>

                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ user ? user.email : '' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Kullanıcı Bilgilerini Güncelle -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg w-full md:w-1/2">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg font-medium text-gray-900">Kullanıcı Bilgilerini Güncelle</h3>
                    </div>
                    <div class="border-t border-gray-200">
                        <form @submit.prevent="updateUser" class="px-4 py-5 sm:px-6">
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">Kullanıcı Adı</label>
                                <input v-model="updatedUser.name" type="text" id="name" name="name" autocomplete="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            </div>
                            <div class="mb-4">
                                <label for="surname" class="block text-sm font-medium text-gray-700">Kullanıcı Soyadı</label>
                                <input v-model="updatedUser.surname" type="text" id="surname" name="surname" autocomplete="surname" class="mt-1 block w-full px-3 py-2 border border-gray-300 shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            </div>

                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input disabled v-model="updatedUser.email" type="email" id="email" name="email" autocomplete="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            </div>

                            <div class="mt-4">
                                <button type="submit" class=" px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full">
                                    Güncelle
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import axios from 'axios';
import { ref, onMounted } from 'vue';
import Header from "@/components/Chat/Header.vue";
import { useToast } from 'vue-toastification';

const toast = useToast();
const router = useRouter();
const userId = router.currentRoute.value.params.userId;
const user = ref({});
const updatedUser = ref({});

const getUser = () => {
    axios.get(`/api/user/${userId}`).then((resp) => {
        user.value = resp.data;
        updatedUser.value = { ...resp.data };
    }).catch((error) => {
        console.error('Error fetching user:', error);
    });
};

const updateUser = () => {
    axios.put(`/api/user/${userId}`, updatedUser.value).then((resp) => {
        toast.success('Kullanıcı Başarıyla Güncellendi')
        getUser();
    }).catch((error) => {
       toast.error(error.response.data.message);
    });
};



onMounted(() => {
    getUser();
});
</script>
