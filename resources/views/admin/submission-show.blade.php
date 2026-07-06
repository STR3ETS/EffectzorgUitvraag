@extends('layouts.admin')

@section('title', 'Inzending #' . $submission->id . ' — Uitvraag')

@section('content')
<div class="mb-6 flex items-center justify-between">
  <div>
    <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 hover:text-blue-800">&larr; Terug naar overzicht</a>
    <h1 class="text-2xl font-bold text-gray-900 mt-2">Inzending #{{ $submission->id }}</h1>
    <p class="text-gray-500 text-sm mt-1">
      Aangemaakt op {{ $submission->created_at->format('d-m-Y H:i') }}
      @if($submission->submitted_at)
        &middot; Laatst verstuurd op {{ $submission->submitted_at->format('d-m-Y H:i') }}
      @endif
      &middot; <a href="{{ url('/' . $submission->id) }}" class="text-blue-600 hover:underline" target="_blank">Formulier openen &rarr;</a>
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

{{-- Algemeen --}}
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

@php
  $answers = $submission->answers ?? [];
  $sections = $answers['sections'] ?? [];
@endphp

@forelse($sections as $section)
  <section class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-5">
      @if(!empty($section['id']))<span class="text-blue-600">{{ $section['id'] }}</span> &mdash; @endif{{ $section['title'] ?? '' }}
    </h2>

    @php $lastGroup = null; @endphp
    <div class="space-y-5">
      @foreach(($section['items'] ?? []) as $item)
        @php
          $group = $item['group'] ?? null;
          $type = $item['type'] ?? 'text';
          $label = $item['label'] ?? '';
          $value = $item['value'] ?? null;
        @endphp

        @if($group && $group !== $lastGroup)
          @php $lastGroup = $group; @endphp
          <h3 class="text-sm font-bold text-gray-900 pt-4 mt-2 border-t border-gray-100">{{ $group }}</h3>
        @endif

        <div>
          <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ $label ?: '—' }}</div>

          @switch($type)
            @case('checks')
              @if(!empty($value))
                <ul class="flex flex-wrap gap-2">
                  @foreach($value as $opt)
                    <li class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-800 rounded-full px-3 py-1 text-sm">
                      <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                      {{ $opt }}
                    </li>
                  @endforeach
                </ul>
              @else
                <p class="text-sm text-gray-400 italic">Niets aangevinkt</p>
              @endif
              @break

            @case('steps')
              @if(!empty($value))
                <ol class="space-y-2">
                  @foreach($value as $i => $step)
                    <li class="border border-gray-100 rounded-lg bg-gray-50 p-3">
                      <div class="text-xs font-semibold text-gray-500 mb-1.5">Stap {{ $i + 1 }}</div>
                      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div>
                          <div class="text-xs text-gray-500">{{ $step['actor_label'] ?? 'Gebruiker' }}</div>
                          <div class="text-gray-900 whitespace-pre-line">{{ ($step['actor'] ?? '') ?: '—' }}</div>
                        </div>
                        <div>
                          <div class="text-xs text-gray-500">{{ $step['system_label'] ?? 'Systeem' }}</div>
                          <div class="text-gray-900 whitespace-pre-line">{{ ($step['system'] ?? '') ?: '—' }}</div>
                        </div>
                      </div>
                    </li>
                  @endforeach
                </ol>
              @else
                <p class="text-sm text-gray-400 italic">Geen stappen ingevuld</p>
              @endif
              @break

            @case('scenarios')
              @if(!empty($value))
                <div class="space-y-2">
                  @foreach($value as $scen)
                    <div class="border border-gray-100 rounded-lg bg-gray-50 p-3 text-sm">
                      <div class="font-medium text-gray-900">{{ ($scen['name'] ?? '') ?: 'Scenario' }}</div>
                      <div class="text-gray-700 whitespace-pre-line mt-1">{{ ($scen['description'] ?? '') ?: '—' }}</div>
                    </div>
                  @endforeach
                </div>
              @else
                <p class="text-sm text-gray-400 italic">Geen scenario's ingevuld</p>
              @endif
              @break

            @case('files')
              @if(!empty($value))
                <div class="flex flex-wrap gap-3">
                  @foreach($value as $file)
                    <a href="{{ $file['preview'] ?? '#' }}" target="_blank" class="inline-flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-2 text-sm text-blue-600 hover:bg-gray-50">
                      @if(!empty($file['image']) && !empty($file['preview']))
                        <img src="{{ $file['preview'] }}" alt="" class="w-10 h-10 object-cover rounded">
                      @else
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                      @endif
                      {{ $file['name'] ?? 'Bestand' }}
                    </a>
                  @endforeach
                </div>
              @else
                <p class="text-sm text-gray-400 italic">Geen bestanden</p>
              @endif
              @break

            @default
              @if(is_array($value))
                <p class="text-sm text-gray-900 whitespace-pre-line">{{ implode(', ', $value) ?: '—' }}</p>
              @else
                <p class="text-sm text-gray-900 whitespace-pre-line">{{ ($value ?? '') !== '' ? $value : '—' }}</p>
              @endif
          @endswitch
        </div>
      @endforeach
    </div>
  </section>
@empty
  <section class="bg-white rounded-xl border border-gray-200 shadow-sm p-12 text-center mb-6">
    <p class="text-gray-500 text-sm">Deze inzending bevat nog geen antwoorden.</p>
  </section>
@endforelse

{{-- Alle bijlagen (volledige lijst uit opslag) --}}
@if($submission->fileUploads->isNotEmpty())
<section class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
  <h2 class="text-lg font-semibold text-gray-800 mb-4">Alle bijlagen</h2>
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
