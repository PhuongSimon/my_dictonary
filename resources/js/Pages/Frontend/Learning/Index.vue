<template>
    <Frontend>
        <div class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded-lg">
            <div class="mb-4">
                <label for="questionType" class="block text-sm font-medium text-gray-700">Chọn loại câu hỏi</label>
                <select v-model="questionType" id="questionType" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="Vocabulary">Từ vựng</option>
                    <option value="phrase">Cụm từ</option>
                    <option value="Vocabulary3">Giới từ</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="wordType" class="block text-sm font-medium text-gray-700">Chọn loại từ</label>
                <select v-model="wordType" id="wordType" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="n">Danh từ</option>
                    <option value="v">Động từ</option>
                    <option value="adj">Tính từ</option>
                    <option value="adv">Trạng từ</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Chọn thời gian</label>
                <div class="mt-2">
                    <label class="inline-flex items-center">
                        <input type="radio" v-model="selectedTimeOption" value="yesterday" class="form-radio text-indigo-600">
                        <span class="ml-2">Hôm qua</span>
                    </label>

                    <label class="inline-flex items-center ml-6">
                        <input type="radio" v-model="selectedTimeOption" value="today" class="form-radio text-indigo-600">
                        <span class="ml-2">Hôm nay</span>
                    </label>

                    <label class="inline-flex items-center ml-6">
                        <input type="radio" v-model="selectedTimeOption" value="date" class="form-radio text-indigo-600">
                        <span class="ml-2">Chọn 1 ngày</span>
                    </label>

                    <label class="inline-flex items-center ml-6">
                        <input type="radio" v-model="selectedTimeOption" value="multiple-dates" class="form-radio text-indigo-600">
                        <span class="ml-2">Chọn nhiều ngày</span>
                    </label>
                </div>
            </div>

            <div v-if="selectedTimeOption === 'multiple-dates' || selectedTimeOption === 'date'" class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="fromDate" class="block text-sm font-medium text-gray-700">
                        {{ selectedTimeOption === 'multiple-dates' ? 'Từ ngày' : 'Chọn ngày' }}
                    </label>
                    <input type="date" id="fromDate" v-model="fromDate" @change="validateDates" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                
                <div v-if="selectedTimeOption !== 'date'">
                    <label for="toDate" class="block text-sm font-medium text-gray-700">Đến ngày</label>
                    <input type="date" id="toDate" v-model="toDate" @change="validateDates" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
            </div>

            <div v-if="invalid" class="text-red-500 text-sm mb-4">
                {{ invalid }}
            </div>

            <div>
                <button @click="submitForm" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Luyện tập
                </button>
            </div>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, watch } from 'vue';
import axios from 'axios';

const today = new Date();
const yesterday = new Date(today);
yesterday.setDate(today.getDate() - 1);

const questionType = ref('Vocabulary');
const wordType = ref('n');
const selectedTimeOption = ref('yesterday');
const fromDate = ref(yesterday.toISOString().substr(0, 10));
const toDate = ref(yesterday.toISOString().substr(0, 10));
const invalid = ref('');

const validateDates = () => {
    if (selectedTimeOption.value === 'multiple-dates') {
        const from = new Date(fromDate.value);
        const to = new Date(toDate.value);

        if (to < from) {
            invalid.value = 'Ngày chọn không hợp lệ!';
        } else {
            invalid.value = '';
        }
    }
};

const submitForm = () => {
    invalid.value = '';
    if (invalid.value) {
        return;
    }

    const payload = {
        questionType: questionType.value,
        wordType: wordType.value,
        fromDate: selectedTimeOption.value === 'yesterday' ? yesterday.toISOString().substr(0, 10) : 
                  selectedTimeOption.value === 'today' ? today.toISOString().substr(0, 10) : fromDate.value,
        toDate: selectedTimeOption.value === 'yesterday' ? yesterday.toISOString().substr(0, 10) : 
                selectedTimeOption.value === 'today' ? today.toISOString().substr(0, 10) : toDate.value,
    };

    axios.post('/learning/check', payload)
        .then(response => {
            if (response.data.redirect) {
                window.location.href = response.data.redirect;
            }
        })
        .catch(error => {
            invalid.value = error.response.data.message;
        });
};

watch([fromDate, toDate], validateDates);
</script>
