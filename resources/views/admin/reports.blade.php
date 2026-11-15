@extends('layouts.app')

@section('content')
<div class="ph"><div><h1 class="ph-title">Laporan Keuangan</h1><p class="ph-sub">Revenue analytics · Puri Jawi Heritage</p></div></div>
<div class="sg ai">
  <div class="stat-card"><span class="sl">Pendapatan Harian</span><div class="sv">Rp {{ number_format($daily,0,',','.') }}</div><div class="si"><i data-lucide="sun"></i></div></div>
  <div class="stat-card"><span class="sl">Pendapatan Mingguan</span><div class="sv">Rp {{ number_format($weekly,0,',','.') }}</div><div class="si"><i data-lucide="calendar"></i></div></div>
  <div class="stat-card"><span class="sl">Pendapatan Bulanan</span><div class="sv">Rp {{ number_format($monthly,0,',','.') }}</div><div class="si"><i data-lucide="trending-up"></i></div></div>
  <div class="stat-card"><span class="sl">Pendapatan Tahunan</span><div class="sv">Rp {{ number_format($yearly,0,',','.') }}</div><div class="si"><i data-lucide="bar-chart-2"></i></div></div>
</div>
<div class="rev-wrap ai2">
  <div class="rev-title">Revenue Overview — Perbandingan Periode</div>
  @foreach([['Daily',$daily],['Weekly',$weekly],['Monthly',$monthly],['Yearly',$yearly]] as [$label,$val])
  <div class="rev-row">
    <span class="rev-l">{{ $label }}</span>
    <div class="rev-track"><div class="rev-fill" data-pct="{{ round($val / $max * 100) }}" style="width:0%"></div></div>
    <span class="rev-val">Rp {{ number_format($val,0,',','.') }}</span>
  </div>
  @endforeach
</div>

@if($recentBookings->count())
<div style="margin-top:24px;" class="ai3">
  <h3 style="font-family:'Cormorant Garamond',serif;font-size:22px;margin-bottom:16px;font-style:italic;">Transaksi Terbaru</h3>
  <div class="tw"><div class="ts"><table class="dt">
    <thead><tr><th>Tamu</th><th>Kamar</th><th>Total</th><th>Tanggal</th></tr></thead>
    <tbody>
    @foreach($recentBookings as $b)
    <tr>
      <td style="font-size:14px;">{{ $b->guest }}</td>
      <td><div style="display:flex;align-items:center;gap:8px;"><div class="rm-num" style="width:30px;height:30px;font-size:10px;">{{ $b->room_no }}</div><span class="badge b-gold">{{ $b->room_type }}</span></div></td>
      <td style="font-family:'Cormorant Garamond',serif;font-size:17px;color:var(--gold);">Rp {{ number_format($b->total_price,0,',','.') }}</td>
      <td class="mono" style="font-size:10px;color:var(--text2);">{{ \Carbon\Carbon::parse($b->created_at)->format('d M Y H:i') }}</td>
    </tr>
    @endforeach
    </tbody>
  </table></div></div>
</div>
@endif
@endsection

@section('scripts')
document.addEventListener('DOMContentLoaded',()=>{
  setTimeout(()=>{ document.querySelectorAll('.rev-fill').forEach(el=>{ el.style.width=(el.dataset.pct||0)+'%'; }); },400);
});
@endsection
