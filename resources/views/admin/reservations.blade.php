@extends('layouts.app')

@section('content')
<div class="ph">
  <div><h1 class="ph-title">Heritage Reservations</h1><p class="ph-sub">Manajemen pemesanan tamu · Puri Jawi</p></div>
  <button onclick="openM('bookM')" class="btn btn-gold"><i data-lucide="plus"></i> New Reservation</button>
</div>
<div class="tw ai">
  <div class="ts">
    <table class="dt">
      <thead><tr><th>Tamu</th><th>Kamar</th><th>Periode</th><th>Total</th><th>Pembayaran</th><th>Aksi</th></tr></thead>
      <tbody>
      @foreach($bookings as $b)
      <tr>
        <td>
          <div style="font-size:15px;font-weight:600;">{{ $b->guest }}</div>
          <div class="mono" style="font-size:10px;color:var(--text2);margin-top:2px;">{{ $b->phone }}</div>
        </td>
        <td>
          <div style="display:flex;align-items:center;gap:9px;">
            <div class="rm-num" style="width:32px;height:32px;font-size:11px;">{{ $b->room_no }}</div>
            <span class="badge b-gold">{{ $b->room_type }}</span>
          </div>
        </td>
        <td class="mono" style="font-size:11px;color:var(--text2);">
          {{ \Carbon\Carbon::parse($b->checkin_date)->format('d M') }} — {{ \Carbon\Carbon::parse($b->checkout_date)->format('d M Y') }}
        </td>
        <td style="font-family:'Cormorant Garamond',serif;font-size:18px;color:var(--gold);font-weight:600;">Rp {{ number_format($b->total_price,0,',','.') }}</td>
        <td>
          <span class="badge {{ $b->payment_status === 'Paid' ? 'b-green' : 'b-amber' }}">{{ $b->payment_status }}</span>
          @if($b->payment_method)<div class="mono" style="font-size:9px;color:var(--text2);margin-top:3px;">{{ $b->payment_method }}</div>@endif
        </td>
        <td>
          <div style="display:flex;gap:3px;align-items:center;">
          @if($b->payment_status === 'Reviewing')
            <button class="ab ab-ok" title="Lihat Bukti & Konfirmasi" onclick="openProofModal({{ $b->id }},'{{ addslashes($b->guest) }}',{{ $b->total_price }},'{{ $b->payment_proof ? asset('storage/'.$b->payment_proof) : '' }}')"><i data-lucide="image"></i></button>
          @elseif($b->payment_status === 'Pending')
            <button class="ab ab-ok" title="Konfirmasi Pembayaran" onclick="openPayModal({{ $b->id }},'{{ addslashes($b->guest) }}',{{ $b->total_price }})"><i data-lucide="check-circle-2"></i></button>
          @else
            <form action="{{ route('admin.checkout') }}" method="POST" style="margin:0">
              @csrf
              <input type="hidden" name="id" value="{{ $b->id }}">
              <button type="submit" class="ab ab-danger" title="Checkout" onclick="return confirm('Konfirmasi checkout tamu {{ addslashes($b->guest) }}?')"><i data-lucide="log-out"></i></button>
            </form>
          @endif
          </div>
        </td>
      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>

{{-- HISTORY SECTION --}}
@if($history->count() > 0)
<div style="margin-top:40px;">
  <div style="margin-bottom:16px;">
    <h2 style="font-family:'Cormorant Garamond',serif;font-size:22px;color:var(--text2);">Riwayat Checkout</h2>
    <p class="mono" style="font-size:10px;color:var(--text3);margin-top:2px;">{{ $history->count() }} tamu telah checkout</p>
  </div>
  <div class="ts" style="opacity:0.7;">
    <table class="dt">
      <thead><tr><th>Tamu</th><th>Kamar</th><th>Periode</th><th>Total</th><th>Metode</th><th>Checkout</th></tr></thead>
      <tbody>
      @foreach($history as $h)
      <tr>
        <td>
          <div style="font-size:15px;font-weight:600;">{{ $h->guest }}</div>
          <div class="mono" style="font-size:10px;color:var(--text2);margin-top:2px;">{{ $h->phone }}</div>
        </td>
        <td>
          <div style="display:flex;align-items:center;gap:9px;">
            <div class="rm-num" style="width:32px;height:32px;font-size:11px;">{{ $h->room_no }}</div>
            <span class="badge b-gold">{{ $h->room_type }}</span>
          </div>
        </td>
        <td class="mono" style="font-size:11px;color:var(--text2);">
          {{ \Carbon\Carbon::parse($h->checkin_date)->format('d M') }} — {{ \Carbon\Carbon::parse($h->checkout_date)->format('d M Y') }}
        </td>
        <td style="font-family:'Cormorant Garamond',serif;font-size:18px;color:var(--gold);font-weight:600;">Rp {{ number_format($h->total_price,0,',','.') }}</td>
        <td><div class="mono" style="font-size:11px;color:var(--text2);">{{ $h->payment_method ?? '-' }}</div></td>
        <td><div class="mono" style="font-size:10px;color:var(--text3);">{{ $h->updated_at ? \Carbon\Carbon::parse($h->updated_at)->format('d M Y, H:i') : '-' }}</div></td>
      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
@endif
@endsection

