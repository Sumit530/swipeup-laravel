    <!-- User Pills -->
    <ul class="nav nav-pills mb-2">
        <li class="nav-item">
            <a class="nav-link {{ (request()->is('admin/users/')) ? 'active' : '' }}" href="{{ route('users.show',array($user->id)) }}">
                <i data-feather="user" class="font-medium-3 me-50"></i>
                <span class="fw-bold">Account</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ (request()->is('admin/users/safety/*')) ? 'active' : '' }}" href="{{ route('admin.users-safety',array($user->id)) }}">
                <i data-feather="lock" class="font-medium-3 me-50"></i>
                <span class="fw-bold">Safety</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ (request()->is('admin/users/notificationsetting/*')) ? 'active' : '' }}" href="{{ route('admin.users-notificationsetting',array($user->id)) }}">
                <i data-feather="bell" class="font-medium-3 me-50"></i><span class="fw-bold">Notifications</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ (request()->is('admin/users/privacy/*')) ? 'active' : '' }}" href="{{ route('admin.users-privacy',array($user->id)) }}">
                <i data-feather="link" class="font-medium-3 me-50"></i><span class="fw-bold">Privacy</span>
            </a>
        </li>
    </ul>
    <!--/ User Pills -->