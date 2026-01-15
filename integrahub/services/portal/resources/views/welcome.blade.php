<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('app.name', 'Integrahub') }}</title>

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

  <style>
    :root{
      --bg: #0b0d12;
      --panel: rgba(255,255,255,.06);
      --panel-2: rgba(255,255,255,.08);
      --text: rgba(255,255,255,.90);
      --muted: rgba(255,255,255,.62);
      --border: rgba(255,255,255,.10);
      --border-2: rgba(255,255,255,.14);
      --shadow: 0 18px 40px rgba(0,0,0,.35);
      --ring: rgba(125, 211, 252, .35);
      --accent: #7dd3fc;
      --accent-2: #a78bfa;
      --danger: #fb7185;
      --ok: #34d399;
      --radius: 16px;
      --radius-sm: 12px;
      --max: 1120px;
    }

    @media (prefers-color-scheme: light){
      :root{
        --bg: #f7f8fb;
        --panel: rgba(15, 23, 42, .04);
        --panel-2: rgba(15, 23, 42, .06);
        --text: rgba(15, 23, 42, .92);
        --muted: rgba(15, 23, 42, .62);
        --border: rgba(15, 23, 42, .10);
        --border-2: rgba(15, 23, 42, .14);
        --shadow: 0 18px 40px rgba(15, 23, 42, .12);
        --ring: rgba(59, 130, 246, .22);
        --accent: #2563eb;
        --accent-2: #7c3aed;
        --danger: #e11d48;
        --ok: #059669;
      }
    }

    *{ box-sizing:border-box; }
    html,body{ height:100%; }
    body{
      margin:0;
      font-family: "Instrument Sans", ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      background: radial-gradient(1000px 520px at 15% 10%, rgba(167,139,250,.18), transparent 60%),
                  radial-gradient(900px 520px at 85% 15%, rgba(125,211,252,.16), transparent 55%),
                  radial-gradient(900px 520px at 50% 95%, rgba(52,211,153,.10), transparent 55%),
                  var(--bg);
      color: var(--text);
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      line-height: 1.35;
    }

    a{ color:inherit; text-decoration:none; }
    .container{
      max-width: var(--max);
      margin: 0 auto;
      padding: 28px 18px;
      min-height: 100%;
      display:flex;
      flex-direction:column;
      gap: 18px;
    }

    .topbar{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap: 12px;
    }

    .brand{
      display:flex; align-items:center; gap: 10px;
      letter-spacing: .2px;
    }
    .brandMark{
      width: 12px; height: 12px;
      border-radius: 999px;
      background: linear-gradient(135deg, var(--accent), var(--accent-2));
      box-shadow: 0 0 0 6px rgba(125,211,252,.10);
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
      display:flex; align-items:center; gap: 10px;
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
      background: var(--panel-2);
      border-color: var(--border-2);
      transform: translateY(-1px);
    }
    .btn:focus{
      outline: none;
      box-shadow: 0 0 0 4px var(--ring);
    }
    .btnPrimary{
      border-color: rgba(125,211,252,.28);
      background: linear-gradient(135deg, rgba(125,211,252,.16), rgba(167,139,250,.14));
    }

    .hero{
      border: 1px solid var(--border);
      background: linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.04));
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
      padding: 34px 32px;
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
      font-size: 34px;
      line-height: 1.05;
      letter-spacing: -0.02em;
      margin:0;
      font-weight: 750;
    }
    .subtitle{
      margin:0;
      max-width: 62ch;
      color: var(--muted);
      font-size: 14px;
      line-height: 1.55;
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
        radial-gradient(700px 300px at 20% 25%, rgba(125,211,252,.18), transparent 55%),
        radial-gradient(700px 320px at 80% 35%, rgba(167,139,250,.18), transparent 55%),
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
  </style>
</head>

<body>
  <div class="container">
    <div class="topbar">
      <div class="brand">
        <span class="brandMark" aria-hidden="true"></span>
        <div>
          <div class="brandName">{{ config('app.name', 'Integrahub') }}</div>
          <div class="brandSub">Portal interno · Integración de sistemas</div>
        </div>
      </div>

      <div class="navActions">
        @if (Route::has('login'))
          @auth
            <a class="btn btnPrimary" href="{{ url('/dashboard') }}">Entrar al portal</a>
          @else
            <a class="btn" href="{{ route('login') }}">Iniciar sesión</a>
            @if (Route::has('register'))
              <a class="btn btnPrimary" href="{{ route('register') }}">Crear cuenta</a>
            @endif
          @endauth
        @endif
      </div>
    </div>

    <div class="hero">
      <div class="heroGrid">
        <div class="heroLeft">
          <div class="kicker">IntegraHub</div>
          <h1 class="title">Interfaz mínima y moderna para tu demo</h1>
          <p class="subtitle">
            Una base limpia para Orders, Inventory y Analytics, con consistencia visual y enfoque en legibilidad.
            Sin elementos recargados ni estilos heredados del starter.
          </p>

          <div class="pillRow">
            <span class="pill">UI consistente</span>
            <span class="pill">Dark mode automático</span>
            <span class="pill">Componentes minimalistas</span>
            <span class="pill">Accesible y responsivo</span>
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
            <a class="btn" href="https://laravel.com/docs" target="_blank" rel="noreferrer">Documentación</a>
          </div>
        </div>

        <div class="heroRight">
          <div class="miniCard">
            <div class="miniLabel">Módulos</div>
            <div class="miniValue">Orders · Inventory · Analytics</div>
          </div>

          <div class="miniCard">
            <div class="miniLabel">Objetivo</div>
            <div class="miniValue">Demo clara y operable</div>
          </div>

          <div class="miniCard">
            <div class="miniLabel">Estilo</div>
            <div class="miniValue">Minimal · 2026 · Enfocado</div>
          </div>
        </div>
      </div>
    </div>

    <div class="footer">
      <div>© {{ date('Y') }} IntegraHub</div>
      <div><a href="https://laravel.com" target="_blank" rel="noreferrer">Laravel</a></div>
    </div>
  </div>
</body>
</html>
