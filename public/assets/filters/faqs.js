$(document).ready(function () {

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

    function filterFaqs() {
    let value = normalize($("#search").val());
    let firstMatchOpened = false;

    $(".accordion-item").each(function () {
        let $item = $(this);
        let $btn = $item.find(".accordion-button");
        let $body = $item.find(".accordion-body");
        let $collapse = $item.find(".accordion-collapse");

        if (!$btn.data("original")) {
            $btn.data("original", $btn.html());
        }

        let originalQuestion = $btn.data("original");

        let questionText = normalize($btn.text());
        let answerText = normalize($body.text());

        let matches =
            value === "" ||
            questionText.includes(value) ||
            answerText.includes(value);

        $btn.html(originalQuestion);

        if (matches) {
            if (value !== "") {
                let regex = new RegExp(
                    "(" + value.replace(/[.*+?^${}()|[\]\\]/g, "\\$&") + ")",
                    "gi"
                );
                $btn.html(originalQuestion.replace(regex, "<mark>$1</mark>"));
            }

            $item.show();

            // 👉 abre só o primeiro match
            if (value !== "" && !firstMatchOpened) {
                $btn.removeClass("collapsed");
                $collapse.addClass("show");

                // scroll suave até ele
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
});
