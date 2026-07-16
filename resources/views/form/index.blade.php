<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Studiomatch — Uitvraag MVP software</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Figtree:wght@400;500;600;700&family=Spline+Sans+Mono:wght@500&display=swap" rel="stylesheet">
@verbatim
<style>
  :root{
    --ink:#1c1533;
    --ink-soft:#5b5472;
    --muted:#9a94ab;
    --paper:#f5f3f0;
    --card:#ffffff;
    --line:#e9e6e0;
    --line-strong:#d9d5cc;
    --accent:#7C3AED;
    --accent-ink:#5b21b6;
    --accent-soft:#f1eafe;
    --gold:#F4B400;
    --field:#faf9f5;
    --shadow:0 1px 2px rgba(28,21,51,.04), 0 26px 54px -32px rgba(28,21,51,.32);
    --shadow-sm:0 1px 2px rgba(28,21,51,.05), 0 10px 26px -18px rgba(28,21,51,.22);
    --radius:16px;
  }
  *{margin:0;padding:0;box-sizing:border-box}
  html{scroll-behavior:smooth}
  body{
    background:
      radial-gradient(1100px 460px at 82% -130px, rgba(124,58,237,.10), transparent 60%),
      radial-gradient(820px 380px at -8% 0%, rgba(244,180,0,.07), transparent 55%),
      var(--paper);
    color:var(--ink);
    font-family:'Figtree', sans-serif;
    font-size:16.5px;line-height:1.65;-webkit-font-smoothing:antialiased;
    padding-bottom:90px;
  }
  .toolbar{position:sticky;top:0;z-index:60;display:flex;align-items:center;justify-content:space-between;gap:14px;padding:13px 24px;background:rgba(247,246,241,.82);backdrop-filter:blur(14px);border-bottom:1px solid var(--line)}
  .tb-left{display:flex;align-items:center;gap:13px;min-width:0}
  .tb-mark{width:34px;height:34px;border-radius:10px;flex-shrink:0;background:linear-gradient(135deg,#7C3AED,#4c1d95);display:flex;align-items:center;justify-content:center;color:#fff;font-family:'Fraunces';font-weight:600;font-size:18px}
  .tb-kicker{font-family:'Spline Sans Mono';font-size:10px;letter-spacing:.16em;text-transform:uppercase;color:var(--accent-ink)}
  .tb-title{font-weight:600;font-size:14px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
  .tb-actions{display:flex;align-items:center;gap:9px;flex-shrink:0}
  .btn{font-family:'Figtree';font-weight:600;font-size:13.5px;border:none;border-radius:10px;cursor:pointer;padding:10px 15px;display:inline-flex;align-items:center;gap:8px;transition:transform .12s ease, box-shadow .2s ease, background .2s, border-color .2s, color .2s}
  .btn svg{width:16px;height:16px}
  .btn-primary{background:var(--ink);color:#fff;box-shadow:0 10px 22px -12px rgba(28,21,51,.6)}
  .btn-primary:hover{transform:translateY(-1px)}
  .btn-ghost{background:#fff;color:var(--ink);border:1px solid var(--line-strong)}
  .btn-ghost:hover{border-color:var(--accent);color:var(--accent-ink)}
  .doc{max-width:820px;margin:32px auto;background:var(--card);border:1px solid var(--line);border-radius:22px;box-shadow:var(--shadow);overflow:hidden}
  .doc-inner{padding:44px clamp(22px,5.5vw,60px)}
  .cover{position:relative;padding:56px clamp(22px,5.5vw,60px) 42px;background:radial-gradient(680px 280px at 86% -70px, rgba(124,58,237,.28), transparent 60%),linear-gradient(180deg,#1c1533 0%, #2a1d52 100%);color:#fff;overflow:hidden}
  .cover:before{content:"";position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.045) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.045) 1px,transparent 1px);background-size:30px 30px;-webkit-mask:radial-gradient(60% 92% at 72% 0%, #000, transparent 70%);mask:radial-gradient(60% 92% at 72% 0%, #000, transparent 70%);pointer-events:none}
  .cover-eyebrow{position:relative;display:inline-flex;align-items:center;gap:9px;font-family:'Spline Sans Mono';font-size:11px;letter-spacing:.18em;text-transform:uppercase;color:#c9b3f5;margin-bottom:22px}
  .cover-eyebrow .sq{width:8px;height:8px;background:var(--gold);border-radius:2px}
  .cover h1{position:relative;font-family:'Fraunces';font-weight:600;font-size:clamp(31px,5.6vw,50px);line-height:1.04;letter-spacing:-.01em;max-width:17ch}
  .cover h1 em{font-style:italic;color:#b794f6}
  .cover .lead{position:relative;margin-top:22px;max-width:58ch;font-size:16.5px;line-height:1.66;color:#d5cdea}
  .cover-meta{position:relative;margin-top:32px;display:flex;flex-wrap:wrap;gap:12px}
  .meta-pill{display:flex;flex-direction:column;gap:3px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.13);border-radius:12px;padding:9px 15px;min-width:155px}
  .meta-pill label{font-family:'Spline Sans Mono';font-size:9.5px;letter-spacing:.13em;text-transform:uppercase;color:#c9b3f5}
  .meta-pill input{background:transparent;border:none;color:#fff;font-family:'Figtree';font-size:14.5px;font-weight:600;width:100%;outline:none}
  .meta-pill input::placeholder{color:rgba(255,255,255,.45)}
  .how{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-top:34px}
  .how-step{background:var(--field);border:1px solid var(--line);border-radius:14px;padding:18px}
  .how-num{display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:8px;background:var(--accent-soft);color:var(--accent-ink);font-family:'Spline Sans Mono';font-size:13px;font-weight:600;margin-bottom:11px}
  .how-step h4{font-family:'Fraunces';font-weight:600;font-size:16px;color:var(--ink);margin-bottom:5px}
  .how-step p{font-size:13.5px;line-height:1.5;color:var(--ink-soft)}
  section{margin-top:52px}
  .sec-head{margin-bottom:24px}
  .eyebrow{display:inline-flex;align-items:center;gap:11px;font-family:'Spline Sans Mono';font-size:11px;letter-spacing:.15em;text-transform:uppercase;color:var(--accent-ink);margin-bottom:12px}
  .ey-num{width:27px;height:27px;border-radius:8px;background:var(--ink);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-family:'Fraunces';font-weight:600;font-size:14px}
  .sec-head h2{font-family:'Fraunces';font-weight:600;font-size:clamp(23px,3.8vw,31px);line-height:1.12;letter-spacing:-.01em;color:var(--ink)}
  .sec-head .lead{margin-top:12px;max-width:66ch;color:var(--ink-soft);font-size:15.5px}
  .sec-rule{height:3px;width:56px;background:var(--gold);border-radius:3px;margin-top:15px}
  .duo{display:grid;grid-template-columns:1fr 1fr;gap:16px}
  .pnav{display:flex;align-items:center;flex-wrap:wrap;gap:9px;margin-bottom:20px;padding:15px 16px;background:var(--field);border:1px solid var(--line);border-radius:14px}
  .pnav-label{font-family:'Spline Sans Mono';font-size:10px;letter-spacing:.13em;text-transform:uppercase;color:var(--ink-soft);margin-right:3px}
  #pnav-chips{display:flex;flex-wrap:wrap;gap:8px}
  .navchip{display:inline-flex;align-items:center;gap:8px;font-family:'Figtree';font-size:13.5px;font-weight:600;color:var(--ink);background:#fff;border:1px solid var(--line-strong);border-radius:999px;padding:7px 14px;cursor:pointer;transition:.15s}
  .navchip:hover{border-color:var(--accent);color:var(--accent-ink)}
  .nc-dot{width:7px;height:7px;border-radius:50%;background:var(--line-strong);transition:.2s}
  .navchip[data-filled="1"] .nc-dot{background:var(--accent);box-shadow:0 0 0 3px var(--accent-soft)}
  .acc{background:var(--card);border:1px solid var(--line-strong);border-radius:var(--radius);margin-bottom:14px;box-shadow:var(--shadow-sm);overflow:hidden}
  .acc-head{width:100%;display:flex;align-items:center;gap:16px;padding:18px 20px;background:transparent;border:none;cursor:pointer;text-align:left;font-family:inherit;transition:background .15s}
  .acc-head:hover{background:var(--field)}
  .acc-num{flex-shrink:0;width:42px;height:42px;border-radius:12px;background:var(--ink);color:#fff;display:flex;align-items:center;justify-content:center;font-family:'Fraunces';font-weight:600;font-size:15px}
  .acc-id{flex:1;min-width:0;display:flex;flex-direction:column;gap:1px}
  .acc-name{font-family:'Fraunces';font-weight:600;font-size:20px;color:var(--ink);line-height:1.18}
  .acc-func{font-size:13.5px;color:var(--ink-soft)}
  .acc-status{flex-shrink:0;width:9px;height:9px;border-radius:50%;background:var(--line-strong);transition:.2s}
  .acc[data-filled="1"] .acc-status{background:var(--accent);box-shadow:0 0 0 4px var(--accent-soft)}
  .acc-chev{flex-shrink:0;width:20px;height:20px;color:var(--muted);transition:transform .3s}
  .acc.open .acc-chev{transform:rotate(180deg)}
  .acc-body{max-height:0;overflow:hidden;transition:max-height .35s ease}
  .acc-inner{padding:6px 20px 26px;border-top:1px solid var(--line)}
  .qbox{margin-top:24px}
  .qlabel{font-family:'Fraunces';font-weight:600;font-size:17.5px;color:var(--ink)}
  .qhint{font-size:13.5px;color:var(--ink-soft);margin-top:4px;line-height:1.5;max-width:62ch}
  .q2{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:24px}
  .q2 .qbox{margin-top:0}
  .flabel{display:block;font-size:12.5px;font-weight:600;color:var(--ink-soft);margin-bottom:6px}
  input[type=text],textarea,select{width:100%;font-family:'Figtree';font-size:15.5px;color:var(--ink);background:#fff;border:1px solid var(--line-strong);border-radius:11px;padding:11px 13px;outline:none;transition:border-color .15s, box-shadow .15s;margin-top:8px}
  textarea.auto{min-height:2.9em;resize:vertical;overflow:hidden;line-height:1.55}
  .qbox > textarea.auto{min-height:3.6em}
  input[type=text]::placeholder,textarea::placeholder{color:var(--muted)}
  input[type=text]:focus,textarea:focus,select:focus{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-soft)}
  select{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%235b5472' stroke-width='3'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 13px center;padding-right:36px;cursor:pointer}
  .entries{display:flex;flex-direction:column;gap:12px;margin-top:12px}
  .entry{position:relative;background:var(--field);border:1px solid var(--line);border-radius:13px;padding:16px 16px 17px}
  .entry-top{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:4px}
  .entry-tag{font-family:'Spline Sans Mono';font-size:10.5px;letter-spacing:.08em;text-transform:uppercase;color:var(--accent-ink);background:var(--accent-soft);border-radius:6px;padding:5px 10px}
  .entry-meta{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:4px}
  .entry textarea,.entry input{background:#fff}
  .fcol{display:flex;flex-direction:column}
  .xbtn{flex-shrink:0;width:26px;height:26px;border-radius:8px;border:1px solid var(--line-strong);background:#fff;color:var(--muted);cursor:pointer;font-size:17px;line-height:1;display:flex;align-items:center;justify-content:center;transition:.15s}
  .xbtn:hover{color:#e0556b;border-color:#f3c2cb;background:#fff5f6}
  .addrow{margin-top:13px;font-family:'Figtree';font-weight:600;font-size:13.5px;color:var(--accent-ink);background:#fff;border:1.5px dashed var(--line-strong);border-radius:11px;padding:10px 15px;cursor:pointer;transition:.15s;display:inline-flex;align-items:center;gap:7px}
  .addrow:hover{border-color:var(--accent);background:var(--accent-soft)}
  .team-block{background:var(--card);border:1px solid var(--line-strong);border-radius:var(--radius);box-shadow:var(--shadow-sm);padding:6px 20px 24px}
  .team-block .tbh{display:flex;align-items:center;gap:14px;padding:18px 0 4px}
  .team-block .tb-icon{width:42px;height:42px;border-radius:12px;background:var(--accent);color:#fff;display:flex;align-items:center;justify-content:center;font-family:'Fraunces';font-weight:600;font-size:20px;flex-shrink:0}
  .team-block .tb-h{font-family:'Fraunces';font-weight:600;font-size:20px}
  .opt{font-family:'Spline Sans Mono';font-size:10px;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);font-weight:500;margin-left:7px;vertical-align:middle}
  .checks{display:grid;grid-template-columns:1fr 1fr;gap:9px;margin-top:12px}
  .check{display:flex;align-items:flex-start;gap:10px;background:#fff;border:1px solid var(--line-strong);border-radius:11px;padding:11px 13px;cursor:pointer;transition:.15s;font-size:14px}
  .check:hover{border-color:var(--accent)}
  .check input{width:auto;margin:3px 0 0;accent-color:var(--accent);flex-shrink:0}
  .check span{line-height:1.4}
  .exbox{margin-top:12px;background:var(--field);border:1px solid var(--line);border-left:3px solid var(--gold);border-radius:11px;padding:13px 16px}
  .exbox h6{font-family:'Spline Sans Mono';font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:var(--accent-ink);margin-bottom:7px}
  .exbox p{font-size:13px;color:var(--ink-soft);line-height:1.65}
  .exbox p b{color:var(--ink)}
  .uploader{margin-top:10px}
  .upbtn{display:inline-flex;align-items:center;gap:8px;font-family:'Figtree';font-weight:600;font-size:13.5px;color:var(--accent-ink);background:#fff;border:1.5px dashed var(--line-strong);border-radius:11px;padding:11px 16px;cursor:pointer;transition:.15s}
  .upbtn:hover{border-color:var(--accent);background:var(--accent-soft)}
  .upbtn svg{width:16px;height:16px}
  .uplist{display:flex;flex-wrap:wrap;gap:11px;margin-top:13px}
  .upitem{position:relative}
  .upimg img{width:152px;height:106px;object-fit:cover;border-radius:11px;border:1px solid var(--line-strong);display:block}
  .upimg .upname{display:block;font-size:11px;color:var(--ink-soft);margin-top:5px;max-width:152px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
  .upfile{display:flex;align-items:center;gap:11px;background:var(--field);border:1px solid var(--line-strong);border-radius:11px;padding:11px 36px 11px 14px;min-width:240px}
  .upicon{flex-shrink:0;width:32px;height:38px;display:flex;align-items:center;justify-content:center;color:var(--accent-ink)}
  .upicon svg{width:22px;height:26px}
  .upmeta{display:flex;flex-direction:column;min-width:0}
  .upmeta .upname{font-weight:600;font-size:13.5px;color:var(--ink);max-width:230px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
  .upsize{font-size:11.5px;color:var(--ink-soft)}
  .upx{position:absolute;top:6px;right:6px;width:22px;height:22px;border-radius:7px;border:1px solid var(--line-strong);background:#fff;color:var(--muted);cursor:pointer;font-size:15px;line-height:1;display:flex;align-items:center;justify-content:center;transition:.15s}
  .upx:hover{color:#e0556b;border-color:#f3c2cb;background:#fff5f6}
  .doc-footer{margin-top:48px;padding:24px clamp(22px,5.5vw,60px) 34px;border-top:1px solid var(--line);background:var(--field);color:var(--ink-soft);font-size:13.5px;line-height:1.55}
  .doc-footer strong{color:var(--ink)}
  .brandbar{display:flex;align-items:center;justify-content:center;gap:22px;flex-wrap:wrap;padding:20px 24px;background:#fff;border-bottom:1px solid var(--line)}
  .logoslot{position:relative;display:inline-flex;align-items:center;justify-content:center;min-width:120px;min-height:54px;padding:6px 16px;border-radius:12px;cursor:pointer;transition:background .15s, box-shadow .15s}
  .logoslot:hover{background:var(--field);box-shadow:inset 0 0 0 1px var(--line)}
  .logo-img{max-height:52px;max-width:230px;object-fit:contain;display:none}
  .logoslot.has-logo .logo-img{display:block}
  .logoslot.has-logo .logo-ph{display:none}
  .wm{font-family:'Figtree';font-weight:800;font-size:28px;letter-spacing:-.025em;line-height:1;white-space:nowrap}
  .wm-eazy{color:var(--ink)}
  .wm-eazy b{color:#0bb6d6;font-weight:800}
  .wm-studio{color:var(--ink)}
  .wm-studio i{font-style:normal;color:var(--accent)}
  .brand-x{color:var(--muted);font-size:17px;font-weight:300;user-select:none}
  .logo-edit{position:absolute;bottom:-8px;left:50%;transform:translateX(-50%);display:inline-flex;align-items:center;gap:4px;font-family:'Spline Sans Mono';font-size:9px;letter-spacing:.06em;text-transform:uppercase;color:var(--accent-ink);background:var(--accent-soft);border:1px solid #ddc9fb;border-radius:999px;padding:2px 9px;opacity:0;transition:opacity .15s;pointer-events:none;white-space:nowrap}
  .logoslot:hover .logo-edit{opacity:1}
  .toast{position:fixed;left:50%;bottom:26px;transform:translate(-50%,120%);z-index:200;max-width:min(90vw,460px);padding:13px 20px;border-radius:12px;background:var(--ink);color:#fff;font-size:14.5px;font-weight:600;line-height:1.5;box-shadow:0 18px 40px -14px rgba(28,21,51,.55);white-space:pre-line;opacity:0;transition:transform .3s ease, opacity .3s ease}
  .toast.show{transform:translate(-50%,0);opacity:1}
  .toast.success{background:#127a53}
  .toast.error{background:#b3372f}
  .btn[disabled]{opacity:.6;cursor:progress}
  .animate-spin{animation:spin 1s linear infinite}
  @keyframes spin{to{transform:rotate(360deg)}}
  @media (max-width:740px){
    .how,.duo,.q2,.entry-meta,.checks{grid-template-columns:1fr}
    .tb-title,.tb-kicker{display:none}
    .acc-name{font-size:18px}
  }
  @media print{
    body{background:#fff;padding:0}
    .toolbar,.pnav,.addrow,.xbtn,.upbtn,.upx,.logo-edit,.how{display:none !important}
    .doc{box-shadow:none;border:none;border-radius:0;margin:0;max-width:100%}
    .doc-inner{padding:6mm 12mm}
    .cover{border-radius:0;-webkit-print-color-adjust:exact;print-color-adjust:exact}
    .acc,.cover,.acc-num,.ey-num,.sec-rule,.team-block .tb-icon,.entry-tag{-webkit-print-color-adjust:exact;print-color-adjust:exact}
    .acc-body{max-height:none !important;overflow:visible !important}
    .acc-chev,.acc-status{display:none !important}
    .acc-head{cursor:default}
    input[type=text],textarea,select{background:#fff !important;border-color:#c9cdda !important;box-shadow:none !important}
    textarea.auto{overflow:visible !important}
    .acc{break-before:page}
    .acc:first-of-type{break-before:auto}
    .qbox,.q2,.entry,.team-block,.check,.exbox{break-inside:avoid}
    .brandbar{-webkit-print-color-adjust:exact;print-color-adjust:exact}
  }
</style>
@endverbatim
</head>
<body>

  <div class="toolbar">
    <div class="tb-left">
      <div class="tb-mark">S</div>
      <div>
        <div class="tb-kicker">EAZYONLINE × Studiomatch</div>
        <div class="tb-title">Uitvraag MVP — functionaliteiten & keuzes</div>
      </div>
    </div>
    <div class="tb-actions">
      <button class="btn btn-ghost" id="expandBtn" onclick="toggleAll()">Alles uitklappen</button>
      <button class="btn btn-ghost" onclick="expandAll();window.print()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6z"/></svg>
        PDF
      </button>
      <button class="btn btn-primary" id="saveBtn" style="background:var(--accent)" onclick="submitForm()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><path d="M17 21v-8H7v8M7 3v5h8"/></svg>
        Opslaan &amp; Versturen
      </button>
    </div>
  </div>

  <main class="doc">

    <div class="brandbar">
      <div class="logoslot" title="Klik om een logo te kiezen" onclick="pickLogo(this)">
        <img class="logo-img" alt="Eazyonline">
        <span class="logo-ph wm wm-eazy"><b>eazy</b>online</span>
        <input type="file" accept="image/*" hidden onchange="setLogo(this)">
        <span class="logo-edit">logo</span>
      </div>
      <span class="brand-x">&times;</span>
      <div class="logoslot" title="Klik om een logo te kiezen" onclick="pickLogo(this)">
        <img class="logo-img" alt="Studiomatch">
        <span class="logo-ph wm wm-studio">Studio<i>match</i></span>
        <input type="file" accept="image/*" hidden onchange="setLogo(this)">
        <span class="logo-edit">logo</span>
      </div>
    </div>

    <div class="cover">
      <div class="cover-eyebrow"><span class="sq"></span> Intake · wat moet de software kunnen</div>
      <h1>Studiomatch — de <em>uitvraag</em> voor de MVP.</h1>
      <p class="lead">Studiomatch verbindt artiesten met opnamestudio's: online zien waar wat zit, beschikbaarheid bekijken en meteen boeken. Voordat we bouwen, lopen we per functionaliteit de keuzes langs. Jouw antwoorden bepalen de exacte invulling van de MVP en worden vastgelegd in de SOW.</p>
      <div class="cover-meta">
        <div class="meta-pill"><label>Datum</label><input type="text" id="meta-datum" placeholder="dd-mm-jjjj"></div>
        <div class="meta-pill"><label>Project</label><input type="text" id="meta-project" value="Studiomatch MVP"></div>
        <div class="meta-pill"><label>Contactpersoon</label><input type="text" id="meta-contact" placeholder="Naam"></div>
      </div>
    </div>

    <div class="doc-inner">

      <div class="how">
        <div class="how-step"><span class="how-num">1</span><h4>Loop de blokken langs</h4><p>Klik bij Deel B op een functionaliteit om het blok te openen. Sla niets over — "weet ik nog niet" is ook een antwoord.</p></div>
        <div class="how-step"><span class="how-num">2</span><h4>Wees concreet</h4><p>Aantallen en voorbeelden helpen: "±30 studio's in Randstad" zegt meer dan "een aantal studio's".</p></div>
        <div class="how-step"><span class="how-num">3</span><h4>Opslaan als PDF</h4><p>Klaar? Klik rechtsboven op "Opslaan als PDF" en stuur het bestand terug naar EAZYONLINE.</p></div>
      </div>

      <!-- DEEL A -->
      <section>
        <div class="sec-head">
          <span class="eyebrow"><span class="ey-num">A</span> Het aanbod</span>
          <h2>Met welke studio's gaan we live?</h2>
          <p class="lead">Een boekingsplatform zonder aanbod is leeg. De belangrijkste vraag vóór de bouw: welke studio's staan er op dag één op de kaart, en wie levert hun gegevens aan?</p>
          <div class="sec-rule"></div>
        </div>
        <div class="team-block">
          <div class="tbh"><div class="tb-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21V5a1 1 0 0 1 1-1h9a1 1 0 0 1 1 1v16"/><path d="M15 9h4a1 1 0 0 1 1 1v11"/><path d="M3 21h18"/></svg></div><div class="tb-h">Startaanbod</div></div>
          <div class="q2">
            <div class="qbox"><span class="qlabel">Hoeveel studio's bij lancering?</span><input type="text" placeholder="bv. 25"></div>
            <div class="qbox"><span class="qlabel">In welke regio('s)?</span><input type="text" placeholder="bv. Amsterdam / Randstad / heel NL"></div>
          </div>
          <div class="qbox"><span class="qlabel">Zijn er al toezeggingen van studio's?</span><p class="qhint">Welke studio's hebben al ja gezegd? Zijn er samenwerkingen of een wachtlijst?</p><textarea class="auto" placeholder="Beschrijf hier…"></textarea></div>
          <div class="qbox"><span class="qlabel">Wie levert de studiogegevens aan?</span><p class="qhint">Vullen studio's hun eigen profiel (foto's, apparatuur, prijzen) of zet Studiomatch dit klaar bij de start?</p><select><option>— kies —</option><option>Studio's vullen zelf hun profiel</option><option>Studiomatch zet alles klaar</option><option>Combinatie: wij starten, studio's beheren daarna zelf</option></select></div>
          <div class="qbox"><span class="qlabel">Voorbeeldstudio's <span class="opt">optioneel</span></span><p class="qhint">Noem 2-3 studio's (met website) die representatief zijn voor het aanbod — handig voor het datamodel en design.</p><textarea class="auto" placeholder="1.&#10;2.&#10;3."></textarea></div>
        </div>
      </section>

      <!-- DEEL B: FUNCTIONALITEITEN -->
      <section>
        <div class="sec-head">
          <span class="eyebrow"><span class="ey-num">B</span> De MVP-functionaliteiten</span>
          <h2>Zeven bouwblokken — per blok jouw keuzes</h2>
          <p class="lead">Dit zijn de functionaliteiten van de MVP. Klik op een blok om het te openen en de vragen te beantwoorden. Het bolletje kleurt zodra een blok is ingevuld.</p>
          <div class="sec-rule"></div>
        </div>
        <div class="pnav"><span class="pnav-label">Spring naar</span><div id="pnav-chips"></div></div>
        <div id="funcs">

          <!-- F1 -->
          <div class="acc" data-filled="0" id="f1">
            <button type="button" class="acc-head" aria-expanded="false" onclick="toggleAcc(this)">
              <span class="acc-num">F1</span>
              <span class="acc-id"><span class="acc-name">Studioprofielen</span><span class="acc-func">Naam, foto's, ruimtes, apparatuur, prijzen</span></span>
              <span class="acc-status"></span>
              <svg class="acc-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            <div class="acc-body"><div class="acc-inner">
              <div class="qbox"><span class="qlabel">Wat moet er op een studiopagina staan?</span><p class="qhint">Vink aan wat in de MVP zichtbaar moet zijn.</p>
                <div class="checks">
                  <label class="check"><input type="checkbox" checked><span>Foto's van de studio & ruimtes</span></label>
                  <label class="check"><input type="checkbox" checked><span>Apparatuurlijst (mics, mixers, instrumenten)</span></label>
                  <label class="check"><input type="checkbox" checked><span>Prijzen per uur / dagdeel</span></label>
                  <label class="check"><input type="checkbox" checked><span>Adres + kaartje</span></label>
                  <label class="check"><input type="checkbox"><span>Technicus/engineer inbegrepen ja/nee</span></label>
                  <label class="check"><input type="checkbox"><span>Genre-specialisatie (hiphop, rock…)</span></label>
                  <label class="check"><input type="checkbox"><span>Audio-voorbeelden van producties</span></label>
                  <label class="check"><input type="checkbox"><span>Huisregels / voorwaarden van de studio</span></label>
                </div>
              </div>
              <div class="qbox"><span class="qlabel">Prijsstructuur</span><p class="qhint">Hoe rekenen studio's? Per uur, per dagdeel (4 uur), per dag — of verschilt dit per studio? Zijn er toeslagen (avond/weekend, engineer)?</p><textarea class="auto" placeholder="Beschrijf hier…"></textarea></div>
              <div class="qbox"><span class="qlabel">Kan één studio meerdere ruimtes hebben?</span><select><option>— kies —</option><option>Ja, ruimtes apart boekbaar (aanbevolen)</option><option>Nee, één studio = één boekbare ruimte</option><option>Nog onbekend</option></select></div>
            </div></div>
          </div>

          <!-- F2 -->
          <div class="acc" data-filled="0" id="f2">
            <button type="button" class="acc-head" aria-expanded="false" onclick="toggleAcc(this)">
              <span class="acc-num">F2</span>
              <span class="acc-id"><span class="acc-name">Zoeken & kaartweergave</span><span class="acc-func">Online zien waar wat zit</span></span>
              <span class="acc-status"></span>
              <svg class="acc-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            <div class="acc-body"><div class="acc-inner">
              <div class="qbox"><span class="qlabel">Waarop moeten artiesten kunnen filteren?</span>
                <div class="checks">
                  <label class="check"><input type="checkbox" checked><span>Plaats / afstand</span></label>
                  <label class="check"><input type="checkbox" checked><span>Prijs (van–tot)</span></label>
                  <label class="check"><input type="checkbox" checked><span>Beschikbaar op datum/tijd</span></label>
                  <label class="check"><input type="checkbox"><span>Type studio (opname, mix/master, repetitie)</span></label>
                  <label class="check"><input type="checkbox"><span>Apparatuur (bv. specifieke microfoon)</span></label>
                  <label class="check"><input type="checkbox"><span>Met/zonder engineer</span></label>
                </div>
              </div>
              <div class="qbox"><span class="qlabel">Wat is de standaardweergave?</span><select><option>— kies —</option><option>Kaart en lijst naast elkaar (aanbevolen)</option><option>Lijst eerst, kaart als tab</option><option>Kaart eerst, lijst als tab</option></select></div>
              <div class="qbox"><span class="qlabel">Welke soorten studio's doen mee?</span><p class="qhint">Alleen opnamestudio's, of ook repetitieruimtes, podcaststudio's, mix/master-only?</p><textarea class="auto" placeholder="Beschrijf hier…"></textarea></div>
            </div></div>
          </div>

          <!-- F3 -->
          <div class="acc" data-filled="0" id="f3">
            <button type="button" class="acc-head" aria-expanded="false" onclick="toggleAcc(this)">
              <span class="acc-num">F3</span>
              <span class="acc-id"><span class="acc-name">Beschikbaarheid</span><span class="acc-func">Kalender met vrije tijdslots per ruimte</span></span>
              <span class="acc-status"></span>
              <svg class="acc-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            <div class="acc-body"><div class="acc-inner">
              <div class="q2">
                <div class="qbox"><span class="qlabel">Kleinste boekbare blok</span><select><option>— kies —</option><option>1 uur</option><option>2 uur</option><option>Dagdeel (4 uur)</option><option>Verschilt per studio</option></select></div>
                <div class="qbox"><span class="qlabel">Hoe ver vooruit boekbaar?</span><select><option>— kies —</option><option>1 maand</option><option>3 maanden</option><option>6 maanden</option><option>Onbeperkt</option></select></div>
              </div>
              <div class="qbox"><span class="qlabel">Gebruiken studio's nu al een agenda-systeem?</span><p class="qhint">Bijv. Google Calendar of een eigen planning. Moet daar in de MVP rekening mee worden gehouden, of beheren ze hun beschikbaarheid volledig in Studiomatch?</p><textarea class="auto" placeholder="Beschrijf hier…"></textarea></div>
              <div class="qbox"><span class="qlabel">Buffer tussen sessies?</span><p class="qhint">Moet er standaard op-/afbouwtijd tussen twee boekingen zitten (bv. 30 min)?</p><input type="text" placeholder="bv. 30 minuten / geen / studio kiest zelf"></div>
            </div></div>
          </div>

          <!-- F4 -->
          <div class="acc" data-filled="0" id="f4">
            <button type="button" class="acc-head" aria-expanded="false" onclick="toggleAcc(this)">
              <span class="acc-num">F4</span>
              <span class="acc-id"><span class="acc-name">Boeken & direct betalen</span><span class="acc-func">Tijdslot kiezen en afrekenen via Mollie</span></span>
              <span class="acc-status"></span>
              <svg class="acc-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            <div class="acc-body"><div class="acc-inner">
              <div class="qbox"><span class="qlabel">Verdienmodel Studiomatch</span><p class="qhint">Hoe verdient Studiomatch aan een boeking? Dit bepaalt de betaal- en uitbetaalflow.</p><select><option>— kies —</option><option>Commissie per boeking (% van het bedrag)</option><option>Vast bedrag per boeking</option><option>Studio's betalen abonnement, boeking 100% naar studio</option><option>Nog onbekend</option></select></div>
              <div class="q2">
                <div class="qbox"><span class="qlabel">Commissie / bedrag</span><input type="text" placeholder="bv. 10% of €5 per boeking"></div>
                <div class="qbox"><span class="qlabel">Is er al een Mollie-account?</span><select><option>— kies —</option><option>Ja</option><option>Nee, moet aangevraagd</option><option>Ander betaalsysteem gewenst</option></select></div>
              </div>
              <div class="qbox"><span class="qlabel">Annuleringsvoorwaarden</span><p class="qhint">Tot wanneer mag een artiest gratis annuleren, en wat gebeurt er daarna? Bijv. gratis tot 48 uur vooraf, daarna 50%, no-show 100%.</p><textarea class="auto" placeholder="Beschrijf hier…"></textarea></div>
              <div class="qbox"><span class="qlabel">Boeking wijzigen of verzetten</span><p class="qhint">Annuleren is één ding — maar mag een artiest ook verzetten naar een ander tijdslot? En wat gebeurt er bij een prijsverschil?</p><select><option>— kies —</option><option>Ja, verzetten in de MVP (binnen de annuleringstermijn)</option><option>Nee: annuleren + opnieuw boeken</option><option>Nog onbekend</option></select><input type="text" placeholder="Bijzonderheden, bv. hoe om te gaan met prijsverschil…"></div>
              <div class="qbox"><span class="qlabel">Facturatie & btw</span><p class="qhint">Wie factureert de artiest: Studiomatch of de studio zelf? Krijgt de studio een maandelijks commissie-overzicht? Welk btw-tarief geldt? Dit bepaalt hoe de betaalstroom en administratie gebouwd worden — stem dit zo nodig af met je boekhouder.</p><textarea class="auto" placeholder="Beschrijf hier…"></textarea></div>
              <div class="qbox"><span class="qlabel">Uitbetaling aan studio's</span><p class="qhint">In de MVP betalen artiesten aan Studiomatch; uitbetaling aan studio's gebeurt periodiek (bv. maandelijks, minus commissie). Akkoord, of anders gewenst?</p><textarea class="auto" placeholder="Beschrijf hier…"></textarea></div>
              <div class="qbox"><span class="qlabel">Kortingscodes / lanceringsactie</span><p class="qhint">Bijv. korting op de eerste boeking. Advies: fase 2, tenzij essentieel voor de lancering.</p><select><option>— kies —</option><option>Fase 2 (aanbevolen)</option><option>Ja, moet in de MVP</option><option>Niet nodig</option></select></div>
            </div></div>
          </div>

          <!-- F5 -->
          <div class="acc" data-filled="0" id="f5">
            <button type="button" class="acc-head" aria-expanded="false" onclick="toggleAcc(this)">
              <span class="acc-num">F5</span>
              <span class="acc-id"><span class="acc-name">Accounts & dashboards</span><span class="acc-func">Artiest- en studioportaal</span></span>
              <span class="acc-status"></span>
              <svg class="acc-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            <div class="acc-body"><div class="acc-inner">
              <div class="qbox"><span class="qlabel">Wat moet een artiest in zijn dashboard kunnen?</span>
                <div class="checks">
                  <label class="check"><input type="checkbox" checked><span>Komende & eerdere boekingen zien</span></label>
                  <label class="check"><input type="checkbox" checked><span>Boeking annuleren</span></label>
                  <label class="check"><input type="checkbox" checked><span>Profiel & gegevens beheren</span></label>
                  <label class="check"><input type="checkbox"><span>Facturen/betaalbewijzen downloaden</span></label>
                </div>
              </div>
              <div class="qbox"><span class="qlabel">Wat moet een studio-eigenaar kunnen?</span>
                <div class="checks">
                  <label class="check"><input type="checkbox" checked><span>Profiel, ruimtes & prijzen beheren</span></label>
                  <label class="check"><input type="checkbox" checked><span>Beschikbaarheid & blokkades instellen</span></label>
                  <label class="check"><input type="checkbox" checked><span>Boekingen & agenda inzien</span></label>
                  <label class="check"><input type="checkbox"><span>Boeking namens artiest annuleren</span></label>
                  <label class="check"><input type="checkbox"><span>Omzetoverzicht inzien</span></label>
                </div>
              </div>
              <div class="qbox"><span class="qlabel">Mogen bezoekers boeken zonder account?</span><select><option>— kies —</option><option>Nee, account verplicht bij boeken (aanbevolen)</option><option>Ja, gastboeking met alleen e-mail</option><option>Nog onbekend</option></select></div>
            </div></div>
          </div>

          <!-- F6 -->
          <div class="acc" data-filled="0" id="f6">
            <button type="button" class="acc-head" aria-expanded="false" onclick="toggleAcc(this)">
              <span class="acc-num">F6</span>
              <span class="acc-id"><span class="acc-name">Notificaties</span><span class="acc-func">Bevestiging, herinnering, annulering</span></span>
              <span class="acc-status"></span>
              <svg class="acc-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            <div class="acc-body"><div class="acc-inner">
              <div class="qbox"><span class="qlabel">Welke berichten moeten automatisch verstuurd worden?</span>
                <div class="checks">
                  <label class="check"><input type="checkbox" checked><span>Boekingsbevestiging (artiest + studio)</span></label>
                  <label class="check"><input type="checkbox" checked><span>Herinnering 24 uur vooraf</span></label>
                  <label class="check"><input type="checkbox" checked><span>Annuleringsbevestiging</span></label>
                  <label class="check"><input type="checkbox"><span>"Nieuwe studio aangemeld" naar admin</span></label>
                  <label class="check"><input type="checkbox"><span>Review-verzoek na sessie (fase 2)</span></label>
                </div>
              </div>
              <div class="qbox"><span class="qlabel">Alleen e-mail, of ook SMS/WhatsApp?</span><p class="qhint">MVP-advies: alleen e-mail. SMS/WhatsApp kan later toegevoegd worden.</p><select><option>— kies —</option><option>Alleen e-mail (aanbevolen voor MVP)</option><option>E-mail + SMS</option><option>E-mail + WhatsApp</option></select></div>
            </div></div>
          </div>

          <!-- F7 -->
          <div class="acc" data-filled="0" id="f7">
            <button type="button" class="acc-head" aria-expanded="false" onclick="toggleAcc(this)">
              <span class="acc-num">F7</span>
              <span class="acc-id"><span class="acc-name">Admin-paneel</span><span class="acc-func">Beheer door Studiomatch</span></span>
              <span class="acc-status"></span>
              <svg class="acc-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            <div class="acc-body"><div class="acc-inner">
              <div class="qbox"><span class="qlabel">Wie gaat het platform beheren?</span><p class="qhint">Naam/rol van degene(n) die studio's goedkeurt, vragen afhandelt en boekingen monitort.</p><input type="text" placeholder="Naam + rol"></div>
              <div class="qbox"><span class="qlabel">Moeten nieuwe studio's handmatig goedgekeurd worden?</span><select><option>— kies —</option><option>Ja, admin keurt elke studio goed (aanbevolen)</option><option>Nee, direct live na aanmelden</option></select></div>
              <div class="qbox"><span class="qlabel">Welke overzichten heeft de admin nodig?</span>
                <div class="checks">
                  <label class="check"><input type="checkbox" checked><span>Alle boekingen + statussen</span></label>
                  <label class="check"><input type="checkbox" checked><span>Omzet & commissie per studio</span></label>
                  <label class="check"><input type="checkbox" checked><span>Gebruikers (artiesten & studio's)</span></label>
                  <label class="check"><input type="checkbox"><span>Export naar Excel/CSV</span></label>
                </div>
              </div>
            </div></div>
          </div>

        </div>
      </section>

      <!-- DEEL C: WORKFLOWS -->
      <section>
        <div class="sec-head">
          <span class="eyebrow"><span class="ey-num">C</span> Workflows</span>
          <h2>Beschrijf het proces zoals jij het voor je ziet</h2>
          <p class="lead">Hier schrijf je in eigen woorden hoe het platform moet werken — stap voor stap, vanuit de artiest én vanuit de studio-verhuurder. Er is geen fout antwoord: hoe concreter, hoe beter wij het kunnen bouwen. Per stap vul je in wat de gebruiker doet en wat het systeem dan moet doen.</p>
          <div class="sec-rule"></div>
        </div>

        <!-- W1 -->
        <div class="acc" data-filled="0" id="w1">
          <button type="button" class="acc-head" aria-expanded="false" onclick="toggleAcc(this)">
            <span class="acc-num">W1</span>
            <span class="acc-id"><span class="acc-name">De artiest zoekt een studio</span><span class="acc-func">Zoekopties + het proces van zoeken tot boeking</span></span>
            <span class="acc-status"></span>
            <svg class="acc-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
          </button>
          <div class="acc-body"><div class="acc-inner">
            <div class="exbox">
              <h6>Zo schrijf je een workflow op — voorbeeld</h6>
              <p><b>Stap 1:</b> artiest zoekt op "Rotterdam" → <b>systeem:</b> toont kaart met studio's die vandaag nog vrij zijn.<br><b>Stap 2:</b> artiest filtert op "met engineer, max €40 p/u" → <b>systeem:</b> lijst wordt kleiner, prijzen direct zichtbaar.<br><b>Stap 3:</b> artiest kiest zaterdag 14:00–18:00 → <b>systeem:</b> rekent totaalprijs uit en houdt het slot vast tijdens het betalen.</p>
            </div>
            <div class="qbox"><span class="qlabel">Welke opties moet de artiest hebben bij het zoeken?</span><p class="qhint">Vink aan wat de artiest moet kunnen kiezen of filteren.</p>
              <div class="checks">
                <label class="check"><input type="checkbox" checked><span>Plaats / afstand vanaf mij</span></label>
                <label class="check"><input type="checkbox" checked><span>Datum & tijd (direct beschikbaar)</span></label>
                <label class="check"><input type="checkbox" checked><span>Prijsklasse (van–tot)</span></label>
                <label class="check"><input type="checkbox"><span>Genre-specialisatie (hiphop, rock…)</span></label>
                <label class="check"><input type="checkbox"><span>Specifieke apparatuur</span></label>
                <label class="check"><input type="checkbox"><span>Met of zonder engineer</span></label>
                <label class="check"><input type="checkbox"><span>Type sessie (opname, mix, repetitie, podcast)</span></label>
                <label class="check"><input type="checkbox"><span>Grootte / aantal personen</span></label>
              </div>
              <input type="text" placeholder="Andere opties die de artiest moet hebben…">
            </div>
            <div class="qbox"><span class="qlabel">Het proces, stap voor stap</span><p class="qhint">Beschrijf per stap wat de artiest doet en wat het systeem daarop moet doen. Voeg zoveel stappen toe als je wilt.</p>
              <div class="entries">
                <div class="entry wstep">
                  <div class="entry-top"><span class="entry-tag">Stap 1</span><button type="button" class="xbtn" onclick="removeStep(this)" aria-label="Verwijderen">&times;</button></div>
                  <div class="entry-meta">
                    <div class="fcol"><label class="flabel">Wat doet de artiest?</label><textarea class="auto" placeholder="Beschrijf de handeling…"></textarea></div>
                    <div class="fcol"><label class="flabel">Wat moet het systeem dan doen?</label><textarea class="auto" placeholder="Beschrijf de reactie van het systeem…"></textarea></div>
                  </div>
                </div>
                <div class="entry wstep">
                  <div class="entry-top"><span class="entry-tag">Stap 2</span><button type="button" class="xbtn" onclick="removeStep(this)" aria-label="Verwijderen">&times;</button></div>
                  <div class="entry-meta">
                    <div class="fcol"><label class="flabel">Wat doet de artiest?</label><textarea class="auto" placeholder="Beschrijf de handeling…"></textarea></div>
                    <div class="fcol"><label class="flabel">Wat moet het systeem dan doen?</label><textarea class="auto" placeholder="Beschrijf de reactie van het systeem…"></textarea></div>
                  </div>
                </div>
              </div>
              <button type="button" class="addrow" onclick="addStep(this,'artiest')">+ stap toevoegen</button>
            </div>
            <div class="qbox"><span class="qlabel">In je eigen woorden</span><p class="qhint">Beschrijf vrij de ideale route van eerste bezoek tot bevestigde boeking — alles wat hierboven niet in stappen past.</p><textarea class="auto" placeholder="Schrijf hier vrijuit…"></textarea></div>
          </div></div>
        </div>

        <!-- W2 -->
        <div class="acc" data-filled="0" id="w2">
          <button type="button" class="acc-head" aria-expanded="false" onclick="toggleAcc(this)">
            <span class="acc-num">W2</span>
            <span class="acc-id"><span class="acc-name">De studio-verhuurder meldt zich aan</span><span class="acc-func">Aanmeldproces + wat de verhuurder moet kunnen beheren</span></span>
            <span class="acc-status"></span>
            <svg class="acc-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
          </button>
          <div class="acc-body"><div class="acc-inner">
            <div class="exbox">
              <h6>Zo schrijf je een workflow op — voorbeeld</h6>
              <p><b>Stap 1:</b> verhuurder maakt account aan en vult studiogegevens in → <b>systeem:</b> zet het profiel klaar in concept, nog niet zichtbaar.<br><b>Stap 2:</b> verhuurder uploadt foto's en zet openingstijden → <b>systeem:</b> checkt of het profiel compleet is en meldt het bij Studiomatch.<br><b>Stap 3:</b> Studiomatch keurt goed → <b>systeem:</b> studio verschijnt op de kaart en is boekbaar, verhuurder krijgt een mail.</p>
            </div>
            <div class="qbox"><span class="qlabel">Het aanmeldproces, stap voor stap</span><p class="qhint">Beschrijf per stap wat de verhuurder doet — van eerste aanmelding tot live op het platform — en wat het systeem automatisch moet doen (mails, controles, goedkeuring).</p>
              <div class="entries">
                <div class="entry wstep">
                  <div class="entry-top"><span class="entry-tag">Stap 1</span><button type="button" class="xbtn" onclick="removeStep(this)" aria-label="Verwijderen">&times;</button></div>
                  <div class="entry-meta">
                    <div class="fcol"><label class="flabel">Wat doet de verhuurder?</label><textarea class="auto" placeholder="Beschrijf de handeling…"></textarea></div>
                    <div class="fcol"><label class="flabel">Wat moet het systeem dan doen?</label><textarea class="auto" placeholder="Beschrijf de reactie van het systeem…"></textarea></div>
                  </div>
                </div>
                <div class="entry wstep">
                  <div class="entry-top"><span class="entry-tag">Stap 2</span><button type="button" class="xbtn" onclick="removeStep(this)" aria-label="Verwijderen">&times;</button></div>
                  <div class="entry-meta">
                    <div class="fcol"><label class="flabel">Wat doet de verhuurder?</label><textarea class="auto" placeholder="Beschrijf de handeling…"></textarea></div>
                    <div class="fcol"><label class="flabel">Wat moet het systeem dan doen?</label><textarea class="auto" placeholder="Beschrijf de reactie van het systeem…"></textarea></div>
                  </div>
                </div>
              </div>
              <button type="button" class="addrow" onclick="addStep(this,'verhuurder')">+ stap toevoegen</button>
            </div>
            <div class="qbox"><span class="qlabel">Wat moet de verhuurder daarna zelf kunnen beheren?</span>
              <div class="checks">
                <label class="check"><input type="checkbox" checked><span>Prijzen wijzigen</span></label>
                <label class="check"><input type="checkbox" checked><span>Tijdslots blokkeren (eigen sessie, onderhoud)</span></label>
                <label class="check"><input type="checkbox" checked><span>Foto's & profiel bijwerken</span></label>
                <label class="check"><input type="checkbox"><span>Ruimte toevoegen of verwijderen</span></label>
                <label class="check"><input type="checkbox"><span>Boeking weigeren of annuleren</span></label>
                <label class="check"><input type="checkbox"><span>Vakantiemodus (tijdelijk onzichtbaar)</span></label>
              </div>
              <input type="text" placeholder="Andere dingen die de verhuurder zelf moet kunnen…">
            </div>
            <div class="qbox"><span class="qlabel">Uitbetaal- & bedrijfsgegevens bij aanmelding</span><p class="qhint">Om maandelijks te kunnen uitbetalen moet het systeem deze gegevens per studio vastleggen. Vink aan wat verplicht is bij aanmelding.</p>
              <div class="checks">
                <label class="check"><input type="checkbox" checked><span>IBAN (rekening voor uitbetaling)</span></label>
                <label class="check"><input type="checkbox" checked><span>KvK-nummer</span></label>
                <label class="check"><input type="checkbox"><span>Btw-nummer</span></label>
                <label class="check"><input type="checkbox"><span>Naam rekeninghouder verifiëren</span></label>
              </div>
              <input type="text" placeholder="Andere verplichte gegevens of controles…">
            </div>
            <div class="qbox"><span class="qlabel">Wat mag de verhuurder absoluut níet zelf kunnen?</span><p class="qhint">Bijv. een betaalde boeking verwijderen zonder terugbetaling, of buiten het platform om laten betalen.</p><textarea class="auto" placeholder="Beschrijf hier…"></textarea></div>
            <div class="qbox"><span class="qlabel">In je eigen woorden</span><p class="qhint">Alles wat je verder belangrijk vindt aan de kant van de verhuurder.</p><textarea class="auto" placeholder="Schrijf hier vrijuit…"></textarea></div>
          </div></div>
        </div>

        <!-- W3 -->
        <div class="acc" data-filled="0" id="w3">
          <button type="button" class="acc-head" aria-expanded="false" onclick="toggleAcc(this)">
            <span class="acc-num">W3</span>
            <span class="acc-id"><span class="acc-name">Eigen scenario's</span><span class="acc-func">Alles wat jij verder in het systeem wilt — vrij te beschrijven</span></span>
            <span class="acc-status"></span>
            <svg class="acc-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
          </button>
          <div class="acc-body"><div class="acc-inner">
            <div class="qbox"><span class="qlabel">Extra workflows of situaties</span><p class="qhint">Denk aan: wat gebeurt er bij een no-show, een klacht, een dubbele boeking, een artiest die wil verlengen? Voeg per situatie een scenario toe en beschrijf het in je eigen woorden.</p>
              <div class="entries">
                <div class="entry wf">
                  <div class="entry-top"><span class="entry-tag">Scenario</span><button type="button" class="xbtn" onclick="removeStep(this)" aria-label="Verwijderen">&times;</button></div>
                  <label class="flabel">Naam van het scenario</label>
                  <input type="text" placeholder="bv. Artiest komt niet opdagen (no-show)">
                  <label class="flabel" style="margin-top:10px">Beschrijf het proces in je eigen woorden</label>
                  <textarea class="auto" placeholder="Wat gebeurt er, wie doet wat, en wat moet het systeem doen?"></textarea>
                </div>
              </div>
              <button type="button" class="addrow" onclick="addWf(this)">+ scenario toevoegen</button>
            </div>
            <div class="qbox"><span class="qlabel">Vrije ruimte</span><p class="qhint">Alles wat je kwijt wilt over hoe het systeem moet werken en nergens anders past — schrijf het hier.</p><textarea class="auto" placeholder="Schrijf hier vrijuit…"></textarea></div>
          </div></div>
        </div>
      </section>

      <!-- DEEL D -->
      <section>
        <div class="sec-head">
          <span class="eyebrow"><span class="ey-num">D</span> De gebruikers</span>
          <h2>Artiesten & studio's — voor wie bouwen we dit?</h2>
          <p class="lead">Hoe beter we de doelgroep kennen, hoe beter de flows aansluiten.</p>
          <div class="sec-rule"></div>
        </div>
        <div class="duo">
          <div class="team-block">
            <div class="tbh"><div class="tb-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/></svg></div><div class="tb-h">Artiesten</div></div>
            <div class="qbox"><span class="qlabel">Wie is de typische artiest?</span><p class="qhint">Beginnende rappers/zangers, bands, producers, podcasters? Leeftijd, budget per sessie?</p><textarea class="auto" placeholder="Beschrijf hier…"></textarea></div>
            <div class="qbox"><span class="qlabel">Hoe vinden ze nu een studio?</span><p class="qhint">Instagram, mond-tot-mond, Google? Wat gaat daarbij mis — het probleem dat Studiomatch oplost.</p><textarea class="auto" placeholder="Beschrijf hier…"></textarea></div>
            <div class="qbox"><span class="qlabel">Mobiel gebruik</span><select><option>— kies —</option><option>Ja, mobiel is essentieel</option><option>Fijn, maar niet vereist</option><option>Nee, vooral desktop</option></select></div>
          </div>
          <div class="team-block">
            <div class="tbh"><div class="tb-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v18"/><path d="M8 7v10M16 7v10M4 10v4M20 10v4"/></svg></div><div class="tb-h">Studio's</div></div>
            <div class="qbox"><span class="qlabel">Wie is de typische studio-eigenaar?</span><p class="qhint">Professionele studio's, home-studio's, of beide? Hoe digitaal vaardig zijn ze?</p><textarea class="auto" placeholder="Beschrijf hier…"></textarea></div>
            <div class="qbox"><span class="qlabel">Waarom doen studio's mee?</span><p class="qhint">Meer bezetting, zichtbaarheid, minder heen-en-weer appen? Wat is hun belangrijkste reden om zich aan te sluiten?</p><textarea class="auto" placeholder="Beschrijf hier…"></textarea></div>
            <div class="qbox"><span class="qlabel">Wat mag studio's níet afschrikken?</span><p class="qhint">Bijv. te hoge commissie, dubbel agenda-beheer, verplichte exclusiviteit.</p><textarea class="auto" placeholder="Beschrijf hier…"></textarea></div>
          </div>
        </div>
      </section>

      <!-- DEEL E -->
      <section>
        <div class="sec-head">
          <span class="eyebrow"><span class="ey-num">E</span> Randvoorwaarden</span>
          <h2>Branding, techniek & de verplichte zaken</h2>
          <div class="sec-rule"></div>
        </div>
        <div class="team-block">
          <div class="tbh"><div class="tb-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="14" rx="2"/><path d="M3 9h18M8 21h8"/></svg></div><div class="tb-h">De website eromheen</div></div>
          <div class="qbox"><span class="qlabel">Welke pagina's heeft de site nodig naast de boekflow?</span>
            <div class="checks">
              <label class="check"><input type="checkbox" checked><span>Homepage (zoeken + uitleg)</span></label>
              <label class="check"><input type="checkbox" checked><span>"Voor studio's" — wervingspagina met aanmelden</span></label>
              <label class="check"><input type="checkbox" checked><span>Hoe werkt het / FAQ</span></label>
              <label class="check"><input type="checkbox" checked><span>Contact</span></label>
              <label class="check"><input type="checkbox"><span>Over ons</span></label>
              <label class="check"><input type="checkbox"><span>Blog / nieuws</span></label>
            </div>
            <input type="text" placeholder="Andere pagina's…">
          </div>
          <div class="qbox"><span class="qlabel">Taal</span><p class="qhint">Meertaligheid achteraf inbouwen is duur — nu kiezen is goedkoop.</p><select><option>— kies —</option><option>Alleen Nederlands</option><option>Nederlands + Engels</option><option>Meer talen (toelichten bij opmerkingen)</option></select></div>
        </div>
        <div class="team-block" style="margin-top:16px">
          <div class="tbh"><div class="tb-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="13.5" cy="6.5" r="2.5"/><path d="M12 2l8 8-10 10a2.8 2.8 0 0 1-4-4z"/></svg></div><div class="tb-h">Branding & content</div></div>
          <div class="qbox"><span class="qlabel">Is er al een huisstijl?</span><p class="qhint">Logo, kleuren, lettertype — of moet dit nog gemaakt worden?</p><select><option>— kies —</option><option>Ja, compleet (logo + kleuren + fonts)</option><option>Alleen een logo</option><option>Nog niets — moet ontworpen worden</option></select></div>
          <div class="q2">
            <div class="qbox"><span class="qlabel">Domeinnaam</span><input type="text" placeholder="bv. studiomatch.nl — geregistreerd?"></div>
            <div class="qbox"><span class="qlabel">Wie schrijft de teksten?</span><select><option>— kies —</option><option>Studiomatch levert aan</option><option>EAZYONLINE schrijft (concept)</option><option>Samen</option></select></div>
          </div>
          <div class="qbox"><span class="qlabel">Huisstijlbestanden <span class="opt">optioneel</span></span><div class="uploader"><label class="upbtn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg> Bestand toevoegen<input type="file" multiple accept="image/*,.pdf,.doc,.docx,.zip" hidden onchange="handleFiles(this)"></label><div class="uplist"></div></div></div>
        </div>
        <div class="team-block" style="margin-top:16px">
          <div class="tbh"><div class="tb-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6l8-3z"/><path d="M9 12l2 2 4-4"/></svg></div><div class="tb-h">Juridisch & AVG</div></div>
          <div class="qbox"><span class="qlabel">Algemene voorwaarden & privacyverklaring</span><p class="qhint">Voor een boekingsplatform met betalingen zijn deze verplicht. Zijn ze er al, of moeten ze (via een jurist) opgesteld worden? Let op: dit valt buiten het bouwbudget.</p><textarea class="auto" placeholder="Beschrijf hier…"></textarea></div>
          <div class="qbox"><span class="qlabel">Bedrijfsgegevens Studiomatch</span><p class="qhint">KvK-nummer, btw-nummer, rechtsvorm — nodig voor Mollie-aanvraag en de SOW.</p><textarea class="auto" placeholder="Beschrijf hier…"></textarea></div>
        </div>
        <div class="team-block" style="margin-top:16px">
          <div class="tbh"><div class="tb-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg></div><div class="tb-h">Planning & succes</div></div>
          <div class="q2">
            <div class="qbox"><span class="qlabel">Gewenste livegang</span><input type="text" placeholder="bv. oktober 2026"></div>
            <div class="qbox"><span class="qlabel">Harde deadline?</span><input type="text" placeholder="bv. event/campagne — of nee"></div>
          </div>
          <div class="qbox"><span class="qlabel">Wanneer is de MVP geslaagd?</span><p class="qhint">Bijv. X boekingen per maand, Y aangesloten studio's binnen 3 maanden.</p><textarea class="auto" placeholder="Beschrijf hier…"></textarea></div>
          <div class="qbox"><span class="qlabel">Vaste beslisser / contactpersoon</span><input type="text" placeholder="Naam + rol"></div>
        </div>
      </section>

      <!-- DEEL F -->
      <section>
        <div class="sec-head">
          <span class="eyebrow"><span class="ey-num">F</span> Afronding</span>
          <h2>Laatste check</h2>
          <div class="sec-rule"></div>
        </div>
        <div class="team-block">
          <div class="tbh"><div class="tb-icon">&#931;</div><div class="tb-h">Overig</div></div>
          <div class="qbox"><span class="qlabel">Voorbeelden & inspiratie</span><p class="qhint">Zijn er platforms die je goed vindt werken (bv. Airbnb, Treatwell, Studiotime)? Wat spreekt je daarin aan?</p><textarea class="auto" placeholder="Beschrijf hier…"></textarea></div>
          <div class="qbox"><span class="qlabel">Algemene opmerkingen</span><textarea class="auto" placeholder="Overige wensen of aandachtspunten voor de MVP…"></textarea></div>
          <div class="qbox"><span class="qlabel">Bijlagen <span class="opt">optioneel</span></span><p class="qhint">Schetsen, documenten of screenshots die relevant zijn. Afbeeldingen verschijnen in de PDF; andere bestanden graag los meesturen.</p><div class="uploader"><label class="upbtn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg> Bestand toevoegen<input type="file" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.csv" hidden onchange="handleFiles(this)"></label><div class="uplist"></div></div></div>
        </div>
      </section>

    </div>

    <div class="doc-footer">
      <strong>Klaar?</strong> Klik rechtsboven op <strong>Opslaan &amp; Versturen</strong> om je antwoorden veilig in het systeem op te slaan. Je krijgt daarna een eigen link waarmee je later verder kunt werken. Liever een PDF? Klik dan op <strong>PDF</strong> — bij het printen worden alle blokken automatisch uitgeklapt.
    </div>

  </main>

  <div class="toast" id="toast"></div>

  <script>
    window.EXISTING_SUBMISSION = @json($submission);
  </script>

@verbatim
<script>
  function grow(el){ el.style.height='auto'; el.style.height=(el.scrollHeight+2)+'px'; }
  function growAll(){ document.querySelectorAll('textarea.auto').forEach(grow); }
  function refreshOpenHeight(acc){ if(acc && acc.classList.contains('open')){ acc.querySelector('.acc-body').style.maxHeight='none'; } }

  function setOpen(acc, open){
    const body=acc.querySelector('.acc-body');
    const head=acc.querySelector('.acc-head');
    if(open){
      acc.classList.add('open'); head.setAttribute('aria-expanded','true');
      body.style.maxHeight=body.scrollHeight+'px';
      const done=()=>{ if(acc.classList.contains('open')) body.style.maxHeight='none'; body.removeEventListener('transitionend',done); };
      body.addEventListener('transitionend',done);
    } else {
      body.style.maxHeight=body.scrollHeight+'px';
      requestAnimationFrame(()=>requestAnimationFrame(()=>{ body.style.maxHeight='0px'; }));
      acc.classList.remove('open'); head.setAttribute('aria-expanded','false');
    }
  }
  function toggleAcc(btn){ const acc=btn.closest('.acc'); setOpen(acc, !acc.classList.contains('open')); }

  let allOpen=false;
  function updateToggleBtn(){ const b=document.getElementById('expandBtn'); if(b) b.textContent = allOpen ? 'Alles inklappen' : 'Alles uitklappen'; }
  function expandAll(){ document.querySelectorAll('.acc').forEach(a=>{ if(!a.classList.contains('open')) setOpen(a,true); }); allOpen=true; updateToggleBtn(); }
  function collapseAll(){ document.querySelectorAll('.acc').forEach(a=>{ if(a.classList.contains('open')) setOpen(a,false); }); allOpen=false; updateToggleBtn(); }
  function toggleAll(){ allOpen ? collapseAll() : expandAll(); }

  function goFunc(id){
    const acc=document.getElementById(id); if(!acc) return;
    if(!acc.classList.contains('open')) setOpen(acc,true);
    setTimeout(()=>acc.scrollIntoView({behavior:'smooth',block:'start'}), 60);
  }

  function rebuildNav(){
    const accs=[...document.querySelectorAll('#funcs .acc')];
    document.getElementById('pnav-chips').innerHTML = accs.map(a=>{
      const nm=a.querySelector('.acc-name').textContent;
      const num=a.querySelector('.acc-num').textContent;
      const filled=a.dataset.filled==='1';
      return '<button class="navchip" '+(filled?' data-filled="1"':'')+' onclick="goFunc(\''+a.id+'\')"><span class="nc-dot"></span><span class="nc-label">'+num+' · '+nm+'</span></button>';
    }).join('');
  }

  function updateFilled(acc){
    let filled=false;
    acc.querySelectorAll('.acc-inner textarea, .acc-inner input[type=text]').forEach(f=>{ if(f.value && f.value.trim()) filled=true; });
    acc.querySelectorAll('.acc-inner select').forEach(s=>{ if(s.selectedIndex>0) filled=true; });
    acc.dataset.filled = filled?'1':'0';
    rebuildNav();
  }

  document.addEventListener('input', e=>{
    const t=e.target;
    if(t.matches('textarea.auto')){ grow(t); refreshOpenHeight(t.closest('.acc')); }
    const acc = t.closest ? t.closest('.acc') : null;
    if(acc) updateFilled(acc);
  });
  document.addEventListener('change', e=>{
    const acc = e.target.closest ? e.target.closest('.acc') : null;
    if(acc) updateFilled(acc);
  });

  function stepEntry(actor){
    return '<div class="entry wstep">'+
      '<div class="entry-top"><span class="entry-tag">Stap</span><button type="button" class="xbtn" onclick="removeStep(this)" aria-label="Verwijderen">&times;</button></div>'+
      '<div class="entry-meta">'+
        '<div class="fcol"><label class="flabel">Wat doet de '+actor+'?</label><textarea class="auto" placeholder="Beschrijf de handeling…"></textarea></div>'+
        '<div class="fcol"><label class="flabel">Wat moet het systeem dan doen?</label><textarea class="auto" placeholder="Beschrijf de reactie van het systeem…"></textarea></div>'+
      '</div></div>';
  }
  function wfEntry(){
    return '<div class="entry wf">'+
      '<div class="entry-top"><span class="entry-tag">Scenario</span><button type="button" class="xbtn" onclick="removeStep(this)" aria-label="Verwijderen">&times;</button></div>'+
      '<label class="flabel">Naam van het scenario</label>'+
      '<input type="text" placeholder="bv. Artiest wil zijn sessie verlengen">'+
      '<label class="flabel" style="margin-top:10px">Beschrijf het proces in je eigen woorden</label>'+
      '<textarea class="auto" placeholder="Wat gebeurt er, wie doet wat, en wat moet het systeem doen?"></textarea>'+
    '</div>';
  }
  function renumberSteps(wrap){
    [...wrap.querySelectorAll('.entry.wstep .entry-tag')].forEach((t,i)=>t.textContent='Stap '+(i+1));
  }
  function addStep(btn, actor){
    const wrap=btn.closest('.qbox').querySelector('.entries');
    wrap.insertAdjacentHTML('beforeend', stepEntry(actor));
    wrap.lastElementChild.querySelectorAll('textarea.auto').forEach(grow);
    renumberSteps(wrap);
    refreshOpenHeight(btn.closest('.acc'));
  }
  function addWf(btn){
    const wrap=btn.closest('.qbox').querySelector('.entries');
    wrap.insertAdjacentHTML('beforeend', wfEntry());
    wrap.lastElementChild.querySelectorAll('textarea.auto').forEach(grow);
    refreshOpenHeight(btn.closest('.acc'));
  }
  function removeStep(btn){
    const entry=btn.closest('.entry'); const wrap=entry.parentElement; const acc=btn.closest('.acc');
    if(wrap.children.length>1){ entry.remove(); }
    else { entry.querySelectorAll('input,textarea').forEach(f=>f.value=''); }
    renumberSteps(wrap);
    if(acc) updateFilled(acc);
    refreshOpenHeight(acc);
  }

  function escapeHtml(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
  function fmtSize(b){ if(b<1024) return b+' B'; if(b<1048576) return (b/1024).toFixed(0)+' KB'; return (b/1048576).toFixed(1)+' MB'; }
  function fileSvg(){ return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>'; }

  function pickLogo(slot){ const i=slot.querySelector('input[type=file]'); if(i) i.click(); }
  function setLogo(input){
    const slot=input.closest('.logoslot');
    const f=input.files && input.files[0]; if(!f) return;
    const r=new FileReader();
    r.onload=e=>{ slot.querySelector('img.logo-img').src=e.target.result; slot.classList.add('has-logo'); };
    r.readAsDataURL(f); input.value='';
  }

  function csrfToken(){ const m=document.querySelector('meta[name="csrf-token"]'); return m ? m.content : ''; }

  function renderUpItem(list, data, isImage, previewSrc){
    const d=document.createElement('div');
    d.dataset.fileId = data.id;
    d.dataset.url = data.url || previewSrc || '';
    if(isImage){
      d.className='upitem upimg';
      d.innerHTML='<img src="'+(previewSrc||data.url||'')+'" alt=""><button type="button" class="upx" onclick="removeFile(this)" aria-label="Verwijderen">&times;</button><span class="upname">'+escapeHtml(data.original_name||'')+'</span>';
    } else {
      d.className='upitem upfile';
      d.innerHTML='<span class="upicon">'+fileSvg()+'</span><span class="upmeta"><span class="upname">'+escapeHtml(data.original_name||'')+'</span><span class="upsize">'+fmtSize(data.file_size||0)+'</span></span><button type="button" class="upx" onclick="removeFile(this)" aria-label="Verwijderen">&times;</button>';
    }
    list.appendChild(d);
    return d;
  }

  function handleFiles(input){
    const list=input.closest('.uploader').querySelector('.uplist');
    const acc=input.closest('.acc');
    Array.from(input.files||[]).forEach(f=>{
      const isImage = f.type.indexOf('image/')===0;
      const fd=new FormData(); fd.append('file', f);
      fetch('/uploads', { method:'POST', headers:{ 'X-CSRF-TOKEN':csrfToken() }, body:fd })
        .then(r=>r.json())
        .then(data=>{
          if(!data || !data.id){ showToast('Uploaden mislukt.', 'error'); return; }
          if(isImage){
            const rd=new FileReader();
            rd.onload=e=>{ renderUpItem(list, data, true, e.target.result); refreshOpenHeight(acc); };
            rd.readAsDataURL(f);
          } else {
            renderUpItem(list, data, false); refreshOpenHeight(acc);
          }
        })
        .catch(()=>showToast('Uploaden mislukt.', 'error'));
    });
    input.value='';
  }

  function removeFile(btn){
    const acc=btn.closest('.acc');
    const item=btn.closest('.upitem');
    const id=item.dataset.fileId;
    if(id){ fetch('/uploads/'+id, { method:'DELETE', headers:{ 'X-CSRF-TOKEN':csrfToken(), 'Content-Type':'application/json' } }).catch(()=>{}); }
    item.remove();
    refreshOpenHeight(acc);
  }

  // ===== TOAST =====
  function showToast(msg, type){
    const toast=document.getElementById('toast');
    if(!toast) return;
    toast.textContent=msg;
    toast.className='toast '+(type||'');
    requestAnimationFrame(()=>toast.classList.add('show'));
    clearTimeout(showToast._t);
    showToast._t=setTimeout(()=>toast.classList.remove('show'), 4500);
  }

  // ===== SERIALIZE / RESTORE =====
  // Walks the document treating checkbox groups, dynamic entry lists and
  // uploaders as single "units", so the same ordered walk drives saving,
  // restoring and the admin view — regardless of how many steps/scenarios exist.

  function labelFor(el){
    const fcol = el.closest('.fcol, .afield');
    if(fcol){ const fl=fcol.querySelector('.flabel'); if(fl) return fl.textContent.trim(); }
    const qbox = el.closest('.qbox, .meta-pill');
    if(qbox){ const ql=qbox.querySelector('.qlabel, label'); if(ql) return ql.textContent.trim(); }
    return el.getAttribute('placeholder') || '';
  }

  function unitsIn(root){
    const units=[];
    (function walk(node){
      for(const child of node.children){
        if(child.matches('.checks')){ units.push({el:child, type:'checks'}); }
        else if(child.matches('.entries')){ units.push({el:child, type: child.querySelector('.wstep') ? 'steps' : 'scenarios'}); }
        else if(child.matches('.uploader')){ units.push({el:child, type:'files'}); }
        else if(child.matches('input[type=text], textarea, select')){ units.push({el:child, type:'simple'}); }
        else { walk(child); }
      }
    })(root);
    return units;
  }

  function sectionMeta(section){
    const num = section.querySelector('.eyebrow .ey-num');
    const h2  = section.querySelector('.sec-head h2');
    return { id: num ? num.textContent.trim() : '', title: h2 ? h2.textContent.trim() : '' };
  }

  function readUnit(u){
    const el=u.el;
    if(u.type==='simple'){
      const isSelect = el.tagName==='SELECT';
      let val = el.value ? el.value.trim() : '';
      if(isSelect && (val==='' || val.startsWith('— kies'))) val='';
      return { type: isSelect ? 'select' : (el.tagName==='TEXTAREA' ? 'textarea' : 'text'), label: labelFor(el), value: val };
    }
    if(u.type==='checks'){
      const checked=[];
      el.querySelectorAll('input[type=checkbox]').forEach(cb=>{ if(cb.checked){ const s=cb.closest('.check').querySelector('span'); checked.push(s ? s.textContent.trim() : ''); } });
      return { type:'checks', label: labelFor(el.querySelector('input') || el), value: checked };
    }
    if(u.type==='steps'){
      const steps=[];
      el.querySelectorAll('.entry.wstep').forEach(entry=>{
        const fcols=entry.querySelectorAll('.fcol');
        const a=fcols[0] ? fcols[0].querySelector('textarea,input') : null;
        const b=fcols[1] ? fcols[1].querySelector('textarea,input') : null;
        const la=fcols[0] ? fcols[0].querySelector('.flabel') : null;
        const lb=fcols[1] ? fcols[1].querySelector('.flabel') : null;
        steps.push({ actor_label: la?la.textContent.trim():'', actor: a?a.value.trim():'', system_label: lb?lb.textContent.trim():'', system: b?b.value.trim():'' });
      });
      return { type:'steps', label: labelFor(el), value: steps };
    }
    if(u.type==='scenarios'){
      const scen=[];
      el.querySelectorAll('.entry.wf').forEach(entry=>{
        const nameEl=entry.querySelector('input[type=text]');
        const descEl=entry.querySelector('textarea');
        scen.push({ name: nameEl?nameEl.value.trim():'', description: descEl?descEl.value.trim():'' });
      });
      return { type:'scenarios', label: labelFor(el), value: scen };
    }
    if(u.type==='files'){
      const files=[];
      el.querySelectorAll('.upitem[data-file-id]').forEach(item=>{
        const img=item.querySelector('img');
        const nameEl=item.querySelector('.upname');
        files.push({ id: parseInt(item.dataset.fileId,10), name: nameEl?nameEl.textContent.trim():'', image: !!img, preview: item.dataset.url || (img?img.getAttribute('src'):'') });
      });
      return { type:'files', label: labelFor(el.querySelector('.upbtn') || el), value: files };
    }
    return null;
  }

  function collectFormData(){
    const meta = {
      datum: (document.getElementById('meta-datum').value||'').trim(),
      project_name: (document.getElementById('meta-project').value||'').trim(),
      contactpersoon: (document.getElementById('meta-contact').value||'').trim(),
    };
    const sections=[];
    const fileIds=[];
    document.querySelectorAll('.doc-inner > section').forEach(section=>{
      const sm=sectionMeta(section);
      const items=[];
      unitsIn(section).forEach(u=>{
        const rec=readUnit(u);
        if(!rec) return;
        const acc=u.el.closest('.acc');
        if(acc){ const nm=acc.querySelector('.acc-name'), nn=acc.querySelector('.acc-num'); rec.group=((nn?nn.textContent.trim()+' ':'')+(nm?nm.textContent.trim():'')).trim(); }
        if(rec.type==='files'){ rec.value.forEach(f=>{ if(f.id) fileIds.push(f.id); }); }
        items.push(rec);
      });
      sections.push({ id: sm.id, title: sm.title, items: items });
    });
    return {
      datum: meta.datum,
      project_name: meta.project_name,
      contactpersoon: meta.contactpersoon,
      answers: { meta: meta, sections: sections },
      file_ids: fileIds,
    };
  }

  function restoreUnit(u, rec){
    if(!rec) return;
    const el=u.el;
    if(u.type==='simple'){
      if(el.tagName==='SELECT'){
        for(let i=0;i<el.options.length;i++){ if(el.options[i].value===rec.value || el.options[i].text===rec.value){ el.selectedIndex=i; break; } }
      } else { el.value = rec.value||''; if(el.matches('textarea.auto')) grow(el); }
    }
    else if(u.type==='checks'){
      const want=new Set(rec.value||[]);
      el.querySelectorAll('.check').forEach(lbl=>{ const s=lbl.querySelector('span'); const cb=lbl.querySelector('input[type=checkbox]'); if(cb) cb.checked = s ? want.has(s.textContent.trim()) : cb.checked; });
    }
    else if(u.type==='steps'){
      const arr=rec.value||[]; if(!arr.length) return;
      const proto=el.querySelector('.entry.wstep');
      while(el.querySelectorAll('.entry.wstep').length < arr.length){ el.appendChild(proto.cloneNode(true)); }
      const entries=el.querySelectorAll('.entry.wstep');
      arr.forEach((s,i)=>{ const fcols=entries[i].querySelectorAll('.fcol'); const a=fcols[0]&&fcols[0].querySelector('textarea,input'); const b=fcols[1]&&fcols[1].querySelector('textarea,input'); if(a){a.value=s.actor||''; if(a.matches('textarea.auto'))grow(a);} if(b){b.value=s.system||''; if(b.matches('textarea.auto'))grow(b);} });
      el.querySelectorAll('.entry.wstep .entry-tag').forEach((t,i)=>t.textContent='Stap '+(i+1));
    }
    else if(u.type==='scenarios'){
      const arr=rec.value||[]; if(!arr.length) return;
      const proto=el.querySelector('.entry.wf');
      while(el.querySelectorAll('.entry.wf').length < arr.length){ el.appendChild(proto.cloneNode(true)); }
      const entries=el.querySelectorAll('.entry.wf');
      arr.forEach((s,i)=>{ const nameEl=entries[i].querySelector('input[type=text]'); const descEl=entries[i].querySelector('textarea'); if(nameEl)nameEl.value=s.name||''; if(descEl){descEl.value=s.description||''; if(descEl.matches('textarea.auto'))grow(descEl);} });
    }
    else if(u.type==='files'){
      const list=el.querySelector('.uplist'); if(!list) return;
      (rec.value||[]).forEach(f=>{ renderUpItem(list, { id:f.id, original_name:f.name, url:f.preview }, !!f.image, f.preview); });
    }
  }

  function populateForm(sub){
    if(!sub) return;
    const a = sub.answers || {};
    const meta = a.meta || {};
    if(meta.datum!==undefined) document.getElementById('meta-datum').value=meta.datum||'';
    if(meta.project_name!==undefined) document.getElementById('meta-project').value=meta.project_name||'';
    if(meta.contactpersoon!==undefined) document.getElementById('meta-contact').value=meta.contactpersoon||'';

    const savedSections = a.sections || [];
    document.querySelectorAll('.doc-inner > section').forEach((section, si)=>{
      const saved = savedSections[si];
      if(!saved || !saved.items) return;
      const units = unitsIn(section);
      units.forEach((u, ui)=>{ restoreUnit(u, saved.items[ui]); });
    });

    // Re-evaluate filled indicators for accordions.
    document.querySelectorAll('.acc').forEach(acc=>{ if(typeof updateFilled==='function') updateFilled(acc); });
  }

  // ===== SUBMIT =====
  function submitForm(){
    const btn=document.getElementById('saveBtn');
    const original=btn.innerHTML;
    btn.disabled=true;
    btn.innerHTML='<svg class="animate-spin" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg> Bezig met opslaan…';

    const data=collectFormData();
    const existing=window.EXISTING_SUBMISSION;
    const isEdit = existing && existing.id;
    const url = isEdit ? '/submissions/'+existing.id : '/submissions';
    const method = isEdit ? 'PUT' : 'POST';

    fetch(url, {
      method: method,
      headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':csrfToken(), 'Accept':'application/json' },
      body: JSON.stringify(data),
    })
    .then(res=>{ if(!res.ok) return res.json().then(err=>{ throw err; }); return res.json(); })
    .then(result=>{
      showToast('Uitvraag succesvol opgeslagen!', 'success');
      btn.innerHTML='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M20 6L9 17l-5-5"/></svg> Opgeslagen';
      if(!isEdit && result.id){ setTimeout(()=>{ window.location.href='/'+result.id; }, 1000); return; }
      if(result.id && !window.EXISTING_SUBMISSION){ window.EXISTING_SUBMISSION={ id: result.id }; }
      setTimeout(()=>{ btn.disabled=false; btn.innerHTML=original; }, 2500);
    })
    .catch(err=>{
      let msg='Er is iets misgegaan bij het opslaan.';
      if(err && err.errors){ msg=Object.values(err.errors).flat().join('\n'); }
      showToast(msg, 'error');
      btn.disabled=false; btn.innerHTML=original;
    });
  }

  // Auto-open the print dialog when opened with ?print=1 (used by "Exporteer als PDF").
  function triggerAutoPrint(){
    expandAll();
    growAll();
    const go=()=>setTimeout(()=>window.print(), 500);
    if(document.readyState==='complete') go();
    else window.addEventListener('load', go, { once:true });
  }

  (function(){
    rebuildNav();
    growAll();
    if(window.EXISTING_SUBMISSION){ populateForm(window.EXISTING_SUBMISSION); rebuildNav(); growAll(); }
    const wantPrint = new URLSearchParams(window.location.search).get('print')==='1';
    if(wantPrint){
      triggerAutoPrint();
    } else {
      const first=document.querySelector('#funcs .acc'); if(first) setOpen(first,true);
    }
  })();
</script>
@endverbatim
</body>
</html>
