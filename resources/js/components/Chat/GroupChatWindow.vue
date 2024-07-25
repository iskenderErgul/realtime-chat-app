<template>
  <div>
    <div v-if="selectedGroupId" class="flex flex-col h-screen">
      <!-- Header -->
      <div class="bg-gray-300 px-4 py-2 flex items-center justify-between mt-20">
        <div class="flex items-center space-x-3">
          <div class="rounded-full bg-gray-300 w-12 h-12 flex items-center justify-center">
            <span class="font-semibold text-xl text-gray-600">{{ getGroupInitials(selectedGroupName)}}</span>
          </div>
          <p class="font-semibold">{{selectedGroupName}}</p>
        </div>
        <div class="relative inline-block text-left group">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="bi bi-three-dots-vertical w-6 h-6">
            <path fill-rule="evenodd" d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
          </svg>
          <div class="origin-top-right absolute right-0 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 hidden group-hover:block">
            <div class="py-1">
              <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 cursor-pointer" @click="closeChatWindow">Close Chat</a>
              <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 cursor-pointer" >Clear Chat</a>
              <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 cursor-pointer" @click="groupSettings(selectedGroupId)" >Group Settings</a>
            </div>
          </div>
        </div>
      </div>


      <div class="flex-1 overflow-y-auto bg-gray-100 p-4">
        <!-- Message Display Area -->
        <div v-for="message in messages" :key="message.id" class="mb-4">
          <div v-if="message.sender_id === currentUser.id" class="flex items-end justify-end">
            <div class="bg-green-500 p-2 rounded-md">
              <p class="text-white">{{ message.message }}</p>
            </div>
          </div>
          <div v-else class="flex items-start">
            <div class="flex items-center">
              <div class="bg-gray-300 text-black rounded-full w-8 h-8 flex items-center justify-center mr-2">
                <p class="text-sm">{{ getUserInitials(message.sender) }}</p>
              </div>
              <div class="bg-blue-500 p-2 rounded-md">
                <p class="text-white">{{ message.message }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>


      <!-- Message Input -->
      <div class="bg-gray-300 px-4 py-2 flex items-center">
        <input v-model="newMessage" type="text"  @keyup.enter="sendMessage"  placeholder="Type your message..." class="w-full p-2 rounded-md border border-gray-500 focus:outline-none focus:ring focus:border-blue-400">
        <button @click="sendMessage" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none">Send</button>
      </div>
    </div>

    <!-- Initial State -->
    <div v-else class="flex flex-col items-center justify-center h-screen">
      <img src="https://picsum.photos/100" alt="" class="rounded-full">
      <p class="mt-2 text-2xl font-semibold">IMS CHAT APP</p>
      <p class="text-gray-600">Select a chat and start messaging</p>
    </div>
  </div>
</template>

<script setup>

import axios from "axios";
import {onMounted, ref, watch, watchEffect} from "vue";
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
    window.location.reload();
}




watch(() => selectedGroupId, async (newGroupId) => {
    if (newGroupId) {
        await fetchGroupMessages(newGroupId);
        await getGroupDetails(newGroupId);
    }
}, { immediate: true });

</script>
