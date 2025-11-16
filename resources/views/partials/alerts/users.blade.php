{{-- ✅ Alerta de Sucesso --}}
@if (session('success'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Sucesso!',
      text: '{{ session('success') }}',
      confirmButtonText: 'Ok'
    });
  </script>
@endif

{{-- ❌ Alerta de Erro --}}
@if (session('error'))
  <script>
    Swal.fire({
      icon: 'error',
      title: 'Erro!',
      text: '{{ session('error') }}',
      confirmButtonText: 'Ok'
    });
  </script>
@endif

{{-- ⚠️ Alerta de Aviso --}}
@if (session('warning'))
  <script>
    Swal.fire({
      icon: 'warning',
      title: 'Atenção!',
      text: '{{ session('warning') }}',
      confirmButtonText: 'Ok'
    });
  </script>
@endif

{{-- ℹ️ Alerta de Informação --}}
@if (session('info'))
  <script>
    Swal.fire({
      icon: 'info',
      title: 'Informação',
      text: '{{ session('info') }}',
      confirmButtonText: 'Ok'
    });
  </script>
@endif

{{-- ✅ Erros de Validação do Laravel --}}
@if ($errors->any())
  <script>
    Swal.fire({
      icon: 'error',
      title: 'Erros no formulário',
      html: `
                <ul style="text-align: left;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
      confirmButtonText: 'Corrigir'
    });
  </script>
@endif