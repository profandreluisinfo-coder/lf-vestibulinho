String.prototype.numbers = function () {
    'use strict';
    const matches = this.match(/\d+/g);
    return matches ? matches.join('') : '';
};

(function ($) {
    $(document).ready(function () {
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

        applyCleave('cpf', {
            blocks: [11],
            numericOnly: true,
            delimiterLazyShow: true
        });

        applyCleave('new_number', {
            blocks: [32],
            numericOnly: true,
            delimiterLazyShow: true
        });

        applyCleave('fls', {
            blocks: [4],
            numericOnly: true,
            delimiterLazyShow: true
        });

        applyCleave('book', {
            blocks: [10],
            numericOnly: true,
            delimiterLazyShow: true
        });

        applyCleave('old_number', {
            blocks: [6],
            numericOnly: true,
            delimiterLazyShow: true
        });

        applyCleave('nis', {
            delimiters: ['.', '.', '-'],
            blocks: [3, 5, 2, 1],
            numericOnly: true,
            delimiterLazyShow: true
        });

        applyCleave('zip', {
            delimiters: ['.', '-'],
            blocks: [2, 3, 3],
            numericOnly: true,
            delimiterLazyShow: true
        });

        applyCleave('school_year', {
            blocks: [4],
            numericOnly: true,
            delimiterLazyShow: true
        });

        applyCleave('school_ra', {
            blocks: [3, 3, 3, 1],
            delimiters: ['.', '.', '-'],
            delimiterLazyShow: true,
            numericOnly: false,
            uppercase: true
        });

        maskPhone();
    });
}(jQuery));