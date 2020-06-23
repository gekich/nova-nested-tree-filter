<template>
    <div>
        <h3 class="text-sm uppercase tracking-wide text-80 bg-30 p-3">
            {{ filter.name }}
        </h3>

        <div class="p-2">
            <treeselect v-model="selectedValues"
                        :multiple="this.filter.multiple"
                        :options="this.options"
                        :normalizer="normalizer"
                        :placeholder="this.filter.placeholder"
                        @input="handleChange" />
        </div>
    </div>
</template>

<script>

import Treeselect from '@riophae/vue-treeselect'
import '@riophae/vue-treeselect/dist/vue-treeselect.css'

export default {
    components: { Treeselect },

    mounted() {
        try {
            this.options = JSON.parse(this.filter.options[0].value)
        } catch (e) {
            this.options = [];
        }
        try {
            this.selectedValues = JSON.parse(this.value)
        } catch (e) {
            this.selectedValues = null;
        }
    },
    data() {
        return {
            selectedValues: null,
            options: [],
        }
    },
    props: {
        resourceName: {
            type: String,
            required: true,
        },
        filterKey: {
            type: String,
            required: true,
        },
    },

    methods: {
        handleChange() {
            this.$store.commit(`${this.resourceName}/updateFilterState`, {
                filterClass: this.filterKey,
                value: JSON.stringify(this.selectedValues)
            })

            this.$emit('change')
        },
        normalizer(node) {
            return  {
                id: node[this.filter.idKey],
                label: node[this.filter.labelKey],
                children: node[this.filter.childrenKey],
            }
        },
    },

    computed: {
        filter() {
            return this.$store.getters[`${this.resourceName}/getFilter`](
                this.filterKey
            )
        },

        value() {
            return this.filter.currentValue
        },

        options() {
            return this.$store.getters[`${this.resourceName}/getOptionsForFilter`](
                this.filterKey
            )
        },
    },
}
</script>
