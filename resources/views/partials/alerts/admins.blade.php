{{-- ✅ Sucesso --}}
@if (session('success'))
  <script>
    showAlert('success', 'Sucesso!', '{{ session('success') }}', 'Ok', true);
  </script>
@endif

{{-- ❌ Erro --}}
@if (session('error'))
  <script>
    showAlert('error', 'Erro!', '{{ session('error') }}', 'Ok', true);
  </script>
@endif

{{-- ⚠️ Aviso --}}
@if (session('warning'))
  <script>
    showAlert('warning', 'Atenção!', '{{ session('warning') }}', 'Ok', true);
  </script>
@endif

{{-- ℹ️ Informação --}}
@if (session('info'))
  <script>
    showAlert('info', 'Informação', '{{ session('info') }}', 'Ok', false);
  </script>
@endif

{{-- ✅ Erros de Validação --}}
@if ($errors->any())
  <script>
    showAlert('error', 'Erros no formulário', `{!! implode('<br>', $errors->all()) !!}`, 'Corrigir', true);
  </script>
@endif