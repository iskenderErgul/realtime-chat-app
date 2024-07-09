<template>
    <div class="container mx-auto my-auto">
        <div class="flex flex-col justify-end h-80">
            <div ref="messagesContainer" class="p-4 overflow-y-auto max-h-fit">
                <div
                    v-for="message in messages"
                    :key="message.id"
                    class="flex items-center mb-2"
                >
                    <div
                        v-if="message.sender_id === currentUser.id"
                        class="p-2 ml-auto text-white bg-blue-500 rounded-lg"
                    >
                        {{ message.message }}
                    </div>
                    <div v-else class="p-2 mr-auto bg-gray-200 rounded-lg">
                        {{ message.message }}
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center mt-4">
            <input
                type="text"
                v-model="newMessage"
                @keyup.enter="sendMessage"
                placeholder="Mesaj yazın..."
                class="flex-1 px-2 py-1 border rounded-lg"
            />
            <button
                @click="sendMessage"
                class="px-4 py-1 ml-2 text-white bg-blue-500 rounded-lg"
            >
                Gönder
            </button>
        </div>
        <small v-if="isFriendTyping" class="text-gray-700">
            {{ friend.name }} yazıyor...
        </small>
    </div>
</template>

<script setup>
import {ref, onMounted} from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';
import {useStore} from "vuex";


const route = useRoute();
const store = useStore();

const messages = ref([]);

const newMessage = ref('');
const currentUser = store.getters.user;
const friend = ref();
const friendId = route.params.friendId;
const isFriendTyping = ref(false);


const sendMessage = () => {
    if (newMessage.value.trim() !== '') {
        const newMsg = {
            id: messages.value.length + 1,
            sender_id: currentUser.value.id,
            text: newMessage.value,
            timestamp: new Date().toISOString()
        };
        messages.value.push(newMsg);
        newMessage.value = '';
    }
};
const fetchFriendUser = async () => {
    try {

        const response = await axios.get(`/api/user/${route.params.friendId}`);
        friend.value = response.data;
        console.log(friend.value)


    } catch (error) {
        console.error('Error fetching user:', error);
    }
};

onMounted(() => {
    fetchFriendUser();


   axios.post('/api/messages', {
        friendId: friendId,
        currentUser: currentUser
    })
        .then((response) => {
            console.log(response);
            messages.value = response.data;
            console.log(messages);
        })
        .catch((error) => {
            console.error('Error fetching messages:', error);
        });

});
</script>

