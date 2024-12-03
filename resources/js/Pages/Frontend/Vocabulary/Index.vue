<template>
    <Head title="Vocabularies"/>
    <Frontend>        
        <div class="mt-4 mx-4">
            <div v-if="$page.props.flash.message" class="alert bg-green-300">
                {{ $page.props.flash.message }}
            </div>
            <div class="flex justify-between mb-4">
                <b class="text-2xl">Lists</b>
                <div>
                    <Link :href="route('vocabulary.create')" class="bg-green-300 text-white p-3 mx-4 rounded mb-2">Tạo mới</Link>
                    <Link :href="route('learning.index')" class="bg-blue-400 text-white p-3 rounded me-4 mb-2">Luyện tập thôi</Link>
                    <Link :href="route('learning.vocabulary.fill_out')" class="bg-orange-400 text-white p-3 rounded mb-2">Hí hí</Link>
                </div>
            </div>
        </div>
        <div class="relative flex flex-col w-full h-full overflow-scroll text-gray-700 bg-white shadow-md bg-clip-border rounded-xl">
            <table class="w-full text-left table-auto min-w-max">
                <thead class="text-white bg-blue-600 dark:text-white">
                    <tr>
                        <th class="py-2 px-4 text-left border">stt</th>
                        <th class="py-2 px-4 text-left border">Từ</th>
                        <th class="py-2 px-4 text-left border">Nghĩa</th>
                        <th class="py-2 px-4 text-left border">Mẫu/cách dùng</th>
                        <th class="py-2 px-4 text-left border">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr 
                    class="even:bg-blue-gray-50/50"
                    v-for="(vocabulary, index) in vocabularies.data" 
                        :key="vocabulary.id" 
                    >
                        <td class="py-2 px-4 border">{{ index + 1 + (vocabularies.current_page - 1) * vocabularies.per_page }}</td>
                        <td class="py-2 px-4 border">{{ vocabulary.word }}</td>
                        <td class="py-2 px-4 border">{{ vocabulary.meaning }}</td>
                        <td class="py-2 px-4 border">{{ vocabulary.example }}</td>
                        <td class="py-2 px-4 border">
                            <Link :href="route('vocabulary.edit', vocabulary.id)" class="px-2 oy-1 text-sm bg-blue-300 text-dark rounded-xl me-2">
                                Edit
                            </Link>
                            <Link :href="route('vocabulary.destroy', vocabulary.id)" class="px-2 oy-1 text-sm bg-red-300 text-dark rounded-xl">
                                Delete
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination Controls -->
            <div class="flex justify-center mt-4">
                <Link 
                    v-for="link in vocabularies.links" 
                    :key="link.label" 
                    :href="link.url" 
                    v-html="link.label" 
                    :class="['p-2 mx-1 rounded', { 'bg-blue-500 text-white': link.active, 'bg-gray-200 text-black': !link.active }]"
                ></Link>
            </div>
        </div>
    </Frontend>
</template>

<script setup>
    import Frontend from '@/Layouts/FrontendLayout.vue';
    import { Head, Link } from '@inertiajs/vue3'

    defineProps({
        vocabularies: Object, // Object to access pagination properties like current_page, links, etc.
    });
</script>
