<template>
    <div ref="recaptchaContainer" class="g-recaptcha"></div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
    siteKey: {
        type: String,
        required: true
    },
    locale: {
        type: String,
        default: 'en'
    }
});

const recaptchaContainer = ref(null);

const loadRecaptchaScript = () => {
    const script = document.createElement('script');
    script.src = `https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl=${props.locale}`;
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);
};

onMounted(() => {
    // `onloadCallback` fonksiyonunu global olarak tanımlayın
    window.onloadCallback = () => {
        if (window.grecaptcha && props.siteKey) {
            window.grecaptcha.render(recaptchaContainer.value, {
                sitekey: props.siteKey
            });
        }
    };

    loadRecaptchaScript();
});
</script>


