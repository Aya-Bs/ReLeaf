@extends('backend.layouts.app')

@section('title', 'Gestion des Ressources')
@section('page-title', 'Gestion des Ressources')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item active">Ressources</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <!-- Statistiques & Graphiques des Ressources -->
            @php
               $allResources = \App\Models\Resource::select('status','resource_type','quantity_needed','quantity_pledged','created_at')->get();

               $totalResources = $allResources->count();
               $needed = $allResources->where('status','needed')->count();
               $pledged = $allResources->where('status','pledged')->count();
               $received = $allResources->where('status','received')->count();

               $byStatusR = $allResources->groupBy('status')->map->count();
               $byTypeR = $allResources->groupBy('resource_type')->map->count();

               $monthsR = collect(range(5,0))->map(fn($i)=>now()->subMonths($i)->format('Y-m'));
               $byMonthR = $monthsR->mapWithKeys(fn($m)=>[
                   $m => $allResources->filter(fn($r)=>optional($r->created_at)->format('Y-m') === $m)->count()
               ]);

               $totalNeededUnits = $allResources->sum('quantity_needed');
               $totalPledgedUnits = $allResources->sum('quantity_pledged');
            @endphp

            <div class="card mb-4">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">
                  <i class="fas fa-boxes me-2" style="color: var(--eco-green, #2d5a27)"></i>
                  Statistiques des Ressources
                </h3>
              </div>
              <div class="card-body">
                <div class="row g-2 mb-2">
                  <div class="col-md-2">
                    <div class="p-2 rounded-3 border text-center kpi-box" style="border-color:#e9ecef;">
                      <div class="text-eco fw-bold">Total</div>
                      <div class="fs-4 fw-bold">{{ number_format($totalResources) }}</div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="p-2 rounded-3 border text-center kpi-box" style="border-color:#e9ecef;">
                      <div class="text-eco fw-bold">Nécessaires</div>
                      <div class="fs-4 fw-bold">{{ number_format($needed) }}</div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="p-2 rounded-3 border text-center kpi-box" style="border-color:#e9ecef;">
                      <div class="text-eco fw-bold">Promises</div>
                      <div class="fs-4 fw-bold">{{ number_format($pledged) }}</div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="p-2 rounded-3 border text-center kpi-box" style="border-color:#e9ecef;">
                      <div class="text-eco fw-bold">Reçues</div>
                      <div class="fs-4 fw-bold">{{ number_format($received) }}</div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="p-2 rounded-3 border text-center kpi-box" style="border-color:#e9ecef;">
                      <div class="text-eco fw-bold">Besoin total</div>
                      <div class="fs-6 fw-bold">{{ number_format($totalNeededUnits, 0, ',', ' ') }}</div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="p-2 rounded-3 border text-center kpi-box" style="border-color:#e9ecef;">
                      <div class="text-eco fw-bold">Promis total</div>
                      <div class="fs-6 fw-bold">{{ number_format($totalPledgedUnits, 0, ',', ' ') }}</div>
                    </div>
                  </div>
                </div>

                <div class="row g-3">
                  <div class="col-lg-4">
                    <h6 class="text-eco fw-bold mb-2">Par Statut</h6>
                    <div class="chart-wrap"><canvas id="resStatusChart" height="120"></canvas></div>
                  </div>
                  <div class="col-lg-4">
                    <h6 class="text-eco fw-bold mb-2">Par Type</h6>
                    <div class="chart-wrap"><canvas id="resTypeChart" height="120"></canvas></div>
                  </div>
                  <div class="col-lg-4">
                    <h6 class="text-eco fw-bold mb-2">Créations (6 mois)</h6>
                    <div class="chart-wrap"><canvas id="resMonthlyChart" height="120"></canvas></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card card-eco">
                <div class="card-header">
                    <h3 class="card-title">Liste des ressources</h3>
                </div>

                <div class="card-body">
                    @if($resources->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nom</th>
                                    <th>Campagne</th>
                                    <th>Type</th>
                                    <th>Quantité</th>
                                    <th>Statut</th>
                                    <th>Priorité</th>
                                    <th>Progression</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resources as $resource)
                                <tr onclick="window.location='{{ route('backend.resources.show', $resource) }}'" style="cursor: pointer;">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($resource->image_url)
                                                <img src="{{ Storage::url($resource->image_url) }}" 
                                                     alt="{{ $resource->name }}" 
                                                     class="rounded me-3"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-box text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $resource->name }}</strong>
                                                @if($resource->provider)
                                                    <br><small class="text-muted">Fournisseur: {{ $resource->provider }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($resource->campaign)
                                            {{ $resource->campaign->name }}
                                        @else
                                            <span class="text-muted">Aucune</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($resource->resource_type)
                                            @case('money')
                                                <span class="badge badge-success">💰 Argent</span>
                                                @break
                                            @case('food')
                                                <span class="badge badge-warning">🍕 Nourriture</span>
                                                @break
                                            @case('clothing')
                                                <span class="badge badge-info">👕 Vêtements</span>
                                                @break
                                            @case('medical')
                                                <span class="badge badge-danger">🏥 Médical</span>
                                                @break
                                            @case('equipment')
                                                <span class="badge badge-primary">🔧 Équipement</span>
                                                @break
                                            @case('human')
                                                <span class="badge badge-secondary">👥 Main d'œuvre</span>
                                                @break
                                            @default
                                                <span class="badge badge-light">📦 Autre</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $resource->quantity_pledged }} / {{ $resource->quantity_needed }} {{ $resource->unit }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($resource->status === 'needed')
                                            <span class="badge badge-warning">Nécessaire</span>
                                        @elseif($resource->status === 'pledged')
                                            <span class="badge badge-info">Promis</span>
                                        @elseif($resource->status === 'received')
                                            <span class="badge badge-success">Reçu</span>
                                        @elseif($resource->status === 'in_use')
                                            <span class="badge badge-primary">En utilisation</span>
                                        @else
                                            <span class="badge badge-light">{{ $resource->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($resource->priority === 'urgent')
                                            <span class="badge badge-danger">Urgent</span>
                                        @elseif($resource->priority === 'high')
                                            <span class="badge badge-warning">Haute</span>
                                        @elseif($resource->priority === 'medium')
                                            <span class="badge badge-info">Moyenne</span>
                                        @elseif($resource->priority === 'low')
                                            <span class="badge badge-success">Basse</span>
                                        @else
                                            <span class="badge badge-light">{{ $resource->priority }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                <div class="progress-bar bg-success" 
                                                     role="progressbar" 
                                                     style="width: {{ $resource->progress_percentage }}%"
                                                     aria-valuenow="{{ $resource->progress_percentage }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ $resource->progress_percentage }}%</small>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $resources->links() }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-box fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Aucune ressource trouvée</h4>
                        <p class="text-muted">Aucune ressource n'a été créée pour le moment.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .text-eco { color: var(--eco-green, #2d5a27) !important; }
    .kpi-box .text-eco { font-size: .75rem; text-transform: uppercase; letter-spacing: .04em; }
    .kpi-box .fs-4 { font-size: 1.25rem !important; }

    /* Charts compacts */
    .chart-wrap { height: 160px; }
    .chart-wrap canvas { max-height: 160px !important; width: 100% !important; }

    /* Table moderne (aligné sur campagnes) */
    .table-modern {
        margin: 0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }
    .table-modern thead th {
        background: #f8f9fa;
        border: none;
        padding: 0.75rem 1.2rem;
        font-weight: 500;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #2d5a27;
    }
    .table-modern tbody tr {
        cursor: pointer;
        transition: all 0.2s ease;
        border-bottom: 1px solid #f0f0f0;
        background: #fff;
    }
    .table-modern tbody tr:hover {
        background: #f4f8f4;
        transform: scale(1.01);
    }
    .table-modern tbody td {
        padding: 0.85rem 1.2rem;
        vertical-align: middle;
        border: none;
        font-size: 13px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function(){
  const byStatus = @json($byStatusR ?? []);
  const byType = @json($byTypeR ?? []);
  const months = @json($monthsR ?? collect());
  const byMonth = @json($byMonthR ?? []);
  const eco = getComputedStyle(document.documentElement).getPropertyValue('--eco-green') || '#2d5a27';
  const ecoLight = getComputedStyle(document.documentElement).getPropertyValue('--eco-light-green') || '#4a7c59';
  const palette = ['#2d5a27','#4a7c59','#6c757d','#17a2b8','#ffc107','#dc3545','#20c997'];

  const sCtx = document.getElementById('resStatusChart');
  if(sCtx){
    new Chart(sCtx,{
      type:'doughnut',
      data:{ labels:Object.keys(byStatus), datasets:[{ data:Object.values(byStatus), backgroundColor:palette.slice(0,Object.keys(byStatus).length), borderWidth:0 }] },
      options:{ plugins:{ legend:{ position:'bottom' } }, responsive:true, maintainAspectRatio:false }
    });
  }

  const tCtx = document.getElementById('resTypeChart');
  if(tCtx){
    new Chart(tCtx,{
      type:'bar',
      data:{ labels:Object.keys(byType).map(x=>titleCase(x)), datasets:[{ label:'Ressources', data:Object.values(byType), backgroundColor:eco }] },
      options:{ plugins:{ legend:{ display:false } }, scales:{ x:{ grid:{ display:false } }, y:{ beginAtZero:true, ticks:{ precision:0 } } }, responsive:true, maintainAspectRatio:false }
    });
  }

  const mCtx = document.getElementById('resMonthlyChart');
  if(mCtx){
    new Chart(mCtx,{
      type:'line',
      data:{ labels:Object.keys(byMonth).map(m=>m), datasets:[{ label:'Créations', data:Object.values(byMonth), borderColor:eco, backgroundColor:ecoLight, tension:.25, fill:true, pointRadius:3, pointBackgroundColor:eco }] },
      options:{ plugins:{ legend:{ display:false } }, scales:{ x:{ grid:{ display:false } }, y:{ beginAtZero:true, ticks:{ precision:0 } } }, responsive:true, maintainAspectRatio:false }
    });
  }

  function titleCase(s){return (s||'').toString().replace(/_/g,' ').replace(/\w\S*/g, t => t.charAt(0).toUpperCase() + t.substr(1).toLowerCase());}
})();
</script>
@endpush

@endsection