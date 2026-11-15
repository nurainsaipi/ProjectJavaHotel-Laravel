@extends('layouts.app')

@section('content')
<div class="ph"><div><h1 class="ph-title">Harga Kamar</h1><p class="ph-sub">Tarif menginap · Puri Jawi Heritage</p></div></div>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px;" class="ai">
  @php $seen = []; @endphp
  @foreach($rooms as $rm)
    @if(in_array($rm->type, $seen)) @continue @endif
    @php $seen[] = $rm->type; @endphp
    @php $hasAvail = $availRooms->where('type', $rm->type)->count() > 0; @endphp
  <div class="card" style="overflow:visible;">
    <div style="padding:0;border-radius:var(--r);overflow:hidden;">
      <div style="height:160px;background:linear-gradient(135deg,#1a0a05,#2a1208);display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;">
        <div style="position:absolute;inset:0;background:url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22 viewBox=%220 0 200 200%22><path d=%22M100 0 L30 130 Q10 160 40 190 L40 240 Q40 260 60 260 L140 260 Q160 260 160 240 L160 190 Q190 160 170 130 Z%22 fill=%22rgba(201,168,76,0.08)%22/></svg>') center/60% no-repeat;opacity:.4;"></div>
        <div style="text-align:center;position:relative;z-index:1;">
          <div style="font-family:'Noto Sans Javanese',serif;font-size:24px;color:var(--gold);opacity:.35;margin-bottom:4px;">ꦏꦩꦂ</div>
          <div style="font-family:'Cormorant Garamond',serif;font-size:32px;font-weight:300;letter-spacing:.25em;color:var(--gold);text-shadow:0 0 20px rgba(201,168,76,.3);">{{ $rm->type }}</div>
        </div>
      </div>
      <div style="padding:22px 24px;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
          <h3 style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:600;">{{ $rm->type }} Chamber</h3>
          <div style="text-align:right;">
            <div style="font-family:'Cormorant Garamond',serif;font-size:20px;color:var(--gold);font-weight:600;">Rp {{ number_format($rm->price,0,',','.') }}</div>
            <div class="mono" style="font-size:8px;color:var(--text2);letter-spacing:.15em;">/ malam</div>
          </div>
        </div>
        <p style="font-size:13px;line-height:1.75;color:var(--text2);margin-bottom:18px;">{{ $rm->description }}</p>
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:18px;">
          @foreach(['Wi-Fi Premium','AC Central','TV 55"','Bathroom'] as $f)
          <span style="font-family:'DM Mono',monospace;font-size:8px;letter-spacing:.12em;padding:3px 8px;border-radius:100px;background:var(--bg-card);border:1px solid var(--border);color:var(--text2);">{{ $f }}</span>
          @endforeach
        </div>
        @if($hasAvail)
          <button onclick="openBookingByType('{{ $rm->type }}')" class="btn btn-gold" style="width:100%;justify-content:center;"><i data-lucide="calendar-plus"></i> Pesan Sekarang</button>
        @else
          <button class="btn btn-ghost" style="width:100%;justify-content:center;cursor:not-allowed;opacity:.5;" disabled><i data-lucide="x-circle"></i> Kamar Penuh</button>
        @endif
      </div>
    </div>
  </div>
  @endforeach
</div>
@endsection