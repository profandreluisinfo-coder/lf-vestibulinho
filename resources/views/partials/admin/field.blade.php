{{--
    Campo reutilizável do formulário de edição.

    Parâmetros:
      name      (obrigatório)  ex.: 'name' ou 'mother[name]'
      label     (obrigatório)  texto do rótulo
      value     valor atual (o old() do Laravel tem prioridade)
      type      text | email | date | number ... (padrão: text)
      col       classe da coluna Bootstrap (padrão: col-md-6)
      required  true para mostrar o asterisco vermelho
      max       maxlength do input
      options   array [valor => texto]; se existir, vira <select>
      inputmode ex.: 'numeric'
--}}
@php
    $dot = str_replace(['[', ']'], ['.', ''], $name); // mother[name] -> mother.name
    $id = str_replace('.', '_', $dot); // mother.name -> mother_name    
    $type = $type ?? 'text';
    $col = $col ?? 'col-md-6';
    $required = $required ?? false;

    $current = old($dot, $value ?? null);
    if ($current instanceof \DateTimeInterface) {
        $current = $current->format('Y-m-d');
    }
@endphp

<div class="{{ $col }}">
    <label for="{{ $id }}" class="form-label {{ $required ? 'required' : '' }}">{{ $label }}</label>

    @if (isset($options))
        <select id="{{ $id }}" name="{{ $name }}" autocomplete="off"
    class="form-select @error($dot) is-invalid @enderror">
    <option value="" @selected(blank($current))>Selecione</option>
    @foreach ($options as $optionValue => $optionText)
        <option value="{{ $optionValue }}" @selected(filled($current) && (string) $current === (string) $optionValue)>
            {{ $optionText }}
        </option>
    @endforeach
</select>
    @else
        <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}"
            value="{{ $current }}" @if (isset($max)) maxlength="{{ $max }}" @endif
            @if (isset($inputmode)) inputmode="{{ $inputmode }}" @endif
            @if (isset($list)) list="{{ $list }}" @endif
            class="form-control {{ $class ?? '' }} @error($dot) is-invalid @enderror">
    @endif

    @error($dot)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
