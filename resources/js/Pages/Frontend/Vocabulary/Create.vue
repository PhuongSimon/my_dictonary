<template>
    <Head :title="isEditMode ? 'Edit Vocabulary' : 'Create Vocabulary'" />
    <Frontend>
        <div v-if="$page.props.flash.message" class="alert">
            {{ $page.props.flash.message }}
        </div>
        <div class="mt-4 mx-4">
            <div class="flex justify-between mb-4">
                <h1 class="text-2xl font-bold">{{ isEditMode ? 'Edit Vocabulary' : 'Create Vocabulary' }}</h1>
            </div>
            <div class="mb-4">
                <h1 class="text-lg">{{ today }}</h1>
            </div>
            <form @submit.prevent="saveVocabulary">
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-6">
                        <div class="mb-3">
                            <InputLabel>Category</InputLabel>
                        </div>
                        <div class="mb-3">
                            <InputLabel>Type</InputLabel>
                            <div class="flex flex-wrap py-1">
                                <div class="flex items-center me-4">
                                    <input v-model="form.word_type" id="red-radio" type="radio" value="n" name="colored-radio" class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:border-gray-600">
                                    <label for="red-radio" class="ms-2 text-sm font-medium">Noun</label>
                                </div>
                                <div class="flex items-center me-4">
                                    <input v-model="form.word_type" id="green-radio" type="radio" value="v" name="colored-radio" class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 dark:border-gray-600">
                                    <label for="green-radio" class="ms-2 text-sm font-medium">Verb</label>
                                </div>
                                <div class="flex items-center me-4">
                                    <input v-model="form.word_type" id="purple-radio" type="radio" value="adj" name="colored-radio" class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:border-gray-600">
                                    <label for="purple-radio" class="ms-2 text-sm font-medium">Adjective</label>
                                </div>
                                <div class="flex items-center me-4">
                                    <input v-model="form.word_type" id="blue-radio" type="radio" value="adv" name="colored-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:border-gray-600">
                                    <label for="blue-radio" class="ms-2 text-sm font-medium">Adverb</label>
                                </div>
                                <div class="flex items-center me-4">
                                    <input v-model="form.word_type" id="yellow-radio" type="radio" value="pre" name="colored-radio" class="w-4 h-4 text-yellow-400 bg-gray-100 border-gray-300 focus:ring-yellow-500 dark:focus:ring-yellow-600 dark:ring-offset-gray-800 focus:ring-2 dark:border-gray-600">
                                    <label for="yellow-radio" class="ms-2 text-sm font-medium">Preposition</label>
                                </div>
                                <div class="flex items-center me-4">
                                    <input v-model="form.word_type" id="gray-radio" type="radio" value="phr" name="colored-radio" class="w-4 h-4 text-gray-400 bg-gray-100 border-gray-300 focus:ring-gray-500 dark:focus:ring-gray-600 dark:ring-offset-gray-800 focus:ring-2 dark:border-gray-600">
                                    <label for="gray-radio" class="ms-2 text-sm font-medium">Phrase</label>
                                </div>
                            </div>
                            <InputError :message="form.errors.word_type"/>
                        </div>
                        <div class="mb-3">
                            <InputLabel>Word</InputLabel>
                            <TextInput 
                                v-model="form.word" 
                                class="py-1 w-full" 
                            />
                            <InputError :message="form.errors.word"/>
                        </div>
                        <div class="mb-3">
                            <InputLabel>Meaning</InputLabel>
                            <TextInput 
                                v-model="form.meaning" 
                                class="py-1 w-full" 
                            />
                            <InputError :message="form.errors.meaning"/>
                        </div>
                        <div class="mb-3">
                            <InputLabel>Example</InputLabel>
                            <TextInput 
                                v-model="form.example"
                                class="py-1 w-full" 
                            />
                        </div>
                        <div class="mt-3 float-end">
                            <button type="submit" class="bg-blue-400 text-white p-3 rounded me-4">Save</button>
                            <Link :href="route('vocabulary.index')" class="bg-red-400 text-white p-3 rounded mb-4">Back</Link>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </Frontend>
</template>

<script setup>
    import Frontend from '@/Layouts/FrontendLayout.vue';
    import { Head, Link, useForm } from '@inertiajs/vue3';
    import NavLink from '@/Components/NavLink.vue';
    import TextInput from '@/Components/TextInput.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import InputError from '@/Components/InputError.vue';

    const props = defineProps(['vocabulary', 'today']);
    const isEditMode = props.vocabulary != null;
    const today = props.today

    const form = useForm({
        word: props.vocabulary?.word || '',
        word_type: props.vocabulary?.word_type || '',
        meaning: props.vocabulary?.meaning || '',
        example: props.vocabulary?.example || '',
    });

    const saveVocabulary = async () => {
        if (isEditMode) {
            await form.put(route('vocabulary.update', props.vocabulary.id));
        } else {
            await form.post(route('vocabulary.store'));
        }
    }
</script>
