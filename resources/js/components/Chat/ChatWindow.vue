<template>
    <div>
        <div v-if="selectedUser"  class="flex flex-col h-screen">
            <!-- Header -->
            <div class="bg-gray-300 px-4 py-2 flex items-center justify-between mt-20">
                <div class="flex items-center space-x-3">
                    <img src="https://picsum.photos/45" alt="" class="rounded-full">
                    <p class="font-semibold">{{ selectedUser.name }}</p>
                </div>
                <div class="relative inline-block text-left group">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="bi bi-three-dots-vertical w-6 h-6">
                        <path fill-rule="evenodd" d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                    </svg>
                    <div class="origin-top-right absolute right-0 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 hidden group-hover:block">
                        <div class="py-1">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 cursor-pointer" @click="chatClose">Close Chat</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 cursor-pointer">Clear Chat</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Messages -->
            <div class="flex-1 overflow-y-auto bg-gray-100 p-4">
                <div v-for="message in filteredMessages" :key="message.id" class="mb-4">
                    <div v-if="message.sender_id === currentUser.id" class="flex items-end justify-end">
                        <div class="bg-green-500 p-2 rounded-md">
                            <p class="text-white">{{ message.message }}</p>
                        </div>
                        <img src="https://picsum.photos/45" alt="" class="rounded-full">
                    </div>
                    <div v-else class="flex items-start">
                        <img src="https://picsum.photos/45" alt="" class="rounded-full">
                        <div class="ml-3 bg-blue-500 p-2 rounded-md">
                            <p class="text-white">{{ message.message }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mesaj Gönderme Bölümü -->
            <div class="bg-gray-300 px-4 py-2 flex items-center">
                <input v-model="newMessage" type="text" placeholder="Mesajınızı girin..." class="w-full p-2 rounded-md border border-gray-500 focus:outline-none focus:ring focus:border-blue-400">
                <button @click="sendMessage" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none">Gönder</button>
            </div>
        </div>

        <!-- Mesajlaşma Penceresi Kapalıysa -->
        <div v-else class="flex flex-col items-center justify-center h-screen">
            <img src="https://picsum.photos/100" alt="" class="rounded-full">
            <p class="mt-2 text-2xl font-semibold">IMS CHAT APP</p>
            <p class="text-gray-600">Bir sohbet seç ve mesajlaşmaya başla</p>
        </div>
    </div>
</template>

<script setup>
import {computed, onMounted, ref} from 'vue';
import axios from "axios";

const props = defineProps({
    currentUser: Object,
    selectedUserId: Number,
    users: Array,
});

const selectedUser = computed(() => {
    return props.users.find(user => user.id === props.selectedUserId);
});
const users = props.users;
const filteredMessages = computed(() => {
    return messages.value.filter(message =>
        (message.sender_id === selectedUser.value.id) || (message.receiver_id === selectedUser.value.id));
});

const newMessage = ref('');
const messages = ref();


const fetchMessages = async (userId) => {
    try {
        const response = await axios.post("/api/messages",{
            id : userId
        });
        messages.value = response.data;

    } catch (error) {
        console.error('Error fetching messages:', error);
    }
};

onMounted(() => {

    fetchMessages(props.currentUser.id);
});


const sendMessage = () => {
    if (newMessage.value.trim() !== '') {
        messages.value.push({
            id: messages.value.length + 1,
            sender_id: props.currentUser.id,
            receiver_id: selectedUser.value.id,
            content: newMessage.value
        });
        newMessage.value = '';
    }
};



const isChatOpen = ref(true);
const chatClose = () => {
    isChatOpen.value=false;
}
</script>

<style scoped>
/* Gerekirse özel stiller eklenebilir */
</style>
