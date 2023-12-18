<script setup>
import {computed, onMounted, ref, watch} from 'vue';

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

defineExpose({ focus: () => input.value.focus() });

const classes = computed(() => {
    return 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm';
});

const searchItems = async () => {
    if (searchQuery.value.length > 0) {
        try {
            filteredItems.value = await props.searchFunction(searchQuery.value);
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    } else {
        filteredItems.value = [];
    }
};

const removeItem = (value) => {
    if(!selectedItems.value.includes(value)) return;
    selectedItems.value = selectedItems.value.filter((item) => item !== value);
    emit('update:modelValue', selectedItems.value);
};

const addItem = (value) => {
    if(selectedItems.value.includes(value)) return;
    selectedItems.value.push(value);
    emit('update:modelValue', selectedItems.value);
};
</script>

<template>
        <div v-show="selectedItems.length > 0" class="mt-1 flex flex-wrap">
          <span
              v-for="selectedItem in selectedItems"
              :key="selectedItem.id"
              class="inline-flex items-center py-1 px-2 mb-2 mx-1 text-xs leading-4 font-bold tracking-wide bg-rose-100 text-rose-600 border border-rose-400 rounded-full shadow-sm"
          >
            <span>{{ selectedItem }}</span>
            <button
                type="button"
                class="ml-2 text-rose-600"
                @click="removeItem(selectedItem)"
            >
              &times;
            </button>
          </span>
        </div>
        <div class="relative">
            <input
                :class="classes"
                type="text"
                v-model="searchQuery"
                @input="searchItems"
                ref="input"
                v-bind="$attrs"
            />

            <ul v-show="filteredItems.length > 0" class="list-none p-2 border rounded-lg shadow absolute w-full bg-white mt-1">
                <template v-for="item in filteredItems"
                    :key="item.id">
                    <li

                        @click="addItem(item.value)"
                        v-if="!selectedItems.includes(item.value)"
                        class="cursor-pointer py-2 px-3 hover:bg-gray-100 rounded-lg"
                    >
                        {{ item.value }}
                    </li>
                </template>
            </ul>
        </div>
</template>
