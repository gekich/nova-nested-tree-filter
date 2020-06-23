Nova.booting((Vue, router, store) => {
    Vue.config.devtools = true
  Vue.component('nested-tree-filter', require('./components/Filter'))
})
