import validateElementVAT from './vat-validation'

Vue.directive('validate', {
    bind(el, binding) {
        if ('vat' in binding.modifiers) {
            el.addEventListener('change', (event) => validateElementVAT(event.target))
            validateElementVAT(el)
        }
    }
})
