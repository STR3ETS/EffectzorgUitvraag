@extends('layouts.admin')

@section('title', 'Inzending #' . $submission->id . ' — Effectzorg Uitvraag')

@section('content')
<div class="mb-6 flex items-center justify-between">
  <div>
    <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 hover:text-blue-800">&larr; Terug naar overzicht</a>
    <h1 class="text-2xl font-bold text-gray-900 mt-2">Inzending #{{ $submission->id }}</h1>
    <p class="text-gray-500 text-sm mt-1">
      Aangemaakt op {{ $submission->created_at->format('d-m-Y H:i') }}
      @if($submission->submitted_at)
        &middot; Verstuurd op {{ $submission->submitted_at->format('d-m-Y H:i') }}
      @endif
    </p>
  </div>
  <div>
    @if($submission->status === 'submitted')
      <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 rounded-full px-3 py-1 text-sm font-medium">Verstuurd</span>
    @else
      <span class="inline-flex items-center gap-1 bg-yellow-50 text-yellow-700 rounded-full px-3 py-1 text-sm font-medium">Concept</span>
    @endif
  </div>
</div>

{{-- Cover --}}
<section class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
  <h2 class="text-lg font-semibold text-gray-800 mb-4">Algemeen</h2>
  <dl class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div>
      <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Datum</dt>
      <dd class="mt-1 text-sm text-gray-900">{{ $submission->datum ?: '—' }}</dd>
    </div>
    <div>
      <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Projectnaam</dt>
      <dd class="mt-1 text-sm text-gray-900">{{ $submission->project_name ?: '—' }}</dd>
    </div>
    <div>
      <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Contactpersoon</dt>
      <dd class="mt-1 text-sm text-gray-900">{{ $submission->contactpersoon ?: '—' }}</dd>
    </div>
  </dl>
</section>

{{-- Sectie A: Systeemtoegang --}}
<section class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
  <h2 class="text-lg font-semibold text-gray-800 mb-4">A &mdash; Systeemtoegang</h2>
  @if($submission->systemAccesses->isEmpty())
    <p class="text-sm text-gray-400 italic">Geen systeemtoegang ingevuld.</p>
  @else
    <div class="space-y-4">
      @foreach($submission->systemAccesses as $sa)
        <div class="border border-gray-100 rounded-lg p-4 bg-gray-50">
          <h3 class="font-medium text-gray-900 mb-3">{{ $sa->system_name }}</h3>
          <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 text-sm">
            <div>
              <dt class="text-xs font-semibold text-gray-500 uppercase">Actie</dt>
              <dd class="mt-0.5 text-gray-900">{{ $sa->action ?: '—' }}</dd>
            </div>
            <div>
              <dt class="text-xs font-semibold text-gray-500 uppercase">Testaccount</dt>
              <dd class="mt-0.5 text-gray-900">{{ $sa->test_account ?: '—' }}</dd>
            </div>
            <div>
              <dt class="text-xs font-semibold text-gray-500 uppercase">Demo URL</dt>
              <dd class="mt-0.5 text-gray-900">
                @if($sa->demo_url)
                  <a href="{{ $sa->demo_url }}" target="_blank" class="text-blue-600 hover:underline">{{ $sa->demo_url }}</a>
                @else
                  —
                @endif
              </dd>
            </div>
            <div>
              <dt class="text-xs font-semibold text-gray-500 uppercase">Contactpersoon toegang</dt>
              <dd class="mt-0.5 text-gray-900">{{ $sa->access_contact ?: '—' }}</dd>
            </div>
            <div>
              <dt class="text-xs font-semibold text-gray-500 uppercase">Modules</dt>
              <dd class="mt-0.5 text-gray-900">{{ $sa->modules ?: '—' }}</dd>
            </div>
            <div>
              <dt class="text-xs font-semibold text-gray-500 uppercase">Opmerkingen</dt>
              <dd class="mt-0.5 text-gray-900">{{ $sa->remarks ?: '—' }}</dd>
            </div>
          </dl>
        </div>
      @endforeach
    </div>
  @endif
</section>

