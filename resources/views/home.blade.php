@extends('layouts.app')

@section('content')
<div class="hero">
  <div class="hero-flanks">
    <div class="gunung">
      <svg width="76" height="116" viewBox="0 0 100 150">
        <path d="M50 0 L15 65 Q5 80 20 95 L20 120 Q20 130 30 130 L70 130 Q80 130 80 120 L80 95 Q95 80 85 65 Z" fill="#c9a84c"/>
        <path d="M50 18 V120 M50 18 C40 30 30 50 20 70 M50 18 C60 30 70 50 80 70" fill="none" stroke="rgba(0,0,0,.22)" stroke-width=".7"/>
        <path d="M42 95 L42 125 L58 125 L58 95 Q50 85 42 95" fill="rgba(0,0,0,.38)"/>
        <rect x="47" y="130" width="6" height="17" fill="#c9a84c" rx="2"/>
      </svg>
    </div>
    <div style="text-align:center">
      <span class="hero-jawa">ꦗꦮꦲꦺꦴꦠꦺꦭ꧀</span>
      <h1 class="hero-h1">JAVA<br>HOTEL</h1>
    </div>
    <div class="gunung" style="transform:scaleX(-1)">
      <svg width="76" height="116" viewBox="0 0 100 150">
        <path d="M50 0 L15 65 Q5 80 20 95 L20 120 Q20 130 30 130 L70 130 Q80 130 80 120 L80 95 Q95 80 85 65 Z" fill="#c9a84c"/>
        <path d="M42 95 L42 125 L58 125 L58 95 Q50 85 42 95" fill="rgba(0,0,0,.38)"/>
        <rect x="47" y="130" width="6" height="17" fill="#c9a84c" rx="2"/>
      </svg>
    </div>
  </div>
  <div class="hero-line"></div>
  <p class="hero-tag">Puri Warisan Jawa &nbsp;·&nbsp; Yogyakarta &nbsp;·&nbsp; Est. 1987</p>
  <div class="hero-cta">
    <form action="{{ route('check') }}" method="GET" style="margin:0">
      <div class="hero-sbox">
        <input type="text" name="nik" placeholder="MASUKKAN NIK TAMU">
        <button type="submit" class="btn btn-gold" style="border-radius:0 8px 8px 0;">Cek Status</button>
      </div>
    </form>
    <button onclick="openM('bookM')" class="btn btn-ghost"><i data-lucide="calendar-plus"></i> Reservasi</button>
    <a href="{{ route('pricing') }}" class="btn btn-ghost"><i data-lucide="tag"></i> Lihat Harga</a>
  </div>
  <div class="neon-strip" style="margin-top:36px;width:300px;"></div>
  <div class="feat-grid">
    <div class="feat-card">
      <i data-lucide="shield-check" class="feat-icon"></i>
      <h3 class="feat-title">Heritage Guard</h3>
      <p class="feat-desc">Verifikasi aman dengan NIK tamu dan protokol keamanan berstandar kerajaan Jawa.</p>
    </div>
    <div class="feat-card">
      <i data-lucide="box" class="feat-icon"></i>
      <h3 class="feat-title">3D Gallery</h3>
      <p class="feat-desc">Jelajahi setiap kamar secara imersif dalam tampilan 3D interaktif sebelum memesan.</p>
    </div>
    <div class="feat-card">
      <i data-lucide="star" class="feat-icon"></i>
      <h3 class="feat-title">Royal Service</h3>
      <p class="feat-desc">Layanan butler 24 jam yang terlatih dalam tradisi keramahan Jawa yang agung.</p>
    </div>
    <div class="feat-card">
      <i data-lucide="credit-card" class="feat-icon"></i>
      <h3 class="feat-title">Easy Payment</h3>
      <p class="feat-desc">Pembayaran fleksibel: Transfer Bank, Cash, dan berbagai metode digital lainnya.</p>
    </div>
  </div>
</div>
@endsection
