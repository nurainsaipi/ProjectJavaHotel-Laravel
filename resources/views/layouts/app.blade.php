<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Java Hotel — Heritage Boutique · Yogyakarta</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Noto+Sans+Javanese:wght@400;700&family=DM+Mono:ital,wght@0,300;0,400&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<!-- ══════════ LOADING SCREEN ══════════ -->
<div id="loader">
  <div class="loader-gunungan">
    <svg width="80" height="120" viewBox="0 0 100 150">
      <path d="M50 0 L15 65 Q5 80 20 95 L20 120 Q20 130 30 130 L70 130 Q80 130 80 120 L80 95 Q95 80 85 65 Z" fill="#c9a84c"/>
      <path d="M50 18 V120 M50 18 C40 30 30 50 20 70 M50 18 C60 30 70 50 80 70" fill="none" stroke="rgba(0,0,0,0.25)" stroke-width="0.7"/>
      <path d="M42 95 L42 125 L58 125 L58 95 Q50 85 42 95" fill="rgba(0,0,0,0.4)"/>
      <rect x="47" y="130" width="6" height="18" fill="#c9a84c" rx="2"/>
    </svg>
  </div>
  <div class="loader-title">JAVA</div>
  <div class="loader-sub">Heritage Boutique Hotel · Yogyakarta</div>
  <div class="loader-bar-wrap"><div class="loader-bar"></div></div>
</div>

<!-- BG LAYERS -->
<div class="bg-glow"></div>
<div class="bg-batik"></div>
<div class="bg-grain"></div>

<!-- MOBILE TOPBAR -->
<div class="topbar" id="topbar">
  <span class="topbar-brand">JAVA</span>
  <button class="burger" onclick="toggleSb()" aria-label="Menu">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
  </button>
</div>
<div class="sb-overlay" id="sbov" onclick="toggleSb()"></div>

<div style="display:flex;min-height:100vh;">

<!-- ══════════ SIDEBAR ══════════ -->
<aside class="sidebar" id="sb">
  <div class="sidebar-top-neon"></div>
  <div class="sb-brand">
    <span class="sb-jawa">ꦗꦮ</span>
    <span class="sb-name">JAVA</span>
    <span class="sb-sub">Heritage Boutique Hotel</span>
  </div>
  <nav class="sb-nav">
    @php $cur = request()->routeIs('home') ? 'home' : (request()->routeIs('pricing') ? 'pricing' : (request()->routeIs('gallery') ? 'gallery' : (request()->routeIs('check') ? 'check' : (request()->routeIs('admin.*') ? request()->route()->getName() : '')))); @endphp
    <a href="{{ route('home') }}" class="nav-a {{ request()->routeIs('home') ? 'on' : '' }}"><i data-lucide="home"></i> Home</a>
    <a href="{{ route('gallery') }}" class="nav-a {{ request()->routeIs('gallery') ? 'on' : '' }}"><i data-lucide="layers"></i> 3D Gallery</a>
    <a href="{{ route('pricing') }}" class="nav-a {{ request()->routeIs('pricing') ? 'on' : '' }}"><i data-lucide="tag"></i> Harga Kamar</a>
    <a href="{{ route('check') }}" class="nav-a {{ request()->routeIs('check') ? 'on' : '' }}"><i data-lucide="search"></i> Cek Booking</a>

    @if(session('admin_auth'))
    <div class="nav-sep"></div>
    <a href="{{ route('admin.reservations') }}" class="nav-a {{ request()->routeIs('admin.reservations') ? 'on' : '' }}"><i data-lucide="calendar"></i> Reservasi</a>
    <a href="{{ route('admin.rooms') }}" class="nav-a {{ request()->routeIs('admin.rooms') ? 'on' : '' }}"><i data-lucide="bed-double"></i> Status Kamar</a>
    <a href="{{ route('admin.reports') }}" class="nav-a {{ request()->routeIs('admin.reports') ? 'on' : '' }}"><i data-lucide="bar-chart-2"></i> Laporan Keuangan</a>
    <a href="{{ route('admin.manage-rooms') }}" class="nav-a {{ request()->routeIs('admin.manage-rooms') ? 'on' : '' }}"><i data-lucide="settings-2"></i> Kelola Kamar</a>
    <div class="nav-sep"></div>
    <form action="{{ route('logout') }}" method="POST" style="margin:0">
      @csrf
      <button type="submit" class="nav-a" style="width:100%;background:none;border:none;cursor:pointer;text-align:left;">
        <i data-lucide="log-out"></i> Logout
      </button>
    </form>
    @else
    <div class="nav-sep"></div>
    <a href="{{ route('login') }}" class="nav-a {{ request()->routeIs('login') ? 'on' : '' }}"><i data-lucide="user"></i> Staff Login</a>
    @endif
  </nav>
