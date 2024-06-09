<template>
    <div>
        <Component
            :is="link.url ? 'Link' : 'span'"
            v-for="(link, index) in limitedLinks"
            :key="index"
            :href="link.url"
            v-html="link.label"
            class="px-3 py-2 mx-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-100 shadow-md"
            :class="{ 'text-gray-300': !link.url, 'font-bold': link.active }"
        />
    </div>
</template>

<script>
export default {
    props: {
        links: Array,
        first_page_url: String,
        last_page_url: String,
        per_page: Number,
    },
    computed: {
        limitedLinks() {
            const maxPages = 10;

            const currentPageIndex = this.links.findIndex(
                (link) => link.active
            );

            let start = Math.max(
                1,
                currentPageIndex - Math.floor(maxPages / 2)
            );
            let end = Math.min(this.links.length - 2, start + maxPages - 1);

            if (start <= 1) {
                end = Math.min(this.links.length - 2, maxPages);
            } else if (end >= this.links.length - 2) {
                start = Math.max(1, this.links.length - 2 - maxPages + 1);
            }
            return this.links.slice();
        },
    },
};
</script>
