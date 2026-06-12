@extends('layouts.app')

@push('scripts')
<script>
    window.EXISTING_SUBMISSION = @json($submission);
</script>
@endpush

@section('content')
<div class="form-page">

  <div class="toolbar">
    <div class="tb-left">
      <div class="tb-mark">E</div>
      <div>
        <div class="tb-kicker">EAZYONLINE &times; Effectzorg</div>
        <div class="tb-title">Uitvraag software & werkprocessen</div>
      </div>
    </div>
    <div class="tb-actions">
      <button class="btn btn-ghost" id="expandBtn" onclick="toggleAll()">Alles uitklappen</button>
      <button class="btn btn-ghost" onclick="addColleague()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
        Collega
      </button>
      <button class="btn btn-success" id="saveBtn" onclick="submitForm()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><path d="M17 21v-8H7v8M7 3v5h8"/></svg>
        Opslaan &amp; Versturen
      </button>
      <button class="btn btn-ghost" onclick="window.print()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6z"/></svg>
        PDF
      </button>
    </div>
  </div>

  <main class="doc">

    {{-- BRAND BAR --}}
    <div class="brandbar">
      <div class="logoslot has-logo" title="Klik om een ander logo te kiezen" onclick="pickLogo(this)">
        <img class="logo-img" alt="Eazyonline" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDIxLjEuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiCgkgdmlld0JveD0iMCAwIDc2NSAxODMiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDc2NSAxODM7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8cGF0aCBmaWxsPSIjMDZDMkQ0IiBkPSJNOTQuMSwxMTcuNmwzLjcsMTguNWMtMy43LDEuNy04LjEsMy0xMy4zLDQuMWMtNS4yLDEuMS0xMC41LDEuNi0xNS44LDEuNmMtNi41LDAtMTIuNi0xLTE4LjItMy4xIGMtNS42LTIuMS0xMC40LTUtMTQuNC04LjljLTQtMy45LTcuMS04LjYtOS4yLTE0LjJjLTIuMi01LjYtMy4yLTExLjctMy4yLTE4LjRjMC02LjQsMS0xMi40LDMtMTcuOXM0LjgtMTAuMiw4LjUtMTQuMSBjMy43LTMuOSw4LTcsMTMuMS05LjFjNS4xLTIuMiwxMC42LTMuMiwxNi42LTMuMmMxMC44LDAsMTkuNSwzLjUsMjYuMSwxMC41YzYuNyw3LDEwLDE2LjEsMTAsMjcuM2MwLDQtMC4yLDguMi0wLjcsMTIuNkg0Ny43IGMyLjEsMTIuOSw5LjksMTkuMywyMy4zLDE5LjNDNzguOCwxMjIuNCw4Ni41LDEyMC44LDk0LjEsMTE3LjZ6IE02NC45LDcxYy00LjgsMC04LjcsMS42LTExLjcsNC44Yy0zLjEsMy4yLTUsNy43LTUuNywxMy4zSDc5di0yIGMwLTQuOC0xLjItOC42LTMuNy0xMS42QzcyLjgsNzIuNSw2OS4zLDcxLDY0LjksNzF6Ii8+Cgk8cGF0aCBmaWxsPSIjMDZDMkQ0IiBkPSJNMTczLjgsMTM0LjRjLTMuOSwyLTksMy43LTE1LjQsNS4yYy02LjQsMS40LTEzLjMsMi4yLTIwLjksMi4yYy0xMSwwLTE5LjQtMi40LTI1LjQtNy4yIGMtNS45LTQuOC04LjktMTEuMS04LjktMTguOWMwLTguMSwzLTE0LjUsOS4xLTE5LjFjNi4xLTQuNywxNC4yLTcsMjQuMy03LjJjNC4zLDAsOS4zLDAuNCwxNC44LDEuM3YtNGMwLTEwLTUuNy0xNS0xNy0xNSBjLTMuNSwwLTcuMywwLjMtMTEuMywxYy00LDAuNy03LjUsMS43LTEwLjYsM2wtNC4yLTE4YzMuNy0xLjYsOC4yLTIuOCwxMy43LTMuN2M1LjUtMC45LDEwLjUtMS4zLDE1LjEtMS4zIGMxMiwwLDIxLjEsMy4xLDI3LjMsOS4yYzYuMiw2LjIsOS4zLDE0LjksOS4zLDI2LjJWMTM0LjR6IE0xMzkuOSwxMjUuM2M0LjksMCw4LjgtMC44LDExLjYtMi4zdi0xNy44Yy00LjEtMC45LTgtMS4zLTExLjgtMS4zIGMtNCwwLTcuMywwLjktOS44LDIuN2MtMi42LDEuOC0zLjgsNC41LTMuOCw4LjFjMCwzLjQsMS4yLDYuMSwzLjcsNy45QzEzMi4yLDEyNC4zLDEzNS42LDEyNS4zLDEzOS45LDEyNS4zeiIvPgoJPHBhdGggZmlsbD0iIzA2QzJENCIgZD0iTTE4MS4zLDU0LjdoNjMuNHYxNS41Yy01LjEsOS41LTEwLjYsMTguNC0xNi41LDI2LjVjLTUuOSw4LjEtMTIuNywxNi40LTIwLjUsMjQuN2gzOS40djE4LjNoLTY3Ljd2LTE1LjUgYzguMi04LjQsMTYtMTcuMiwyMy40LTI2LjRjNy40LTkuMiwxMy4xLTE3LjUsMTctMjQuOGgtMzguNlY1NC43eiIvPgoJPHBhdGggZmlsbD0iIzA2QzJENCI+PC9wYXRoPgoJPHBhdGggZmlsbD0iIzE5MjAzRCIgZD0iTTQxNy4yLDk3LjJjMCw2LjUtMC45LDEyLjYtMi44LDE4LjFjLTEuOSw1LjUtNC42LDEwLjMtOC4yLDE0LjJjLTMuNiwzLjktOCw3LTEzLjIsOS4xIGMtNS4yLDIuMS0xMC45LDMuMi0xNy4xLDMuMmMtMTIuNiwwLTIyLjctNC4xLTMwLjMtMTIuMmMtNy41LTguMS0xMS4zLTE4LjktMTEuMy0zMi4zYzAtMTMuMywzLjgtMjQsMTEuMy0zMi4yIGM3LjUtOC4xLDE3LjYtMTIuMiwzMC4zLTEyLjJjNi4yLDAsMTEuOSwxLjEsMTcuMSwzLjJjNS4yLDIuMSw5LjYsNS4xLDEzLjIsOS4xYzMuNiwzLjksNi4zLDguNiw4LjIsMTQuMSBDNDE2LjIsODQuNiw0MTcuMiw5MC42LDQxNy4yLDk3LjJ6Ii8+Cgk8cGF0aCBmaWxsPSIjMTkyMDNEIiBkPSJNNDQ3LjgsMTM5LjdINDI1VjYwLjFjNS4yLTIuMiwxMS4yLTQsMTgtNS4zYzYuOC0xLjMsMTMuMy0yLDE5LjYtMmMxMi4yLDAsMjEuNiwyLjksMjguMyw4LjggYzYuNyw1LjksMTAsMTQuMywxMCwyNS4zdjUyLjloLTIzVjg4LjVjMC0xMS4yLTUuMi0xNi44LTE1LjUtMTYuOGMtNS40LDAtMTAuMywxLTE0LjYsM1YxMzkuN3oiLz4KCTxwYXRoIGZpbGw9IiMxOTIwM0QiIGQ9Ik01MzQuNiwxOS4yVjEwOWMwLDQuNSwwLjcsOCwyLjEsMTAuM2MxLjQsMi4zLDMuNywzLjUsNi45LDMuNWMxLjgsMCwzLjQtMC4yLDQuOC0wLjVsMS43LDE4LjEgYy00LjIsMC45LTguMSwxLjMtMTEuNiwxLjNjLTE3LjksMC0yNi44LTEwLjgtMjYuOC0zMi40VjIwLjVMNTM0LjYsMTkuMnoiLz4KCTxwYXRoIGZpbGw9IiMxOTIwM0QiIGQ9Ik01NjUuOCw0My40Yy00LjEsMC03LjMtMS4yLTkuNy0zLjVjLTIuNC0yLjMtMy42LTUuNC0zLjYtOS4zYzAtMy45LDEuMi03LDMuNi05LjNjMi40LTIuMyw1LjYtMy41LDkuNy0zLjUgYzQsMCw3LjIsMS4yLDkuNiwzLjZjMi40LDIuNCwzLjYsNS41LDMuNiw5LjJjMCwzLjgtMS4yLDYuOC0zLjYsOS4yQzU3Myw0Mi4yLDU2OS44LDQzLjQsNTY1LjgsNDMuNHoiLz4KCTxwYXRoIGZpbGw9IiMxOTIwM0QiIGQ9Ik01NzcuMyw1NC43djg1aC0yM3YtODVINTc3LjN6Ii8+Cgk8cGF0aCBmaWxsPSIjMTkyMDNEIiBkPSJNNjEyLjcsMTM5LjdINTkwVjYwLjFjNS4yLTIuMiwxMS4yLTQsMTgtNS4zYzYuOC0xLjMsMTMuMy0yLDE5LjYtMmMxMi4yLDAsMjEuNiwyLjksMjguMyw4LjggYzYuNyw1LjksMTAsMTQuMywxMCwyNS4zdjUyLjloLTIzVjg4LjVjMC0xMS4yLTUuMi0xNi44LTE1LjUtMTYuOGMtNS40LDAtMTAuMywxLTE0LjYsM1YxMzkuN3oiLz4KCTxwYXRoIGZpbGw9IiMxOTIwM0QiIGQ9Ik03NDMuMSwxMTcuNmwzLjcsMTguNWMtMy43LDEuNy04LjEsMy0xMy4zLDQuMWMtNS4yLDEuMS0xMC41LDEuNi0xNS44LDEuNmMtNi41LDAtMTIuNi0xLTE4LjItMy4xIGMtNS42LTIuMS0xMC40LTUtMTQuNC04LjljLTQtMy45LTcuMS04LjYtOS4yLTE0LjJjLTIuMi01LjYtMy4yLTExLjctMy4yLTE4LjRjMC02LjQsMS0xMi40LDMtMTcuOXM0LjgtMTAuMiw4LjUtMTQuMSBjMy43LTMuOSw4LTcsMTMuMS05LjFjNS4xLTIuMiwxMC42LTMuMiwxNi42LTMuMmMxMC44LDAsMTkuNSwzLjUsMjYuMSwxMC41YzYuNyw3LDEwLDE2LjEsMTAsMjcuM2MwLDQtMC4yLDguMi0wLjcsMTIuNmgtNTIuNiBjMi4xLDEyLjksOS45LDE5LjMsMjMuMywxOS4zQzcyNy44LDEyMi40LDczNS41LDEyMC44LDc0My4xLDExNy42eiBNNzEzLjksNzFjLTQuOCwwLTguNywxLjYtMTEuNyw0LjhjLTMuMSwzLjItNSw3LjctNS43LDEzLjMgSDcyOHYtMmMwLTQuOC0xLjItOC42LTMuNy0xMS42QzcyMS44LDcyLjUsNzE4LjMsNzEsNzEzLjksNzF6Ii8+CjwvZz4KPC9zdmc+">
        <span class="logo-ph wm wm-eazy"><b>eazy</b>online</span>
        <input type="file" accept="image/*" hidden onchange="setLogo(this)">
        <span class="logo-edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4M7 9l5-5 5 5M5 20h14"/></svg> logo</span>
      </div>
      <span class="brand-x">&times;</span>
      <div class="logoslot logoslot-dark has-logo" title="Klik om een ander logo te kiezen" onclick="pickLogo(this)">
        <img class="logo-img" alt="Effectzorg" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCA4ODUuOCAxMzkuNCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgODg1LjggMTM5LjQ7Ij4KPHN0eWxlPi5hLC5ie2ZpbGw6I2ZmZn0uY3tmaWxsOiMzNGNjYmZ9PC9zdHlsZT4KPHBvbHlnb24gY2xhc3M9ImEiIHBvaW50cz0iNjkuNCw0Mi45IDQwLjgsNDIuOSA0MC44LDU4LjUgNjYsNTguNSA2Niw3MyA0MC44LDczIDQwLjgsOTAgNjkuNCw5MCA2OS40LDEwNS4xIDIyLDEwNS4xIDIyLDI3LjggNjkuNCwyNy44Ii8+CjxwYXRoIGNsYXNzPSJhIiBkPSJNODEuNiwyNy44aDUwLjN2MTUuMWgtMzEuNXYxNi4ySDEyNHYxNC42aC0yMy42VjEwNUg4MS42eiIvPgo8cGF0aCBjbGFzcz0iYSIgZD0iTTE0MS45LDI3LjhoNTAuM3YxNS4xaC0zMS41djE2LjJoMjMuNnYxNC42aC0yMy42VjEwNWgtMTguOHoiLz4KPHBvbHlnb24gY2xhc3M9ImEiIHBvaW50cz0iMjQ5LjYsNDIuOSAyMjEsNDIuOSAyMjEsNTguNSAyNDYuMiw1OC41IDI0Ni4yLDczIDIyMSw3MyAyMjEsOTAgMjQ5LjYsOTAgMjQ5LjYsMTA1LjEgMjAyLjIsMTA1LjEgMjAyLjIsMjcuOCAyNDkuNiwyNy44Ii8+CjxwYXRoIGNsYXNzPSJhIiBkPSJNMjk3LjUsMjYuOGMxOC4zLDAsMzIuMywxMC4zLDM2LjcsMjcuMmgtMjAuN2MtMy4yLTYuNi05LTEwLTE2LjItMTBjLTExLjYsMC0xOS41LDguNS0xOS41LDIyLjJzNy45LDIyLjIsMTkuNSwyMi4yYzcuMiwwLDEzLTMuNCwxNi4yLTEwaDIwLjljLTQuMywxNi43LTE4LjMsMjctMzYuNywyN2MtMjIuOCwwLTM4LjktMTYuMi0zOC45LTM5LjRTMjc0LjcsMjYuOCwyOTcuNSwyNi44eiIvPgo8cGF0aCBjbGFzcz0iYSIgZD0iTTM0MS42LDI3LjhoNTkuOHYxNS4xSDM4MXY2Mi4yaC0xOC44VjQyLjloLTIwLjZ6Ii8+CjxwYXRoIGNsYXNzPSJjIiBkPSJNNjI5LjMsNDIuN0w1OTYsOTAuM2gzMy4zdjE1LjRoLTU0LjdWOTEuNGwzMy4xLTQ3LjZoLTMzLjFWMjguMmg1NC43eiIvPgo8cGF0aCBjbGFzcz0iYyIgZD0iTTY3OC40LDEwNi41Yy0yMS45LDAtMzkuNy0xNi40LTM5LjctMzkuN3MxNy44LTM5LjUsMzkuNy0zOS41YzIyLDAsMzkuNSwxNi4yLDM5LjUsMzkuNUM3MTcuOSw5MCw3MDAuMiwxMDYuNSw2NzguNCwxMDYuNXogTTY3OC40LDg5LjNjMTIuNCwwLDIwLjQtOSwyMC40LTIyLjVjMC0xMy44LTgtMjIuNS0yMC40LTIyLjVjLTEyLjUsMC0yMC42LDguNy0yMC42LDIyLjVDNjU3LjgsODAuMyw2NjUuOCw4OS4zLDY3OC40LDg5LjN6Ii8+CjxwYXRoIGNsYXNzPSJjIiBkPSJNNzYwLDI4LjJjMTksMCwyOC41LDEwLjksMjguNSwyNC40YzAsOS42LTUuMywxOC44LTE3LjIsMjIuM2wxNy44LDMwLjdoLTIxLjJsLTE2LjEtMjkuM2gtNC41djI5LjNoLTE4LjhWMjguMkg3NjB6IE03NTguOSw0NGgtMTEuN3YxOS4xaDExLjdjNy4xLDAsMTAuMy0zLjcsMTAuMy05LjZDNzY5LjIsNDcuNyw3NjYsNDQsNzU4LjksNDR6Ii8+CjxwYXRoIGNsYXNzPSJjIiBkPSJNODcyLjQsNTIuOGgtMjAuOWMtMi45LTUuMS04LjItNy45LTE1LjMtNy45Yy0xMi4xLDAtMjAuMSw4LjUtMjAuMSwyMmMwLDE0LjMsOC4yLDIyLjcsMjEuNywyMi43YzkuMywwLDE1LjktNSwxOC44LTEzLjdoLTI0VjYyaDQxdjE3LjVjLTQsMTMuMy0xNi45LDI2LjctMzcuMywyNi43Yy0yMy4xLDAtMzkuNC0xNi4yLTM5LjQtMzkuNHMxNi4yLTM5LjQsMzkuNC0zOS40Qzg1NC45LDI3LjQsODY4LjQsMzYuOSw4NzIuNCw1Mi44eiIvPgo8Y2lyY2xlIGNsYXNzPSJhIiBjeD0iNDg0IiBjeT0iNjQuNiIgcj0iNTYuNyIvPgo8cGF0aCBjbGFzcz0iYyIgZD0iTTUyMi45LDUwLjZWMzcuOWgtNTkuM3YxMy4yaDMxLjNsLTMxLjMsMjcuNXYxMi41aDc5LjdjLTkuNSwxOC0yOC41LDMwLjItNTAuMiwzMC4yYy0zMS4zLDAtNTYuNy0yNS40LTU2LjctNTYuN3MyNS40LTU2LjcsNTYuNy01Ni43czU2LjcsMjUuNCw1Ni43LDU2LjdjMCw0LjctMC41LDktMS42LDEzLjNoLTU3LjRMNTIyLjksNTAuNnoiLz4KPHBhdGggY2xhc3M9ImEiIGQ9Ik01NDguNSw3Ny45Yy0xLjEsNC43LTIuNyw5LjItNSwxMy4zaC03OS45Vjc4LjdsMzEuMy0yNy41aC0zMS4zVjM3LjloNTkuM3YxMi41bC0zMS44LDI3LjNoNTcuNHoiLz4KPC9zdmc+">
        <span class="logo-ph wm wm-effect">Effect<i>zorg</i></span>
        <input type="file" accept="image/*" hidden onchange="setLogo(this)">
        <span class="logo-edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4M7 9l5-5 5 5M5 20h14"/></svg> logo</span>
      </div>
    </div>

    {{-- COVER --}}
    <div class="cover">
      <div class="cover-eyebrow"><span class="sq"></span> Intake &middot; wat moet de software kunnen</div>
      <h1>Effectzorg &mdash; de <em>uitvraag</em> voor de nieuwe software.</h1>
      <p class="lead">Voordat we bouwen, brengen we eerst in kaart hoe er n&uacute; gewerkt wordt: wat er handmatig gebeurt, welke functionaliteit ontbreekt en hoe het ideale proces eruitziet. Dit vullen we per collega in. Daarnaast vragen we tijdelijk toegang tot de huidige systemen, zodat we de structuur kunnen bekijken.</p>
      <div class="cover-meta">
        <div class="meta-pill"><label>Datum</label><input type="text" id="meta-datum" placeholder="dd-mm-jjjj"></div>
        <div class="meta-pill"><label>Project</label><input type="text" id="meta-project" value="Software Effectzorg"></div>
        <div class="meta-pill"><label>Contactpersoon</label><input type="text" id="meta-contact" placeholder="Naam"></div>
      </div>
    </div>

    <div class="doc-inner">

      {{-- HOW IT WORKS --}}
      <div class="how">
        <div class="how-step"><span class="how-num">1</span><h4>Vul per collega in</h4><p>Klik bij Deel B op een naam om dat blok te openen en in te vullen. Iedereen vult zijn eigen deel.</p></div>
        <div class="how-step"><span class="how-num">2</span><h4>Wees concreet</h4><p>Noem minuten en frequenties &mdash; &ldquo;20 min, 10&times; per week&rdquo; zegt meer dan &ldquo;kost veel tijd&rdquo;.</p></div>
        <div class="how-step"><span class="how-num">3</span><h4>Opslaan &amp; Versturen</h4><p>Klaar? Klik rechtsboven op &ldquo;Opslaan &amp; Versturen&rdquo; om alles op te slaan.</p></div>
      </div>

      {{-- DEEL A --}}
      <section>
        <div class="sec-head">
          <span class="eyebrow"><span class="ey-num">A</span> Toegang tot de systemen</span>
          <h2>Mag EAZYONLINE tijdelijk meekijken onder een testaccount?</h2>
          <p class="lead">Om te bepalen wat er qua structuur nog nodig is, willen we de huidige systemen van binnen bekijken &mdash; bij voorkeur een <strong>read-only testaccount</strong>, &oacute;f een <strong>demo-omgeving</strong> of <strong>GitHub-repository</strong>. Geef per systeem aan wat mogelijk is en wie toegang kan verlenen.</p>
          <div class="sec-rule"></div>
        </div>
        <div class="access-grid" id="access"></div>
      </section>

      {{-- DEEL B --}}
      <section>
        <div class="sec-head">
          <span class="eyebrow"><span class="ey-num">B</span> Per collega</span>
          <h2>Jouw werk, jouw wensen, jouw knelpunten</h2>
          <p class="lead">Klik op je naam om je blok te openen. Per persoon: wat zou je willen, waar loop je tegenaan, wat doe je handmatig en welke functionaliteit mis je per systeem.</p>
          <div class="sec-rule"></div>
        </div>
        <div class="pnav"><span class="pnav-label">Spring naar</span><div id="pnav-chips"></div></div>
        <div id="team"></div>
        <div class="add-person-wrap">
          <button class="add-person" onclick="addColleague()">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
            Collega toevoegen
          </button>
        </div>
      </section>

      {{-- DEEL C --}}
      <section>
        <div class="sec-head">
          <span class="eyebrow"><span class="ey-num">C</span> Externe gebruikers</span>
          <h2>Kandidaten &amp; werkgevers &mdash; de gebruikers van het portaal</h2>
          <p class="lead">De software draait niet alleen om het interne team. De zorgprofessionals (kandidaten) en de zorginstellingen (werkgevers) zijn de belangrijkste gebruikers. Beschrijf wat z&iacute;j zelf moeten kunnen &mdash; dit is vaak het eigenlijke product.</p>
          <div class="sec-rule"></div>
        </div>
        <div class="duo">
          <div class="team-block">
            <div class="tb-head"><div class="tb-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div><div class="tb-h">Kandidaten <span class="opt">zorgprofessionals</span></div></div>
            <div class="qbox"><span class="qlabel">Wat moeten kandidaten zelf kunnen?</span><p class="qhint">Denk aan: profiel aanmaken, beschikbaarheid doorgeven, openstaande diensten zien en oppakken, diploma's/documenten uploaden, uren indienen, onboarding en status volgen.</p><textarea class="auto" id="candidates_capabilities" placeholder="Beschrijf hier…"></textarea></div>
            <div class="qbox"><span class="qlabel">Aanmelden &amp; matchen</span><p class="qhint">Hoe melden kandidaten zich nu aan en hoe worden ze aan diensten gekoppeld? Hoe zou dat idealiter gaan?</p><textarea class="auto" id="candidates_matching" placeholder="Beschrijf hier…"></textarea></div>
            <div class="qbox"><span class="qlabel">Mobiel gebruik</span><p class="qhint">Gebruiken kandidaten vooral hun telefoon? Moet het portaal mobiel-first zijn?</p><select id="candidates_mobile"><option>— kies —</option><option>Ja, mobiel is essentieel</option><option>Fijn, maar niet vereist</option><option>Nee, vooral desktop</option></select></div>
          </div>
          <div class="team-block">
            <div class="tb-head"><div class="tb-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21V5a1 1 0 0 1 1-1h9a1 1 0 0 1 1 1v16"/><path d="M15 9h4a1 1 0 0 1 1 1v11"/><path d="M3 21h18"/><path d="M8 8h.01M8 12h.01M8 16h.01M11 8h.01M11 12h.01M11 16h.01"/></svg></div><div class="tb-h">Werkgevers <span class="opt">zorginstellingen</span></div></div>
            <div class="qbox"><span class="qlabel">Wat moeten werkgevers zelf kunnen?</span><p class="qhint">Denk aan: personeel of diensten aanvragen, voorgestelde kandidaten en matches zien, gewerkte uren goedkeuren, facturen/overzichten inzien, contact opnemen.</p><textarea class="auto" id="employers_capabilities" placeholder="Beschrijf hier…"></textarea></div>
            <div class="qbox"><span class="qlabel">Aanvraag van personeel</span><p class="qhint">Hoe vragen werkgevers nu personeel aan, en hoe zou dat idealiter verlopen?</p><textarea class="auto" id="employers_requesting" placeholder="Beschrijf hier…"></textarea></div>
            <div class="qbox"><span class="qlabel">Eigen portaal / login?</span><p class="qhint">Krijgen werkgevers een eigen omgeving met login?</p><select id="employers_portal"><option>— kies —</option><option>Ja</option><option>Nee</option><option>Nog onbekend</option></select></div>
          </div>
        </div>
      </section>

      {{-- DEEL D --}}
      <section>
        <div class="sec-head">
          <span class="eyebrow"><span class="ey-num">D</span> Scope, data &amp; compliance</span>
          <h2>De grote keuzes en de verplichte zaken</h2>
          <p class="lead">Deze vragen bepalen de omvang, de planning &eacute;n de wettelijke randvoorwaarden. Juist hier zit het grootste risico als het wordt overgeslagen.</p>
          <div class="sec-rule"></div>
        </div>
        <div class="team-block">
          <div class="tb-head"><div class="tb-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="4.5"/></svg></div><div class="tb-h">Scope &amp; aanpak</div></div>
          <div class="qbox"><span class="qlabel">Vervangen of koppelen?</span><p class="qhint">Bouwen we &eacute;&eacute;n nieuw systeem dat de huidige software vervangt, of een schil eroverheen die de systemen koppelt?</p><textarea class="auto" id="scope_replace_or_connect" placeholder="Beschrijf hier…"></textarea></div>
          <div class="qbox"><span class="qlabel">Minimale eerste versie (MVP)</span><p class="qhint">Als we klein beginnen: welke 2-3 dingen m&oacute;&eacute;t de eerste versie kunnen om al waarde te hebben?</p><textarea class="auto" id="scope_mvp" placeholder="1.&#10;2.&#10;3."></textarea></div>
          <div class="qbox"><span class="qlabel">Budget &amp; deadline</span><p class="qhint">Is er een richtbudget en een gewenste opleverdatum of harde deadline?</p><textarea class="auto" id="scope_budget_deadline" placeholder="Beschrijf hier…"></textarea></div>
          <div class="qbox"><span class="qlabel">Vaste beslisser / contactpersoon</span><p class="qhint">Wie neemt namens Effectzorg de knopen door en is ons vaste aanspreekpunt?</p><input type="text" id="scope_decision_maker" placeholder="Naam + rol"></div>
        </div>
        <div class="team-block" style="margin-top:16px">
          <div class="tb-head"><div class="tb-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.5 13.5a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1.3 1.3"/><path d="M13.5 10.5a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1.3-1.3"/></svg></div><div class="tb-h">Data &amp; koppelingen</div></div>
          <div class="qbox"><span class="qlabel">Welke data zit er nu in de systemen?</span><p class="qhint">Bijv. kandidaten, dienstverbanden, planning, urenregistratie, facturen &mdash; en ongeveer hoeveel.</p><textarea class="auto" id="data_current_systems" placeholder="Beschrijf hier…"></textarea></div>
          <div class="qbox"><span class="qlabel">Wat moet er mee (migratie)?</span><p class="qhint">Welke gegevens en hoeveel historie moeten over naar de nieuwe software? Wat mag achterblijven?</p><textarea class="auto" id="data_migration" placeholder="Beschrijf hier…"></textarea></div>
          <div class="qbox"><span class="qlabel">Koppelingen (API / export)</span><p class="qhint">Hebben de huidige systemen een API of export? En welke externe koppelingen zijn nodig &mdash; salarisadministratie, boekhouding/facturatie, planning, DigiD/iDIN?</p><textarea class="auto" id="data_api_integrations" placeholder="Beschrijf hier…"></textarea></div>
        </div>
        <div class="team-block" style="margin-top:16px">
          <div class="tb-head"><div class="tb-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6l8-3z"/><path d="M9 12l2 2 4-4"/></svg></div><div class="tb-h">Compliance, privacy &amp; kwaliteit</div></div>
          <div class="qbox"><span class="qlabel">Zorg-specifieke checks</span><p class="qhint">Welke verplichte controles horen erbij? Denk aan BIG-registratie, VOG, diploma-/certificaatcontrole, ID-verificatie (IDN) en urenregistratie volgens CAO.</p><textarea class="auto" id="compliance_checks" placeholder="Beschrijf hier…"></textarea></div>
          <div class="qbox"><span class="qlabel">Privacy &amp; beveiliging (AVG)</span><p class="qhint">Het gaat om gevoelige (zorg)gegevens. Waar mag de data staan (NL/EU)? Zijn er verwerkersovereenkomsten, bewaartermijnen of eisen als tweestapsverificatie?</p><textarea class="auto" id="compliance_privacy" placeholder="Beschrijf hier…"></textarea></div>
          <div class="qbox"><span class="qlabel">Wat werkt nu w&eacute;l goed?</span><p class="qhint">Wat in de huidige werkwijze of systemen moet absoluut behouden blijven en mag niet verloren gaan?</p><textarea class="auto" id="compliance_working_well" placeholder="Beschrijf hier…"></textarea></div>
          <div class="qbox"><span class="qlabel">Aantallen &amp; succes</span><p class="qhint">Hoeveel kandidaten, werkgevers en diensten per maand ongeveer? En waaraan merken we straks dat het project geslaagd is?</p><textarea class="auto" id="compliance_numbers_success" placeholder="Beschrijf hier…"></textarea></div>
        </div>
      </section>

      {{-- DEEL E --}}
      <section>
        <div class="sec-head">
          <span class="eyebrow"><span class="ey-num">E</span> Overkoepelend</span>
          <h2>Afronding</h2>
          <p class="lead">E&eacute;n keer invullen &mdash; door of namens het team.</p>
          <div class="sec-rule"></div>
        </div>
        <div class="team-block">
          <div class="tb-head"><div class="tb-icon">&#931;</div><div class="tb-h">Teamniveau</div></div>
          <div class="qbox"><span class="qlabel">Welke meldingen / workflows moeten automatisch lopen?</span><p class="qhint">En welke handelingen zouden idealiter volledig verdwijnen?</p><textarea class="auto" id="overall_workflows" placeholder="Beschrijf hier…"></textarea></div>
          <div class="qbox"><span class="qlabel">Algemene opmerkingen</span><textarea class="auto" id="overall_remarks" placeholder="Overige wensen of aandachtspunten voor de nieuwe software…"></textarea></div>
          <div class="qbox"><span class="qlabel">Algemene bijlagen <span class="opt">optioneel</span></span><p class="qhint">Documenten, exports of screenshots die voor het hele project relevant zijn.</p><div class="uploader" id="overall-uploader"><label class="upbtn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg> Bestand toevoegen<input type="file" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.csv" hidden onchange="handleFiles(this)"></label><div class="uplist"></div></div></div>
        </div>
      </section>

    </div>

    <div class="doc-footer">
      <strong>Klaar?</strong> Klik rechtsboven op <strong>Opslaan &amp; Versturen</strong> om alle gegevens op te slaan. Je antwoorden worden veilig opgeslagen in het systeem en zijn daarna inzichtelijk voor het projectteam.
    </div>

  </main>

</div>

{{-- TOAST NOTIFICATION --}}
<div class="toast" id="toast"></div>
@endsection
