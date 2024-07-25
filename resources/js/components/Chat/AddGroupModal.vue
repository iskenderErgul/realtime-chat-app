<template>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold mb-4">Add New Group</h2>
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-semibold">Group Name</label>
                    <input v-model="groupName" type="text" id="name" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 font-semibold">Description</label>
                    <textarea v-model="groupDescription" id="description" class="w-full px-4 py-2 border rounded-md" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">Members</label>
                    <ul v-if="selectedMembers.length > 0" class="mt-2">
                        <li v-for="member in selectedMembers" :key="member.id" class="flex items-center justify-between bg-gray-100 px-3 py-1 rounded-md mb-1">
                            <span>{{ member.name }} {{ member.surname }}</span>
                            <button type="button" @click="removeMember(member.id)" class="ml-2 text-red-500">Remove</button>
                        </li>
                    </ul>
                    <div class="mt-2">
                        <button v-for="friend in friends" :key="friend.id" type="button" @click="addMember(friend.friend)" class="bg-blue-500 text-white px-4 py-2 rounded-md mr-2">{{ friend.friend.name }} {{ friend.friend.surname }}</button>
                    </div>
                </div>

                    <div class="flex justify-end">
                        <div>
                            <button @click="createGroup" class="bg-green-500 text-white px-4 py-2 rounded-md mr-2">Create Group</button>
                        </div>
                        <div>
                            <button @click="closeModal" class="bg-gray-500 text-white px-4 py-2 rounded-md">Cancel</button>
                        </div>
                    </div>
        </div>
    </div>
</template>

<script setup>
import { ref  } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';


const router = useRouter();
const groupName = ref('');
const groupDescription = ref('');
const selectedMembers = ref([]);
const friends = ref([]);
import { useToast } from 'vue-toastification';

const toast = useToast();

const closeModal = () => {
    window.location.reload();
};





const createGroup = async () => {
    try {
        const response = await axios.post('/api/groups', {
            name: groupName.value,
            description: groupDescription.value,
            members: selectedMembers.value.map(member => member.id)
        });
       toast.success('Grup Başarıyla Oluşturldu');
        setTimeout(() => {
            closeModal();
        }, 3000);
    } catch (error) {
        toast.error('Grup Oluşturulamadı');
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

const addMember = (friend) => {
    if (!selectedMembers.value.some(member => member.id === friend.id)) {
        selectedMembers.value.push(friend);
    }
};

const removeMember = memberId => {
    selectedMembers.value = selectedMembers.value.filter(member => member.id !== memberId);
};

getFriends();
</script>
