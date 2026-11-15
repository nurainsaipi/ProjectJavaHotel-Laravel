@extends('layouts.app')

@section('content')
<style>
@media print {
  body * { visibility: hidden !important; }
  #print-receipt, #print-receipt * { visibility: visible !important; }
  #print-receipt { display: block !important; position: fixed; top: 0; left: 0; width: 100%; padding: 40px; background: white; z-index: 9999; }
}
</style>
<div class="ph"><div><h1 class="ph-title">Heritage Guest Portal</h1><p class="ph-sub">Verifikasi status pemesanan via NIK</p></div></div>
<div class="sc-card ai">
  <form action="{{ route('check') }}" method="GET">
    <label class="fl">Nomor Induk Kependudukan (NIK)</label>
    <div style="display:flex;gap:11px;align-items:stretch;flex-wrap:wrap;">
      <input type="text" name="nik" value="{{ $nik }}" placeholder="3200000000000000" class="fc" style="flex:1;min-width:180px;font-family:'DM Mono',monospace;letter-spacing:.1em;">
      <button class="btn btn-gold"><i data-lucide="search"></i> Verifikasi</button>
    </div>
  </form>

  @if($booking)
  <div class="sc-result">
    <h3 style="font-family:'Cormorant Garamond',serif;font-size:26px;color:var(--gold);margin-bottom:18px;">{{ $booking->name }}</h3>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:18px;margin-bottom:24px;">
      <div><p class="fl" style="margin-bottom:4px;">Kamar</p><p style="font-family:'Cormorant Garamond',serif;font-size:19px;">Room {{ $booking->r_no }}</p><p style="font-size:11px;color:var(--text2);">{{ $booking->r_type }}</p></div>
      <div><p class="fl" style="margin-bottom:4px;">Status</p>
        <span class="badge {{ $booking->payment_status === 'Paid' ? 'b-green' : ($booking->payment_status === 'Reviewing' ? 'b-blue' : 'b-amber') }}">
          {{ $booking->payment_status === 'Reviewing' ? 'Menunggu Verifikasi' : $booking->payment_status }}
        </span>
      </div>
      <div><p class="fl" style="margin-bottom:4px;">Check-in</p><p class="mono" style="font-size:12px;">{{ \Carbon\Carbon::parse($booking->checkin_date)->format('d M Y') }}</p></div>
      <div><p class="fl" style="margin-bottom:4px;">Check-out</p><p class="mono" style="font-size:12px;">{{ \Carbon\Carbon::parse($booking->checkout_date)->format('d M Y') }}</p></div>
      <div><p class="fl" style="margin-bottom:4px;">Total</p><p style="font-family:'Cormorant Garamond',serif;font-size:18px;color:var(--gold);">Rp {{ number_format($booking->total_price,0,',','.') }}</p></div>
      @if($booking->payment_method)
      <div><p class="fl" style="margin-bottom:4px;">Metode Bayar</p><p style="font-size:13px;">{{ $booking->payment_method }}</p></div>
      @endif
    </div>

    {{-- PAYMENT SECTION --}}
    @if($booking->payment_status === 'Paid' || $booking->payment_status === 'Checked Out')
      {{-- BANNER KONFIRMASI --}}
      <div style="background:rgba(74,222,128,0.08);border:1px solid rgba(74,222,128,0.3);border-radius:12px;padding:16px 20px;display:flex;align-items:center;gap:14px;margin-bottom:16px;">
        <i data-lucide="check-circle-2" style="width:28px;height:28px;color:#4ade80;flex-shrink:0;"></i>
        <div>
          <p style="font-size:14px;color:#4ade80;font-weight:600;margin-bottom:2px;">Pembayaran Dikonfirmasi! ✓</p>
          <p style="font-size:12px;color:var(--text2);">Nota pemesananmu sudah siap. Kamu bisa melihat dan menyimpannya sebagai PDF di bawah ini untuk ditunjukkan saat check-in.</p>
        </div>
      </div>

      {{-- RECEIPT --}}
      <div style="background:var(--gold-dim);border:1px solid var(--gold-border);border-radius:12px;padding:24px;text-align:center;">
        <i data-lucide="file-check" style="width:40px;height:40px;color:var(--gold);margin:0 auto 12px;display:block;"></i>
        <p style="font-family:'Cormorant Garamond',serif;font-size:20px;color:var(--gold);margin-bottom:4px;">Nota Pemesanan</p>
        <p class="mono" style="font-size:10px;color:var(--text2);margin-bottom:16px;">{{ $booking->payment_method }} · {{ \Carbon\Carbon::parse($booking->updated_at)->format('d M Y, H:i') }}</p>
        <div style="border-top:1px solid var(--border);padding-top:16px;display:grid;grid-template-columns:1fr 1fr;gap:10px;text-align:left;">
          <div><p class="fl" style="font-size:9px;">Tamu</p><p style="font-size:13px;">{{ $booking->name }}</p></div>
          <div><p class="fl" style="font-size:9px;">Total</p><p style="font-size:13px;color:var(--gold);">Rp {{ number_format($booking->total_price,0,',','.') }}</p></div>
          <div><p class="fl" style="font-size:9px;">Kamar</p><p style="font-size:13px;">No. {{ $booking->r_no }} · {{ $booking->r_type }}</p></div>
          <div><p class="fl" style="font-size:9px;">Periode</p><p style="font-size:12px;" class="mono">{{ \Carbon\Carbon::parse($booking->checkin_date)->format('d M') }} — {{ \Carbon\Carbon::parse($booking->checkout_date)->format('d M Y') }}</p></div>
        </div>
        <div style="margin-top:20px;">
          <button onclick="printReceipt()" class="btn btn-gold" style="width:100%;justify-content:center;padding:14px;">
            <i data-lucide="printer"></i> Print / Simpan PDF
          </button>
        </div>
      </div>

      {{-- NOTA PRINT (hanya muncul saat print) --}}
      <div id="print-receipt" style="display:none;">
        <div style="max-width:600px;margin:0 auto;font-family:Georgia,serif;color:#1a1a1a;">
          {{-- Header --}}
          <div style="text-align:center;border-bottom:3px double #b8960c;padding-bottom:20px;margin-bottom:24px;">
            <p style="font-size:28px;font-weight:700;letter-spacing:.2em;color:#b8960c;margin-bottom:2px;">JAVA</p>
            <p style="font-size:11px;letter-spacing:.3em;color:#666;margin-bottom:2px;">HERITAGE BOUTIQUE HOTEL</p>
            <p style="font-size:10px;color:#888;">Jl. Malioboro No. 1, Yogyakarta 55271</p>
            <p style="font-size:10px;color:#888;">Tel: +62 274 555 999 | heritage@javahotel.com</p>
          </div>

          {{-- Title --}}
          <div style="text-align:center;margin-bottom:24px;">
            <p style="font-size:18px;font-weight:700;letter-spacing:.1em;">KONFIRMASI PEMESANAN</p>
            <p style="font-size:12px;color:#888;margin-top:4px;">Booking #JH-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</p>
          </div>

          {{-- Info Tamu --}}
          <table style="width:100%;border-collapse:collapse;margin-bottom:20px;">
            <tr style="background:#f9f3e0;">
              <td style="padding:10px 14px;font-size:11px;font-weight:700;letter-spacing:.1em;color:#b8960c;width:40%;">NAMA TAMU</td>
              <td style="padding:10px 14px;font-size:13px;">{{ $booking->name }}</td>
            </tr>
            <tr>
              <td style="padding:10px 14px;font-size:11px;font-weight:700;letter-spacing:.1em;color:#666;">NIK</td>
              <td style="padding:10px 14px;font-size:13px;font-family:monospace;">{{ $booking->customer_nik }}</td>
            </tr>
            <tr style="background:#f9f3e0;">
              <td style="padding:10px 14px;font-size:11px;font-weight:700;letter-spacing:.1em;color:#b8960c;">KAMAR</td>
              <td style="padding:10px 14px;font-size:13px;">No. {{ $booking->r_no }} &mdash; {{ $booking->r_type }}</td>
            </tr>
            <tr>
              <td style="padding:10px 14px;font-size:11px;font-weight:700;letter-spacing:.1em;color:#666;">CHECK-IN</td>
              <td style="padding:10px 14px;font-size:13px;font-family:monospace;">{{ \Carbon\Carbon::parse($booking->checkin_date)->format('d F Y') }}</td>
            </tr>
            <tr style="background:#f9f3e0;">
              <td style="padding:10px 14px;font-size:11px;font-weight:700;letter-spacing:.1em;color:#b8960c;">CHECK-OUT</td>
              <td style="padding:10px 14px;font-size:13px;font-family:monospace;">{{ \Carbon\Carbon::parse($booking->checkout_date)->format('d F Y') }}</td>
            </tr>
            <tr>
              <td style="padding:10px 14px;font-size:11px;font-weight:700;letter-spacing:.1em;color:#666;">METODE BAYAR</td>
              <td style="padding:10px 14px;font-size:13px;">{{ $booking->payment_method ?? '-' }}</td>
            </tr>
            <tr style="background:#f9f3e0;">
              <td style="padding:10px 14px;font-size:11px;font-weight:700;letter-spacing:.1em;color:#b8960c;">TGL KONFIRMASI</td>
              <td style="padding:10px 14px;font-size:13px;font-family:monospace;">{{ \Carbon\Carbon::parse($booking->updated_at)->format('d F Y, H:i') }} WIB</td>
            </tr>
          </table>

          {{-- Total --}}
          <div style="border:2px solid #b8960c;border-radius:8px;padding:16px 20px;display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
            <span style="font-size:14px;font-weight:700;letter-spacing:.1em;">TOTAL PEMBAYARAN</span>
            <span style="font-size:22px;font-weight:700;color:#b8960c;">Rp {{ number_format($booking->total_price,0,',','.') }}</span>
          </div>

          {{-- Status --}}
          <div style="text-align:center;margin-bottom:28px;">
            <div style="display:inline-block;background:#16a34a;color:white;padding:8px 28px;border-radius:20px;font-size:14px;font-weight:700;letter-spacing:.15em;">✓ LUNAS</div>
          </div>

          {{-- Footer --}}
          <div style="border-top:1px solid #ddd;padding-top:16px;text-align:center;">
            <p style="font-size:11px;color:#888;line-height:1.8;">Dokumen ini merupakan bukti konfirmasi pemesanan resmi dari Java Heritage Boutique Hotel.</p>
            <p style="font-size:11px;color:#888;">Tunjukkan dokumen ini kepada resepsionis saat check-in.</p>
            <p style="font-size:10px;color:#aaa;margin-top:8px;">Dicetak: {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB</p>
          </div>
        </div>
      </div>

    @elseif($booking->payment_status === 'Reviewing')
      <div style="background:rgba(59,130,246,0.08);border:1px solid rgba(59,130,246,0.3);border-radius:12px;padding:20px;">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
          <i data-lucide="clock" style="width:28px;height:28px;color:#60a5fa;flex-shrink:0;"></i>
          <div>
            <p style="font-size:14px;color:#60a5fa;font-weight:600;margin-bottom:2px;">Bukti pembayaran sedang diverifikasi</p>
            <p class="mono" style="font-size:10px;color:var(--text2);">Harap tunggu 1x24 jam</p>
          </div>
        </div>
        <div style="background:rgba(59,130,246,0.06);border:1px solid rgba(59,130,246,0.15);border-radius:8px;padding:12px 16px;">
          <p style="font-size:12px;color:var(--text2);line-height:1.8;">
            💡 <strong style="color:var(--text);">Info:</strong> Setelah admin mengkonfirmasi pembayaranmu, kamu bisa kembali ke halaman ini untuk melihat dan menyimpan <strong style="color:#60a5fa;">nota pemesanan</strong> sebagai bukti untuk ditunjukkan saat check-in di hotel.
          </p>
        </div>
      </div>

    @else
      {{-- UPLOAD BUKTI PEMBAYARAN --}}
      <div style="border-top:1px solid var(--border);padding-top:20px;">
        <p style="font-family:'Cormorant Garamond',serif;font-size:18px;margin-bottom:8px;">Lakukan Pembayaran</p>
        <div style="background:rgba(201,168,76,0.06);border:1px solid var(--gold-border);border-radius:8px;padding:12px 16px;margin-bottom:16px;">
          <p style="font-size:12px;color:var(--text2);line-height:1.8;">
            💡 Setelah melakukan pembayaran, upload bukti transfer di bawah. Admin akan memverifikasi dalam 1x24 jam. Setelah dikonfirmasi, kamu bisa kembali ke halaman ini untuk melihat dan menyimpan <strong style="color:var(--gold);">nota pemesanan</strong> sebagai bukti check-in.
          </p>
        </div>

        {{-- Payment method tabs --}}
        <div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;">
          <button onclick="showMethod('transfer')" id="tab-transfer" class="btn btn-gold" style="font-size:11px;padding:8px 16px;">🏦 Transfer Bank</button>
          <button onclick="showMethod('qris')" id="tab-qris" class="btn btn-ghost" style="font-size:11px;padding:8px 16px;">📱 QRIS</button>
          <button onclick="showMethod('cash')" id="tab-cash" class="btn btn-ghost" style="font-size:11px;padding:8px 16px;">💵 Cash</button>
        </div>

        {{-- Transfer info --}}
        <div id="method-transfer">
          <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:10px;padding:16px;margin-bottom:16px;">
            <p class="fl" style="font-size:9px;margin-bottom:8px;">REKENING TUJUAN</p>
            <p style="font-size:15px;font-weight:600;">Bank BCA</p>
            <p class="mono" style="font-size:20px;color:var(--gold);letter-spacing:.15em;margin:4px 0;">1234 5678 90</p>
            <p style="font-size:12px;color:var(--text2);">a.n. Java Hotel Heritage Yogyakarta</p>
            <p class="mono" style="font-size:11px;color:var(--gold);margin-top:8px;">Total: Rp {{ number_format($booking->total_price,0,',','.') }}</p>
          </div>
        </div>

        {{-- QRIS --}}
        <div id="method-qris" style="display:none;">
          <div style="background:white;border-radius:12px;padding:20px;text-align:center;margin-bottom:16px;max-width:220px;">
            <p style="font-size:12px;color:#333;margin-bottom:8px;font-weight:600;">Scan QRIS</p>
            <div style="width:160px;height:160px;background:#f0f0f0;margin:0 auto;border-radius:8px;display:flex;align-items:center;justify-content:center;">
              <p style="font-size:10px;color:#999;">QR Code Hotel</p>
            </div>
            <p style="font-size:10px;color:#666;margin-top:8px;">Java Hotel Heritage</p>
          </div>
        </div>

        {{-- Cash --}}
        <div id="method-cash" style="display:none;">
          <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:10px;padding:16px;margin-bottom:16px;">
            <p class="fl" style="font-size:9px;margin-bottom:8px;">PEMBAYARAN CASH</p>
            <p style="font-size:13px;color:var(--text2);line-height:1.7;">Silakan datang langsung ke resepsionis hotel dan tunjukkan NIK kamu untuk melakukan pembayaran tunai.</p>
            <p class="mono" style="font-size:11px;color:var(--gold);margin-top:8px;">Total: Rp {{ number_format($booking->total_price,0,',','.') }}</p>
          </div>
        </div>

        {{-- Upload form --}}
        <form action="{{ route('booking.upload-proof') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="booking_id" value="{{ $booking->id }}">
          <input type="hidden" name="payment_method" id="selected-method" value="Transfer Bank">

          <div class="fg">
            <label class="fl">Upload Bukti Pembayaran</label>
            <div id="drop-zone" style="border:2px dashed var(--border);border-radius:10px;padding:28px;text-align:center;cursor:pointer;transition:.2s;" onclick="document.getElementById('proof-file').click()">
              <i data-lucide="upload-cloud" style="width:32px;height:32px;color:var(--text2);margin:0 auto 8px;display:block;"></i>
              <p style="font-size:13px;color:var(--text2);">Klik untuk upload foto bukti transfer</p>
              <p class="mono" style="font-size:10px;color:var(--text3);margin-top:4px;">JPG, PNG, max 2MB</p>
            </div>
            <input type="file" id="proof-file" name="payment_proof" accept="image/*" style="display:none;" onchange="previewImage(this)">
          </div>

          {{-- Preview --}}
          <div id="preview-wrap" style="display:none;margin-top:12px;">
            <p class="fl" style="font-size:9px;margin-bottom:8px;">PREVIEW BUKTI</p>
            <div style="position:relative;display:inline-block;">
              <img id="preview-img" src="" style="max-width:100%;max-height:220px;border-radius:8px;border:1px solid var(--border);">
              <button type="button" onclick="removeImage()" style="position:absolute;top:6px;right:6px;background:rgba(0,0,0,.6);border:none;border-radius:50%;width:24px;height:24px;cursor:pointer;color:white;font-size:14px;display:flex;align-items:center;justify-content:center;">×</button>
            </div>
          </div>

          <div class="mfoot" style="margin-top:20px;padding:0;">
            <button type="submit" class="btn btn-gold" style="width:100%;justify-content:center;"><i data-lucide="send"></i> Kirim Bukti Pembayaran</button>
          </div>
        </form>
      </div>
    @endif
  </div>
  @elseif($nik)
  <div class="sc-result" style="text-align:center;padding:28px 0;">
    <i data-lucide="search-x" style="width:34px;height:34px;color:var(--text2);margin:0 auto 10px;display:block;"></i>
    <p style="font-size:14px;color:var(--text2);">Tidak ditemukan pemesanan untuk NIK tersebut.</p>
  </div>
  @endif
</div>
@endsection

@section('scripts')
function printReceipt() {
  document.getElementById('print-receipt').style.display = 'block';
  window.print();
  document.getElementById('print-receipt').style.display = 'none';
}
function showMethod(m) {
  ['transfer','qris','cash'].forEach(x => {
    document.getElementById('method-'+x).style.display = x===m ? '' : 'none';
    const tab = document.getElementById('tab-'+x);
    tab.className = x===m ? 'btn btn-gold' : 'btn btn-ghost';
    tab.style.fontSize = '11px';
    tab.style.padding = '8px 16px';
  });
  const methodMap = {transfer:'Transfer Bank', qris:'QRIS', cash:'Cash'};
  document.getElementById('selected-method').value = methodMap[m];
}
function previewImage(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      document.getElementById('preview-img').src = e.target.result;
      document.getElementById('preview-wrap').style.display = 'block';
      document.getElementById('drop-zone').style.display = 'none';
    };
    reader.readAsDataURL(input.files[0]);
  }
}
function removeImage() {
  document.getElementById('proof-file').value = '';
  document.getElementById('preview-wrap').style.display = 'none';
  document.getElementById('drop-zone').style.display = 'block';
}
@endsection