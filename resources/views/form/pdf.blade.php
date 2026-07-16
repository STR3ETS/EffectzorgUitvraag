@php
    $answers  = $submission->answers ?? [];
    $sections = $answers['sections'] ?? [];

    // Returns true when an item holds any real answer (so empty questions are skipped).
    $hasContent = function (array $item): bool {
        $type = $item['type'] ?? 'text';
        $value = $item['value'] ?? null;
        if ($type === 'checks') {
            return !empty($value);
        }
        if ($type === 'steps') {
            foreach (($value ?? []) as $s) {
                if (trim(($s['actor'] ?? '') . ($s['system'] ?? '')) !== '') return true;
            }
            return false;
        }
        if ($type === 'scenarios') {
            foreach (($value ?? []) as $s) {
                if (trim(($s['name'] ?? '') . ($s['description'] ?? '')) !== '') return true;
            }
            return false;
        }
        if ($type === 'files') {
            return !empty($value);
        }
        return is_string($value) && trim($value) !== '';
    };

    // Pre-filter to sections that actually contain answers.
    $filledSections = [];
    foreach ($sections as $section) {
        $items = array_values(array_filter($section['items'] ?? [], $hasContent));
        if (!empty($items)) {
            $filledSections[] = ['id' => $section['id'] ?? '', 'title' => $section['title'] ?? '', 'items' => $items];
        }
    }
