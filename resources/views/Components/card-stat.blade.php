@props(['title' => '-', 'value' => '-', 'icon' => 'bi-graph-up', 'color' => 'primary'])

<div class="card shadow-sm border-0 rounded-4 h-100">
  <div class="card-body d-flex align-items-center justify-content-between">
    <div>
      <div class="text-muted small">{{ $title }}</div>
      <h5 class="fw-bold mb-0">{{ $value }}</h5>
    </div>
    <div class="text-{{ $color }}">
      <i class="bi {{ $icon }} fs-3"></i>
    </div>
  </div>
</div>
