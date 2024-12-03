<template>
    <Frontend>
        <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
            <div v-if="questions.length">
                <h2 class="text-2xl font-bold mb-4">Câu hỏi {{ currentQuestionIndex + 1 }}</h2>
                <p class="text-lg mb-4">{{ currentQuestion.meaning }}</p>
                <div class="space-y-2">
                    <div v-for="(option, index) in currentOptions" :key="index" class="flex items-center">
                        <input 
                            type="radio" 
                            :id="'option' + index" 
                            :value="option" 
                            v-model="selectedAnswer" 
                            :disabled="isAnswered" 
                            class="mr-2"
                        />
                        <label 
                            :for="'option' + index" 
                            class="text-sm" 
                            :class="{
                                'text-green-500': isAnswered && option === currentQuestion.word,
                                'text-red-500': isAnswered && option === selectedAnswer && option !== currentQuestion.word
                            }"
                        >
                            {{ option }}
                        </label>
                    </div>
                </div>

                <!-- Phản hồi sau khi trả lời -->
                <div v-if="isAnswered" class="mt-4">
                    <p class="italic text-gray-500">Ví dụ: {{ currentQuestion.example }}</p>
                </div>

                <!-- Nút hành động -->
                <div class="flex space-x-4 mt-4">
                    <button 
                        @click="submitAnswer" 
                        v-if="!isAnswered"
                        class="bg-blue-400 text-white px-4 py-2 rounded hover:bg-blue-600"
                    >
                        Trả lời
                    </button>
    
                    <button 
                        @click="nextQuestion" 
                        v-if="isAnswered"
                        class="bg-green-400 text-white px-4 py-2 rounded hover:bg-green-600"
                    >
                        Next
                    </button>
                </div>
            </div>
        </div>
    </Frontend>
</template>

<script setup>
import { ref, computed } from 'vue';
import Frontend from '@/Layouts/FrontendLayout.vue';

const props = defineProps({
    questions: Array,
});

const currentQuestionIndex = ref(0);
const selectedAnswer = ref(null);
const isAnswered = ref(false);
const isCorrect = ref(false);

const currentQuestion = computed(() => props.questions[currentQuestionIndex.value]);
const currentOptions = computed(() => currentQuestion.value.options);

const submitAnswer = () => {
    if (selectedAnswer.value !== null) {
        isCorrect.value = selectedAnswer.value === currentQuestion.value.meaning;
        isAnswered.value = true;
    }
};

const nextQuestion = () => {
    if (currentQuestionIndex.value < props.questions.length - 1) {
        currentQuestionIndex.value++;
        selectedAnswer.value = null;
        isAnswered.value = false;
    }
};
</script>
