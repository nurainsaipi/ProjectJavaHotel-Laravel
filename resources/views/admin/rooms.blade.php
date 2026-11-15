@extends('layouts.app')

@section('content')
<div class="ph">
  <div><h1 class="ph-title">Chamber Monitoring</h1><p class="ph-sub">Status & pemeliharaan kamar heritage</p></div>
</div>
<div class="rm-grid ai">
@foreach($rooms as $rm)
<div class="rm-card">
  <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;">
    <div class="rm-num">{{ $rm->number }}</div>
    <span class="badge {{ $rm->status === 'Clean' ? 'b-green' : ($rm->status === 'Dirty' ? 'b-rose' : ($rm->status === 'Occupied' ? 'b-blue' : 'b-amber')) }}">{{ $rm->status }}</span>
  </div>
  <h3 style="font-family:'Cormorant Garamond',serif;font-size:18px;font-weight:600;margin-bottom:2px;">{{ $rm->type }} Room</h3>
  <p style="font-family:'DM Mono',monospace;font-size:9px;letter-spacing:.18em;text-transform:uppercase;color:var(--text2);margin-bottom:16px;">Traditional Sanctuary</p>
  <div class="divider"></div>
  <form action="{{ route('admin.rooms.status') }}" method="POST">
    @csrf
    <input type="hidden" name="room_id" value="{{ $rm->id }}">
    <select name="status" onchange="this.form.submit()" class="fc" style="font-size:10px;">
      <option value="">— Update Status —</option>
      <option value="Clean">Mark Clean</option>
      <option value="Dirty">Mark Dirty</option>
      <option value="Inspected">Inspected</option>
    </select>
  </form>
  <div class="rm-deco">
    <svg width="110" height="170" viewBox="0 0 100 150" fill="currentColor">
      <path d="M50 0 L15 65 Q5 80 20 95 L20 120 Q20 130 30 130 L70 130 Q80 130 80 120 L80 95 Q95 80 85 65 Z"/>
    </svg>
  </div>
</div>
@endforeach
</div>
@endsection
