import validateElementVAT from './vat-validation'
import { useEventListener } from '@vueuse/core'

Vue.directive('validate', {
    bind(el, binding) {
        if ('vat' in binding.modifiers) {
            useEventListener(el, 'change', (event) => validateElementVAT(event.target))
            validateElementVAT(el)
        }
    }
})