{{-- Sectie B: Collega's --}}
<section class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
  <h2 class="text-lg font-semibold text-gray-800 mb-4">B &mdash; Collega's</h2>
  @if($submission->colleagues->isEmpty())
    <p class="text-sm text-gray-400 italic">Geen collega's ingevuld.</p>
  @else
    <div class="space-y-6">
      @foreach($submission->colleagues as $colleague)
        <div class="border border-gray-200 rounded-lg overflow-hidden">
          <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
            <h3 class="font-medium text-gray-900">{{ $colleague->name ?: 'Collega ' . ($loop->index + 1) }}</h3>
            @if($colleague->function)
              <p class="text-xs text-gray-500 mt-0.5">{{ $colleague->function }}</p>
            @endif
          </div>
          <div class="p-4 space-y-4">
            {{-- Wensen, pijnpunten, gewenste workflow --}}
            <dl class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
              <div>
                <dt class="text-xs font-semibold text-gray-500 uppercase">Wensen</dt>
                <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $colleague->wishes ?: '—' }}</dd>
              </div>
              <div>
                <dt class="text-xs font-semibold text-gray-500 uppercase">Pijnpunten</dt>
                <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $colleague->pain_points ?: '—' }}</dd>
              </div>
              <div>
                <dt class="text-xs font-semibold text-gray-500 uppercase">Gewenste workflow</dt>
                <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $colleague->desired_workflow ?: '—' }}</dd>
              </div>
            </dl>

            {{-- Handmatige taken --}}
            @if($colleague->manualTasks->isNotEmpty())
              <div>
                <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">Handmatige taken</h4>
                <div class="overflow-x-auto">
                  <table class="min-w-full text-sm border border-gray-100 rounded">
                    <thead class="bg-gray-50">
                      <tr>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500">Omschrijving</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500">Frequentie</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500">Tijd per keer</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                      @foreach($colleague->manualTasks as $task)
                        <tr>
                          <td class="px-3 py-2 text-gray-900">{{ $task->description ?: '—' }}</td>
                          <td class="px-3 py-2 text-gray-700">{{ $task->frequency ?: '—' }}</td>
                          <td class="px-3 py-2 text-gray-700">{{ $task->time_per_task ?: '—' }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            @endif

            {{-- Ontbrekende features --}}
            @if($colleague->missingFeatures->isNotEmpty())
              <div>
                <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">Ontbrekende features</h4>
                <div class="overflow-x-auto">
                  <table class="min-w-full text-sm border border-gray-100 rounded">
                    <thead class="bg-gray-50">
                      <tr>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500">Systeem</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500">Omschrijving</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500">Workaround</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500">Extra tijd</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                      @foreach($colleague->missingFeatures as $mf)
                        <tr>
                          <td class="px-3 py-2 text-gray-900">{{ $mf->system_name ?: '—' }}</td>
                          <td class="px-3 py-2 text-gray-700">{{ $mf->description ?: '—' }}</td>
                          <td class="px-3 py-2 text-gray-700">{{ $mf->workaround ?: '—' }}</td>
                          <td class="px-3 py-2 text-gray-700">{{ $mf->extra_time ?: '—' }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            @endif

            {{-- Bestanden --}}
            @if($colleague->fileUploads->isNotEmpty())
              <div>
                <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">Bestanden</h4>
                <ul class="space-y-1">
                  @foreach($colleague->fileUploads as $file)
                    <li class="text-sm text-blue-600">
                      <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="hover:underline">{{ $file->original_name }}</a>
                      <span class="text-gray-400 text-xs">({{ number_format($file->file_size / 1024, 0) }} KB)</span>
                    </li>
                  @endforeach
                </ul>
              </div>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  @endif
</section>

{{-- Sectie C: Externe gebruikers --}}
<section class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
  <h2 class="text-lg font-semibold text-gray-800 mb-4">C &mdash; Externe gebruikers</h2>
  <div class="space-y-6">
    <div>
      <h3 class="text-sm font-semibold text-gray-700 mb-3">Kandidaten</h3>
      <dl class="space-y-3 text-sm">
        <div>
          <dt class="text-xs font-semibold text-gray-500 uppercase">Mogelijkheden</dt>
          <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $submission->candidates_capabilities ?: '—' }}</dd>
        </div>
        <div>
          <dt class="text-xs font-semibold text-gray-500 uppercase">Matching</dt>
          <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $submission->candidates_matching ?: '—' }}</dd>
        </div>
        <div>
          <dt class="text-xs font-semibold text-gray-500 uppercase">Mobiel / App</dt>
          <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $submission->candidates_mobile ?: '—' }}</dd>
        </div>
      </dl>
    </div>
    <hr class="border-gray-100">
    <div>
      <h3 class="text-sm font-semibold text-gray-700 mb-3">Opdrachtgevers</h3>
      <dl class="space-y-3 text-sm">
        <div>
          <dt class="text-xs font-semibold text-gray-500 uppercase">Mogelijkheden</dt>
          <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $submission->employers_capabilities ?: '—' }}</dd>
        </div>
        <div>
          <dt class="text-xs font-semibold text-gray-500 uppercase">Aanvragen</dt>
          <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $submission->employers_requesting ?: '—' }}</dd>
        </div>
        <div>
          <dt class="text-xs font-semibold text-gray-500 uppercase">Portaal</dt>
          <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $submission->employers_portal ?: '—' }}</dd>
        </div>
      </dl>
    </div>
  </div>
