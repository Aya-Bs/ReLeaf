@extends('layouts.frontend')

@section('title', 'EcoEvents - Sélection des places')

@section('content')
<style>
/* Container principal style moderne et clair */
.cinema-container {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
}

/* Header style cinéma */
.cinema-header {
    background: linear-gradient(135deg, #2d5a27 0%, #1e3c1a 100%);
    padding: 30px 0;
    border-bottom: 3px solid #ffd700;
}

.event-details {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-top: 10px;
}

.event-details span {
    color: rgba(255, 255, 255, 0.9);
    font-size: 14px;
    padding: 5px 10px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 15px;
    backdrop-filter: blur(10px);
}

.organizer-badge {
    background: rgba(255, 215, 0, 0.2);
    color: #ffd700;
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 14px;
    border: 1px solid rgba(255, 215, 0, 0.3);
}

/* Section principale */
.cinema-main {
    padding: 40px 0;
    background: #ffffff;
    border-radius: 20px;
    margin: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

/* Légende */
.seat-legend-container {
    display: flex;
    justify-content: center;
    gap: 30px;
    padding: 20px;
    background: rgba(248, 249, 250, 0.8);
    border-radius: 15px;
    border: 1px solid #e9ecef;
    backdrop-filter: blur(10px);
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #495057;
    font-size: 14px;
    font-weight: 500;
}

.seat-mini {
    width: 20px;
    height: 20px;
    border-radius: 6px;
    border: 1px solid;
}

.seat-mini.available {
    background: #28a745;
    border-color: #28a745;
}

.seat-mini.reserved {
    background: #dc3545;
    border-color: #dc3545;
}

.seat-mini.locked {
    background: #ff8c00;
    border-color: #ff8c00;
}

.seat-mini.selected {
    background: #ffd700;
    border-color: #ffd700;
}


/* Grille des sièges */
.seats-grid {
    max-width: 400px;
    margin: 0 auto;
}

.seat-row {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.row-label {
    color: #2d5a27;
    font-weight: bold;
    font-size: 18px;
    width: 30px;
    text-align: center;
    background: rgba(45, 90, 39, 0.1);
    border-radius: 50%;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.seats-section {
    display: flex;
    gap: 20px;
    justify-content: center;
}

/* Sièges style moderne */
.cinema-seat {
    width: 60px;
    height: 60px;
    border: 2px solid #e9ecef;
    border-radius: 15px;
    position: relative;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    background: linear-gradient(145deg, #ffffff, #f8f9fa);
    box-shadow: 
        0 4px 8px rgba(0, 0, 0, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.8);
}

.cinema-seat:before {
    content: '';
    position: absolute;
    top: -5px;
    left: 10%;
    right: 10%;
    height: 8px;
    background: inherit;
    border-radius: 8px 8px 0 0;
}

.cinema-seat .seat-number {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #495057;
    font-weight: bold;
    font-size: 16px;
}

/* États des sièges */
.cinema-seat.available {
    background: linear-gradient(145deg, #28a745, #1e7e34);
    color: white;
    border-color: #28a745;
}

.cinema-seat.available:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 
        0 8px 25px rgba(40, 167, 69, 0.4),
        inset 0 1px 0 rgba(255, 255, 255, 0.2);
}

.cinema-seat.reserved {
    background: linear-gradient(145deg, #dc3545, #c82333);
    cursor: not-allowed;
    opacity: 0.7;
    border-color: #dc3545;
}

.cinema-seat.locked {
    background: linear-gradient(145deg, #ff8c00, #ff6500);
    cursor: not-allowed;
    opacity: 0.8;
    border-color: #ff8c00;
    animation: pulse-locked 2s infinite;
}

@keyframes pulse-locked {
    0%, 100% { opacity: 0.8; }
    50% { opacity: 0.6; }
}

.cinema-seat.selected {
    background: linear-gradient(145deg, #ffd700, #ffb700);
    color: #000;
    border-color: #ffd700;
    transform: translateY(-5px) scale(1.1);
    box-shadow: 
        0 10px 30px rgba(255, 215, 0, 0.5),
        inset 0 1px 0 rgba(255, 255, 255, 0.3);
}

/* Panel de réservation */
.reservation-panel {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(135deg, #2d5a27 0%, #1e3c1a 100%);
    border-top: 3px solid #ffd700;
    padding: 25px;
    box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(10px);
    z-index: 1000;
    animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
    from {
        transform: translateY(100%);
    }
    to {
        transform: translateY(0);
    }
}

.selected-seat-info h5 {
    color: #ffd700;
    margin-bottom: 20px;
    text-align: center;
}

.reservation-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 20px;
}

.btn-eco {
    background: linear-gradient(135deg, #ffd700 0%, #ffb700 100%);
    border: none;
    color: #000;
    font-weight: bold;
    padding: 12px 30px;
    border-radius: 25px;
    transition: all 0.3s ease;
}

.btn-eco:hover {
    background: linear-gradient(135deg, #ffb700 0%, #ff8f00 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 215, 0, 0.4);
    color: #000;
}

.btn-outline-secondary {
    background: transparent;
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: #fff;
    padding: 12px 30px;
    border-radius: 25px;
    transition: all 0.3s ease;
}

.btn-outline-secondary:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.5);
    color: #fff;
}

/* Responsive */
@media (max-width: 768px) {
    .cinema-main {
        margin: 10px;
        padding: 20px 0;
    }
    
    .event-details {
        gap: 10px;
    }
    
    .seat-legend-container {
        gap: 15px;
        padding: 15px;
        flex-wrap: wrap;
    }
    
    .cinema-seat {
        width: 50px;
        height: 50px;
    }
    
    .cinema-seat .seat-number {
        font-size: 14px;
    }
    
    .reservation-panel {
        padding: 20px 15px;
    }
    
    .reservation-actions {
        flex-direction: column;
    }
    
    .reservation-actions .btn {
        width: 100%;
    }
    
    .legend-item {
        font-size: 12px;
    }
}
</style>
<div class="cinema-container">
    <!-- Header avec informations de l'événement -->
    <div class="cinema-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="text-white mb-1">{{ $event->title }}</h2>
                    <div class="event-details">
                        <span><i class="fas fa-calendar me-1"></i>{{ $event->date->format('d/m/Y à H:i') }}</span>
                        <span><i class="fas fa-map-marker-alt me-1"></i>{{ $event->location->name ?? 'Lieu non défini' }}</span>
                        <span><i class="fas fa-users me-1"></i>{{ 3 - count($reservedSeats) }}/3 places disponibles</span>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <span class="organizer-badge">
                        <i class="fas fa-user-tie me-1"></i>{{ $event->user->name }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if($userReservation)
        <div class="container py-4">
            <div class="alert alert-info text-center">
                <h5><i class="fas fa-check-circle me-2"></i>Réservation existante</h5>
                <p class="mb-2">Place <strong>{{ $userReservation->seat_number }}</strong> réservée</p>
                <span class="badge bg-{{ $userReservation->status === 'confirmed' ? 'success' : ($userReservation->status === 'pending' ? 'warning' : 'secondary') }} fs-6">
                    {{ ucfirst($userReservation->status) }}
                </span>
            </div>
        </div>
    @else
        <!-- Cinema Layout -->
        <div class="cinema-main">
            <div class="container">
                
                <!-- Légende -->
                <div class="seat-legend-container mb-4">
                    <div class="legend-item">
                        <div class="seat-mini available"></div>
                        <span>Disponible</span>
                    </div>
                    <div class="legend-item">
                        <div class="seat-mini reserved"></div>
                        <span>Occupé</span>
                    </div>
                    <div class="legend-item">
                        <div class="seat-mini locked"></div>
                        <span>Bloqué</span>
                    </div>
                    <div class="legend-item">
                        <div class="seat-mini selected"></div>
                        <span>Sélectionné</span>
                    </div>
                </div>


                <!-- Plan des sièges style cinéma -->
                <div class="seats-grid">
                    <!-- 3 places seulement -->
                    <div class="seat-row">
                        <span class="row-label">A</span>
                        <div class="seats-section">
                            @php
                                $seatA1Status = in_array('A1', $reservedSeats) ? 'reserved' : (in_array('A1', $lockedSeats) ? 'locked' : 'available');
                                $seatA2Status = in_array('A2', $reservedSeats) ? 'reserved' : (in_array('A2', $lockedSeats) ? 'locked' : 'available');
                                $seatA3Status = in_array('A3', $reservedSeats) ? 'reserved' : (in_array('A3', $lockedSeats) ? 'locked' : 'available');
                            @endphp
                            
                            <button type="button" 
                                    class="cinema-seat {{ $seatA1Status }}"
                                    data-seat="A1"
                                    {{ $seatA1Status !== 'available' ? 'disabled' : '' }}>
                                <span class="seat-number">1</span>
                            </button>
                            <button type="button" 
                                    class="cinema-seat {{ $seatA2Status }}"
                                    data-seat="A2"
                                    {{ $seatA2Status !== 'available' ? 'disabled' : '' }}>
                                <span class="seat-number">2</span>
                            </button>
                            <button type="button" 
                                    class="cinema-seat {{ $seatA3Status }}"
                                    data-seat="A3"
                                    {{ $seatA3Status !== 'available' ? 'disabled' : '' }}>
                                <span class="seat-number">3</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Formulaire de réservation flottant -->
                <div class="reservation-panel" id="reservationPanel" style="display: none;">
                    <form id="reservationForm" method="POST" action="{{ route('reservations.store', $event) }}">
                        @csrf
                        <input type="hidden" name="seat_number" id="selectedSeat">
                        
                        <div class="selected-seat-info">
                            <h5><i class="fas fa-ticket-alt me-2"></i>Place <span id="selectedSeatDisplay">-</span></h5>
                        </div>

                        <input type="hidden" name="num_guests" value="1">
                        <input type="hidden" name="comments" value="">

                        <div class="reservation-actions">
                            <button type="button" class="btn btn-outline-secondary" onclick="cancelSelection()">
                                Annuler
                            </button>
                            <button type="submit" class="btn btn-eco">
                                <i class="fas fa-check me-2"></i>Réserver
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const eventId = {{ $event->id }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const seats = document.querySelectorAll('.cinema-seat.available');
    const selectedSeatInput = document.getElementById('selectedSeat');
    const selectedSeatDisplay = document.getElementById('selectedSeatDisplay');
    const reservationPanel = document.getElementById('reservationPanel');
    let selectedSeat = null;
    let lockTimer = null;

    // Fonction pour mettre à jour le statut des places
    function updateSeatsStatus() {
        fetch(`/ajax/event/${eventId}/seats-status`)
            .then(response => response.json())
            .then(data => {
                // Mettre à jour l'affichage de toutes les places
                document.querySelectorAll('.cinema-seat').forEach(seat => {
                    const seatNumber = seat.dataset.seat;
                    
                    // Réinitialiser les classes
                    seat.classList.remove('available', 'reserved', 'locked', 'selected');
                    seat.disabled = false;
                    
                    if (data.reserved_seats.includes(seatNumber)) {
                        seat.classList.add('reserved');
                        seat.disabled = true;
                    } else if (data.locked_seats.includes(seatNumber)) {
                        seat.classList.add('locked');
                        seat.disabled = true;
                    } else {
                        seat.classList.add('available');
                    }
                });
                
                // Restaurer la sélection de l'utilisateur actuel
                if (data.user_lock && data.user_lock.seat_number) {
                    const userSeat = document.querySelector(`[data-seat="${data.user_lock.seat_number}"]`);
                    if (userSeat) {
                        userSeat.classList.remove('available', 'locked');
                        userSeat.classList.add('selected');
                        userSeat.disabled = false;
                        selectedSeat = userSeat;
                        selectedSeatInput.value = data.user_lock.seat_number;
                        selectedSeatDisplay.textContent = data.user_lock.seat_number;
                        reservationPanel.style.display = 'block';
                    }
                }
            })
            .catch(error => console.error('Erreur lors de la mise à jour:', error));
    }

    // Fonction pour verrouiller une place
    function lockSeat(seatNumber) {
        return fetch('/ajax/seat/lock', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                event_id: eventId,
                seat_number: seatNumber
            })
        }).then(response => response.json());
    }

    // Fonction pour libérer une place
    function releaseSeat(seatNumber) {
        return fetch('/ajax/seat/release', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                event_id: eventId,
                seat_number: seatNumber
            })
        }).then(response => response.json());
    }

    // Gestionnaire de clic sur les places
    seats.forEach(seat => {
        seat.addEventListener('click', function() {
            const seatNumber = this.dataset.seat;
            
            // Si une place est déjà sélectionnée, la libérer
            if (selectedSeat && selectedSeat !== this) {
                const oldSeatNumber = selectedSeat.dataset.seat;
                selectedSeat.classList.remove('selected');
                releaseSeat(oldSeatNumber);
            }

            // Verrouiller la nouvelle place
            lockSeat(seatNumber)
                .then(data => {
                    if (data.success) {
                        // Sélectionner le nouveau siège
                        this.classList.remove('available');
                        this.classList.add('selected');
                        selectedSeat = this;

                        selectedSeatInput.value = seatNumber;
                        selectedSeatDisplay.textContent = seatNumber;
                        
                        // Afficher le panel de réservation
                        reservationPanel.style.display = 'block';
                        
                        // Programmer la libération automatique après 5 minutes
                        if (lockTimer) clearTimeout(lockTimer);
                        lockTimer = setTimeout(() => {
                            cancelSelection();
                            alert('Votre sélection a expiré. Veuillez sélectionner une nouvelle place.');
                            updateSeatsStatus();
                        }, 5 * 60 * 1000); // 5 minutes
                        
                    } else {
                        alert(data.message || 'Cette place n\'est pas disponible.');
                        updateSeatsStatus();
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors de la sélection de la place.');
                    updateSeatsStatus();
                });
        });
    });

    // Validation du formulaire
    document.getElementById('reservationForm').addEventListener('submit', function(e) {
        if (!selectedSeatInput.value) {
            e.preventDefault();
            alert('Veuillez sélectionner une place.');
        }
    });

    // Mise à jour périodique du statut des places (toutes les 10 secondes)
    setInterval(updateSeatsStatus, 10000);
    
    // Mise à jour initiale
    updateSeatsStatus();
});

// Fonction pour annuler la sélection
function cancelSelection() {
    const selectedSeat = document.querySelector('.cinema-seat.selected');
    const reservationPanel = document.getElementById('reservationPanel');
    
    if (selectedSeat) {
        const seatNumber = selectedSeat.dataset.seat;
        
        // Libérer la place côté serveur
        fetch('/ajax/seat/release', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                event_id: {{ $event->id }},
                seat_number: seatNumber
            })
        });
        
        selectedSeat.classList.remove('selected');
        selectedSeat.classList.add('available');
    }
    
    reservationPanel.style.display = 'none';
    document.getElementById('selectedSeat').value = '';
}
</script>
@endsection
