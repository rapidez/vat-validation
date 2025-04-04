import { useThrottleFn, useMemoize } from '@vueuse/core'
import { token } from 'Vendor/rapidez/core/resources/js/stores/useUser'
import { mask } from 'Vendor/rapidez/core/resources/js/stores/useMask'
import { checkVAT, countries } from 'jsvat'

const viesCheckable = ['AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'EL', 'ES', 'FI', 'FR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PL', 'PT', 'RO', 'SE', 'SI', 'SK', 'XI']

document.addEventListener('vue:loaded', function () {
    window.app.$on('vat-change', async (event) => {
        let cleanVatid = event.target.value.replace(/[\s\.-]/g, '')

        if(event.target.value != cleanVatid) {
            event.target.value = cleanVatid

            // Set field and call `change` event to tell Vue
            // This event unfortunately also calls this function again, so we return here to avoid a double request
            event.target.dispatchEvent(new Event('change'))
            return
        }

        event.target.setCustomValidity('')

        if (!event.target.checkValidity()) {
            return
        }

        if (!cleanVatid || cleanVatid.length == 0) {
            return
        }

        if (preValidate(cleanVatid) === false) {
            event.target.setCustomValidity(window.config.vat_validation.translations.invalid)
            return
        }

        if (!isViesCheckable(cleanVatid)) {
            // If we can't check it by VIES then we just assume it's valid.
            return
        }

        let result = await validate(cleanVatid)
        if (result === 'error') {
            return
        }

        event.target.setCustomValidity(result ? '' : window.config.vat_validation.translations.failed)
        event.target.reportValidity()
    });
})

function isViesCheckable(vatId) {
    if (vatId.length < 2) {
        return false
    }

    let code = vatId.substring(0, 2)
    return viesCheckable.includes(code)
}

function preValidate(vatId) {
    let result = checkVAT(vatId, countries)
    if (!result.isSupportedCountry) {
        return null
    }

    return result.isValid
}

const validate = useMemoize(useThrottleFn(
    async function (vatId) {
        if (vatId.length == 0) {
            return false
        }

        let data = {
            id: vatId,
        }

        let options = {
            headers: {
                Authorization: `Bearer ${token.value || mask.value}`,
                Accept: 'application/json',
            },
        }

        return await window
            .rapidezAPI('post', 'vat-validate', data, options)
            .catch((error) => {
                if (FetchError.prototype.isPrototypeOf(error)) {
                    if (error.response.status === 422) {
                        return false
                    }
                }

                window.Notify(window.config.translations.errors.wrong, 'error')
                return 'error'
            })
    },
    5000,
    true,
))
