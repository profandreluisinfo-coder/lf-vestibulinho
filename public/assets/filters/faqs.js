$(document).ready(function () {

    /**
     * Normalize a string by converting it to lowercase and removing diacritical marks.
     *
     * This function uses the Unicode Normalization Form "NFD" to decompose
     * characters into their base form and a combining character. It then
     * removes the combining characters to produce a string that is
     * equivalent to the original string, but without any diacritical marks.
     *
     * @param {string} str - The string to normalize.
     *
     * @return {string} The normalized string.
     */
    function normalize(str) {
        return str
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .toLowerCase();
    }

    // debounce simples
    function debounce(fn, delay = 300) {
        let timer;
        return function (...args) {
            clearTimeout(timer);
            timer = setTimeout(() => fn.apply(this, args), delay);
        };
    }

    /**
     * Filtra as FAQs com base no valor do input de busca.
     * Mostra ou esconde os elementos de acordo com o valor de busca.
     * Se o valor de busca for diferente de vazio, destaca as palavras que
     * combinam com o valor de busca.
     */
    function filterFaqs() {
        let value = normalize($("#search").val());
        let showPublished = $("#filterPublished").is(":checked");
        let showUnpublished = $("#filterUnpublished").is(":checked");

        let firstMatchOpened = false;

        $(".accordion-item").each(function () {
            let $item = $(this);
            let status = $item.data("status"); // 1 ou 0

            let $btn = $item.find(".accordion-button");
            let $body = $item.find(".accordion-body");
            let $collapse = $item.find(".accordion-collapse");

            if (!$btn.data("original")) {
                $btn.data("original", $btn.html());
            }

            let originalQuestion = $btn.data("original");

            let questionText = normalize($btn.text());
            let answerText = normalize($body.text());

            let matchesSearch =
                value === "" ||
                questionText.includes(value) ||
                answerText.includes(value);

            let matchesStatus =
                (status == 1 && showPublished) ||
                (status == 0 && showUnpublished);

            $btn.html(originalQuestion);

            if (matchesSearch && matchesStatus) {

                if (value !== "") {
                    let regex = new RegExp(
                        "(" + value.replace(/[.*+?^${}()|[\]\\]/g, "\\$&") + ")",
                        "gi"
                    );
                    $btn.html(originalQuestion.replace(regex, "<mark>$1</mark>"));
                }

                $item.show();

                if (value !== "" && !firstMatchOpened) {
                    $btn.removeClass("collapsed");
                    $collapse.addClass("show");

                    $('html, body').animate({
                        scrollTop: $item.offset().top - 120
                    }, 400);

                    firstMatchOpened = true;
                } else {
                    $btn.addClass("collapsed");
                    $collapse.removeClass("show");
                }

            } else {
                $item.hide();
                $btn.addClass("collapsed");
                $collapse.removeClass("show");
            }
        });
    }

    // aplica debounce no input
    $("#search").on(
        "keyup",
        debounce(filterFaqs, 300)
    );

    $("#filterPublished, #filterUnpublished").on("change", function () {
        filterFaqs();
    });
});
