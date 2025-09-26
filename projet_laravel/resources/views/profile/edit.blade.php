@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            <!-- Menu latéral -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="rounded-circle me-2" width="40">
                        <div>
                            <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                            <small class="text-muted">{{ auth()->user()->email }}</small>
                        </div>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="#profile" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                            <i class="fas fa-user me-2"></i>Profil
                        </a>
                        <a href="#security" class="list-group-item list-group-item-action" data-bs-toggle="list">
                            <i class="fas fa-shield-alt me-2"></i>Sécurité
                        </a>
                        <a href="#notifications" class="list-group-item list-group-item-action" data-bs-toggle="list">
                            <i class="fas fa-bell me-2"></i>Notifications
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="tab-content">
                <!-- Profil -->
                <div class="tab-pane fade show active" id="profile">
                    @include('profile.partials.update-profile-information-form')
                    @include('profile.partials.update-password-form')
                    @include('profile.partials.delete-user-form')
                </div>

                <!-- Sécurité -->
                <div class="tab-pane fade" id="security">
                    @include('profile.partials.security-settings')
                </div>

                <!-- Notifications -->
                <div class="tab-pane fade" id="notifications">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="fas fa-bell me-2 text-eco"></i>
                                Préférences de notification
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Contenu des notifications à venir -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Activer les onglets Bootstrap
    var triggerTabList = [].slice.call(document.querySelectorAll('.list-group-item'))
    triggerTabList.forEach(function(triggerEl) {
        new bootstrap.Tab(triggerEl)
    })

    // Gérer l'état actif des onglets
    triggerTabList.forEach(function(trigger) {
        trigger.addEventListener('click', function(event) {
            triggerTabList.forEach(t => t.classList.remove('active'))
            event.target.classList.add('active')
        })
    })
})
</script>
@endpush

@push('styles')
<style>
.btn-eco {
    background-color: #2d5a27;
    border-color: #2d5a27;
    color: white;
}
.btn-eco:hover {
    background-color: #234420;
    border-color: #234420;
    color: white;
}
.text-eco {
    color: #2d5a27;
}
.list-group-item.active {
    background-color: #2d5a27;
    border-color: #2d5a27;
}
</style>
@endpush
@endsection