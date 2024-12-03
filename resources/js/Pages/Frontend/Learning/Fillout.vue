<template>
    <Frontend>
        <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
            <div v-if="currentQuestion">
                <div class="justify-between flex">
                    <p class="text-xl mb-4">
                        {{ currentQuestion.meaning }} <strong>({{ currentQuestion.word_type }})</strong>&nbsp;
                        <span v-if="isAnswered" :class="{'text-green-500': isCorrect, 'text-red-500': !isCorrect}">
                        {{ isCorrect ? getRandomCorrectMessage() : `${currentQuestion.word}` }}
                    </span>
                    </p>
                    <p class="text-xl mb-4 text-gray-600 text-lg">
                        Doing: {{ countAnswered }} / {{ total }}
                    </p>
                </div>
                <input 
                    v-model="userAnswer" 
                    @keyup.enter="handleEnterKey" 
                    type="text" 
                    placeholder="Word..." 
                    class="w-full p-2 border border-gray-300 rounded focus:ring-0"
                    :class="{'!border-green-500 border-2': isCorrect && isAnswered, '!border-red-500 border-2': !isCorrect && isAnswered}"
                    :readonly="isAnswered"
                />
                <p v-if="isAnswered" class="text-xl my-4">
                    {{ currentQuestion.example }}
                </p>
            </div>
            <div v-else>
                <p class="text-center text-xl">Congratulations.</p>
                <CircleProgressBar
                    :value="correctCount"
                    :max="totalQuestion"
                    :strokeWidth="10"
                    :startAngle="0"
                    percentage
                    rounded
                />

                <div class="mt-4 flex justify-center">
                    <Link :href="route('learning.vocabulary.fill_out')" class="bg-blue-400 text-white px-4 py-2 rounded hover:bg-blue-600">Redo</Link>
                </div>

                <div class="mt-10 relative flex flex-col w-full h-full overflow-scroll text-gray-700 bg-white shadow-md bg-clip-border rounded-xl">
                    <table class="w-full text-left table-auto min-w-max">
                        <thead class="text-white bg-blue-600 dark:text-white">
                            <tr>
                                <th class="py-2 px-2 text-left border" width="10%">#</th>
                                <th class="py-2 px-2 text-left border" width="20%">Percentage</th>
                                <th class="py-2 px-2 text-left border" width="20%">Correct</th>
                                <th class="py-2 px-2 text-left border" width="20%">Incorrect</th>
                                <th class="py-2 px-2 text-left border" width="30%">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(result, index) in results" :key="result.id">
                                <td class="py-2 px-2 border">{{ index + 1 }}</td>
                                <td class="py-2 px-2 border">{{ result.percent }}%</td>
                                <td class="py-2 px-2 border">
                                    <span class="text-green-500">{{ result.correct }}</span>
                                </td>
                                <td class="py-2 px-2 border">
                                    <!-- <span href="{{ route('learning.vocabulary.fill_out.incorrect') }}" class="text-red-700">{{ result.total - result.correct }}</span> -->
                                    <button class="" @click="fetchIncorrectWords(result.id)">
                                        <span class="text-red-700">{{ result.total - result.correct }}</span>
                                    </button>
                                </td>
                                <td class="py-2 px-2 border">{{ new Date(result.created_at).toLocaleString() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="flex space-x-4 mt-4">
                <button 
                    @click="submitAnswer" 
                    v-if="!isAnswered && currentQuestion"
                    class="bg-blue-400 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Submit
                </button>

                <button type="submit"
                    @click="nextQuestion"
                    v-if="isAnswered && currentQuestion && countAnswered !== totalQuestion"
                    class="bg-green-400 text-white px-4 py-2 rounded hover:bg-green-600">Next
                </button>

                <button type="submit"
                    @keyup.enter="handleEnterKey"
                    v-if="currentQuestion && countAnswered === totalQuestion"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-800">Done
                </button>
            </div>
        </div>
        <IncorrectWordsModal
        v-if="showModal"
        :words="incorrectWords"
        @close="showModal = false"
    />
    </Frontend>
</template>

<script setup>
import IncorrectWordsModal from '@/Components/IncorrectWordsModal.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import Frontend from '@/Layouts/FrontendLayout.vue';
// import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { CircleProgressBar } from 'circle-progress.vue';
import { useForm, Link } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    questions: {
        type: Array,
        required: true,
    },
    total: {
        type: Number,
        required: true,
    },
});

const showModal = ref(false);
const incorrectWords = ref([]);
const totalQuestion = ref(props.total);;
const shuffledQuestions = ref([...props.questions]);
const currentQuestion = ref(shuffledQuestions.value.length > 0 ? shuffledQuestions.value[0] : null);
const userAnswer = ref('');
const isAnswered = ref(false);
const isCorrect = ref(false);
const countAnswered = ref(0);
const correctCount = ref(0);
const incorrectIds = ref([]);

const fetchIncorrectWords = async (id) => {
    try {
        const response = await fetch(`fill-out/incorrect/${id}`);
        const data = await response.json();
        incorrectWords.value = JSON.parse(data.list);
        showModal.value = true;
    } catch (error) {
        alert('Có lỗi xảy ra khi tải dữ liệu.');
    }
};

const submitAnswer = () => {
    countAnswered.value++;
    if (!currentQuestion.value || isAnswered.value) return;

    isAnswered.value = true;
    const correctAnswer = currentQuestion.value.word.trim().toLowerCase();
    const userInput = userAnswer.value.trim().toLowerCase();

    isCorrect.value = (userInput === correctAnswer);
    
    if (isCorrect.value) {
        correctCount.value++;
    } else {
        incorrectIds.value.push(currentQuestion.value.id);
        console.log('incorrectIds: ', incorrectIds);
    }
};

const nextQuestion = () => {
    if (shuffledQuestions.value.length > 0) {
        shuffledQuestions.value.shift();
        currentQuestion.value = shuffledQuestions.value.length > 0 ? shuffledQuestions.value[0] : null;

        userAnswer.value = '';
        isAnswered.value = false;
        isCorrect.value = false;
    }
};

const getRandomCorrectMessage = () => {
  const messages = ['Correct!', 'Good!', 'Great!'];
  return messages[Math.floor(Math.random() * messages.length)];
};

const handleEnterKey = () => {
    if (currentQuestion.value === null) {
    return;
  }

  if (!isAnswered.value) {
    submitAnswer();
  } else if (countAnswered.value === totalQuestion.value) {
    finishQuiz();
  } else {
    nextQuestion();
  }
};

const results = ref([]); 

const finishQuiz = async () => {
    countAnswered.value = 0;
    try {
        await axios.post('/learning/vocabulary/fill-out/save-result', {
            correctCount: correctCount.value,
            total: totalQuestion.value,   
            incorrectIds: incorrectIds.value,
        });

        const response = await axios.post('/learning/vocabulary/results', {
            type: 'fill-out'
        });

        console.log('response: ', response);

        results.value = response.data.results;

    } catch (error) {
        console.error('Error saving results:', error);
        alert('Failed to save results. Please try again.');
    }

    currentQuestion.value = null; 
};

</script>

<style scoped>
</style>
