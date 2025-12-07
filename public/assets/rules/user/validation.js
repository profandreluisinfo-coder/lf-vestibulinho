$(document).ready(function() {
    // Adicionar métodos customizados de validação
    
    $.validator.addMethod("noSimplePatterns", function (value, element) {
        if (!value) return true;

        // Remove formatação (pontos e hífen) e converte para análise
        const rawValue = value.replace(/[^\w]/g, ''); // Remove tudo exceto letras/números
        const numericPart = rawValue.substring(0, 9);  // Pega apenas os 9 primeiros (devem ser números)
        const digit = rawValue.substring(9, 10);      // Último caractere (pode ser letra/número)

        // Se não tiver 10 caracteres ou os 9 primeiros não forem todos números, ignora a validação
        if (rawValue.length < 10 || !/^\d{9}$/.test(numericPart)) {
            return true; // Deixa outras regras (como required/mask) tratarem isso
        }

        // 1. Todos os 9 primeiros dígitos iguais (111.111.111-X)
        if (/^(\d)\1{8}$/.test(numericPart)) {
            return false;
        }

        // 2. Sequência crescente (123.456.789-X) ou decrescente (987.654.321-X)
        if ('0123456789'.includes(numericPart) || '987654321'.includes(numericPart)) {
            return false;
        }

        // 3. Padrões repetitivos nos 9 primeiros dígitos (ex: 121.212.121-X, 123.123.123-X)
        const half = Math.floor(numericPart.length / 2);
        for (let i = 1; i <= half; i++) {
            const pattern = numericPart.substring(0, i);
            if (pattern.repeat(numericPart.length / i) === numericPart) {
                return false;
            }
        }

        return true; // Passou em todas as verificações
    }, "Padrão numérico inválido para o documento");

    $.validator.addMethod("noSequences", function (value, element) {
        var words = value.trim().split(/\s+/);

        for (var i = 0; i < words.length; i++) {
            if (/^(\S)\1+$/.test(words[i])) {
                return false;
            }
        }

        return true;
    }, "Sequência inválida");

    $.validator.addMethod("wordLength", value => 
        value.trim().split(/\s+/).every(word => word.length >= 2),
        "Use pelo menos de 2 letras"
    );

    $.validator.addMethod("minWords", value => 
        value.trim().split(/\s+/).length >= 2,
        "Use pelo menos de 2 palavras"
    );

    $.validator.addMethod("isValidDomainName", function (value, element) {
        const invalidDomains = [
            '@gmail.com.br', '@test.com', '@fakeemail.com', '@invalid.com',
            '@example.com', '@example.com.br', '@email.com', '@email.com.br',
            '@educacaosumare.com', '@hotmail.com.br', '@outlook.com.br'
        ];
        return !invalidDomains.some(domain => value.endsWith(domain));
    }, "O domínio de e-mail informado é inválido.");

    // Validação de CPF
    $.validator.addMethod("cpfBR", function(value, element) {
        if (this.optional(element)) return true;
        
        value = value.replace(/[^\d]+/g, '');
        
        if (value.length !== 11 || /^(\d)\1{10}$/.test(value)) {
            return false;
        }
        
        let soma = 0;
        let resto;
        
        for (let i = 1; i <= 9; i++) {
            soma += parseInt(value.substring(i - 1, i)) * (11 - i);
        }
        
        resto = (soma * 10) % 11;
        if (resto === 10 || resto === 11) resto = 0;
        if (resto !== parseInt(value.substring(9, 10))) return false;
        
        soma = 0;
        for (let i = 1; i <= 10; i++) {
            soma += parseInt(value.substring(i - 1, i)) * (12 - i);
        }
        
        resto = (soma * 10) % 11;
        if (resto === 10 || resto === 11) resto = 0;
        if (resto !== parseInt(value.substring(10, 11))) return false;
        
        return true;
    }, "CPF inválido");
    
    // Validação de CEP
    $.validator.addMethod("cepBR", function(value, element) {
        if (this.optional(element)) return true;
        return /^\d{8}$/.test(value.replace(/[^\d]+/g, ''));
    }, "CEP inválido");
    
    // Validação de telefone brasileiro
    $.validator.addMethod("phoneBR", function(value, element) {
        if (this.optional(element)) return true;
        value = value.replace(/[^\d]+/g, '');
        return value.length === 10 || value.length === 11;
    }, "Telefone inválido");
    
    // Validação de ano (4 dígitos)
    $.validator.addMethod("year", function(value, element) {
        if (this.optional(element)) return true;
        return /^\d{4}$/.test(value) && parseInt(value) >= 1900 && parseInt(value) <= new Date().getFullYear() + 10;
    }, "Ano inválido");
    
    // Validação de NIS
    $.validator.addMethod("nisBR", function(value, element) {
        if (this.optional(element)) return true;
        return /^\d{11}$/.test(value.replace(/[^\d]+/g, ''));
    }, "NIS deve conter 11 dígitos");
    
    // Validação de certidão nova (32 dígitos)
    $.validator.addMethod("certidaoNova", function(value, element) {
        if (this.optional(element)) return true;
        return /^\d{32}$/.test(value.replace(/[^\d]+/g, ''));
    }, "Certidão deve conter 32 dígitos");
    
    // Configuração do formulário
    $("form.row.g-3").validate({
        rules: {
            // Dados Pessoais
            cpf: {
                required: true,
                cpfBR: true,
                minlength: 11,
                maxlength: 11
            },
            name: {
                required: true,
                minlength: 3,
                maxlength: 100,
                pattern: /^[a-zA-ZÀ-ÿ ]*$/,
                noSequences: true,
                wordLength: true,
                minWords: true
            },
            social_name: {
                maxlength: 100
            },
            birth: {
                required: true,
                date: true
            },
            gender: {
                required: true,
                range: [1, 4]
            },
            pne: {
                required: true
            },
            phone: {
                required: true,
                phoneBR: true,
                minlength: 10,
                maxlength: 11
            },
            
            // Documentação
            nationality: {
                required: true,
                range: [1, 2]
            },
            doc_type: {
                required: true,
                range: [1, 3]
            },
            doc_number: {
                required: true,
                minlength: 5,
                maxlength: 11,
                pattern: /^\d{7}[\dA-Za-z]{0,4}$/,
                noSimplePatterns: true
            },
            
            // Certidão Nova
            new_number: {
                certidaoNova: true,
                minlength: 32,
                maxlength: 32
            },
            
            // Certidão Antiga
            fls: {
                maxlength: 4,
                digits: true
            },
            book: {
                maxlength: 10
            },
            old_number: {
                maxlength: 6
            },
            municipality: {
                maxlength: 45
            },
            
            // Endereço
            zip: {
                required: true,
                cepBR: true,
                minlength: 8,
                maxlength: 8
            },
            street: {
                required: true,
                minlength: 3,
                maxlength: 60,
                pattern: /^[a-zA-ZÀ-ÿ0-9 ()]*$/,
                noSequences: true,
                wordLength: true
            },
            number: {
                maxlength: 10
            },
            complement: {
                maxlength: 20
            },
            burgh: {
                required: true,
                minlength: 2,
                maxlength: 60,
                pattern: /^[a-zA-ZÀ-ÿ0-9 ()]*$/,
                noSequences: true,
                wordLength: true
            },
            city: {
                required: true,
                minlength: 2,
                maxlength: 30,
                pattern: /^[a-zA-ZÀ-ÿ ]*$/,
                noSequences: true,
                wordLength: true
            },
            state: {
                required: true,
                minlength: 2,
                maxlength: 32,
                pattern: /^[a-zA-ZÀ-ÿ ]*$/
            },
            
            // Escola
            school_name: {
                required: true,
                minlength: 3,
                maxlength: 150
            },
            school_city: {
                required: true,
                minlength: 2,
                maxlength: 30,
                pattern: /^[a-zA-ZÀ-ÿ ()]*$/
            },
            school_state: {
                required: true,
                minlength: 2,
                maxlength: 32
            },
            school_year: {
                required: true,
                year: true
            },
            school_ra: {
                required: true,
                maxlength: 13,
                noSimplePatterns: true
            },
            
            // Filiação
            mother: {
                required: true,
                minlength: 3,
                maxlength: 60
            },
            mother_phone: {
                phoneBR: true,
                minlength: 10,
                maxlength: 11
            },
            father: {
                minlength: 3,
                maxlength: 60
            },
            father_phone: {
                phoneBR: true,
                minlength: 10,
                maxlength: 11
            },
            responsible: {
                minlength: 3,
                maxlength: 60
            },
            degree: {
                // required apenas se responsible estiver preenchido
            },
            kinship: {
                maxlength: 45
            },
            responsible_phone: {
                phoneBR: true,
                minlength: 10,
                maxlength: 11
            },
            parents_email: {
                required: true,
                email: true,
                maxlength: 60
            },
            
            // Saúde e Sociais
            health: {
                maxlength: 60
            },
            accessibility: {
                maxlength: 60
            },
            nis: {
                nisBR: true,
                minlength: 11,
                maxlength: 11
            }
        },
        
        messages: {
            // Dados Pessoais
            cpf: {
                required: "CPF é obrigatório",
                minlength: "CPF deve conter 11 dígitos",
                maxlength: "CPF deve conter 11 dígitos"
            },
            name: {
                required: "Nome completo é obrigatório",
                minlength: "Nome deve ter pelo menos 3 caracteres",
                maxlength: "Nome deve ter no máximo 100 caracteres"
            },
            social_name: {
                maxlength: "Nome social deve ter no máximo 100 caracteres"
            },
            birth: {
                required: "Data de nascimento é obrigatória",
                date: "Data inválida"
            },
            gender: {
                required: "Selecione o gênero",
                range: "Selecione um gênero válido"
            },
            pne: {
                required: "Informe se é PNE"
            },
            phone: {
                required: "Telefone é obrigatório",
                minlength: "Telefone deve conter 10 ou 11 dígitos",
                maxlength: "Telefone deve conter no máximo 11 dígitos"
            },
            
            // Documentação
            nationality: {
                required: "Selecione a nacionalidade",
                range: "Selecione uma nacionalidade válida"
            },
            doc_type: {
                required: "Selecione o tipo de documento",
                range: "Selecione um tipo de documento válido"
            },
            doc_number: {
                required: "Número do documento é obrigatório",
                minlength: "Número do documento deve ter pelo menos 5 caracteres"
            },
            
            // Certidão
            new_number: {
                minlength: "Certidão nova deve ter 32 dígitos",
                maxlength: "Certidão nova deve ter 32 dígitos"
            },
            fls: {
                maxlength: "Folha deve ter no máximo 4 caracteres",
                digits: "Digite apenas números"
            },
            
            // Endereço
            zip: {
                required: "CEP é obrigatório",
                minlength: "CEP deve conter 8 dígitos",
                maxlength: "CEP deve conter 8 dígitos"
            },
            street: {
                required: "Logradouro é obrigatório",
                minlength: "Logradouro deve ter pelo menos 3 caracteres",
                maxlength: "Logradouro deve ter no máximo 60 caracteres",
                pattern: "Apenas letras, números e espaços."
            },
            burgh: {
                required: "Bairro é obrigatório",
                minlength: "Bairro deve ter pelo menos 2 caracteres"
            },
            city: {
                required: "Cidade é obrigatória",
                minlength: "Cidade deve ter pelo menos 2 caracteres",
                pattern: "Apenas letras, números e espaços."
            },
            state: {
                required: "Estado é obrigatório",
                minlength: "Estado deve ter pelo menos 2 caracteres",
                pattern: "Apenas letras e espaços."
            },
            
            // Escola
            school_name: {
                required: "Nome da escola é obrigatório",
                minlength: "Nome da escola deve ter pelo menos 3 caracteres"
            },
            school_city: {
                required: "Cidade da escola é obrigatória",
                pattern: "Apenas letras e espaços."
            },
            school_state: {
                required: "Estado da escola é obrigatório"
            },
            school_year: {
                required: "Ano de conclusão é obrigatório"
            },
            school_ra: {
                required: "RA é obrigatório"
            },
            
            // Filiação
            mother: {
                required: "Nome da mãe é obrigatório",
                minlength: "Nome da mãe deve ter pelo menos 3 caracteres"
            },
            parents_email: {
                required: "E-mail dos pais/responsáveis é obrigatório",
                email: "E-mail inválido"
            }
        },
        
        errorElement: 'div',
        errorClass: 'invalid-feedback',
        
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        },
        
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        
        submitHandler: function(form) {
            // Se a validação passar, submete o formulário
            form.submit();
        }
    });

    // Controle de exibição dos campos de certidão de nascimento
    function toggleBirthCertificateFields() {
        const selectedModel = $('select[name="birth_certificate"]').val();
        
        // Oculta todos os campos
        $('.new-model, .old-model').hide();
        
        // Remove validação de todos os campos de certidão
        $('input[name="new_number"]').rules('remove');
        $('input[name="fls"]').rules('remove');
        $('input[name="book"]').rules('remove');
        $('input[name="old_number"]').rules('remove');
        $('input[name="municipality"]').rules('remove');
        
        // Limpa os valores dos campos ocultos
        if (selectedModel === '1') {
            // Modelo Novo
            $('.new-model').show();
            $('input[name="new_number"]').rules('add', {
                required: true,
                certidaoNova: true,
                minlength: 32,
                maxlength: 32,
                messages: {
                    required: "Número da matrícula é obrigatório",
                    minlength: "Certidão deve ter 32 dígitos",
                    maxlength: "Certidão deve ter 32 dígitos"
                }
            });
            
            // Limpa campos do modelo antigo
            $('input[name="fls"]').val('');
            $('input[name="book"]').val('');
            $('input[name="old_number"]').val('');
            $('input[name="municipality"]').val('');
            
        } else if (selectedModel === '2') {
            // Modelo Antigo
            $('.old-model').show();
            $('input[name="fls"]').rules('add', {
                required: true,
                maxlength: 4,
                digits: true,
                messages: {
                    required: "Folha é obrigatória",
                    maxlength: "Folha deve ter no máximo 4 caracteres",
                    digits: "Digite apenas números"
                }
            });
            $('input[name="book"]').rules('add', {
                required: true,
                maxlength: 10,
                messages: {
                    required: "Livro é obrigatório",
                    maxlength: "Livro deve ter no máximo 10 caracteres"
                }
            });
            $('input[name="old_number"]').rules('add', {
                required: true,
                maxlength: 6,
                messages: {
                    required: "Número é obrigatório",
                    maxlength: "Número deve ter no máximo 6 caracteres"
                }
            });
            $('input[name="municipality"]').rules('add', {
                required: true,
                maxlength: 45,
                messages: {
                    required: "Município é obrigatório",
                    maxlength: "Município deve ter no máximo 45 caracteres"
                }
            });
            
            // Limpa campo do modelo novo
            $('input[name="new_number"]').val('');
        }
    }
    
    // Executa ao carregar a página (para manter seleção com old())
    toggleBirthCertificateFields();
    
    // Executa ao mudar a seleção
    $('select[name="birth_certificate"]').on('change', function() {
        toggleBirthCertificateFields();
    });
    
    // Validação condicional: se responsible for preenchido, degree deve ser obrigatório
    $('input[name="responsible"]').on('input', function() {
        if ($(this).val().length > 0) {
            $('select[name="degree"]').rules('add', {
                required: true,
                messages: {
                    required: "Grau de parentesco é obrigatório quando responsável é informado"
                }
            });
        } else {
            $('select[name="degree"]').rules('remove', 'required');
        }
    });
    
    // Validação condicional: se degree for "4" (Outro), kinship deve ser obrigatório
    $('select[name="degree"]').on('change', function() {
        if ($(this).val() === '4') {
            $('input[name="kinship"]').rules('add', {
                required: true,
                messages: {
                    required: "Especifique o grau de parentesco"
                }
            });
        } else {
            $('input[name="kinship"]').rules('remove', 'required');
        }
    });
    
    // Máscara para CPF
    $('input[name="cpf"]').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 11) value = value.slice(0, 11);
        $(this).val(value);
    });
    
    // Máscara para CEP
    $('input[name="zip"]').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 8) value = value.slice(0, 8);
        $(this).val(value);
    });
    
    // Máscara para telefones
    $('input[name="phone"], input[name="mother_phone"], input[name="father_phone"], input[name="responsible_phone"]').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 11) value = value.slice(0, 11);
        $(this).val(value);
    });
    
    // Máscara para NIS
    $('input[name="nis"]').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 11) value = value.slice(0, 11);
        $(this).val(value);
    });
    
    // Máscara para certidão nova
    $('input[name="new_number"]').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 32) value = value.slice(0, 32);
        $(this).val(value);
    });

    // Máscara para RA escolar
    $('input[name="school_ra"]').on('input', function() {
        let value = $(this).val().toUpperCase();
        
        // Remove tudo que não for letra ou número
        value = value.replace(/[^A-Z0-9]/g, '');
        
        // Limita o tamanho máximo (3+3+3+1 = 10 caracteres)
        if (value.length > 10) value = value.slice(0, 10);
        
        // Aplica a formatação: 3.3.3-1
        let formatted = '';
        for (let i = 0; i < value.length; i++) {
            if (i === 3 || i === 6 || i === 9) {
                formatted += i === 9 ? '-' : '.';
            }
            formatted += value[i];
        }
        
        $(this).val(formatted);
    });
    
    // Buscar endereço pelo CEP
    $('input[name="zip"]').on('blur', function() {
        const cep = $(this).val().replace(/\D/g, '');
        
        if (cep.length === 8) {
            $.ajax({
                url: `https://viacep.com.br/ws/${cep}/json/`,
                dataType: 'json',
                success: function(data) {
                    if (!data.erro) {
                        $('input[name="street"]').val(data.logradouro);
                        $('input[name="burgh"]').val(data.bairro);
                        $('input[name="city"]').val(data.localidade);
                        $('input[name="state"]').val(data.uf);
                        $('input[name="number"]').focus();
                    }
                }
            });
        }
    });
});