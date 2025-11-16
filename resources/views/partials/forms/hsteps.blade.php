<ul class="nav nav-pills justify-content-between steps-horizontal d-md-none mb-3"
    style="--progress: {{ $progress / 100 }};">

    {{-- Etapa 1 --}}
    <li class="nav-item">
        <a class="nav-link text-center
      {{ request()->routeIs('personal') ? 'active' : '' }}
      {{ !session()->has('step1_done') ? 'disabled-link' : '' }}
      {{ session()->has('step1_done') ? 'completed' : '' }}"
            href="{{ session()->has('step1_done') ? route('step.personal') : '#' }}">
            1
        </a>
    </li>

    {{-- Etapa 2 --}}
    <li class="nav-item">
        <a class="nav-link text-center
      {{ request()->routeIs('certificate') ? 'active' : '' }}
      {{ !session()->has('step2_done') ? 'disabled-link' : '' }}
      {{ session()->has('step2_done') ? 'completed' : '' }}"
            href="{{ session()->has('step1_done') ? route('step.certificate') : '#' }}">
            2
        </a>
    </li>

    {{-- Etapa 3 --}}
    <li class="nav-item">
        <a class="nav-link text-center
      {{ request()->routeIs('address') ? 'active' : '' }}
      {{ !session()->has('step3_done') ? 'disabled-link' : '' }}
      {{ session()->has('step3_done') ? 'completed' : '' }}"
            href="{{ session()->has('step2_done') ? route('step.address') : '#' }}">
            3
        </a>
    </li>

    {{-- Etapa 4 --}}
    <li class="nav-item">
        <a class="nav-link text-center
      {{ request()->routeIs('academic') ? 'active' : '' }}
      {{ !session()->has('step4_done') ? 'disabled-link' : '' }}
      {{ session()->has('step4_done') ? 'completed' : '' }}"
            href="{{ session()->has('step3_done') ? route('step.academic') : '#' }}">
            4
        </a>
    </li>

    {{-- Etapa 5 --}}
    <li class="nav-item">
        <a class="nav-link text-center
      {{ request()->routeIs('family') ? 'active' : '' }}
      {{ !session()->has('step5_done') ? 'disabled-link' : '' }}
      {{ session()->has('step5_done') ? 'completed' : '' }}"
            href="{{ session()->has('step4_done') ? route('step.family') : '#' }}">
            5
        </a>
    </li>

    {{-- Etapa 6 --}}
    <li class="nav-item">
        <a class="nav-link text-center
      {{ request()->routeIs('other') ? 'active' : '' }}
      {{ !session()->has('step6_done') ? 'disabled-link' : '' }}
      {{ session()->has('step6_done') ? 'completed' : '' }}"
            href="{{ session()->has('step5_done') ? route('step.other') : '#' }}">
            6
        </a>
    </li>

    {{-- Etapa 7 --}}
    <li class="nav-item">
        <a class="nav-link text-center
      {{ request()->routeIs('course') ? 'active' : '' }}
      {{ !session()->has('step6_done') ? 'disabled-link' : '' }}
      {{ session()->has('step7_done') ? 'completed' : '' }}"
            href="{{ session()->has('step6_done') ? route('step.course') : '#' }}">
            7
        </a>
    </li>

    {{-- Etapa 8 --}}
    <li class="nav-item">
        <a class="nav-link text-center
      {{ request()->routeIs('forms.confirm') ? 'active' : '' }}
      {{ !session()->has('step7_done') ? 'disabled-link' : '' }}
      {{ session()->has('step_done') ? 'completed' : '' }}"
            href="{{ session()->has('step7_done') ? route('step.confirm') : '#' }}">
            8
        </a>
    </li>
</ul>
