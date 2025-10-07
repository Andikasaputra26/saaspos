<nav class="navbar navbar-expand-lg px-3 mb-4">
    <div class="container-fluid">
        <button class="btn btn-light d-none d-lg-block me-2" id="collapseBtn">
            <i class="bi bi-list"></i>
        </button>

        <div class="ms-auto d-flex align-items-center gap-3">
            <span class="text-muted small">
                <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                ({{ auth()->user()->role }})
            </span>
        </div>
    </div>
</nav>
