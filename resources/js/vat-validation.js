import { useThrottleFn } from '@vueuse/core'
import { token } from 'Vendor/rapidez/core/resources/js/stores/useUser'
import { mask } from 'Vendor/rapidez/core/resources/js/stores/useMask'

const patterns = {
    AT: /^U[A-Z\d]{8}$/,
    BE: /^[0-2]\d{9}$/,
    BG: /^\d{9,10}$/,
    CY: /^\d{8}[A-Z]$/,
    CZ: /^\d{8,10}$/,
    DE: /^\d{9}$/,
    DK: /^(\d{2} ?){3}\d{2}$/,
    EE: /^\d{9}$/,
    EL: /^\d{9}$/,
    ES: /^([A-Z]\d{7}[A-Z]|\d{8}[A-Z]|[A-Z]\d{8})$/,
    EU: /^\d{9}$/,
    FI: /^\d{8}$/,
    FR: /^[A-Z\d]{2}\d{9}$/,
    GB: /^(\d{9}|\d{12}|(GD|HA)\d{3})$/,
    HR: /^\d{11}$/,
    HU: /^\d{8}$/,
    IE: /^([A-Z\d]{8}|[A-Z\d]{9})$/,
    IT: /^\d{11}$/,
    LT: /^(\d{9}|\d{12})$/,
    LU: /^\d{8}$/,
    LV: /^\d{11}$/,
    MT: /^\d{8}$/,
    NL: /^\d{9}B\d{2}$/,
    PL: /^\d{10}$/,
    PT: /^\d{9}$/,
    RO: /^\d{2,10}$/,
    SE: /^\d{12}$/,
    SI: /^\d{8}$/,
    SK: /^\d{10}$/,
    SM: /^\d{5}$/,
}

document.addEventListener('turbo:load', function () {
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

        if (!cleanVatid || cleanVatid.length == 0) {
            return
        }

        if (prevalidate(cleanVatid) === false) {
            event.target.setCustomValidity(window.config.vat_validation.translations.invalid)
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

function prevalidate(vatId) {
    vatId = vatId.toUpperCase()

    let country = vatId.substring(0, 2)
    if (/[0-9]+/.test(country)) {
        return false
    }

    let number = vatId.substring(2)
    const matcher = patterns[country] || null

    if (!matcher) {
        return null
    }

    return matcher.test(number)
}

const validate = useThrottleFn(
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
            },
        }

        return await window
            .rapidezAPI('post', 'vat-validate', data, options)
            .then((res) => {
                if (res.error) {
                    window.Notify(res.error, 'error')
                    return 'error'
                }

                return res.result
            })
            .catch(() => {
                window.Notify(window.config.translations.errors.wrong, 'error')
                return 'error'
            })
    },
    5000,
    true,
)
