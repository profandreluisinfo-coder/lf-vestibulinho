<ul class="nav nav-pills steps-menu flex-column">
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('personal') ? 'active' : '' }} {{ !session()->has('step1_done') ? 'disabled-link' : '' }} {{ session()->has('step1_done') ? 'completed' : '' }}"
      href="{{ session()->has('step1_done') ? route('step.personal') : '#' }}">
      <span class="step-number">1</span>
      <span class="step-title">Dados Pessoais</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('certificate') ? 'active' : '' }} {{-- link ativo --}} {{ !session()->has('step2_done') ? 'disabled-link' : '' }} {{-- não completou --}} {{ session()->has('step2_done') ? 'completed' : '' }}"
      {{-- completou --}} href="{{ session()->has('step1_done') ? route('step.certificate') : '#' }}">
      <span class="step-number">2</span>
      <span class="step-title">Certidão de Nascimento</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('address') ? 'active' : '' }} {{ !session()->has('step3_done') ? 'disabled-link' : '' }} {{ session()->has('step3_done') ? 'completed' : '' }}"
      href="{{ session()->has('step2_done') ? route('step.address') : '#' }}">
      <span class="step-number">3</span>
      <span class="step-title">Endereço</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('academic') ? 'active' : '' }} {{ !session()->has('step3_done') ? 'disabled-link' : '' }} {{ session()->has('step4_done') ? 'completed' : '' }}"
      href="{{ session()->has('step3_done') ? route('step.academic') : '#' }}">
      <span class="step-number">4</span>
      <span class="step-title">Dados Escolares</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('family') ? 'active' : '' }} {{ !session()->has('step4_done') ? 'disabled-link' : '' }} {{ session()->has('step5_done') ? 'completed' : '' }}"
      href="{{ session()->has('step4_done') ? route('step.family') : '#' }}">
      <span class="step-number">5</span>
      <span class="step-title">Filiação</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('other') ? 'active' : '' }} {{ !session()->has('step5_done') ? 'disabled-link' : '' }} {{ session()->has('step6_done') ? 'completed' : '' }}"
      href="{{ session()->has('step5_done') ? route('step.other') : '#' }}">
      <span class="step-number">6</span>
      <span class="step-title">Outras Informações</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('course') ? 'active' : '' }} {{ !session()->has('step6') ? 'disabled-link' : '' }} {{ session()->has('step6_done') ? 'completed' : '' }}"
      href="{{ session()->has('step6_done') ? route('step.course') : '#' }}">
      <span class="step-number">7</span>
      <span class="step-title">Curso</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('forms.confirm') ? 'active' : '' }} {{ !session()->has('step7_done') ? 'disabled-link' : '' }} {{ session()->has('step_done') ? 'completed' : '' }}"
      href="{{ session()->has('step7') ? route('step.confirm') : '#' }}">
      <span class="step-number">8</span>
      <span class="step-title">Confirmar Dados</span>
    </a>
  </li>
</ul>