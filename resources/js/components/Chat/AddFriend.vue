<template>
    <Header />
    <div class="container mx-auto mt-[120px]">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Arkadaş ekle</h1>
        <div class="flex justify-center mb-8">
            <input
                v-model="searchQuery"
                type="text"
                placeholder="Keskulla Adı veya Email Girin"
                class="w-full max-w-md p-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400"
            />
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
                v-for="user in filteredUsers"
                :key="user.id"
                class="p-6 border rounded-lg shadow-md bg-white flex flex-col items-center"
            >
                <div class="relative w-24 h-24 mb-4">
                    <div
                        v-if="!user.avatar"
                        class="w-24 h-24 rounded-full flex items-center justify-center text-2xl font-semibold text-white"
                    >
                        {{ getInitials(user.name, user.surname) }}
                    </div>
                    <img v-else :src="user.avatar" alt="avatar" class="w-24 h-24 rounded-full object-cover" />
                </div>
                <div class="text-center">
                    <p class="font-semibold text-lg">{{ user.name }} {{ user.surname }}</p>
                    <p class="text-gray-600">{{ user.email }}</p>
                </div>
                <button
                    @click="addFriend(user.id)"
                    class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none transition-colors"
                >
                    Add Friend
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import { useStore } from 'vuex';
import Header from './Header.vue';
const store = useStore();
import { useToast } from 'vue-toastification';

const toast = useToast();

const searchQuery = ref('');
const users = ref([]);
const friends = ref([]);
const currentUser = computed(() => store.getters.user);

const getAllUsers = async () => {
    try {
        const response = await axios.get('/api/users');
        users.value = response.data;
    } catch (error) {
        console.error('Error fetching users:', error);
    }
};

const getFriends = async () => {
    try {
        const response = await axios.get('/api/friends');
        friends.value = response.data;
    } catch (error) {
        console.error('Error fetching friends:', error);
    }
};

const addFriend = async (userId) => {
    try {
        await axios.post('/api/friends', { friend_id: userId });
        toast.success('Kullanıcı başarıyla eklendi');
        getFriends();
    } catch (error) {
        toast.error('Kullanıcı eklenemedi');
        console.error('Error adding friend:', error);
    }
};

const filteredUsers = computed(() => {
    const friendIds = friends.value.map(friend => friend.id);
    if (!searchQuery.value.trim()) {
        return [];
    }
    const searchTerm = searchQuery.value.toLowerCase();
    return users.value.filter(user =>
        (user.name.toLowerCase().includes(searchTerm) || user.email.toLowerCase().includes(searchTerm)) &&
        user.id !== currentUser.value.id &&
        !friendIds.includes(user.id)
    );
});

const getInitials = (name, surname) => {
    const nameInitial = name ? name.charAt(0).toUpperCase() : '';
    const surnameInitial = surname ? surname.charAt(0).toUpperCase() : '';
    return nameInitial + surnameInitial;
};



onMounted(() => {
    getAllUsers();
    getFriends();
});
</script>
