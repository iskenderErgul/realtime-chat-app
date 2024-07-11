<template>
    <div>
        <h2>User Profile</h2>
        <p>User ID: {{ $route.params.userId }}</p>

    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();
const userId = router.currentRoute.value.params.userId;


</script>
