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
                        <div class="flex flex-col items-end">
                            <div v-if="message.type === 'unknown'" class="bg-green-200 p-2 rounded-md max-w-xs">
                                <div v-if="message.file_path" class="mb-1">
                                    <template v-if="message.file_path.endsWith('.mp3') || message.file_path.endsWith('.wav')">
                                        <div class="audio-player-container">
                                            <audio controls class="audio-player">
                                                <source :src="`/storage/${message.file_path}`" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                            <p class="audio-file-name">{{ getFileName(message.file_path) }}</p>
                                        </div>
                                    </template>
                                    <template v-else-if="message.file_path.endsWith('.jpg') || message.file_path.endsWith('.png')">
                                        <img :src="`/storage/${message.file_path}`" alt="Message Attachment" class="max-w-xs h-auto rounded-md object-cover"/>
                                    </template>
                                    <template v-else-if="message.file_path.endsWith('.mp4') || message.file_path.endsWith('.avi') || message.file_path.endsWith('.mov')">
                                        <video controls class="w-full max-w-xs h-auto rounded-md">
                                            <source :src="`/storage/${message.file_path}`" type="video/mp4">
                                            Your browser does not support the video element.
                                        </video>
                                    </template>
                                </div>
                                <div v-if="message.message">
                                    <p class="text-black mt-1">{{ message.message }}</p>
                                </div>
                            </div>
                            <div v-else-if="message.type === 'image'" class="bg-green-500 p-2 rounded-md max-w-xs">
                                <img :src="`/storage/${message.file_path}`" alt="Message Image" class="w-full h-auto rounded-md object-cover" @click="openImageModal(`/storage/${message.file_path}`)"/>
                                <div v-if="message.message" class="mt-1">
                                    <p class="text-white">{{ message.message }}</p>
                                </div>
                            </div>
                            <div v-else-if="message.type === 'audio'" class="bg-green-200 p-2 rounded-md max-w-xs">
                                <div class="audio-player-container">
                                    <audio controls class="audio-player">
                                        <source :src="`/storage/${message.file_path}`" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                    <p class="audio-file-name">{{ getFileName(message.file_path) }}</p>
                                </div>
                                <div v-if="message.message" class="mt-1">
                                    <p class="text-black">{{ message.message }}</p>
                                </div>
                            </div>
                            <div v-else-if="message.type === 'video'" class="bg-green-500 p-2 rounded-md max-w-xs">
                                <video controls class="w-full max-w-xs h-auto rounded-md">
                                    <source :src="`/storage/${message.file_path}`" type="video/mp4">
                                    Your browser does not support the video element.
                                </video>
                                <div v-if="message.message" class="mt-1">
                                    <p class="text-white">{{ message.message }}</p>
                                </div>
                            </div>
                            <div v-else-if="message.type === 'text'" class="bg-green-500 p-2 rounded-md max-w-xs">
                                <p class="text-white">{{ message.message }}</p>
                            </div>
                        </div>
                        <!-- Avatar -->
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
                        <div class="flex flex-col items-start">
                            <div v-if="message.type === 'unknown'" class="bg-blue-200 p-2 rounded-md max-w-xs">
                                <div v-if="message.file_path" class="mb-1">
                                    <template v-if="message.file_path.endsWith('.mp3') || message.file_path.endsWith('.wav')">
                                        <div class="audio-player-container">
                                            <audio controls class="audio-player">
                                                <source :src="`/storage/${message.file_path}`" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                            <p class="audio-file-name">{{ getFileName(message.file_path) }}</p>
                                        </div>
                                    </template>
                                    <template v-else-if="message.file_path.endsWith('.jpg') || message.file_path.endsWith('.png')">
                                        <img :src="`/storage/${message.file_path}`" alt="Message Attachment" class="max-w-xs h-auto rounded-md object-cover"/>
                                    </template>
                                    <template v-else-if="message.file_path.endsWith('.mp4') || message.file_path.endsWith('.avi') || message.file_path.endsWith('.mov')">
                                        <video controls class="w-full max-w-xs h-auto rounded-md">
                                            <source :src="`/storage/${message.file_path}`" type="video/mp4">
                                            Your browser does not support the video element.
                                        </video>
                                    </template>
                                </div>
                                <div v-if="message.message" class="mt-1">
                                    <p class="text-black">{{ message.message }}</p>
                                </div>
                            </div>
                            <div v-else-if="message.type === 'image'" class="bg-blue-500 p-2 rounded-md max-w-xs">
                                <img :src="`/storage/${message.file_path}`" alt="Message Image" class="w-full h-auto rounded-md object-cover" @click="openImageModal(`/storage/${message.file_path}`)"/>
                                <div v-if="message.message" class="mt-1">
                                    <p class="text-white">{{ message.message }}</p>
                                </div>
                            </div>
                            <div v-else-if="message.type === 'audio'" class="bg-blue-200 p-2 rounded-md max-w-xs">
                                <div class="audio-player-container">
                                    <audio controls class="audio-player">
                                        <source :src="`/storage/${message.file_path}`" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                    <p class="audio-file-name">{{ getFileName(message.file_path) }}</p>
                                </div>
                                <div v-if="message.message" class="mt-1">
                                    <p class="text-black">{{ message.message }}</p>
                                </div>
                            </div>
                            <div v-else-if="message.type === 'video'" class="bg-blue-500 p-2 rounded-md max-w-xs">
                                <video controls class="w-full max-w-xs h-auto rounded-md">
                                    <source :src="`/storage/${message.file_path}`" type="video/mp4">
                                    Your browser does not support the video element.
                                </video>
                                <div v-if="message.message" class="mt-1">
                                    <p class="text-white">{{ message.message }}</p>
                                </div>
                            </div>
                            <div v-else-if="message.type === 'text'" class="bg-blue-500 p-2 rounded-md max-w-xs">
                                <p class="text-white">{{ message.message }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




            <!-- Input Area -->
            <div class="bg-gray-300 px-4 py-2 flex items-center border-t border-gray-400">

                <label for="file-upload" class="p-2 bg-gray-200 rounded-full hover:bg-gray-300 cursor-pointer">
                    <font-awesome-icon :icon="['fas', 'paperclip']" class="text-lg"/>
                    <input id="file-upload" type="file" @change="handleFileSelection" class="hidden"/>
                </label>


                <p v-if="fileName" class="ml-2 text-gray-700">{{ fileName }}</p>

                <!-- Message Input -->
                <input v-model="newMessage" type="text" @keyup.enter="sendMessage" placeholder="Mesajınızı girin..." class="w-full px-3 py-2 border border-gray-500 rounded-md focus:outline-none focus:ring focus:border-blue-400 ml-2">

                <!-- Send Button -->
                <button @click="sendMessage" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none">
                    <font-awesome-icon :icon="['fas', 'paper-plane']" class="text-lg"/>
                </button>
            </div>

        </div>




        <div v-else class="flex flex-col items-center justify-center h-screen">
            <img src="https://picsum.photos/100" alt="" class="rounded-full">
            <p class="mt-2 text-2xl font-semibold">IMS CHAT APP</p>
            <p class="text-gray-600">Bir sohbet seç ve mesajlaşmaya başla</p>
        </div>

        <FullScreenModal :imageSrc="currentImage" :isOpen="isModalOpen" @update:isOpen="isModalOpen = $event"/>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import router from '@/router/router.js';
import FullScreenModal from './FullScreenModal.vue';
import { useToast } from 'vue-toastification';

const toast = useToast();

window.Pusher = Pusher;

const echo = new Echo({
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
const file = ref(null);
const fileName = ref('');
const isModalOpen = ref(false);
const currentImage = ref('');

const openImageModal = (imageSrc) => {
    currentImage.value = imageSrc;
    isModalOpen.value = true;
};

const fetchMessages = async (userId) => {
    try {
        const response = await axios.post('/api/messages', { id: userId });
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
            console.log('Message received:', e.message);
            messages.value.push(e.message);
        });
});

const sendMessage = async () => {
    if (newMessage.value.trim() !== '' || file.value) {
        const formData = new FormData();
        formData.append('sender_id', props.currentUser.id);
        formData.append('receiver_id', selectedUser.value.id);
        formData.append('message', newMessage.value);
        if (file.value) {
            formData.append('file', file.value);
        }

        try {
            const response = await axios.post('/api/messages/send', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
            messages.value.push(response.data);
            newMessage.value = '';
            file.value = null;
            fileName.value = '';
        } catch (error) {
            console.error('Error sending message:', error);
        }
    }
};

const getFileName = (filePath) => {
    return filePath.split('/').pop();
}

const handleFileSelection = (event) => {
    const selectedFile = event.target.files[0];
    if (selectedFile) {
        file.value = selectedFile;
        fileName.value = selectedFile.name;
    }
};

const getInitials = (name, surname) => {
    return (name[0] + (surname ? surname[0] : '')).toUpperCase();
};

const formatDate = (date) => {
    return new Date(date).toLocaleTimeString();
};

const closeChatWindow = () => {
    router.push('/');
};

const clearChat = () => {
    axios.delete('/api/messages/clear', {
        params: {
            sender_id: props.currentUser.id,
            receiver_id: selectedUser.value.id
        }
    })
        .then(response => {
            messages.value = messages.value.filter(message => message.receiver_id !== selectedUser.value.id);
            console.log(response);
            toast.success('Sohbet Temizlendi');
        })
        .catch(error => {
            toast.error('There was an error clearing the chat!', error);
        });
};

</script>

<style scoped>
.audio-player-container {
    width: 100%; /* Full width to occupy the container */
    display: flex;
    flex-direction: column;
    align-items: center; /* Center the audio player horizontally */
}

.audio-player {
    width: 100%; /* Full width to fit within the container */
    max-width: 100%; /* Ensure it doesn't exceed container width */
    height: 50px; /* Adjust the height as needed */
}

.audio-file-name {
    margin-top: 8px; /* Space between player and file name */
    font-size: 14px; /* Adjust font size */
    color: #333; /* Text color */
    text-align: center; /* Center align the text */
}
</style>
