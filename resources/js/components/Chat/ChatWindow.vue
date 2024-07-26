<template>
    <div>
        <div v-if="selectedUser" class="flex flex-col h-screen">
            <!-- Header -->
            <div class="bg-gray-300 px-4 py-2 flex items-center justify-between mt-20">
                <div class="flex items-center space-x-3">
                    <template v-if="selectedUser.avatar">
                        <img :src="selectedUser.avatar" alt="Avatar" class="rounded-full" width="45">
                    </template>
                    <template v-else>
                        <div class="rounded-full bg-gray-300 w-12 h-12 flex items-center justify-center">
                            <span class="font-semibold text-xl text-gray-600">{{ getInitials(selectedUser.name, selectedUser.surname) }}</span>
                        </div>
                    </template>
                    <p class="font-semibold">{{ selectedUser.name }} {{ selectedUser.surname }}</p>
                </div>
                <div class="relative inline-block text-left group">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="bi bi-three-dots-vertical w-6 h-6">
                        <path fill-rule="evenodd" d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                    </svg>
                    <div class="origin-top-right absolute right-0 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 hidden group-hover:block">
                        <div class="py-1">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 cursor-pointer" @click="closeChatWindow">Close Chat</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 cursor-pointer" @click="clearChat">Clear Chat</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Messages -->
            <div class="flex-1 overflow-y-auto bg-gray-100 p-4">
                <div v-for="message in filteredMessages" :key="message.id" class="mb-4">
                    <div v-if="message.sender_id === currentUser.id" class="flex items-end justify-end">
                        <div class="bg-green-500 p-2 rounded-md">
                            <p class="text-white" :data-tooltip="formatDate(message.updated_at)"
                               v-tooltip="formatDate(message.updated_at)">{{ message.message }}</p>
                        </div>
                        <template v-if="currentUser.avatar">
                            <img :src="currentUser.avatar" alt="Avatar" class="rounded-full ml-2" width="35">
                        </template>
                        <template v-else>
                            <div class="rounded-full bg-gray-300 w-9 h-9 flex items-center justify-center ml-2">
                                <span class="font-semibold text-sm text-gray-600">{{ getInitials(currentUser.name, currentUser.surname) }}</span>
                            </div>
                        </template>
                    </div>
                    <div v-else class="flex items-start">
                        <template v-if="selectedUser.avatar">
                            <img :src="selectedUser.avatar" alt="Avatar" class="rounded-full mr-2" width="35">
                        </template>
                        <template v-else>
                            <div class="rounded-full bg-gray-300 w-9 h-9 flex items-center justify-center mr-2">
                                <span class="font-semibold text-sm text-gray-600">{{ getInitials(selectedUser.name, selectedUser.surname) }}</span>
                            </div>
                        </template>
                        <div class="bg-blue-500 p-2 rounded-md">
                            <p class="text-white" :data-tooltip="formatDate(message.updated_at)"
                               v-tooltip="formatDate(message.updated_at)">{{ message.message }}</p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="bg-gray-300 px-4 py-2 flex items-center">
                <input v-model="newMessage" type="text"  @keyup.enter="sendMessage"  placeholder="Mesajınızı girin..." class="w-full p-2 rounded-md border border-gray-500 focus:outline-none focus:ring focus:border-blue-400">
                <button @click="sendMessage" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none">Gönder</button>
            </div>
        </div>


        <div v-else class="flex flex-col items-center justify-center h-screen">
            <img src="https://picsum.photos/100" alt="" class="rounded-full">
            <p class="mt-2 text-2xl font-semibold">IMS CHAT APP</p>
            <p class="text-gray-600">Bir sohbet seç ve mesajlaşmaya başla</p>
        </div>
    </div>
</template>

<script setup>
import {computed, onMounted, ref, watchEffect} from 'vue';
import axios from "axios";
import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
import router from "@/router/router.js";
window.Pusher = Pusher;

const echo= new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});


const props = defineProps({
    currentUser: Object,
    selectedUserId: Number,

    users: Array,
});

const selectedUser = computed(() => {
    return props.users.find(user => user.id === props.selectedUserId);
});

const newMessage = ref('');
const messages = ref([]);


const fetchMessages = async (userId) => {
    try {
        const response = await axios.post("/api/messages", { id: userId });
        messages.value = response.data;
    } catch (error) {
        console.error('Error fetching messages:', error);
    }
};

const filteredMessages = computed(() => {
    return messages.value.filter(message =>
        (message.sender_id === selectedUser.value.id) || (message.receiver_id === selectedUser.value.id)
    );
});

onMounted(() => {
    fetchMessages(props.currentUser.id);

    echo.channel(`chat.${props.currentUser.id}`)
        .listen('MessageSent', (e) => {
            console.log(e);
            messages.value.push(e.message);
        });

});

const sendMessage = async () => {
    if (newMessage.value.trim() !== '') {
        try {
            const response = await axios.post("/api/messages/send", {
                sender_id: props.currentUser.id,
                receiver_id: selectedUser.value.id,
                message: newMessage.value
            });
            messages.value.push(response.data);
            newMessage.value = '';
        } catch (error) {
            console.error('Error sending message:', error);
        }
    }
};

const clearChat = async () => {
    try {
        await axios.delete("/api/messages/clear", {
            params: {
                sender_id: props.currentUser.id,
                receiver_id: selectedUser.value.id
            }
        });
        messages.value = [];
        chatClose();
    } catch (error) {
        console.error('Error clearing chat:', error);
    }
};

const closeChatWindow = ()  =>  {
    router.push('/chat');
    window.location.reload();
}

const getInitials = (name, surname) => {
    const nameInitial = name ? name.charAt(0).toUpperCase() : '';
    const surnameInitial = surname ? surname.charAt(0).toUpperCase() : '';
    return nameInitial + surnameInitial;
};

const formatDate = (dateString) => {
    const options = {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',

    };


    const date = new Date(dateString);
    return date.toLocaleDateString('tr-TR', options);
};
</script>

