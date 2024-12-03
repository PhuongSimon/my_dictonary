<script setup>
import { ref } from 'vue';

const props = defineProps({
    words: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(['close']);
</script>

<template>
    <Teleport to="body">
        <Transition leave-active-class="duration-200">
            <div v-show="props.words.length > 0" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" scroll-region>
                <div
                    class="fixed inset-0 transform transition-all bg-gray-800 bg-opacity-50"
                    @click="$emit('close')"
                ></div>
                <div
                    class="relative bg-white rounded-lg shadow-xl p-4 max-w-lg mx-auto"
                >
                    <h2 class="text-lg font-semibold mb-4">Danh sách từ sai</h2>
                    <ul>
                        <li v-for="word in props.words" :key="word" class="text-gray-700">{{ word }}</li>
                    </ul>
                    <button
                        class="mt-4 bg-blue-500 text-white py-1 px-3 rounded"
                        @click="$emit('close')"
                    >
                        Đóng
                    </button>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