@endphp
<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Uitvraag — {{ $submission->project_name ?: ('Inzending #' . $submission->id) }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Figtree:wght@400;500;600;700&family=Spline+Sans+Mono:wght@500&display=swap" rel="stylesheet">
@verbatim
<style>
  :root{
    --ink:#1c1533; --ink-soft:#5b5472; --muted:#9a94ab;
    --paper:#f5f3f0; --card:#fff; --line:#e9e6e0; --line-strong:#d9d5cc;
    --accent:#7C3AED; --accent-ink:#5b21b6; --accent-soft:#f1eafe; --gold:#F4B400; --field:#faf9f5;
  }
  *{margin:0;padding:0;box-sizing:border-box}
  body{background:var(--paper);color:var(--ink);font-family:'Figtree',sans-serif;font-size:15px;line-height:1.6;-webkit-font-smoothing:antialiased}
  .no-print{position:sticky;top:0;z-index:10;display:flex;gap:10px;justify-content:flex-end;padding:12px 20px;background:rgba(247,246,241,.9);backdrop-filter:blur(8px);border-bottom:1px solid var(--line)}
  .btn{font-family:'Figtree';font-weight:600;font-size:13.5px;border:none;border-radius:10px;cursor:pointer;padding:10px 16px;display:inline-flex;align-items:center;gap:8px}
  .btn svg{width:16px;height:16px}
  .btn-primary{background:var(--accent);color:#fff}
  .btn-ghost{background:#fff;color:var(--ink);border:1px solid var(--line-strong)}
  .doc{max-width:820px;margin:26px auto;background:var(--card);border:1px solid var(--line);border-radius:18px;overflow:hidden;box-shadow:0 20px 50px -30px rgba(28,21,51,.35)}
  .cover{padding:40px clamp(24px,5vw,54px) 34px;background:linear-gradient(180deg,#1c1533,#2a1d52);color:#fff}
  .cover .eyebrow{font-family:'Spline Sans Mono';font-size:11px;letter-spacing:.16em;text-transform:uppercase;color:#c9b3f5;margin-bottom:16px;display:inline-flex;align-items:center;gap:9px}
  .cover .eyebrow .sq{width:8px;height:8px;background:var(--gold);border-radius:2px}
  .cover h1{font-family:'Fraunces';font-weight:600;font-size:clamp(26px,4.4vw,38px);line-height:1.08;letter-spacing:-.01em}
  .cover .meta{margin-top:22px;display:flex;flex-wrap:wrap;gap:22px}
  .cover .meta div{display:flex;flex-direction:column;gap:2px}
  .cover .meta label{font-family:'Spline Sans Mono';font-size:9.5px;letter-spacing:.12em;text-transform:uppercase;color:#c9b3f5}
  .cover .meta span{font-size:15px;font-weight:600}
  .body{padding:20px clamp(24px,5vw,54px) 44px}
  section{margin-top:34px}
  section:first-child{margin-top:22px}
  .sec-head{display:flex;align-items:center;gap:12px;margin-bottom:4px;padding-bottom:12px;border-bottom:2px solid var(--line)}
  .ey-num{width:30px;height:30px;flex-shrink:0;border-radius:8px;background:var(--ink);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-family:'Fraunces';font-weight:600;font-size:15px}
  .sec-head h2{font-family:'Fraunces';font-weight:600;font-size:22px;line-height:1.1;color:var(--ink)}
  .group{font-family:'Fraunces';font-weight:600;font-size:16px;color:var(--accent-ink);margin-top:22px;padding-top:6px}
  .q{margin-top:16px;break-inside:avoid;page-break-inside:avoid}
  .qlabel{font-family:'Spline Sans Mono';font-size:10.5px;letter-spacing:.06em;text-transform:uppercase;color:var(--ink-soft);font-weight:600;margin-bottom:5px}
  .ans{font-size:15px;color:var(--ink);white-space:pre-wrap;word-break:break-word}
  .checks{display:flex;flex-wrap:wrap;gap:7px}
  .chk{display:inline-flex;align-items:center;gap:6px;background:var(--accent-soft);color:var(--accent-ink);border-radius:999px;padding:5px 12px;font-size:13.5px;font-weight:600;-webkit-print-color-adjust:exact;print-color-adjust:exact}
  .chk svg{width:13px;height:13px}
  .steps{display:flex;flex-direction:column;gap:8px;margin-top:2px}
  .step{border:1px solid var(--line);border-radius:10px;background:var(--field);padding:12px 14px}
  .step-tag{font-family:'Spline Sans Mono';font-size:10px;letter-spacing:.08em;text-transform:uppercase;color:var(--accent-ink);margin-bottom:8px}
  .step-cols{display:grid;grid-template-columns:1fr 1fr;gap:14px}
  .step-cols .sublabel{font-size:11px;color:var(--muted);margin-bottom:2px}
  .step-cols .val{white-space:pre-wrap}
  .scenario{border:1px solid var(--line);border-radius:10px;background:var(--field);padding:12px 14px;margin-top:8px}
  .scenario .name{font-weight:700;margin-bottom:3px}
  .files{display:flex;flex-wrap:wrap;gap:12px;margin-top:2px}
  .file{display:flex;align-items:center;gap:9px;border:1px solid var(--line-strong);border-radius:10px;padding:8px 12px;font-size:13.5px}
  .file img{width:120px;height:82px;object-fit:cover;border-radius:8px;border:1px solid var(--line-strong)}
  .file .fname{max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
  .empty{color:var(--muted);font-style:italic}
  .foot{margin-top:40px;padding-top:16px;border-top:1px solid var(--line);font-size:12px;color:var(--muted)}
  @page{margin:14mm}
  @media print{
    body{background:#fff}
    .no-print{display:none !important}
    .doc{box-shadow:none;border:none;border-radius:0;margin:0;max-width:100%}
    .cover,.ey-num,.chk,.step,.scenario{-webkit-print-color-adjust:exact;print-color-adjust:exact}
    section{break-inside:auto}
    .sec-head{break-after:avoid}
  }
  @media (max-width:640px){ .step-cols{grid-template-columns:1fr} }
</style>
@endverbatim
</head>
<body>

  <div class="no-print">
    <a class="btn btn-ghost" href="{{ url('/' . $submission->id) }}">&larr; Terug naar formulier</a>
    <button class="btn btn-primary" onclick="window.print()">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6z"/></svg>
      Opslaan als PDF
    </button>
  </div>

  <main class="doc">
    <div class="cover">
      <div class="eyebrow"><span class="sq"></span> Uitvraag &middot; alle antwoorden</div>
      <h1>{{ $submission->project_name ?: ('Inzending #' . $submission->id) }}</h1>
      <div class="meta">
        @if($submission->datum)<div><label>Datum</label><span>{{ $submission->datum }}</span></div>@endif
        @if($submission->contactpersoon)<div><label>Contactpersoon</label><span>{{ $submission->contactpersoon }}</span></div>@endif
        <div><label>Verstuurd</label><span>{{ optional($submission->submitted_at)->format('d-m-Y H:i') ?: $submission->created_at->format('d-m-Y H:i') }}</span></div>
      </div>
    </div>

    <div class="body">
      @forelse($filledSections as $section)
        <section>
          <div class="sec-head">
            @if(!empty($section['id']))<span class="ey-num">{{ $section['id'] }}</span>@endif
            <h2>{{ $section['title'] }}</h2>
          </div>

          @php $lastGroup = null; @endphp
          @foreach($section['items'] as $item)
            @php
              $group = $item['group'] ?? null;
              $type  = $item['type'] ?? 'text';
              $label = $item['label'] ?? '';
              $value = $item['value'] ?? null;
            @endphp

            @if($group && $group !== $lastGroup)
              @php $lastGroup = $group; @endphp
              <div class="group">{{ $group }}</div>
            @endif

            <div class="q">
              @if($label)<div class="qlabel">{{ $label }}</div>@endif

              @switch($type)
                @case('checks')
                  <div class="checks">
                    @foreach($value as $opt)
                      <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>{{ $opt }}</span>
                    @endforeach
                  </div>
                  @break

                @case('steps')
                  <div class="steps">
                    @php $n = 0; @endphp
                    @foreach($value as $step)
                      @php $has = trim(($step['actor'] ?? '') . ($step['system'] ?? '')) !== ''; @endphp
                      @if($has)
                        @php $n++; @endphp
                        <div class="step">
                          <div class="step-tag">Stap {{ $n }}</div>
                          <div class="step-cols">
                            <div>
                              <div class="sublabel">{{ $step['actor_label'] ?? 'Gebruiker' }}</div>
                              <div class="val">{{ ($step['actor'] ?? '') ?: '—' }}</div>
                            </div>
                            <div>
                              <div class="sublabel">{{ $step['system_label'] ?? 'Systeem' }}</div>
                              <div class="val">{{ ($step['system'] ?? '') ?: '—' }}</div>
                            </div>
                          </div>
                        </div>
                      @endif
                    @endforeach
                  </div>
                  @break

                @case('scenarios')
                  @foreach($value as $scen)
                    @if(trim(($scen['name'] ?? '') . ($scen['description'] ?? '')) !== '')
                      <div class="scenario">
                        <div class="name">{{ ($scen['name'] ?? '') ?: 'Scenario' }}</div>
                        <div class="ans">{{ ($scen['description'] ?? '') ?: '—' }}</div>
                      </div>
                    @endif
                  @endforeach
                  @break

                @case('files')
                  <div class="files">
                    @foreach($value as $file)
                      <div class="file">
                        @if(!empty($file['image']) && !empty($file['preview']))
                          <img src="{{ $file['preview'] }}" alt="">
                        @else
                          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                          <span class="fname">{{ $file['name'] ?? 'Bestand' }}</span>
                        @endif
                      </div>
                    @endforeach
                  </div>
                  @break

                @default
                  <div class="ans">{{ is_array($value) ? implode(', ', $value) : $value }}</div>
              @endswitch
            </div>
          @endforeach
        </section>
      @empty
        <section>
          <p class="empty">Deze inzending bevat nog geen ingevulde antwoorden.</p>
        </section>
      @endforelse

      <div class="foot">Uitvraag #{{ $submission->id }} &middot; gegenereerd via EAZYONLINE</div>
    </div>
  </main>

<script>
  // Open the print dialog once the document (incl. fonts & image thumbnails) is ready.
  (function(){
    function print(){ setTimeout(function(){ window.print(); }, 350); }
    if(document.readyState === 'complete') print();
    else window.addEventListener('load', print, { once:true });
  })();
</script>
</body>
</html>
