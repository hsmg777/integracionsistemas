<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('app.name', 'Integrahub') }}</title>

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800|space-mono:400,700" rel="stylesheet" />

  <style>
    :root{
      --bg:#f7f7f2;
      --bg-2:#eef2f7;
      --text:#0c111b;
      --muted:#5b6474;
      --panel: rgba(255,255,255,.7);
      --panel2: rgba(255,255,255,.9);
      --border: rgba(15,23,42,.12);
      --border2: rgba(15,23,42,.2);
      --shadow: 0 24px 60px rgba(15,23,42,.12);
      --ring: rgba(37,99,235,.25);
      --accent: #2563eb;
      --accent-2: #0ea5a4;
      --accent-3: #f59e0b;
      --radius: 20px;
      --radius-sm: 14px;
      --max: 1120px;
      --grid: rgba(15,23,42,.06);
    }

    @media (prefers-color-scheme: dark){
      :root{
        --bg:#0b0f14;
        --bg-2:#0f1520;
        --text: rgba(255,255,255,.92);
        --muted: rgba(255,255,255,.6);
        --panel: rgba(15,23,42,.6);
        --panel2: rgba(15,23,42,.8);
        --border: rgba(148,163,184,.2);
        --border2: rgba(148,163,184,.35);
        --shadow: 0 24px 60px rgba(0,0,0,.4);
        --ring: rgba(14,165,164,.35);
        --grid: rgba(148,163,184,.08);
      }
    }

    *{ box-sizing:border-box; }
    html,body{ height:100%; }
    body{
      margin:0;
      font-family: "Manrope", "Segoe UI", ui-sans-serif, system-ui, -apple-system, Arial, sans-serif;
      background:
        radial-gradient(900px 480px at 8% -10%, rgba(37,99,235,.18), transparent 60%),
        radial-gradient(900px 520px at 92% 5%, rgba(14,165,164,.18), transparent 60%),
        linear-gradient(180deg, var(--bg) 0%, var(--bg-2) 100%);
      color: var(--text);
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      line-height: 1.4;
    }
    body::before{
      content:"";
      position: fixed;
      inset: 0;
      background-image:
        linear-gradient(to right, var(--grid) 1px, transparent 1px),
        linear-gradient(to bottom, var(--grid) 1px, transparent 1px);
      background-size: 140px 140px;
      opacity: .22;
      pointer-events: none;
      mask-image: radial-gradient(circle at 20% 0%, rgba(0,0,0,.9), transparent 65%);
    }

    a{ color:inherit; text-decoration:none; }
    .container{
      max-width: var(--max);
      margin: 0 auto;
      padding: 30px 18px 48px;
      min-height: 100%;
      display:flex;
      flex-direction:column;
      gap: 18px;
      position: relative;
      z-index: 1;
    }

    .topbar{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap: 16px;
    }

    .brand{
      display:flex; align-items:center; gap: 12px;
      letter-spacing: .2px;
    }
    .brandMark{
      width: 40px; height: 40px;
      border-radius: 14px;
      background: linear-gradient(135deg, rgba(37,99,235,.92), rgba(14,165,164,.92));
      box-shadow: 0 12px 24px rgba(37,99,235,.25);
      display:flex;
      align-items:center;
      justify-content:center;
      font-weight: 800;
      color: #f8fafc;
      font-size: 13px;
      letter-spacing: .08em;
    }
    .brandName{
      font-weight: 800;
      font-size: 14px;
      color: var(--text);
    }
    .brandSub{
      font-size: 12px;
      color: var(--muted);
      margin-top: 2px;
    }

    .navActions{
      display:flex; align-items:center; gap: 10px; flex-wrap:wrap;
    }

    .btn{
      border: 1px solid var(--border);
      background: var(--panel);
      color: var(--text);
      padding: 10px 14px;
      border-radius: 12px;
      font-weight: 700;
      font-size: 13px;
      transition: transform .12s ease, background .12s ease, border-color .12s ease;
      backdrop-filter: blur(10px);
    }
    .btn:hover{
      background: var(--panel2);
      border-color: var(--border2);
      transform: translateY(-1px);
    }
    .btn:focus{
      outline: none;
      box-shadow: 0 0 0 4px var(--ring);
    }
    .btnPrimary{
      border-color: rgba(37,99,235,.35);
      background: linear-gradient(135deg, rgba(37,99,235,.18), rgba(14,165,164,.18));
    }

    .hero{
      border: 1px solid var(--border);
      background: var(--panel);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow:hidden;
      position: relative;
      backdrop-filter: blur(16px);
    }

    .heroGrid{
      display:grid;
      grid-template-columns: 1.2fr .8fr;
      gap: 0;
    }

    @media (max-width: 920px){
      .heroGrid{ grid-template-columns: 1fr; }
    }

    .heroLeft{
      padding: 36px 34px;
      display:flex;
      flex-direction:column;
      gap: 16px;
    }
    .kicker{
      font-size: 11px;
      color: var(--muted);
      letter-spacing: .2em;
      text-transform: uppercase;
    }
    .title{
      font-size: 42px;
      line-height: 1.04;
      letter-spacing: -0.02em;
      margin:0;
      font-weight: 800;
    }
    .subtitle{
      margin:0;
      max-width: 62ch;
      color: var(--muted);
      font-size: 14px;
      line-height: 1.6;
    }

    .pillRow{
      display:flex;
      flex-wrap:wrap;
      gap: 10px;
      margin-top: 2px;
    }
    .pill{
      font-size: 12px;
      padding: 8px 10px;
      border-radius: 999px;
      border: 1px solid var(--border);
      background: var(--panel2);
      color: var(--muted);
    }

    .ctaRow{
      display:flex;
      gap: 10px;
      flex-wrap:wrap;
      margin-top: 10px;
    }

    .heroRight{
      border-left: 1px solid var(--border);
      background:
        radial-gradient(700px 300px at 20% 25%, rgba(37,99,235,.18), transparent 55%),
        radial-gradient(700px 320px at 80% 35%, rgba(14,165,164,.18), transparent 55%),
        var(--panel2);
      padding: 34px 28px;
      display:flex;
      flex-direction:column;
      justify-content:space-between;
      gap: 16px;
    }

    @media (max-width: 920px){
      .heroRight{ border-left: none; border-top: 1px solid var(--border); }
    }

    .miniCard{
      border: 1px solid var(--border);
      background: var(--panel2);
      border-radius: var(--radius-sm);
      padding: 14px 14px;
      backdrop-filter: blur(10px);
    }
    .miniLabel{
      font-size: 12px;
      color: var(--muted);
      margin-bottom: 6px;
    }
    .miniValue{
      font-size: 14px;
      font-weight: 700;
      color: var(--text);
    }

    .footer{
      margin-top:auto;
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap: 12px;
      color: var(--muted);
      font-size: 12px;
      padding: 10px 2px;
    }
    .footer a{
      text-decoration: underline;
      text-underline-offset: 3px;
    }

    .reveal{
      opacity: 0;
      transform: translateY(10px);
      animation: rise .6s ease forwards;
      animation-delay: var(--delay, 0s);
    }
    @keyframes rise{
      to{ opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 760px){
      .topbar{ flex-direction: column; align-items:flex-start; }
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="topbar reveal" style="--delay:.05s;">
      <div class="brand">
        <span class="brandMark" aria-hidden="true">IH</span>
        <div>
          <div class="brandName">{{ config('app.name', 'Integrahub') }}</div>
          <div class="brandSub">Portal interno de integracion</div>
        </div>
      </div>

      <div class="navActions">
        @if (Route::has('login'))
          @auth
            <a class="btn btnPrimary" href="{{ url('/dashboard') }}">Entrar al portal</a>
          @else
            <a class="btn" href="{{ route('login') }}">Iniciar sesion</a>
            @if (Route::has('register'))
              <a class="btn btnPrimary" href="{{ route('register') }}">Crear cuenta</a>
            @endif
          @endauth
        @endif
      </div>
    </div>

    <div class="hero reveal" style="--delay:.1s;">
      <div class="heroGrid">
        <div class="heroLeft">
          <div class="kicker">IntegraHub</div>
          <h1 class="title">UI clara, audaz y enfocada en operaciones</h1>
          <p class="subtitle">
            Un portal limpio para Orders, Inventory y Analytics con una experiencia visual consistente.
            Menos ruido, mas lectura y datos al frente.
          </p>

          <div class="pillRow">
            <span class="pill">Diseno consistente</span>
            <span class="pill">Soporte responsive</span>
            <span class="pill">Legibilidad alta</span>
            <span class="pill">Dashboard rapido</span>
          </div>

          <div class="ctaRow">
            @if (Route::has('login'))
              @auth
                <a class="btn btnPrimary" href="{{ url('/dashboard') }}">Abrir portal</a>
              @else
                <a class="btn btnPrimary" href="{{ route('login') }}">Acceder</a>
                @if (Route::has('register'))
                  <a class="btn" href="{{ route('register') }}">Registrar</a>
                @endif
              @endauth
            @endif
            <a class="btn" href="https://laravel.com/docs" target="_blank" rel="noreferrer">Documentacion</a>
          </div>
        </div>

        <div class="heroRight">
          <div class="miniCard">
            <div class="miniLabel">Modulos</div>
            <div class="miniValue">Orders / Inventory / Analytics</div>
          </div>

          <div class="miniCard">
            <div class="miniLabel">Objetivo</div>
            <div class="miniValue">Demo clara y operable</div>
          </div>

          <div class="miniCard">
            <div class="miniLabel">Estilo</div>
            <div class="miniValue">Minimal / 2026 / Focus</div>
          </div>
        </div>
      </div>
    </div>

    <div class="footer reveal" style="--delay:.16s;">
      <div>c {{ date('Y') }} IntegraHub</div>
      <div><a href="https://laravel.com" target="_blank" rel="noreferrer">Laravel</a></div>
    </div>
  </div>
</body>
</html>
