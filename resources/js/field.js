Nova.booting((Vue, router, store) => {
  Vue.component('index-stripe-checkout', require('./components/IndexField'))
  Vue.component('detail-stripe-checkout', require('./components/DetailField'))
  Vue.component('form-stripe-checkout', require('./components/DetailField'))
})
