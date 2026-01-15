<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('app.name', 'Integrahub') }}</title>

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|ibm-plex-mono:400,500" rel="stylesheet" />

  <style>
    :root{
      --bg:#0c0b0a;
      --bg-2:#141110;
      --text: rgba(255,255,255,.94);
      --muted: rgba(255,255,255,.6);
      --panel: rgba(255,255,255,.06);
      --panel2: rgba(255,255,255,.1);
      --border: rgba(255,255,255,.12);
      --border2: rgba(255,255,255,.2);
      --shadow: 0 20px 50px rgba(0,0,0,.45);
      --ring: rgba(20,184,166,.35);
      --accent: #14b8a6;
      --accent-2: #f59e0b;
      --accent-3: #fb7185;
      --radius: 18px;
      --radius-sm: 12px;
      --max: 1120px;
      --grid: rgba(255,255,255,.05);
    }

    @media (prefers-color-scheme: light){
      :root{
        --bg:#f6f2ec;
        --bg-2:#fffdf9;
        --text: rgba(17,24,39,.92);
        --muted: rgba(17,24,39,.62);
        --panel: rgba(17,24,39,.04);
        --panel2: rgba(17,24,39,.06);
        --border: rgba(17,24,39,.12);
        --border2: rgba(17,24,39,.2);
        --shadow: 0 16px 40px rgba(17,24,39,.12);
        --ring: rgba(15,118,110,.28);
        --accent: #0f766e;
        --accent-2: #b45309;
        --accent-3: #e11d48;
        --grid: rgba(17,24,39,.06);
      }
    }

    *{ box-sizing:border-box; }
    html,body{ height:100%; }
    body{
      margin:0;
      font-family: "Space Grotesk", "IBM Plex Sans", ui-sans-serif, system-ui, -apple-system, "Segoe UI", Arial, sans-serif;
      background:
        radial-gradient(1100px 600px at 10% -10%, rgba(20,184,166,.22), transparent 60%),
        radial-gradient(1000px 520px at 90% 0%, rgba(245,158,11,.2), transparent 60%),
        radial-gradient(900px 520px at 50% 100%, rgba(248,113,113,.12), transparent 60%),
        var(--bg);
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
      opacity: .18;
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
      width: 38px; height: 38px;
      border-radius: 12px;
      background: linear-gradient(135deg, rgba(20,184,166,.9), rgba(245,158,11,.9));
      box-shadow: 0 10px 20px rgba(20,184,166,.25);
      display:flex;
      align-items:center;
      justify-content:center;
      font-weight: 700;
      color: #0b0a09;
      font-size: 14px;
    }
    .brandName{
      font-weight: 700;
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
      padding: 10px 12px;
      border-radius: 12px;
      font-weight: 600;
      font-size: 13px;
      transition: transform .12s ease, background .12s ease, border-color .12s ease;
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
      border-color: rgba(20,184,166,.28);
      background: linear-gradient(135deg, rgba(20,184,166,.18), rgba(245,158,11,.18));
    }

    .hero{
      border: 1px solid var(--border);
      background: linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.02));
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow:hidden;
      position: relative;
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
      font-size: 12px;
      color: var(--muted);
      letter-spacing: .12em;
      text-transform: uppercase;
    }
    .title{
      font-size: 40px;
      line-height: 1.05;
      letter-spacing: -0.02em;
      margin:0;
      font-weight: 760;
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
      background: rgba(255,255,255,.04);
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
        radial-gradient(700px 300px at 20% 25%, rgba(20,184,166,.2), transparent 55%),
        radial-gradient(700px 320px at 80% 35%, rgba(245,158,11,.18), transparent 55%),
        rgba(255,255,255,.02);
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
      background: rgba(255,255,255,.04);
      border-radius: var(--radius-sm);
      padding: 14px 14px;
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
            <span class="pill">Diseño consistente</span>
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
      <div>© {{ date('Y') }} IntegraHub</div>
      <div><a href="https://laravel.com" target="_blank" rel="noreferrer">Laravel</a></div>
    </div>
  </div>
</body>
</html>
