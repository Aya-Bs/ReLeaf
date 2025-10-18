@extends('layouts.frontend')

@section('title', 'Devenir Volontaire')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if(auth()->user()->hasPendingVolunteerApplication())
                <div class="alert alert-warning">
                    <i class="fas fa-clock me-2"></i>
                    <strong>Votre candidature est en cours de traitement</strong><br>
                    <small>Veuillez attendre l'email de confirmation de notre équipe.</small>
                </div>
                <div class="text-center">
                    <a href="{{ route('profile.show') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Retour au profil
                    </a>
                </div>
            @elseif(auth()->user()->hasRejectedVolunteerApplication())
                <div class="alert alert-danger">
                    <i class="fas fa-times-circle me-2"></i>
                    <strong>Votre candidature a été rejetée</strong><br>
                    <small>Vous pouvez contacter notre équipe pour plus d'informations.</small>
                </div>
                <div class="text-center">
                    <a href="{{ route('profile.show') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Retour au profil
                    </a>
                </div>
            @elseif(auth()->user()->isVolunteer())
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Vous êtes déjà volontaire approuvé !</strong><br>
                    <small>Vous pouvez consulter votre profil volontaire.</small>
                </div>
                <div class="text-center">
                    <a href="{{ route('volunteers.show', auth()->user()->volunteer) }}" class="btn btn-success me-2">
                        <i class="fas fa-eye me-2"></i>Voir mon profil volontaire
                    </a>
                    <a href="{{ route('profile.show') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Retour au profil
                    </a>
                </div>
            @else
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-hands-helping me-2"></i>Devenir Volontaire
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('volunteers.store') }}">
                        @csrf

                        <!-- Compétences -->
                        <div class="mb-4">
                            <label for="skills" class="form-label">Compétences <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="skills[]" value="gardening" id="skill_gardening">
                                        <label class="form-check-label" for="skill_gardening">Jardinage</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="skills[]" value="coordination" id="skill_coordination">
                                        <label class="form-check-label" for="skill_coordination">Coordination</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="skills[]" value="first_aid" id="skill_first_aid">
                                        <label class="form-check-label" for="skill_first_aid">Premiers secours</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="skills[]" value="communication" id="skill_communication">
                                        <label class="form-check-label" for="skill_communication">Communication</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="skills[]" value="logistics" id="skill_logistics">
                                        <label class="form-check-label" for="skill_logistics">Logistique</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="skills[]" value="education" id="skill_education">
                                        <label class="form-check-label" for="skill_education">Éducation</label>
                                    </div>
                                </div>
                            </div>
                            @error('skills')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Disponibilités -->
                        <div class="mb-4">
                            <label class="form-label">Disponibilités <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="availability[]" value="weekday_morning" id="avail_weekday_morning">
                                        <label class="form-check-label" for="avail_weekday_morning">Matin (semaine)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="availability[]" value="weekday_afternoon" id="avail_weekday_afternoon">
                                        <label class="form-check-label" for="avail_weekday_afternoon">Après-midi (semaine)</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="availability[]" value="weekend" id="avail_weekend">
                                        <label class="form-check-label" for="avail_weekend">Week-end</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="availability[]" value="evening" id="avail_evening">
                                        <label class="form-check-label" for="avail_evening">Soirée</label>
                                    </div>
                                </div>
                            </div>
                            @error('availability')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Niveau d'expérience -->
                        <div class="mb-4">
                            <label for="experience_level" class="form-label">Niveau d'expérience <span class="text-danger">*</span></label>
                            <select class="form-select" id="experience_level" name="experience_level" required>
                                <option value="">Sélectionnez votre niveau</option>
                                <option value="beginner">Débutant - Première expérience</option>
                                <option value="intermediate">Intermédiaire - Quelques expériences</option>
                                <option value="advanced">Avancé - Expérience confirmée</option>
                            </select>
                            @error('experience_level')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Régions préférées -->
                        <div class="mb-4">
                            <label class="form-label">Régions préférées <span class="text-danger">*</span></label>
                            <div class="row">
                                @php
                                    $regions = config('tunisia_regions.regions');
                                    $regionsPerColumn = ceil(count($regions) / 3);
                                    $regionsArray = array_chunk($regions, $regionsPerColumn, true);
                                @endphp
                                
                                @foreach($regionsArray as $columnIndex => $columnRegions)
                                <div class="col-md-4">
                                    @foreach($columnRegions as $key => $region)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="preferred_regions[]" value="{{ $region }}" id="region_{{ $key }}" {{ in_array($region, old('preferred_regions', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="region_{{ $key }}">{{ $region }}</label>
                                    </div>
                                    @endforeach
                                </div>
                                @endforeach
                            </div>
                            @error('preferred_regions')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Heures par semaine -->
                        <div class="mb-4">
                            <label for="max_hours_per_week" class="form-label">Heures maximum par semaine <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('max_hours_per_week') is-invalid @enderror" 
                                   id="max_hours_per_week" name="max_hours_per_week" 
                                   value="{{ old('max_hours_per_week', 20) }}" 
                                   min="1" max="168" step="1" required
                                   placeholder="Entrez un nombre entre 1 et 168">
                            <div class="form-text">Nombre d'heures que vous pouvez consacrer par semaine (entre 1 et 168 heures)</div>
                            @error('max_hours_per_week')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contact d'urgence -->
                        <div class="mb-4">
                            <label for="emergency_contact" class="form-label">Contact d'urgence <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">+216</span>
                                <input type="tel" class="form-control @error('emergency_contact') is-invalid @enderror" 
                                       id="emergency_contact" name="emergency_contact" 
                                       value="{{ old('emergency_contact') }}" 
                                       pattern="[0-9]{8}" 
                                       placeholder="12345678" 
                                       maxlength="8" required>
                            </div>
                            <div class="form-text">Numéro de téléphone tunisien (8 chiffres après +216)</div>
                            @error('emergency_contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Conditions médicales -->
                        <div class="mb-4">
                            <label for="medical_conditions" class="form-label">Conditions médicales</label>
                            <textarea class="form-control @error('medical_conditions') is-invalid @enderror" 
                                      id="medical_conditions" name="medical_conditions" 
                                      rows="3" maxlength="1000" 
                                      placeholder="Décrivez vos conditions médicales importantes...">{{ old('medical_conditions') }}</textarea>
                            <div class="form-text">Informations médicales importantes (allergies, limitations, etc.) - Maximum 1000 caractères</div>
                            @error('medical_conditions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bio -->
                        <div class="mb-4">
                            <label for="bio" class="form-label">Présentation <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" 
                                      id="bio" name="bio" rows="4" 
                                      maxlength="1000" required
                                      placeholder="Parlez-nous de vous en quelques mots...">{{ old('bio') }}</textarea>
                            <div class="form-text">Parlez-nous de vous en quelques mots - Maximum 1000 caractères</div>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Motivation -->
                        <div class="mb-4">
                            <label for="motivation" class="form-label">Motivation <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('motivation') is-invalid @enderror" 
                                      id="motivation" name="motivation" rows="4" 
                                      maxlength="1000" required
                                      placeholder="Pourquoi souhaitez-vous devenir volontaire ?">{{ old('motivation') }}</textarea>
                            <div class="form-text">Pourquoi souhaitez-vous devenir volontaire ? - Maximum 1000 caractères</div>
                            @error('motivation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Expérience précédente -->
                        <div class="mb-4">
                            <label for="previous_volunteer_experience" class="form-label">Expérience de volontariat</label>
                            <textarea class="form-control @error('previous_volunteer_experience') is-invalid @enderror" 
                                      id="previous_volunteer_experience" name="previous_volunteer_experience" 
                                      rows="3" maxlength="1000"
                                      placeholder="Décrivez vos expériences de volontariat précédentes...">{{ old('previous_volunteer_experience') }}</textarea>
                            <div class="form-text">Décrivez vos expériences de volontariat précédentes (optionnel) - Maximum 1000 caractères</div>
                            @error('previous_volunteer_experience')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('volunteers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                            <button type="submit" class="btn btn-success" id="submitBtn">
                                <i class="fas fa-check me-2"></i>Créer mon profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation du numéro de téléphone
    const phoneInput = document.getElementById('emergency_contact');
    phoneInput.addEventListener('input', function() {
        // Supprimer tous les caractères non numériques
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Limiter à 8 chiffres
        if (this.value.length > 8) {
            this.value = this.value.slice(0, 8);
        }
        
        // Validation visuelle
        if (this.value.length === 8) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        } else {
            this.classList.remove('is-valid');
        }
    });

    // Validation des champs texte
    const textFields = ['bio', 'motivation', 'medical_conditions', 'previous_volunteer_experience'];
    textFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', function() {
                const value = this.value.trim();
                const minLength = fieldId === 'bio' || fieldId === 'motivation' ? 10 : 0;
                
                if (value.length >= minLength && value.length <= 1000) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                    if (value.length > 0 && (value.length < minLength || value.length > 1000)) {
                        this.classList.add('is-invalid');
                    }
                }
            });
        }
    });

    // Validation des heures par semaine
    const hoursInput = document.getElementById('max_hours_per_week');
    hoursInput.addEventListener('input', function() {
        const value = parseInt(this.value);
        if (value >= 1 && value <= 168) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        } else {
            this.classList.remove('is-valid');
            if (this.value && (value < 1 || value > 168)) {
                this.classList.add('is-invalid');
            }
        }
    });

    // Validation des régions
    const regionCheckboxes = document.querySelectorAll('input[name="preferred_regions[]"]');
    const regionContainer = document.querySelector('.mb-4:has(input[name="preferred_regions[]"])');
    
    function validateRegions() {
        const checkedRegions = document.querySelectorAll('input[name="preferred_regions[]"]:checked');
        if (checkedRegions.length > 0) {
            regionContainer.classList.remove('is-invalid');
            regionContainer.classList.add('is-valid');
        } else {
            regionContainer.classList.remove('is-valid');
            regionContainer.classList.add('is-invalid');
        }
    }
    
    regionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', validateRegions);
    });

    // Validation du formulaire avant soumission
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Vérifier le téléphone
        if (phoneInput.value.length !== 8) {
            phoneInput.classList.add('is-invalid');
            isValid = false;
        }
        
        // Vérifier les champs requis
        const requiredFields = ['bio', 'motivation'];
        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field && (field.value.trim().length < 10 || field.value.trim().length > 1000)) {
                field.classList.add('is-invalid');
                isValid = false;
            }
        });
        
        // Vérifier les heures
        const hours = parseInt(hoursInput.value);
        if (hours < 1 || hours > 168) {
            hoursInput.classList.add('is-invalid');
            isValid = false;
        }
        
        // Vérifier les régions
        const checkedRegions = document.querySelectorAll('input[name="preferred_regions[]"]:checked');
        if (checkedRegions.length === 0) {
            regionContainer.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Veuillez corriger les erreurs dans le formulaire avant de soumettre.');
        }
    });
});
</script>
@endsection