@section('modals')
<!-- PAYMENT MODAL -->
<div class="moverlay" id="payM">
  <div class="mbox" style="max-width:440px;">
    <div class="mhdr">
      <div><div class="mtitle">Konfirmasi Pembayaran</div><div class="msub">Payment Authorization</div></div>
      <button class="mclose" onclick="closeM('payM')"><i data-lucide="x"></i></button>
    </div>
    <form action="{{ route('admin.pay') }}" method="POST" class="mbody">
      @csrf
      <input type="hidden" name="id" id="pay-id">
      <div style="background:var(--gold-dim);border:1px solid var(--gold-border);border-radius:10px;padding:16px;margin-bottom:18px;">
        <p class="mono" style="font-size:9px;letter-spacing:.15em;color:var(--text2);margin-bottom:4px;">TAMU</p>
        <p id="pay-guest" style="font-family:'Cormorant Garamond',serif;font-size:20px;color:var(--gold);"></p>
        <p id="pay-total" class="mono" style="font-size:11px;color:var(--text2);margin-top:3px;"></p>
      </div>
      <div class="fg">
        <label class="fl">Metode Pembayaran</label>
        <select name="method" class="fc">
          <option style="background:#100806;">Transfer Bank</option>
          <option style="background:#100806;">Cash</option>
          <option style="background:#100806;">OVO</option>
          <option style="background:#100806;">GoPay</option>
          <option style="background:#100806;">QRIS</option>
          <option style="background:#100806;">Kartu Kredit</option>
        </select>
      </div>
      <div class="fg">
        <label class="fl">Catatan (opsional)</label>
        <input type="text" name="note" class="fc" placeholder="No. referensi / keterangan">
      </div>
      <div class="mfoot" style="margin:0 -24px -22px;margin-top:8px;">
        <button type="button" onclick="closeM('payM')" class="btn btn-ghost">Batal</button>
        <button type="submit" class="btn btn-gold"><i data-lucide="check-circle-2"></i> Konfirmasi Bayar</button>
      </div>
    </form>
  </div>
</div>

<!-- PROOF MODAL -->
<div class="moverlay" id="proofM">
  <div class="mbox" style="max-width:520px;">
    <div class="mhdr">
      <div><div class="mtitle">Bukti Pembayaran</div><div class="msub">Verifikasi Transfer</div></div>
      <button class="mclose" onclick="closeM('proofM')"><i data-lucide="x"></i></button>
    </div>
    <div class="mbody">
      <div style="background:var(--gold-dim);border:1px solid var(--gold-border);border-radius:10px;padding:16px;margin-bottom:18px;">
        <p class="mono" style="font-size:9px;letter-spacing:.15em;color:var(--text2);margin-bottom:4px;">TAMU</p>
        <p id="proof-guest" style="font-family:'Cormorant Garamond',serif;font-size:20px;color:var(--gold);"></p>
        <p id="proof-total" class="mono" style="font-size:11px;color:var(--text2);margin-top:3px;"></p>
      </div>
      <div style="text-align:center;margin-bottom:18px;">
        <div id="proof-img-wrap">
          <img id="proof-img" src="" alt="Bukti Pembayaran" style="max-width:100%;max-height:320px;border-radius:10px;border:1px solid var(--gold-border);">
          <p style="margin-top:8px;"><a id="proof-link" href="#" target="_blank" class="mono" style="font-size:10px;color:var(--gold);">Buka di tab baru ↗</a></p>
        </div>
        <div id="proof-no-img" style="display:none;padding:24px;background:var(--gold-dim);border:1px solid var(--gold-border);border-radius:10px;">
          <i data-lucide="image-off" style="width:32px;height:32px;color:var(--text2);margin:0 auto 8px;display:block;"></i>
          <p class="mono" style="font-size:11px;color:var(--text2);">Tamu belum upload bukti pembayaran.</p>
          <p class="mono" style="font-size:10px;color:var(--text3);margin-top:4px;">Kamu tetap bisa konfirmasi manual jika sudah verifikasi di luar sistem.</p>
        </div>
      </div>
      <div style="display:flex;gap:10px;">
        <!-- Tolak -->
        <form action="{{ route('admin.reject-proof') }}" method="POST" style="flex:1;margin:0">
          @csrf
          <input type="hidden" name="id" id="proof-reject-id">
          <button type="submit" class="btn btn-ghost" style="width:100%;color:#e07070;border-color:#e07070;">
            <i data-lucide="x-circle"></i> Tolak
          </button>
        </form>
        <!-- Konfirmasi -->
        <form action="{{ route('admin.pay') }}" method="POST" style="flex:1;margin:0">
          @csrf
          <input type="hidden" name="id" id="proof-pay-id">
          <select name="method" class="fc" style="margin-bottom:8px;">
            <option style="background:#100806;">Transfer Bank</option>
            <option style="background:#100806;">Cash</option>
            <option style="background:#100806;">OVO</option>
            <option style="background:#100806;">GoPay</option>
            <option style="background:#100806;">QRIS</option>
            <option style="background:#100806;">Kartu Kredit</option>
          </select>
          <button type="submit" class="btn btn-gold" style="width:100%;">
            <i data-lucide="check-circle-2"></i> Konfirmasi Bayar
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
function openPayModal(id,guest,total){
  document.getElementById('pay-id').value=id;
  document.getElementById('pay-guest').textContent=guest;
  document.getElementById('pay-total').textContent='Total: Rp '+total.toLocaleString('id-ID');
  openM('payM');
}
function openProofModal(id,guest,total,imgUrl){
  document.getElementById('proof-pay-id').value=id;
  document.getElementById('proof-reject-id').value=id;
  document.getElementById('proof-guest').textContent=guest;
  document.getElementById('proof-total').textContent='Total: Rp '+total.toLocaleString('id-ID');
  var imgWrap = document.getElementById('proof-img-wrap');
  var noProof = document.getElementById('proof-no-img');
  if(imgUrl){
    document.getElementById('proof-img').src=imgUrl;
    document.getElementById('proof-link').href=imgUrl;
    imgWrap.style.display='block';
    noProof.style.display='none';
  } else {
    imgWrap.style.display='none';
    noProof.style.display='block';
  }
  openM('proofM');
}
@endsection