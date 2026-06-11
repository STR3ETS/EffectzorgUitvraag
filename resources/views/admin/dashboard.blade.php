@extends('layouts.admin')

@section('title', 'Dashboard — Effectzorg Uitvraag')

@section('content')
<div class="mb-8">
  <h1 class="text-2xl font-bold text-gray-900">Inzendingen</h1>
  <p class="text-gray-500 mt-1">Overzicht van alle ingevulde uitvragen.</p>
</div>

@if($submissions->isEmpty())
  <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
    <div class="text-gray-400 text-5xl mb-4">&#128203;</div>
    <h3 class="text-lg font-semibold text-gray-700 mb-2">Nog geen inzendingen</h3>
    <p class="text-gray-500 text-sm">Zodra iemand het formulier invult en verstuurt, verschijnt het hier.</p>
  </div>
@else
  <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
          <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Datum</th>
          <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Project</th>
          <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contactpersoon</th>
          <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Collega's</th>
          <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
          <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Verstuurd</th>
          <th class="px-6 py-3"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        @foreach($submissions as $sub)
        <tr class="hover:bg-gray-50 transition">
          <td class="px-6 py-4 text-sm text-gray-500">{{ $sub->id }}</td>
          <td class="px-6 py-4 text-sm text-gray-700">{{ $sub->datum ?: '—' }}</td>
          <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $sub->project_name ?: '—' }}</td>
          <td class="px-6 py-4 text-sm text-gray-700">{{ $sub->contactpersoon ?: '—' }}</td>
          <td class="px-6 py-4 text-sm text-gray-500">
            <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 rounded-full px-2.5 py-0.5 text-xs font-medium">
              {{ $sub->colleagues_count }}
            </span>
          </td>
          <td class="px-6 py-4 text-sm">
            @if($sub->status === 'submitted')
              <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 rounded-full px-2.5 py-0.5 text-xs font-medium">Verstuurd</span>
            @else
              <span class="inline-flex items-center gap-1 bg-yellow-50 text-yellow-700 rounded-full px-2.5 py-0.5 text-xs font-medium">Concept</span>
            @endif
          </td>
          <td class="px-6 py-4 text-sm text-gray-500">{{ $sub->submitted_at ? $sub->submitted_at->format('d-m-Y H:i') : '—' }}</td>
          <td class="px-6 py-4 text-right">
            <a href="{{ route('admin.submissions.show', $sub) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">Bekijken &rarr;</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-6">
    {{ $submissions->links() }}
  </div>
@endif
@endsection