</section>

{{-- Sectie D: Scope, Data & Compliance --}}
<section class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
  <h2 class="text-lg font-semibold text-gray-800 mb-4">D &mdash; Scope, Data &amp; Compliance</h2>
  <div class="space-y-6">
    <div>
      <h3 class="text-sm font-semibold text-gray-700 mb-3">Scope</h3>
      <dl class="space-y-3 text-sm">
        <div>
          <dt class="text-xs font-semibold text-gray-500 uppercase">Vervangen of koppelen</dt>
          <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $submission->scope_replace_or_connect ?: '—' }}</dd>
        </div>
        <div>
          <dt class="text-xs font-semibold text-gray-500 uppercase">MVP</dt>
          <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $submission->scope_mvp ?: '—' }}</dd>
        </div>
        <div>
          <dt class="text-xs font-semibold text-gray-500 uppercase">Budget &amp; deadline</dt>
          <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $submission->scope_budget_deadline ?: '—' }}</dd>
        </div>
        <div>
          <dt class="text-xs font-semibold text-gray-500 uppercase">Beslisser</dt>
          <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $submission->scope_decision_maker ?: '—' }}</dd>
        </div>
      </dl>
    </div>
    <hr class="border-gray-100">
    <div>
      <h3 class="text-sm font-semibold text-gray-700 mb-3">Data</h3>
      <dl class="space-y-3 text-sm">
        <div>
          <dt class="text-xs font-semibold text-gray-500 uppercase">Huidige systemen</dt>
          <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $submission->data_current_systems ?: '—' }}</dd>
        </div>
        <div>
          <dt class="text-xs font-semibold text-gray-500 uppercase">Migratie</dt>
          <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $submission->data_migration ?: '—' }}</dd>
        </div>
        <div>
          <dt class="text-xs font-semibold text-gray-500 uppercase">API-integraties</dt>
          <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $submission->data_api_integrations ?: '—' }}</dd>
        </div>
      </dl>
    </div>
    <hr class="border-gray-100">
    <div>
      <h3 class="text-sm font-semibold text-gray-700 mb-3">Compliance</h3>
      <dl class="space-y-3 text-sm">
        <div>
          <dt class="text-xs font-semibold text-gray-500 uppercase">Controles</dt>
          <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $submission->compliance_checks ?: '—' }}</dd>
        </div>
        <div>
          <dt class="text-xs font-semibold text-gray-500 uppercase">Privacy</dt>
          <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $submission->compliance_privacy ?: '—' }}</dd>
        </div>
        <div>
          <dt class="text-xs font-semibold text-gray-500 uppercase">Wat werkt goed</dt>
          <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $submission->compliance_working_well ?: '—' }}</dd>
        </div>
        <div>
          <dt class="text-xs font-semibold text-gray-500 uppercase">Cijfers &amp; succes</dt>
          <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $submission->compliance_numbers_success ?: '—' }}</dd>
        </div>
      </dl>
    </div>
  </div>
</section>

{{-- Sectie E: Overall --}}
<section class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
  <h2 class="text-lg font-semibold text-gray-800 mb-4">E &mdash; Overall</h2>
  <dl class="space-y-3 text-sm">
    <div>
      <dt class="text-xs font-semibold text-gray-500 uppercase">Workflows</dt>
      <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $submission->overall_workflows ?: '—' }}</dd>
    </div>
    <div>
      <dt class="text-xs font-semibold text-gray-500 uppercase">Opmerkingen</dt>
      <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $submission->overall_remarks ?: '—' }}</dd>
    </div>
  </dl>
</section>

{{-- Bijlagen op submission-niveau --}}
@if($submission->fileUploads->isNotEmpty())
<section class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
  <h2 class="text-lg font-semibold text-gray-800 mb-4">Bijlagen</h2>
  <ul class="space-y-1">
    @foreach($submission->fileUploads as $file)
      <li class="text-sm text-blue-600">
        <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="hover:underline">{{ $file->original_name }}</a>
        <span class="text-gray-400 text-xs">({{ number_format($file->file_size / 1024, 0) }} KB)</span>
      </li>
    @endforeach
  </ul>
</section>
@endif
@endsection
