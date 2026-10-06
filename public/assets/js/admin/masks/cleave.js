String.prototype.numbers = function () {
    'use strict';
    const matches = this.match(/\d+/g);
    return matches ? matches.join('') : '';
};

(function ($) {
    $(document).ready(function () {

        // Recebe o ID do campo como TEXTO (sem #). Ex.: 'certificate_number'
        function applyCleave(id, config, onInput = null) {
            const el = document.getElementById(id);
            if (!el) return;

            new Cleave(el, config);

            if (typeof onInput === 'function') {
                el.addEventListener('input', onInput);
            }
        }

        function maskPhone(e) {
            if (e === undefined) {
                const phoneElements = document.querySelectorAll('.phone-mask');
                if (phoneElements.length === 0) return;

                phoneElements.forEach(element => {
                    if (element.cleave) {
                        element.cleave.destroy();
                    }

                    element.addEventListener('input', maskPhone);
                    maskPhone({ target: element });
                });
                return;
            }

            const element = e.target;
            const digits = element.value.numbers();

            const numberWithoutDDD = digits.slice(2);
            const isFixo = numberWithoutDDD.length === 8;

            if (element.cleave) {
                element.cleave.destroy();
            }

            element.cleave = new Cleave(element, {
                delimiters: ['(', ') ', '-'],
                blocks: isFixo ? [0, 2, 4, 4] : [0, 2, 5, 4],
                numericOnly: true,
                delimiterLazyShow: true
            });
        }

        // Número da certidão: nova (1) = 32 dígitos | antiga (2) = 6 dígitos
        function maskCertificateNumber() {
            const el = document.getElementById('certificate_number');
            const type = document.getElementById('certificate_type');
            if (!el || !type) return;

            if (el.cleave) {
                el.cleave.destroy();
            }

            el.cleave = new Cleave(el, {
                blocks: [type.value === '2' ? 6 : 32],
                numericOnly: true,
                delimiterLazyShow: true
            });
        }

        // ---------- Identificação ----------
        applyCleave('cpf', {
            blocks: [11],
            numericOnly: true,
            delimiterLazyShow: true
        });

        // ---------- Certidão ----------
        maskCertificateNumber();
        const certType = document.getElementById('certificate_type');
        if (certType) {
            certType.addEventListener('change', maskCertificateNumber);
        }

        applyCleave('certificate_fls', {
            blocks: [4],
            numericOnly: true,
            delimiterLazyShow: true
        });

        applyCleave('certificate_book', {
            blocks: [10],
            numericOnly: true,
            delimiterLazyShow: true
        });

        // ---------- Complementares ----------
        applyCleave('nis', {
            delimiters: ['.', '.', '-'],
            blocks: [3, 5, 2, 1],
            numericOnly: true,
            delimiterLazyShow: true
        });

        // ---------- Endereço ----------
        applyCleave('zip', {
            delimiters: ['.', '-'],
            blocks: [2, 3, 3],
            numericOnly: true,
            delimiterLazyShow: true
        });

        // ---------- Escolaridade ----------
        applyCleave('academic_year', {
            blocks: [4],
            numericOnly: true,
            delimiterLazyShow: true
        });

        applyCleave('academic_ra', {
            blocks: [3, 3, 3, 1],
            delimiters: ['.', '.', '-'],
            delimiterLazyShow: true,
            numericOnly: false,
            uppercase: true
        });

        // ---------- Telefones (campos com a classe .phone-mask) ----------
        maskPhone();
    });
}(jQuery));