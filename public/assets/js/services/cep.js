function clean_form() {
    $("#street").val("");
    $("#burgh").val("");
    $("#city").val("");
    $("#state").val("");
}

function setUpCepBlur() {
    $("#zip").blur(function () {
        var cep = $(this).val().replace(/\D/g, '');

        if (cep != "") {
            var validate_cep = /^[0-9]{8}$/;

            if (validate_cep.test(cep)) {
                // Exibir reticências nos campos enquanto aguarda os dados
                $("#street").val("buscando, aguarde...");
                $("#burgh").val("buscando, aguarde...");
                $("#city").val("buscando, aguarde...");
                $("#state").val("buscando, aguarde...");

                $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {
                    if (!("erro" in dados)) {
                        // Remover acentuação e preencher os campos com os dados da API
                        $("#street").val(dados.logradouro);
                        $("#burgh").val(dados.bairro);
                        $("#city").val(dados.localidade);
                        $("#state").val(dados.uf);
                    } else {
                        clean_form();
                        alert("CEP não encontrado.");
                    }
                });
            } else {
                clean_form();
                alert("Formato de CEP inválido.");
            }
        } else {
            clean_form();
        }
    });
}

setUpCepBlur();