<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kalkulator FPB — Euclidean</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300&family=DM+Mono:wght@300;400&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:        #0c0c0e;
      --surface:   #111114;
      --border:    #2a2a30;
      --border-hi: #c9a96e;
      --gold:      #c9a96e;
      --gold-dim:  #8a6f3e;
      --text:      #e8e4da;
      --muted:     #6b6760;
      --green:     #7ec99a;
      --red:       #d47a7a;
      --font-serif: 'Cormorant Garamond', Georgia, serif;
      --font-mono:  'DM Mono', 'Courier New', monospace;
    }

    body {
      font-family: var(--font-serif);
      background-color: var(--bg);
      color: var(--text);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding: 60px 16px 80px;
      position: relative;
      overflow-x: hidden;
    }

    /* Subtle background texture */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background:
        radial-gradient(ellipse 60% 50% at 20% 10%, rgba(201,169,110,0.055) 0%, transparent 70%),
        radial-gradient(ellipse 50% 60% at 85% 85%, rgba(201,169,110,0.035) 0%, transparent 70%);
      pointer-events: none;
      z-index: 0;
    }

    .container {
      width: 100%;
      max-width: 500px;
      position: relative;
      z-index: 1;
      animation: fadeUp .7s cubic-bezier(.16,1,.3,1) both;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(24px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Header ── */
    .header {
      text-align: center;
      margin-bottom: 44px;
    }

    .header-eyebrow {
      font-family: var(--font-mono);
      font-size: 10px;
      letter-spacing: .25em;
      text-transform: uppercase;
      color: var(--gold-dim);
      margin-bottom: 10px;
    }

    .header h1 {
      font-size: clamp(28px, 6vw, 38px);
      font-weight: 300;
      letter-spacing: .02em;
      line-height: 1.15;
      color: var(--text);
    }

    .header h1 em {
      font-style: italic;
      color: var(--gold);
    }

    .divider {
      width: 48px;
      height: 1px;
      background: linear-gradient(90deg, transparent, var(--gold), transparent);
      margin: 18px auto 0;
    }

    /* ── Card ── */
    .card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 2px;
      padding: 40px 36px;
      position: relative;
    }

    /* Corner ornaments */
    .card::before, .card::after {
      content: '';
      position: absolute;
      width: 12px;
      height: 12px;
      border-color: var(--gold-dim);
      border-style: solid;
    }
    .card::before { top: -1px; left: -1px; border-width: 1px 0 0 1px; }
    .card::after  { bottom: -1px; right: -1px; border-width: 0 1px 1px 0; }

    /* ── Form ── */
    .field { margin-bottom: 24px; }

    .field label {
      display: block;
      font-family: var(--font-mono);
      font-size: 10px;
      letter-spacing: .18em;
      text-transform: uppercase;
      color: var(--muted);
      margin-bottom: 8px;
    }

    .field input[type="text"] {
      width: 100%;
      padding: 12px 16px;
      font-family: var(--font-mono);
      font-size: 18px;
      font-weight: 300;
      color: var(--text);
      background: var(--bg);
      border: 1px solid var(--border);
      border-radius: 1px;
      outline: none;
      transition: border-color .2s, box-shadow .2s;
      -webkit-appearance: none;
    }

    .field input[type="text"]:focus {
      border-color: var(--gold-dim);
      box-shadow: 0 0 0 3px rgba(201,169,110,.08);
    }

    .field input[type="text"]::placeholder { color: #3a3a40; }

    /* ── Button ── */
    .btn-row { margin-top: 8px; }

    button[type="submit"] {
      width: 100%;
      padding: 14px 20px;
      font-family: var(--font-mono);
      font-size: 11px;
      letter-spacing: .2em;
      text-transform: uppercase;
      color: var(--bg);
      background: var(--gold);
      border: none;
      border-radius: 1px;
      cursor: pointer;
      transition: background .2s, transform .15s;
    }

    button[type="submit"]:hover  { background: #dbbe82; }
    button[type="submit"]:active { transform: scale(.99); }

    /* ── Result ── */
    .result-wrap {
      margin-top: 32px;
      animation: fadeUp .45s cubic-bezier(.16,1,.3,1) both;
    }

    .result-label {
      font-family: var(--font-mono);
      font-size: 9px;
      letter-spacing: .22em;
      text-transform: uppercase;
      color: var(--gold-dim);
      margin-bottom: 12px;
    }

    .result-box {
      border: 1px solid var(--border);
      border-left: 2px solid var(--gold);
      padding: 20px 22px;
      background: rgba(201,169,110,.03);
      border-radius: 0 1px 1px 0;
    }

    .result-main {
      font-size: 15px;
      font-weight: 300;
      letter-spacing: .01em;
      line-height: 1.6;
      color: var(--text);
      margin-bottom: 8px;
    }

    .result-main strong {
      font-family: var(--font-mono);
      font-size: 26px;
      font-weight: 400;
      color: var(--gold);
      display: block;
      margin: 4px 0 10px;
    }

    .badge {
      display: inline-block;
      font-family: var(--font-mono);
      font-size: 9px;
      letter-spacing: .18em;
      text-transform: uppercase;
      padding: 4px 10px;
      border-radius: 1px;
      margin-bottom: 18px;
    }
    .badge.prima    { background: rgba(126,201,154,.12); color: var(--green); border: 1px solid rgba(126,201,154,.25); }
    .badge.no-prima { background: rgba(212,122,122,.10); color: var(--red);   border: 1px solid rgba(212,122,122,.22); }

    .steps-title {
      font-family: var(--font-mono);
      font-size: 9px;
      letter-spacing: .18em;
      text-transform: uppercase;
      color: var(--muted);
      margin-bottom: 10px;
    }

    .steps-list {
      display: flex;
      flex-direction: column;
      gap: 5px;
    }

    .step-item {
      font-family: var(--font-mono);
      font-size: 12px;
      color: #555;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: color .15s;
    }

    .step-item:last-child { color: var(--muted); }

    .step-item::before {
      content: '';
      width: 4px; height: 4px;
      border-radius: 50%;
      background: var(--border);
      flex-shrink: 0;
    }
    .step-item:last-child::before { background: var(--gold-dim); }

    .error-box {
      border: 1px solid rgba(212,122,122,.25);
      border-left: 2px solid var(--red);
      padding: 14px 18px;
      background: rgba(212,122,122,.05);
      font-family: var(--font-mono);
      font-size: 12px;
      color: var(--red);
      letter-spacing: .04em;
      margin-top: 24px;
    }

    /* ── Footer ── */
    .footer {
      text-align: center;
      margin-top: 32px;
      font-family: var(--font-mono);
      font-size: 9px;
      letter-spacing: .15em;
      text-transform: uppercase;
      color: #2e2e34;
    }
  </style>
</head>
<body>
<div class="container">

  <div class="header">
    <p class="header-eyebrow">Algoritma Euclidean</p>
    <h1>Kalkulator <em>FPB</em></h1>
    <div class="divider"></div>
  </div>

  <div class="card">
    <form method="POST" action="">
      <div class="field">
        <label for="angka1">Nilai A</label>
        <input type="text" id="angka1" name="angka1" placeholder="misal: 48"
          value="<?php echo isset($_POST['angka1']) ? htmlspecialchars($_POST['angka1']) : ''; ?>">
      </div>

      <div class="field">
        <label for="angka2">Nilai B</label>
        <input type="text" id="angka2" name="angka2" placeholder="misal: 18"
          value="<?php echo isset($_POST['angka2']) ? htmlspecialchars($_POST['angka2']) : ''; ?>">
      </div>

      <div class="btn-row">
        <button type="submit">Hitung FPB</button>
      </div>
    </form>

<?php
// ============================================================
// Fungsi Algoritma Euclidean
// ============================================================
function hitungFPB($a, $b, &$langkah) {
    $langkah = [];
    while ($b != 0) {
        $sisa = $a % $b;
        $langkah[] = "$a mod $b = $sisa";
        $a = $b;
        $b = $sisa;
    }
    return $a;
}

// ============================================================
// Proses form jika sudah di-submit
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputA = trim($_POST['angka1']);
    $inputB = trim($_POST['angka2']);

    if (!is_numeric($inputA) || !is_numeric($inputB)
        || intval($inputA) <= 0 || intval($inputB) <= 0) {
        echo '<div class="error-box">⚠ Masukkan angka bulat positif yang valid.</div>';
    } else {
        $a = intval($inputA);
        $b = intval($inputB);
        $langkah = [];
        $fpb = hitungFPB($a, $b, $langkah);

        $isPrima = ($fpb == 1);
        $badgeClass  = $isPrima ? 'prima' : 'no-prima';
        $badgeText   = $isPrima ? 'Relatif Prima' : 'Tidak Relatif Prima';

        echo '<div class="result-wrap">';
        echo '<p class="result-label">Hasil Perhitungan</p>';
        echo '<div class="result-box">';
        echo "<p class=\"result-main\">FPB dari {$a} dan {$b}<strong>{$fpb}</strong></p>";
        echo "<span class=\"badge {$badgeClass}\">{$badgeText}</span>";

        if (!empty($langkah)) {
            echo '<p class="steps-title">Langkah Euclidean</p>';
            echo '<div class="steps-list">';
            foreach ($langkah as $step) {
                echo "<div class=\"step-item\">{$step}</div>";
            }
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';
    }
}
?>
  </div>

  <p class="footer">Greatest Common Divisor &nbsp;·&nbsp; Euclidean Algorithm</p>

</div>
</body>
</html>