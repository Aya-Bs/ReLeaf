@extends('backend.layouts.app')

@section('title', 'Modifier utilisateur')
@section('page-title', 'Modifier utilisateur')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item"><a href="{{ route('backend.users.index') }}">Utilisateurs</a></li>
    <li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-eco">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-edit mr-2"></i>Modifier utilisateur</h3>
            </div>
            <form method="POST" action="{{ route('backend.users.update', $user) }}">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Rôle</label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Utilisateur</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="auteur" {{ old('role', $user->role) == 'auteur' ? 'selected' : '' }}>Auteur</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-eco">Enregistrer</button>
                    <a href="{{ route('backend.users.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
