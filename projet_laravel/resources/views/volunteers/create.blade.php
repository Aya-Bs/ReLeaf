@extends('layouts.frontend')

@section('title', 'Devenir Volontaire')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
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
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="preferred_regions[]" value="Tunis" id="region_tunis">
                                        <label class="form-check-label" for="region_tunis">Tunis</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="preferred_regions[]" value="Sfax" id="region_sfax">
                                        <label class="form-check-label" for="region_sfax">Sfax</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="preferred_regions[]" value="Sousse" id="region_sousse">
                                        <label class="form-check-label" for="region_sousse">Sousse</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="preferred_regions[]" value="Monastir" id="region_monastir">
                                        <label class="form-check-label" for="region_monastir">Monastir</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="preferred_regions[]" value="Bizerte" id="region_bizerte">
                                        <label class="form-check-label" for="region_bizerte">Bizerte</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="preferred_regions[]" value="Autre" id="region_autre">
                                        <label class="form-check-label" for="region_autre">Autre</label>
                                    </div>
                                </div>
                            </div>
                            @error('preferred_regions')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Heures par semaine -->
                        <div class="mb-4">
                            <label for="max_hours_per_week" class="form-label">Heures maximum par semaine <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="max_hours_per_week" name="max_hours_per_week" 
                                   value="{{ old('max_hours_per_week', 20) }}" min="1" max="168" required>
                            <div class="form-text">Nombre d'heures que vous pouvez consacrer par semaine</div>
                            @error('max_hours_per_week')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contact d'urgence -->
                        <div class="mb-4">
                            <label for="emergency_contact" class="form-label">Contact d'urgence <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="emergency_contact" name="emergency_contact" 
                                   value="{{ old('emergency_contact') }}" required>
                            <div class="form-text">Nom et numéro de téléphone d'une personne à contacter en cas d'urgence</div>
                            @error('emergency_contact')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Conditions médicales -->
                        <div class="mb-4">
                            <label for="medical_conditions" class="form-label">Conditions médicales</label>
                            <textarea class="form-control" id="medical_conditions" name="medical_conditions" rows="3">{{ old('medical_conditions') }}</textarea>
                            <div class="form-text">Informations médicales importantes (allergies, limitations, etc.)</div>
                            @error('medical_conditions')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bio -->
                        <div class="mb-4">
                            <label for="bio" class="form-label">Présentation <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="bio" name="bio" rows="4" required>{{ old('bio') }}</textarea>
                            <div class="form-text">Parlez-nous de vous en quelques mots</div>
                            @error('bio')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Motivation -->
                        <div class="mb-4">
                            <label for="motivation" class="form-label">Motivation <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="motivation" name="motivation" rows="4" required>{{ old('motivation') }}</textarea>
                            <div class="form-text">Pourquoi souhaitez-vous devenir volontaire ?</div>
                            @error('motivation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Expérience précédente -->
                        <div class="mb-4">
                            <label for="previous_volunteer_experience" class="form-label">Expérience de volontariat</label>
                            <textarea class="form-control" id="previous_volunteer_experience" name="previous_volunteer_experience" rows="3">{{ old('previous_volunteer_experience') }}</textarea>
                            <div class="form-text">Décrivez vos expériences de volontariat précédentes (optionnel)</div>
                            @error('previous_volunteer_experience')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('volunteers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check me-2"></i>Créer mon profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