</aside>

<!-- ══════════ MAIN ══════════ -->
<div class="main" style="display:flex;flex-direction:column;min-width:0;">
<main class="page">
  @if(session('success'))
    <div class="alert alert-ok" style="margin-bottom:16px;"><i data-lucide="check-circle"></i> {{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-err" style="margin-bottom:16px;"><i data-lucide="alert-circle"></i> {{ session('error') }}</div>
  @endif
  @if(session('paid'))
    <div class="alert alert-ok" style="margin-bottom:16px;"><i data-lucide="check-circle"></i> {{ session('paid') }}</div>
  @endif

  @yield('content')
</main>

<!-- FOOTER -->
<footer class="footer">
  <div class="footer-batik"></div>
  <div class="footer-top-neon"></div>
  <div class="fg-grid">
    <div>
      <span class="fb-name">JAVA</span>
      <span class="fb-sub">Heritage Boutique Hotel</span>
      <p class="fb-desc">Merasakan keanggunan abadi warisan Jawa. Di mana setiap sudut menceritakan hikmat dan keindahan budaya leluhur.</p>
    </div>
    <div>
      <span class="fc-title">Menu</span>
      <ul class="flinks">
        <li><a href="{{ route('home') }}">Beranda</a></li>
        <li><a href="{{ route('pricing') }}">Harga Kamar</a></li>
        <li><a href="{{ route('gallery') }}">3D Gallery</a></li>
        <li><a href="{{ route('check') }}">Cek Booking</a></li>
      </ul>
    </div>
    <div>
      <span class="fc-title">Reservasi</span>
      <div class="fci"><i data-lucide="phone"></i><span>+62 274 555 999</span></div>
      <div class="fci"><i data-lucide="mail"></i><span>heritage@javahotel.com</span></div>
      <div class="fci"><i data-lucide="map-pin"></i><span>Jl. Malioboro No. 1, Yogyakarta 55271</span></div>
    </div>
    <div>
      <span class="fc-title">Ikuti Kami</span>
      <div class="fsoc">
        <a href="#" class="fsoc-a"><i data-lucide="instagram"></i></a>
        <a href="#" class="fsoc-a"><i data-lucide="facebook"></i></a>
        <a href="#" class="fsoc-a"><i data-lucide="message-circle"></i></a>
      </div>
      <p style="font-size:10.5px;font-style:italic;color:var(--text3);margin-top:18px;line-height:1.7;">"Hamemayu Hayuning Bawana"<br>Menjaga keindahan & kedamaian alam semesta.</p>
    </div>
  </div>
  <div class="footer-center">
    <span class="sb-jawa" style="font-size:16px;display:block;margin-bottom:3px;">ꦗꦮꦲꦺꦴꦠꦺꦭ꧀</span>
    <span class="fb-name" style="opacity:.14;font-size:20px;">JAVA HOTEL</span>
  </div>
  <div class="foot-bottom">
    <p class="foot-copy">© {{ date('Y') }} Puri Jawi Legacy · Heritage Management System</p>
    <div class="foot-links"><a href="#">Privacy</a><a href="#">Terms</a></div>
  </div>
</footer>
</div><!-- /main+footer -->
</div><!-- /layout -->

<!-- ══════ BOOKING MODAL ══════ -->
<div class="moverlay" id="bookM">
  <div class="mbox">
    <div class="mhdr">
      <div><div class="mtitle">Buat Reservasi</div><div class="msub">Heritage Guest Registration</div></div>
      <button class="mclose" onclick="closeM('bookM')"><i data-lucide="x"></i></button>
    </div>
    <form action="{{ route('booking.store') }}" method="POST" class="mbody">
      @csrf
      <div class="fg2">
        <div class="fg"><label class="fl">NIK Identity</label><input type="text" name="nik" required class="fc" placeholder="3200000000000000" style="font-family:'DM Mono',monospace;letter-spacing:.06em;"></div>
        <div class="fg"><label class="fl">Nama Tamu</label><input type="text" name="guest_name" required class="fc" placeholder="Nama lengkap"></div>
      </div>
      <div class="fg2">
        <div class="fg"><label class="fl">Telepon</label><input type="text" name="phone" required class="fc" placeholder="08xxxxxxxx"></div>
        <div class="fg"><label class="fl">Email</label><input type="email" name="email" required class="fc" placeholder="tamu@email.com"></div>
      </div>
      <div class="fg">
        <label class="fl">Pilih Kamar</label>
        <select name="room_id" id="room-select" required class="fc">
          <option value="" style="background:#100806;">— Pilih Kamar Tersedia —</option>
          @foreach($availRooms as $r)
          <option value="{{ $r->id }}" data-type="{{ $r->type }}" style="background:#100806;">
            No. {{ $r->number }} — {{ $r->type }} (Rp {{ number_format($r->price,0,',','.') }}/malam)
          </option>
          @endforeach
        </select>
      </div>
      <div class="fg2">
        <div class="fg"><label class="fl">Check-in</label><input type="date" name="checkin_date" required class="fc"></div>
        <div class="fg"><label class="fl">Check-out</label><input type="date" name="checkout_date" required class="fc"></div>
      </div>
      <div class="fg">
        <label class="fl">Fasilitas Tambahan</label>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
          @foreach($facilities as $f)
          <label class="cb-card">
            <input type="checkbox" name="facilities[]" value="{{ $f->id }}">
            <span>{{ $f->name }} <span style="color:var(--gold);font-size:8px;">+Rp {{ number_format($f->price,0,',','.') }}</span></span>
          </label>
          @endforeach
        </div>
      </div>
      <div class="mfoot" style="margin:0 -24px -22px;margin-top:8px;">
        <button type="button" onclick="closeM('bookM')" class="btn btn-ghost">Batalkan</button>
        <button type="submit" class="btn btn-gold"><i data-lucide="check"></i> Konfirmasi Reservasi</button>
      </div>
    </form>
  </div>
</div>

@yield('modals')

<script>
lucide.createIcons();
window.addEventListener('load',()=>{ setTimeout(()=>document.getElementById('loader').classList.add('done'),2200); });
function toggleSb(){ const sb=document.getElementById('sb'),ov=document.getElementById('sbov'); const open=sb.classList.toggle('open'); ov.classList.toggle('vis',open); document.body.style.overflow=open?'hidden':''; }
function openM(id){document.getElementById(id).classList.add('open');document.body.style.overflow='hidden';}
function closeM(id){document.getElementById(id).classList.remove('open');document.body.style.overflow='';}
document.querySelectorAll('.moverlay').forEach(el=>{el.addEventListener('click',function(e){if(e.target===this)closeM(this.id);});});
(()=>{ let h=0; setInterval(()=>{ h=(h+0.5)%360; const g=h<30||h>330?`rgba(201,168,76,1)`:`rgba(232,201,106,1)`; document.querySelectorAll('.neon-strip').forEach(s=>{ s.style.background=`linear-gradient(90deg,transparent 0%,${g} 50%,transparent 100%)`; }); },50); })();

// Filter rooms by type
function openBookingByType(type) {
  openM('bookM');
  setTimeout(() => {
    const select = document.getElementById('room-select');
    const options = select.querySelectorAll('option');
    // Reset all options to visible
    options.forEach(opt => opt.style.display = '');
    if (type) {
      // Hide options that don't match the selected type (except placeholder)
      options.forEach(opt => {
        if (opt.value !== '' && opt.dataset.type !== type) {
          opt.style.display = 'none';
        }
      });
      // Keep placeholder as default
      select.value = '';
    }
  }, 50);
}

// Auto-set checkout min date based on checkin
document.querySelector('input[name="checkin_date"]').addEventListener('change', function() {
  const checkin = new Date(this.value);
  checkin.setDate(checkin.getDate() + 1);
  const minCheckout = checkin.toISOString().split('T')[0];
  const checkoutInput = document.querySelector('input[name="checkout_date"]');
  checkoutInput.min = minCheckout;
  if (checkoutInput.value && checkoutInput.value <= this.value) {
    checkoutInput.value = minCheckout;
  }
});
@yield('scripts')
</script>
</body>
</html>