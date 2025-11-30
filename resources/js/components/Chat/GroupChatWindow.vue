<template>
  <div>
    <div v-if="selectedGroupId" class="flex flex-col h-screen">
      <!-- Header -->
      <div class="glass-header px-6 py-4 flex items-center justify-between mt-20 border-b border-white/20">
        <div class="flex items-center space-x-3">
          <div class="avatar avatar-initials rounded-full w-12 h-12">
            <span class="font-semibold text-xl">{{ getGroupInitials(selectedGroupName)}}</span>
          </div>
          <p class="font-semibold text-lg text-gray-800">{{selectedGroupName}}</p>
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
              <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gradient-primary hover:text-white cursor-pointer transition-all" >Clear Chat</a>
              <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gradient-primary hover:text-white cursor-pointer transition-all" @click="groupSettings(selectedGroupId)" >Group Settings</a>
            </div>
          </div>
        </div>
      </div>


      <div class="flex-1 overflow-y-auto p-6 chat-messages-area">
        <!-- Message Display Area -->
        <div v-for="message in messages" :key="message.id" class="mb-4">
          <div v-if="message.sender_id === currentUser.id" class="flex items-end justify-end">
            <div class="message-bubble message-sent">
              <p class="text-white" :data-tooltip="formatDate(message.updated_at)"
                 v-tooltip="formatDate(message.updated_at)">{{ message.message }}</p>
            </div>
          </div>
          <div v-else class="flex items-start">
            <div class="flex items-center">
              <div class="avatar avatar-initials rounded-full w-8 h-8 mr-2">
                <p class="text-sm">{{ getUserInitials(message.sender) }}</p>
              </div>
              <div class="message-bubble message-received">
                  <p v-tooltip="`${message.sender.name} ${message.sender.surname} - ${formatDate(message.updated_at)}`">
                      {{ message.message }}
                  </p>
              </div>
            </div>
          </div>
        </div>
      </div>


      <!-- Message Input -->
      <div class="glass-header px-6 py-4 flex items-center gap-3 border-t border-white/20">
        <input v-model="newMessage" type="text"  @keyup.enter="sendMessage"  placeholder="Mesajınızı girin..." class="input-modern flex-1 px-4 py-3 rounded-xl focus:outline-none">
        <button @click="sendMessage" class="btn-modern px-5 py-3 bg-gradient-primary text-white rounded-xl shadow-lg hover:shadow-xl">
          <font-awesome-icon :icon="['fas', 'paper-plane']" class="text-lg"/>
        </button>
      </div>
    </div>

    <!-- Initial State -->
    <div v-else class="flex flex-col items-center justify-center h-screen animate-fade-in">
      <div class="glass-card p-12 rounded-3xl text-center">
        <div class="w-24 h-24 bg-gradient-secondary rounded-full mx-auto mb-6 flex items-center justify-center">
          <font-awesome-icon :icon="['fas', 'users']" class="text-5xl text-white"/>
        </div>
        <h2 class="text-3xl font-bold text-gradient mb-3">IMS CHAT APP</h2>
        <p class="text-gray-600 text-lg">Bir grup seç ve mesajlaşmaya başla</p>
      </div>
    </div>
  </div>
</template>

<script setup>

import axios from "axios";
import {onMounted, ref, watch, computed} from "vue";
import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
import router from "../../router/router.js";
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
  selectedGroupId: Number,
  currentUser: Object,

});

const selectedGroupId = props.selectedGroupId;
const selectedGroup= ref(null);
const selectedGroupName = ref(null);
const messages = ref([]);
const newMessage = ref("");

onMounted(() => {

    echo.channel(`group.${selectedGroupId}`)
        .listen('GroupMessageSent', (e) => {
            messages.value.push(e.message);
            console.log(e);
        });

});


const fetchGroupMessages = async (groupId) => {
  try {
    const response = await axios.get(`/api/groups/${groupId}/messages`);
    messages.value = response.data;
  } catch (error) {
    console.error('Error fetching group messages:', error);
  }
};
const getGroupDetails = async  (groupId) => {
    const response = await axios.get(`/api/groups/${groupId}`);
    selectedGroupName.value=response.data.name;
    selectedGroup.value=response.data;
}
const sendMessage = async () => {
  if (!newMessage.value.trim()) return;

  const messageData = {
    group_id: selectedGroupId,
    sender_id: props.currentUser.id,
    message: newMessage.value,
  };

  try {
    const response = await axios.post("/api/groups/messages", messageData);

    newMessage.value = "";
  } catch (error) {
    console.error("Error sending message:", error);
  }
};
const groupSettings = async (selectedGroupId) => {
    await router.push({ name: 'groupProfile', params: { selectedGroupId } });
};


const getGroupInitials = (name) => {
  if (!name) return '';
  return name.substring(0, 2).toUpperCase();
};
const getUserInitials = (user) => {
  if (!user) return '';
  const initials = user.name.charAt(0) + user.surname.charAt(0);
  return initials.toUpperCase();
};


const closeChatWindow = ()  =>  {
    router.push('/chat');
    setTimeout(() => {
        window.location.reload();

    },200)
}
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




watch(() => selectedGroupId, async (newGroupId) => {
    if (newGroupId) {
        await fetchGroupMessages(newGroupId);
        await getGroupDetails(newGroupId);
    }
}, { immediate: true });

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
</style>
