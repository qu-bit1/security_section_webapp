<script setup>
import {Link} from "@inertiajs/vue3";

defineProps({paginator: Object});
</script>
<template>
    <nav v-if="paginator.last_page>1" aria-label="Pagination Navigation"
         class="flex items-center justify-between py-4 not-prose" role="navigation">
        <div class="flex justify-between flex-1 md:hidden not-prose">
                <span
                    v-if="paginator.current_page === 1"
                    class="relative inline-flex items-center py-2 px-4 mr-3 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700">
                        <svg aria-hidden="true" class="mr-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd"
                                  d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z"
                                  fill-rule="evenodd"></path>
                        </svg>
                        Previous
                </span>
            <Link v-else :href="paginator.prev_page_url"
                  class="relative  inline-flex items-center py-2 px-4 mr-3 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 transition ease-in-out duration-150">
                <svg aria-hidden="true" class="mr-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                     xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd"
                          d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z"
                          fill-rule="evenodd"></path>
                </svg>
                Previous
            </Link>

            <template v-if="paginator.last_page>paginator.current_page">
                <Link
                    :href="paginator.next_page_url"
                    class="relative inline-flex items-center py-2 px-4 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 transition ease-in-out duration-150">
                    Next
                    <svg aria-hidden="true" class="ml-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd"
                              d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                              fill-rule="evenodd">
                        </path>
                    </svg>
                </Link>
            </template>
            <span v-else
                  class="relative inline-flex items-center py-2 px-4 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700">
                        Next
                        <svg aria-hidden="true" class="ml-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd"
                                  d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                                  fill-rule="evenodd">
                            </path>
                        </svg>
                </span>
        </div>

        <div class="hidden md:flex-1 md:flex md:items-center md:justify-between">
            <div>
                <p class="font-normal text-gray-700 dark:text-gray-400 leading-5">
                    Showing
                    <template v-if="paginator.from">
                        <span class="font-medium">{{ paginator.from }}</span>
                        to
                        <span class="font-medium">{{ paginator.to }}</span>
                    </template>
                    <template v-else>
                        {{ paginator.per_page }}
                    </template>
                    of
                    <span class="font-medium">{{ paginator.total }}</span>
                    results
                </p>
            </div>


            <div>
                    <span class="relative z-0 inline-flex shadow-sm rounded-md">
<!--                        Previous Page Link-->
                        <template v-if="paginator.current_page === 1">
                            <span aria-disabled="true" aria-label="pagination.previous">
                                <span
                                    aria-hidden="true"
                                    class="relative inline-flex items-center px-3 py-2 ml-0 leading-tight  text-gray-500 bg-white border rounded-l-lg border-gray-300 cursor-pointer  hover:bg-gray-100 hover:text-gray-700">
                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path clip-rule="evenodd"
                                              d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z"
                                              fill-rule="evenodd">
                                        </path>
                                    </svg>
                                </span>
                            </span>
                        </template>
                        <template v-else>
                            <Link :href="paginator.prev_page_url"
                                  aria-label="pagination.previous"
                                  class="relative inline-flex items-center px-3 py-2 ml-0 leading-tight  text-gray-500 bg-white border rounded-l-lg border-gray-300 cursor-pointer  hover:bg-gray-100 hover:text-gray-700 transition ease-in-out duration-150"
                                  rel="prev">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path clip-rule="evenodd"
                                          d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z"
                                          fill-rule="evenodd">
                                    </path>
                                </svg>
                            </Link>
                        </template>

                        <template v-if="paginator.current_page > 3">
                            <Link :href="paginator.links[1].url"
                                  aria-label="Go to page 1"
                                  class="relative inline-flex items-center py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 cursor-pointer hover:bg-gray-100 hover:text-gray-700">
                                1
                            </Link>
                        </template>
                        <template v-if="paginator.current_page > 4">
                            <span aria-disabled="true"
                                  class="py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">...</span>
                        </template>
                         <template
                             v-for="i in paginator.last_page"
                             :key="i"
                         >
                          <span v-if="i >= paginator.current_page-2 && i <= paginator.current_page+2">
                            <span v-if="i === paginator.current_page" aria-current="page"
                                  class="relative inline-flex items-center py-2 px-3 leading-tight text-gray-600 bg-gray-50 border border-gray-300 cursor-pointer hover:bg-gray-100 hover:text-skin-600"
                            >
                              {{ i }}
                            </span>
                            <Link v-else :aria-label="`Go to page ${i}`"
                                  :href="paginator.links[i].url"
                                  class="relative inline-flex items-center py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 cursor-pointer hover:bg-gray-100 hover:text-gray-700">
                              {{ i }}
                            </Link>
                          </span>
                        </template>

                        <template v-if="paginator.current_page < paginator.last_page - 3">
                            <span aria-disabled="true"
                                  class="py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">...</span>
                        </template>
                        <template v-if="paginator.current_page < paginator.last_page - 2">
                            <Link :aria-label="`Go to page ${paginator.last_page}`"
                                  :href="paginator.links[paginator.last_page].url"
                                  class="relative inline-flex items-center py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 cursor-pointer hover:bg-gray-100 hover:text-gray-700">
                                {{ paginator.last_page }}
                            </Link>
                        </template>

                        <!--                        Next Page Link-->
                        <template v-if="paginator.last_page > paginator.current_page">
                            <Link :href="paginator.next_page_url"
                                  aria-label="pagination.next"
                                  class="relative inline-flex items-center px-3 py-2 ml-0 leading-tight  text-gray-500 bg-white border rounded-r-lg border-gray-300 cursor-pointer  hover:bg-gray-100 hover:text-gray-700 transition ease-in-out duration-150"
                                  rel="next">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path clip-rule="evenodd"
                                          d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                                          fill-rule="evenodd">
                                    </path>
                                </svg>
                            </Link>
                        </template>
                        <template v-else>
                            <span aria-disabled="true" aria-label="pagination.next">
                                <span
                                    aria-hidden="true"
                                    class="relative inline-flex items-center px-3 py-2 ml-0 leading-tight  text-gray-500 bg-white border rounded-r-lg border-gray-300 cursor-pointer  hover:bg-gray-100 hover:text-gray-700">
                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path clip-rule="evenodd"
                                              d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                                              fill-rule="evenodd">
                                        </path>
                                    </svg>
                                </span>
                            </span>
                        </template>
                    </span>
            </div>
        </div>
    </nav>
</template>
