@extends('layouts.app')

@section('content')
<div class="login-wrap">
  <div class="login-card ai">
    <div class="l-icon"><i data-lucide="lock"></i></div>
    <div style="text-align:center;margin-bottom:24px;">
      <h2 class="ph-title" style="font-size:28px;font-style:italic;margin-bottom:4px;">Staff Authorization</h2>
      <p class="ph-sub">Heritage Access Portal</p>
    </div>
    @if(session('error'))
      <div class="alert alert-err"><i data-lucide="alert-circle"></i> {{ session('error') }}</div>
    @endif
    <form action="{{ route('login.post') }}" method="POST">
      @csrf
      <div class="fg"><label class="fl">Username</label><input type="text" name="username" required class="fc" placeholder="admin"></div>
      <div class="fg"><label class="fl">Password</label><input type="password" name="password" required class="fc" placeholder="••••••••"></div>
      <button type="submit" class="btn btn-gold" style="width:100%;justify-content:center;padding:13px;margin-top:6px;"><i data-lucide="key-round"></i> Masuk ke Portal</button>
    </form>
    <p style="margin-top:18px;text-align:center;font-family:'DM Mono',monospace;font-size:9px;color:var(--text3);letter-spacing:.12em;">Demo: admin / admin123</p>
  </div>
</div>
@endsection
