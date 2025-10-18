@extends('layouts.auth')

@section('title', 'Inscription')

@section('content')
<div>
    <h2 class="auth-title h3">Rejoignez EcoEvents</h2>
    <p class="auth-subtitle">Créez votre compte et participez à la révolution écologique</p>

    <!-- Information importante -->
    <div class="alert alert-info" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Note :</strong> Après avoir créé votre compte, vous serez redirigé vers la page de connexion
        pour vous authentifier avant d'accéder à votre espace personnel.
    </div>

    <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf

        <!-- First Name & Last Name -->
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="first_name" class="form-label">
                        <i class="fas fa-user me-2"></i>Prénom <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                        class="form-control @error('first_name') is-invalid @enderror"
                        id="first_name"
                        name="first_name"
                        value="{{ old('first_name') }}"
                        required
                        autofocus
                        autocomplete="given-name"
                        placeholder="Votre prénom">
                    @error('first_name')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="last_name" class="form-label">
                        <i class="fas fa-user me-2"></i>Nom <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                        class="form-control @error('last_name') is-invalid @enderror"
                        id="last_name"
                        name="last_name"
                        value="{{ old('last_name') }}"
                        required
                        autocomplete="family-name"
                        placeholder="Votre nom">
                    @error('last_name')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Name (auto-generated) -->
        <input type="hidden" id="name" name="name" value="{{ old('name') }}">

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">
                <i class="fas fa-envelope me-2"></i>Adresse email <span class="text-danger">*</span>
            </label>
            <input type="email"
                class="form-control @error('email') is-invalid @enderror"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="username"
                placeholder="votre@email.com">
            @error('email')
            <div class="invalid-feedback">
                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
            </div>
            @enderror
        </div>

        <!-- Role Selection -->
        <div class="mb-3">
            <label for="role" class="form-label">
                <i class="fas fa-user-tag me-2"></i>Type de compte
            </label>
            <select class="form-select @error('role') is-invalid @enderror"
                id="role"
                name="role"
                required>
                <option value="user" {{ old('role', 'user') == 'user' ? 'selected' : '' }}>
                    Utilisateur
                </option>
                <option value="organizer" {{ old('role') == 'organizer' ? 'selected' : '' }}>
                    Organisateur
                </option>
            </select>
            @error('role')
            <div class="invalid-feedback">
                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
            </div>
            @enderror
            <div class="mt-2 small text-muted">
                Vous représentez une entreprise ou organisation ?
                <a href="{{ route('sponsors.create') }}" class="fw-semibold text-success">
                    Devenir sponsor
                </a>
                (dossier à valider par l'administration).
            </div>
        </div>


        <!-- Phone -->
        <div class="mb-3">
            <label for="phone" class="form-label">
                <i class="fas fa-phone me-2"></i>Téléphone
            </label>
            <input type="tel"
                class="form-control @error('phone') is-invalid @enderror"
                id="phone"
                name="phone"
                value="{{ old('phone') }}"
                autocomplete="tel"
                placeholder="+33 1 23 45 67 89">
            @error('phone')
            <div class="invalid-feedback">
                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
            </div>
            @enderror
            <small class="form-text text-muted">Optionnel - Format: +33 1 23 45 67 89</small>
        </div>

        <!-- Date de naissance -->
        <div class="mb-3">
            <label for="birth_date" class="form-label">
                <i class="fas fa-calendar me-2"></i>Date de naissance
            </label>
            <input type="date"
                class="form-control @error('birth_date') is-invalid @enderror"
                id="birth_date"
                name="birth_date"
                value="{{ old('birth_date') }}"
                autocomplete="bday">
            @error('birth_date')
            <div class="invalid-feedback">
                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
            </div>
            @enderror
        </div>

        <!-- Ville et Pays -->
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="city" class="form-label">
                        <i class="fas fa-city me-2"></i>Ville
                    </label>
                    <input type="text"
                        class="form-control @error('city') is-invalid @enderror"
                        id="city"
                        name="city"
                        value="{{ old('city') }}"
                        autocomplete="address-level2"
                        placeholder="Votre ville">
                    @error('city')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="country" class="form-label">
                        <i class="fas fa-globe me-2"></i>Pays
                    </label>
                    <select class="form-select @error('country') is-invalid @enderror"
                        id="country"
                        name="country"
                        autocomplete="country">
                        <option value="">Sélectionnez un pays</option>
                        <option value="TN" {{ old('country') == 'TN' ? 'selected' : '' }}>Tunisie</option>
                        <option value="FR" {{ old('country') == 'FR' ? 'selected' : '' }}>France</option>
                        <option value="DZ" {{ old('country') == 'DZ' ? 'selected' : '' }}>Algérie</option>
                        <option value="MA" {{ old('country') == 'MA' ? 'selected' : '' }}>Maroc</option>
                        <option value="BE" {{ old('country') == 'BE' ? 'selected' : '' }}>Belgique</option>
                        <option value="CH" {{ old('country') == 'CH' ? 'selected' : '' }}>Suisse</option>
                        <option value="CA" {{ old('country') == 'CA' ? 'selected' : '' }}>Canada</option>
                        <option value="LB" {{ old('country') == 'LB' ? 'selected' : '' }}>Liban</option>
                        <option value="EG" {{ old('country') == 'EG' ? 'selected' : '' }}>Égypte</option>
                        <option value="SN" {{ old('country') == 'SN' ? 'selected' : '' }}>Sénégal</option>
                        <option value="CI" {{ old('country') == 'CI' ? 'selected' : '' }}>Côte d'Ivoire</option>
                    </select>
                    @error('country')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">
                <i class="fas fa-lock me-2"></i>Mot de passe <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <input type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    id="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Minimum 8 caractères">
                <span class="input-group-text password-toggle" onclick="togglePassword('password')">
                    <i class="fas fa-eye" id="password-icon"></i>
                </span>
                @error('password')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
                @enderror
            </div>
            <small class="form-text text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Minimum 8 caractères avec lettres et chiffres
            </small>
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">
                <i class="fas fa-lock me-2"></i>Confirmer le mot de passe <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <input type="password"
                    class="form-control @error('password_confirmation') is-invalid @enderror"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Répétez votre mot de passe">
                <span class="input-group-text password-toggle" onclick="togglePassword('password_confirmation')">
                    <i class="fas fa-eye" id="password_confirmation-icon"></i>
                </span>
                @error('password_confirmation')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <!-- Terms and Conditions -->
        <div class="mb-4">
            <div class="form-check">
                <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" id="terms" name="terms" required>
                <label class="form-check-label" for="terms">
                    J'accepte les <a href="#" class="auth-link">conditions d'utilisation</a>
                    et la <a href="#" class="auth-link">politique de confidentialité</a>
                    <span class="text-danger">*</span>
                </label>
                @error('terms')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mb-4">
            <button type="submit" class="btn btn-eco">
                <i class="fas fa-user-plus me-2"></i>Créer mon compte
            </button>
        </div>

        <!-- Links -->
        <div class="text-center">
            <p class="mb-0">
                Déjà un compte ?
                <a href="{{ route('login') }}" class="auth-link">
                    <i class="fas fa-sign-in-alt me-1"></i>Se connecter
                </a>
            </p>
        </div>
    </form>
</div>




@push('scripts')
<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '-icon');

        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Auto-generate name field from first_name and last_name
    function updateNameField() {
        const firstName = document.getElementById('first_name').value;
        const lastName = document.getElementById('last_name').value;
        const nameField = document.getElementById('name');

        if (firstName && lastName) {
            nameField.value = firstName + ' ' + lastName;
        } else if (firstName) {
            nameField.value = firstName;
        } else if (lastName) {
            nameField.value = lastName;
        }
    }

    // Show/hide organizer information
    function toggleOrganizerInfo() {
        const organizerInfo = document.getElementById('organizerInfo');
        const organizerRadio = document.getElementById('role_organizer');

        if (organizerRadio.checked) {
            organizerInfo.style.display = 'block';
        } else {
            organizerInfo.style.display = 'none';
        }
    }

    // Fonction de validation d'email robuste
    function isValidEmail(email) {
        // Vérifications de base
        if (!email || email.length === 0) return false;
        
        // Pas d'espaces au début ou à la fin
        if (email !== email.trim()) return false;
        
        // Doit contenir un @
        if (email.indexOf('@') === -1) return false;
        
        // Ne doit pas commencer ou finir par @
        if (email.startsWith('@') || email.endsWith('@')) return false;
        
        // Ne doit pas avoir de double @
        if (email.split('@').length !== 2) return false;
        
        // Regex plus stricte
        const emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
        
        if (!emailRegex.test(email)) return false;
        
        // Vérifications supplémentaires
        const [localPart, domainPart] = email.split('@');
        
        // Partie locale ne doit pas être vide
        if (!localPart || localPart.length === 0) return false;
        
        // Partie locale ne doit pas dépasser 64 caractères
        if (localPart.length > 64) return false;
        
        // Domaine ne doit pas être vide
        if (!domainPart || domainPart.length === 0) return false;
        
        // Domaine ne doit pas dépasser 253 caractères
        if (domainPart.length > 253) return false;
        
        // Domaine doit contenir au moins un point
        if (domainPart.indexOf('.') === -1) return false;
        
        // Domaine ne doit pas commencer ou finir par un point ou un tiret
        if (domainPart.startsWith('.') || domainPart.endsWith('.') || 
            domainPart.startsWith('-') || domainPart.endsWith('-')) return false;
        
        return true;
    }

    // Validation côté client améliorée
    function validateForm() {
        let isValid = true;
        const fieldErrors = {};

        // Validation du prénom
        const firstName = document.getElementById('first_name').value.trim();
        if (!firstName) {
            fieldErrors.first_name = 'Le prénom est obligatoire.';
            isValid = false;
        } else if (firstName.length < 2) {
            fieldErrors.first_name = 'Le prénom doit contenir au moins 2 caractères.';
            isValid = false;
        } else if (!/^[a-zA-ZÀ-ÿ\s\-\']+$/.test(firstName)) {
            fieldErrors.first_name = 'Le prénom ne peut contenir que des lettres, espaces, tirets et apostrophes.';
            isValid = false;
        }

        // Validation du nom
        const lastName = document.getElementById('last_name').value.trim();
        if (!lastName) {
            fieldErrors.last_name = 'Le nom est obligatoire.';
            isValid = false;
        } else if (lastName.length < 2) {
            fieldErrors.last_name = 'Le nom doit contenir au moins 2 caractères.';
            isValid = false;
        } else if (!/^[a-zA-ZÀ-ÿ\s\-\']+$/.test(lastName)) {
            fieldErrors.last_name = 'Le nom ne peut contenir que des lettres, espaces, tirets et apostrophes.';
            isValid = false;
        }

        // Validation de l'email
        const email = document.getElementById('email').value.trim();
        if (!email) {
            fieldErrors.email = 'L\'adresse email est obligatoire.';
            isValid = false;
        } else if (!isValidEmail(email)) {
            fieldErrors.email = 'L\'adresse email doit être valide.';
            isValid = false;
        }

        // Validation du mot de passe
        const password = document.getElementById('password').value;
        if (!password) {
            fieldErrors.password = 'Le mot de passe est obligatoire.';
            isValid = false;
        } else if (password.length < 8) {
            fieldErrors.password = 'Le mot de passe doit contenir au moins 8 caractères.';
            isValid = false;
        } else if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])/.test(password)) {
            fieldErrors.password = 'Le mot de passe doit contenir au moins une lettre majuscule, une minuscule, un chiffre et un symbole.';
            isValid = false;
        }

        // Validation de la confirmation du mot de passe
        const passwordConfirmation = document.getElementById('password_confirmation').value;
        if (!passwordConfirmation) {
            fieldErrors.password_confirmation = 'La confirmation du mot de passe est obligatoire.';
            isValid = false;
        } else if (password !== passwordConfirmation) {
            fieldErrors.password_confirmation = 'Les mots de passe ne correspondent pas.';
            isValid = false;
        }

        // Validation du téléphone (optionnel)
        const phone = document.getElementById('phone').value.trim();
        if (phone && !/^[\+]?[0-9\s\-\(\)]{8,20}$/.test(phone)) {
            fieldErrors.phone = 'Le format du numéro de téléphone n\'est pas valide.';
            isValid = false;
        }

        // Validation de la date de naissance (optionnel)
        const birthDate = document.getElementById('birth_date').value;
        if (birthDate) {
            const birth = new Date(birthDate);
            const today = new Date();
            const age = today.getFullYear() - birth.getFullYear();
            
            if (birth > today) {
                fieldErrors.birth_date = 'La date de naissance doit être antérieure à aujourd\'hui.';
                isValid = false;
            } else if (age < 13) {
                fieldErrors.birth_date = 'Vous devez avoir au moins 13 ans pour créer un compte.';
                isValid = false;
            }
        }

        // Validation des conditions d'utilisation
        const terms = document.getElementById('terms').checked;
        if (!terms) {
            fieldErrors.terms = 'Vous devez accepter les conditions d\'utilisation.';
            isValid = false;
        }

        return { isValid, fieldErrors };
    }

    // Validation en temps réel
    function setupRealTimeValidation() {
        const fields = ['first_name', 'last_name', 'email', 'password', 'password_confirmation', 'phone', 'birth_date'];
        
        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('blur', function() {
                    validateField(fieldId);
                });
                
                field.addEventListener('input', function() {
                    clearFieldError(fieldId);
                });
            }
        });

        // Validation spéciale pour la confirmation du mot de passe
        const passwordField = document.getElementById('password');
        const confirmField = document.getElementById('password_confirmation');
        
        if (passwordField && confirmField) {
            confirmField.addEventListener('input', function() {
                if (confirmField.value && passwordField.value !== confirmField.value) {
                    showFieldError('password_confirmation', 'Les mots de passe ne correspondent pas.');
                } else {
                    clearFieldError('password_confirmation');
                }
            });
        }
    }

    function validateField(fieldId) {
        const field = document.getElementById(fieldId);
        const value = field.value.trim();
        
        switch(fieldId) {
            case 'first_name':
            case 'last_name':
                if (!value) {
                    showFieldError(fieldId, 'Ce champ est obligatoire.');
                } else if (value.length < 2) {
                    showFieldError(fieldId, 'Ce champ doit contenir au moins 2 caractères.');
                } else if (!/^[a-zA-ZÀ-ÿ\s\-\']+$/.test(value)) {
                    showFieldError(fieldId, 'Ce champ ne peut contenir que des lettres, espaces, tirets et apostrophes.');
                } else {
                    clearFieldError(fieldId);
                }
                break;
                
            case 'email':
                if (!value) {
                    showFieldError(fieldId, 'L\'adresse email est obligatoire.');
                } else if (!isValidEmail(value)) {
                    showFieldError(fieldId, 'L\'adresse email doit être valide.');
                } else {
                    clearFieldError(fieldId);
                }
                break;
                
            case 'password':
                if (!value) {
                    showFieldError(fieldId, 'Le mot de passe est obligatoire.');
                } else if (value.length < 8) {
                    showFieldError(fieldId, 'Le mot de passe doit contenir au moins 8 caractères.');
                } else if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])/.test(value)) {
                    showFieldError(fieldId, 'Le mot de passe doit contenir au moins une lettre majuscule, une minuscule, un chiffre et un symbole.');
                } else {
                    clearFieldError(fieldId);
                }
                break;
                
            case 'phone':
                if (value && !/^[\+]?[0-9\s\-\(\)]{8,20}$/.test(value)) {
                    showFieldError(fieldId, 'Le format du numéro de téléphone n\'est pas valide.');
                } else {
                    clearFieldError(fieldId);
                }
                break;
        }
    }

    function showFieldError(fieldId, message) {
        const field = document.getElementById(fieldId);
        if (!field) return;
        
        // Trouver le bon conteneur pour l'erreur selon la structure HTML
        let container, errorDiv;
        
        // Pour les champs avec input-group (password, etc.)
        if (field.parentNode.classList.contains('input-group')) {
            container = field.parentNode.parentNode; // Le div .mb-3
        } 
        // Pour les checkboxes (terms, etc.)
        else if (field.type === 'checkbox' && field.parentNode.classList.contains('form-check')) {
            container = field.parentNode; // Le div .form-check
        }
        // Pour les champs normaux (date, text, email, etc.)
        else {
            container = field.parentNode; // Le div .mb-3
        }
        
        // Chercher l'élément d'erreur existant
        errorDiv = container.querySelector('.invalid-feedback');
        
        if (errorDiv) {
            // Utiliser l'élément existant
            errorDiv.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>' + message;
            errorDiv.style.display = 'block';
            errorDiv.style.color = '#dc3545'; // Rouge Bootstrap
        } else {
            // Créer un nouvel élément d'erreur
            const errorElement = document.createElement('div');
            errorElement.className = 'invalid-feedback';
            errorElement.style.display = 'block';
            errorElement.style.color = '#dc3545';
            errorElement.style.marginTop = '0.25rem';
            errorElement.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>' + message;
            
            // Insérer l'erreur à la fin du conteneur approprié
            container.appendChild(errorElement);
        }
        
        // Ajouter la classe d'erreur au champ
        field.classList.add('is-invalid');
    }

    function clearFieldError(fieldId) {
        const field = document.getElementById(fieldId);
        if (!field) return;
        
        // Trouver le bon conteneur et masquer l'erreur
        let container;
        
        // Pour les champs avec input-group
        if (field.parentNode.classList.contains('input-group')) {
            container = field.parentNode.parentNode;
        } 
        // Pour les checkboxes
        else if (field.type === 'checkbox' && field.parentNode.classList.contains('form-check')) {
            container = field.parentNode;
        }
        // Pour les champs normaux
        else {
            container = field.parentNode;
        }
        
        // Masquer l'erreur existante
        const errorDiv = container.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.style.display = 'none';
            errorDiv.innerHTML = '';
        }
        
        // Retirer la classe d'erreur
        field.classList.remove('is-invalid');
    }

    // Form validation 
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const validation = validateForm();
        
        // Toujours mettre à jour le champ name avant soumission
        updateNameField();
        
        if (!validation.isValid) {
            // Afficher les erreurs côté client immédiatement pour l'UX
            displayFieldErrors(validation.fieldErrors);
            
            // Mais laisser la soumission se faire pour que Laravel puisse aussi valider
            // Cela permettra d'avoir à la fois les erreurs côté client ET côté serveur
            console.log('Erreurs côté client détectées, mais soumission autorisée pour validation serveur');
        }
        
        // Ne pas empêcher la soumission - laissez Laravel gérer les erreurs de validation
    });

    // Fonction pour afficher les erreurs sous les champs correspondants
    function displayFieldErrors(fieldErrors) {
        // D'abord, nettoyer toutes les erreurs existantes
        clearAllFieldErrors();
        
        // Puis afficher les nouvelles erreurs sous les champs concernés
        Object.keys(fieldErrors).forEach(fieldId => {
            showFieldError(fieldId, fieldErrors[fieldId]);
        });
    }

    // Fonction pour nettoyer toutes les erreurs
    function clearAllFieldErrors() {
        const fields = ['first_name', 'last_name', 'email', 'password', 'password_confirmation', 'phone', 'birth_date', 'terms'];
        fields.forEach(fieldId => {
            clearFieldError(fieldId);
        });
    }
    // Fonction pour afficher les erreurs Laravel existantes au chargement
    function displayLaravelErrors() {
        console.log('Recherche des erreurs Laravel...');
        
        // Chercher tous les éléments d'erreur Laravel
        const laravelErrors = document.querySelectorAll('.invalid-feedback');
        console.log('Erreurs Laravel trouvées:', laravelErrors.length);
        
        laravelErrors.forEach((errorElement, index) => {
            const errorText = errorElement.textContent.trim();
            console.log(`Erreur ${index}:`, errorText);
            
            if (errorText) {
                // Trouver le champ associé - chercher dans le conteneur parent
                const fieldContainer = errorElement.closest('.mb-3, .mb-4, .form-check');
                
                if (fieldContainer) {
                    // Chercher le champ input, select ou textarea
                    let field = fieldContainer.querySelector('input, select, textarea');
                    
                    // Si pas trouvé, chercher dans le conteneur parent
                    if (!field) {
                        const parentContainer = fieldContainer.parentNode;
                        if (parentContainer) {
                            field = parentContainer.querySelector('input, select, textarea');
                        }
                    }
                    
                    if (field) {
                        console.log('Champ trouvé pour erreur:', field.id, field.name);
                        
                        // Ajouter la classe d'erreur Bootstrap
                        field.classList.add('is-invalid');
                        
                        // Forcer l'affichage de l'erreur avec tous les styles nécessaires
                        errorElement.style.cssText = `
                            display: block !important;
                            visibility: visible !important;
                            opacity: 1 !important;
                            color: #dc3545 !important;
                        `;
                        
                        // Ajouter les classes Bootstrap
                        errorElement.classList.add('d-block');
                        errorElement.classList.remove('d-none');
                        
                        // Supprimer tout style qui pourrait cacher l'élément
                        errorElement.removeAttribute('hidden');
                        
                        console.log('Erreur Laravel affichée pour:', field.id, errorText);
                    } else {
                        console.log('Champ non trouvé pour erreur:', errorText);
                    }
                }
            }
        });
        
        // Vérifier le résultat
        const allInvalidFields = document.querySelectorAll('.is-invalid');
        const visibleErrors = document.querySelectorAll('.invalid-feedback');
        console.log('Total champs avec erreurs:', allInvalidFields.length);
        console.log('Total erreurs visibles:', visibleErrors.length);
    }

    // Fonction pour forcer l'affichage des erreurs après soumission
    function ensureErrorsDisplayed() {
        // Attendre un peu que le DOM soit mis à jour
        setTimeout(() => {
            displayLaravelErrors();
            
            // Si on a des erreurs, scroller vers la première
            const firstInvalidField = document.querySelector('.is-invalid');
            if (firstInvalidField) {
                firstInvalidField.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
                
                // Focus sur le champ pour aider l'utilisateur
                setTimeout(() => {
                    firstInvalidField.focus();
                }, 300);
            }
        }, 100);
    }

    // Fonction pour forcer l'affichage immédiat des erreurs Laravel
    function forceLaravelErrorsDisplay() {
        // Rechercher spécifiquement les erreurs pour le champ email
        const emailField = document.getElementById('email');
        if (emailField) {
            const emailContainer = emailField.closest('.mb-3');
            if (emailContainer) {
                const emailError = emailContainer.querySelector('.invalid-feedback');
                if (emailError && emailError.textContent.trim()) {
                    console.log('Erreur email Laravel détectée:', emailError.textContent);
                    emailField.classList.add('is-invalid');
                    emailError.style.display = 'block';
                    emailError.style.color = '#dc3545';
                    emailError.classList.add('d-block');
                    return true;
                }
            }
        }
        return false;
    }

    // Initialize name field and organizer info on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateNameField();
        toggleOrganizerInfo();
        setupRealTimeValidation();
        
        // Afficher les erreurs Laravel existantes immédiatement
        displayLaravelErrors();
        forceLaravelErrorsDisplay();
        
        // Vérifier à nouveau après des délais progressifs
        setTimeout(displayLaravelErrors, 100);
        setTimeout(displayLaravelErrors, 500);
        setTimeout(ensureErrorsDisplayed, 1000);
        
        // Observer les changements dans le DOM pour détecter les nouvelles erreurs
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' || mutation.type === 'attributes') {
                    // Vérifier s'il y a de nouveaux éléments d'erreur
                    const newErrors = document.querySelectorAll('.invalid-feedback');
                    newErrors.forEach(errorElement => {
                        if (errorElement.textContent.trim() && 
                            errorElement.style.display === 'none' || 
                            !errorElement.classList.contains('d-block')) {
                            displayLaravelErrors();
                        }
                    });
                }
            });
        });
        
        // Observer les changements dans le formulaire
        const form = document.getElementById('registerForm');
        if (form) {
            observer.observe(form, {
                childList: true,
                subtree: true,
                attributes: true,
                attributeFilter: ['class', 'style']
            });
        }
    });
</script>
@endpush
@endsection