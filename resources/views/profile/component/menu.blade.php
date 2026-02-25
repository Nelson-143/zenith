 <nav class="nav nav-borders">
     <a class="nav-link " href="{{ route('profile.edit') }}">{{ __('Profile') }}</a>
     @role('Super Admin')
     <a class="nav-link active " href="{{ route('profile.settings') }}">{{ __('Settings') }}</a>
     <a class="nav-link " href="{{ route('profile.store.settings') }}">{{ __('Store') }}</a>
        <a class="nav-link text-danger" href="{{ route('liabilities.index') }}"><b>{{ __('Liabilities') }}💰</b></a>
     @endrole
 </nav>
