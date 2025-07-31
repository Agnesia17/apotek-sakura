<li class="dropdown pc-h-item header-user-profile">
  <a
    class="pc-head-link dropdown-toggle arrow-none me-0"
    data-bs-toggle="dropdown"
    href="#"
    role="button"
    aria-haspopup="false"
    data-bs-auto-close="outside"
    aria-expanded="false"
  >
    <img src="{{ auth()->user()->avatar ?? asset('assets/admin/images/user/avatar-2.jpg') }}" alt="user-image" class="user-avtar">
    <span>{{ auth()->user()->name ?? 'Stebin Ben' }}</span>
  </a>
  <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
    <div class="dropdown-header">
      <div class="d-flex mb-1">
        <div class="flex-shrink-0">
          <img src="{{ auth()->user()->avatar ?? asset('assets/admin/images/user/avatar-2.jpg') }}" alt="user-image" class="user-avtar wid-35">
        </div>
        <div class="flex-grow-1 ms-3">
          <h6 class="mb-1">{{ auth()->user()->name ?? 'Stebin Ben' }}</h6>
          <span>{{ auth()->user()->role ?? 'UI/UX Designer' }}</span>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
          @csrf
          <button type="submit" class="pc-head-link bg-transparent border-0" style="background: none;">
            <i class="ti ti-logout text-danger"></i>
          </button>
        </form>
      </div>
    </div>

  </div>
</li>