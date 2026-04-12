<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Kalkulation - {{ $inquiry->angebot_nummer }}</title>
    <style>
/* Page */
@page {
    margin: 60px 60px;
}

body {
    font-family: Helvetica, Arial, sans-serif;
    font-size: 13px;
    color: #1e293b;
    line-height: 1.6;
    position: relative;
    
}

/* 🔥 Watermark logo */
.watermark {
    position: fixed;
    top: 40%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0.06;
    z-index: 0;
}

.watermark img {
    width: 400px;
}



/* Header */
.header {
    position: relative;
    z-index: 2;
    
}

.header-table {
    width: 100%;
}

.logo {
    width: 140px;
}

.company {
    font-size: 12px;
    color: #64748b;
}

/* Title */
.title {
    margin-top:30px;
    align-items: center;
}

.title h1 {
    color: #0b3d91;
}

.subtitle {
    font-size: 12px;
    color: #94a3b8;
}

/* Info */
.info-table {
    width: 100%;
    margin-bottom: 30px;
}

.info-label {
    color: #64748b;
    width: 180px;
    padding: 6px 0;
}

.info-value {
    font-weight: 600;
}

/* Table */
.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.table th {
    text-align: left;
    font-size: 11px;
    text-transform: uppercase;
    color: #64748b;
    padding: 10px 6px;
    border-bottom: 2px solid #e2e8f0;
}

.table td {
    padding: 12px 6px;
    border-bottom: 1px solid #f1f5f9;
}

.right {
    text-align: right;
}
.th-right {
    text-align: right !important;
}

/* Total */
.total-box {
    margin-top: 30px;
    width: 100%;
}

.total-row {
    border-top: 2px solid #0b3d91;
}

.total-label {
    padding: 14px 6px;
    font-weight: 600;
    font-size: 18px;
}

.total-value {
    padding: 14px 6px;
    font-size: 18px;
    font-weight: bold;
    color: #0b3d91;
}

/* Footer */
.footer {
    margin-top: 60px;
    font-size: 11px;
    color: #94a3b8;
    text-align: center;
}

.footer a {
    color: #0b3d91;
    text-decoration: none;
}

    </style>
</head>
<body>


<div class="watermark">
    <img src="{{ public_path('logo.png')}}">
    <h1 style="text-align: center;">Unverbindliche Kostenschätzung</h1>
    
</div>
    <!-- Header Grid -->
   <div class="header">
    <table class="header-table">
        <tr>
            <td>
                <img src="{{ public_path('logo.png') }}" class="logo">
                <div class="company">
                    Webdesign & Digitalisierung<br>
                    Bloherfelder Str. 71<br>
                    26129 Oldenburg<br>
                    0441 18018566<br>
                    info@agentur-77.de
                </div>
            </td>

            <td style="text-align:right;">
                <div class="subtitle">
                    @if(Str::startsWith($inquiry->angebot_nummer, 'BOT-'))
                        Angebot-Nr
                    @else
                        Projekt
                    @endif
                </div>
                <div><strong>{{ $inquiry->angebot_nummer }}</strong></div>

                <div class="subtitle" style="margin-top:10px;">Datum</div>
                <div>{{ $inquiry->created_at->format('d.m.Y') }}</div>
            </td>
        </tr>
    </table>
</div>
   
    <!-- Title Bar -->
    <div class="title">
    <h1>Unverbindliche Kostenschätzung</h1>
    
</div>

    <!-- Inquiry Info -->
    <table class="table">
    <thead>
        <tr>
            <th style="width:45%">Leistung</th>
            <th style="width:30%">Auswahl</th>
            <th class="th-right" style="width:25%">Preis (€)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($inquiry->items as $item)
        <tr>
            <td>{{ $item->priceModule ? $item->priceModule->bezeichnung_de : '-' }}</td>
            <td>{{ $item->kunden_auswahl ?? ($item->menge > 1 ? $item->menge . 'x' : 'Ja') }}</td>
            <td class="right">
                {{ number_format($item->preis_zum_zeitpunkt * $item->menge, 2, ',', '.') }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

    <!-- Totals -->
    <table class="total-box">
    <tr class="total-row">
        <td class="total-label">Gesamtsumme</td>
        <td class="total-value right">
            {{ number_format($inquiry->geschätzter_gesamtpreis, 2, ',', '.') }} €
        </td>
    </tr>
</table>

    <!-- Footer -->
    <div class="footer">
    Diese Kalkulation ist unverbindlich und dient zur Orientierung.<br>
    
    ** Für zukünftige fragen bitte angebotnummer angeben: <strong>{{ $inquiry->angebot_nummer }}</strong> **<br>
     <a href="https://www.agentur-77.de/designvorschlag/#kontakt">
        Für eine detaillierte Beratung und ein maßgeschneidertes Angebot kontaktieren Sie uns gerne.
    </a>
    
</div>

</body>
</html>
