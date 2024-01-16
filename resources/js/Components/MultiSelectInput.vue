<script setup>
import {computed, onMounted, ref, watch} from 'vue';
import Tag from "@/Components/Tag.vue";

const props = defineProps({
    modelValue: {
        type: Array,
        required: true,
    },
    searchFunction: {
        type: Function,
        required: true,
    },
});

const emit = defineEmits(['update:modelValue']);

const input = ref(null);
const searchQuery = ref('');
const filteredItems = ref([]);
const selectedItems = ref([...props.modelValue]);

watch(() => props.modelValue, (value) => {
    selectedItems.value = [...value];
});

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({focus: () => input.value.focus()});

const classes = computed(() => {
    return 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm';
});

const searchItems = async () => {
    if (searchQuery.value.length > 0) {
        try {
            const searchResults = await props.searchFunction(searchQuery.value);

            // Filter out items that are already selected
            filteredItems.value = searchResults.filter(item => !selectedItems.value.includes(item.value));
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    } else {
        filteredItems.value = [];
    }
};

const removeItem = (value) => {
    if (!selectedItems.value.includes(value)) return;
    selectedItems.value = selectedItems.value.filter((item) => item !== value);
    emit('update:modelValue', selectedItems.value);
};

const addItem = (value) => {
    if (selectedItems.value.includes(value)) return;
    selectedItems.value.push(value);
    if (filteredItems.value.length < 1) {
        searchQuery.value = '';
    }
    emit('update:modelValue', selectedItems.value);
};
</script>

<template>
    <div v-show="selectedItems.length > 0" class="mt-1 flex flex-wrap">
          <template
              v-for="selectedItem in selectedItems"
              :key="selectedItem.id"
          >
              <Tag :value="selectedItem" :remove="removeItem"/>
          </template>
    </div>
    <div class="relative">
        <input
            ref="input"
            v-model="searchQuery"
            :class="classes"
            type="text"
            v-bind="$attrs"
            @input="searchItems"
            @keydown.escape="filteredItems = []"
            autocomplete="off"
            @keydown.enter.prevent="addItem(searchQuery)"
        />
        <template v-if="filteredItems.length > 0">
            <ul class="list-none p-2 border rounded-lg shadow absolute w-full bg-white mt-1">
                <template v-for="item in filteredItems"
                          :key="item.id">
                    <li

                        v-if="!selectedItems.includes(item.value)"
                        class="cursor-pointer py-2 px-3 hover:bg-gray-100 rounded-lg"
                        @click="addItem(item.value)"
                    >
                        {{ item.value }}
                    </li>
                </template>
            </ul>
        </template>
    </div>
</template>
