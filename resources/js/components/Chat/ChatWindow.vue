<template>
    <div>
        <div v-if="selectedUser" class="flex flex-col h-screen">
            <!-- Header -->
            <div class="glass-header px-6 py-4 flex items-center justify-between mt-20 border-b border-white/20">
                <div class="flex items-center space-x-3">
                    <template v-if="selectedUser.avatar">
                        <img :src="selectedUser.avatar" alt="Avatar" class="avatar rounded-full" width="45">
                    </template>
                    <template v-else>
                        <div class="avatar avatar-initials rounded-full w-12 h-12">
                            <span class="font-semibold text-xl">{{ getInitials(selectedUser.name, selectedUser.surname) }}</span>
                        </div>
                    </template>
                    <p class="font-semibold text-lg text-gray-800">{{ selectedUser.name }} {{ selectedUser.surname }}</p>
                </div>
                <div class="relative inline-block text-left group">
                    <button class="p-2 hover:bg-white/50 rounded-lg transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6 text-gray-700">
                            <path fill-rule="evenodd" d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                        </svg>
                    </button>
                    <div class="origin-top-right absolute right-0 w-44 rounded-xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 z-50 hidden group-hover:block overflow-hidden">
                        <div class="py-1">
                            <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gradient-primary hover:text-white cursor-pointer transition-all" @click="closeChatWindow">Close Chat</a>
                            <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gradient-primary hover:text-white cursor-pointer transition-all" @click="clearChat">Clear Chat</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Messages -->
            <div class="flex-1 overflow-y-auto p-6 chat-messages-area">
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
                            <div v-else-if="message.type === 'text'" class="message-bubble message-sent">
                                <p class="text-white">{{ message.message }}</p>
                            </div>
                        </div>
                        <!-- Avatar -->
                        <template v-if="currentUser.avatar">
                            <img :src="currentUser.avatar" alt="Avatar" class="avatar rounded-full ml-2" width="35">
                        </template>
                        <template v-else>
                            <div class="avatar avatar-initials rounded-full w-9 h-9 ml-2">
                                <span class="font-semibold text-sm">{{ getInitials(currentUser.name, currentUser.surname) }}</span>
                            </div>
                        </template>
                    </div>

                    <div v-else class="flex items-start">
                        <template v-if="selectedUser.avatar">
                            <img :src="selectedUser.avatar" alt="Avatar" class="avatar rounded-full mr-2" width="35">
                        </template>
                        <template v-else>
                            <div class="avatar avatar-initials rounded-full w-9 h-9 mr-2">
                                <span class="font-semibold text-sm">{{ getInitials(selectedUser.name, selectedUser.surname) }}</span>
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
                            <div v-else-if="message.type === 'text'" class="message-bubble message-received">
                                <p>{{ message.message }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




            <!-- Input Area -->
            <div class="glass-header px-6 py-4 flex items-center gap-3 border-t border-white/20">

                <label for="file-upload" class="btn-modern p-3 bg-white/80 rounded-xl hover:bg-white cursor-pointer shadow-md">
                    <font-awesome-icon :icon="['fas', 'paperclip']" class="text-lg text-gray-700"/>
                    <input id="file-upload" type="file" @change="handleFileSelection" class="hidden"/>
                </label>

                <div class="flex-1 flex items-center gap-2">
                    <p v-if="fileName" class="text-sm text-gray-700 bg-white/60 px-3 py-2 rounded-lg">{{ fileName }}</p>

                    <!-- Message Input -->
                    <input v-model="newMessage" type="text" @keyup.enter="sendMessage" placeholder="Mesajınızı girin..." class="input-modern flex-1 px-4 py-3 rounded-xl focus:outline-none">
                </div>

                <!-- Send Button -->
                <button @click="sendMessage" class="btn-modern px-5 py-3 bg-gradient-primary text-white rounded-xl shadow-lg hover:shadow-xl">
                    <font-awesome-icon :icon="['fas', 'paper-plane']" class="text-lg"/>
                </button>
            </div>

        </div>




        <div v-else class="flex flex-col items-center justify-center h-screen animate-fade-in">
            <div class="glass-card p-12 rounded-3xl text-center">
                <div class="w-24 h-24 bg-gradient-primary rounded-full mx-auto mb-6 flex items-center justify-center">
                    <font-awesome-icon :icon="['fas', 'comments']" class="text-5xl text-white"/>
                </div>
                <h2 class="text-3xl font-bold text-gradient mb-3">IMS CHAT APP</h2>
                <p class="text-gray-600 text-lg">Bir sohbet seç ve mesajlaşmaya başla</p>
            </div>
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
.glass-header {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}

.chat-messages-area {
    background: rgba(255, 255, 255, 0.3);
}

.audio-player-container {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.audio-player {
    width: 100%;
    max-width: 100%;
    height: 50px;
}

.audio-file-name {
    margin-top: 8px;
    font-size: 14px;
    color: #333;
    text-align: center;
}
</style>
