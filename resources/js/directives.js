import validateElementVAT from './vat-validation'
import { useEventListener } from '@vueuse/core'

document.addEventListener('vue:loaded', function (event) {
    const vue = event.detail.vue
    vue.directive('validate', {
        bind(el, binding) {
            if ('vat' in binding.modifiers) {
                useEventListener(el, 'change', (event) => validateElementVAT(event.target))
                validateElementVAT(el)
            }
        }
    })
})
