@extends('layouts.app')

@section('content')
<div class="ph">
  <div><h1 class="ph-title">Heritage Inventory</h1><p class="ph-sub">Kelola portfolio kamar & suite</p></div>
  <button onclick="openRoomM()" class="btn btn-gold"><i data-lucide="plus"></i> New Chamber</button>
</div>
<div class="tw ai"><div class="ts">
  <table class="dt">
    <thead><tr><th>No.</th><th>Tipe</th><th>Harga / Malam</th><th>Deskripsi</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
    @foreach($rooms as $rm)
    <tr>
      <td class="mono" style="color:var(--gold);font-size:16px;">{{ $rm->number }}</td>
      <td><span class="badge b-gold">{{ $rm->type }}</span></td>
      <td style="font-family:'Cormorant Garamond',serif;font-size:17px;">Rp {{ number_format($rm->price,0,',','.') }}</td>
      <td style="font-size:12.5px;color:var(--text2);max-width:240px;">{{ Str::limit($rm->description, 70) }}</td>
      <td><span class="badge {{ $rm->availability ? 'b-green' : 'b-rose' }}">{{ $rm->availability ? 'Tersedia' : 'Terisi' }}</span></td>
      <td>
        <div style="display:flex;gap:3px;">
          <button onclick='editRoom({{ json_encode($rm) }})' class="ab ab-info" title="Edit"><i data-lucide="pencil"></i></button>
          <form action="{{ route('admin.manage-rooms.destroy', $rm->id) }}" method="POST" onsubmit="return confirm('Hapus kamar {{ $rm->number }} secara permanen?')" style="margin:0">
            @csrf
            @method('DELETE')
            <button class="ab ab-danger" title="Hapus"><i data-lucide="trash-2"></i></button>
          </form>
        </div>
      </td>
    </tr>
    @endforeach
    </tbody>
  </table>
</div></div>
@endsection

@section('modals')
<!-- ROOM MODAL -->
<div class="moverlay" id="roomM">
  <div class="mbox" style="max-width:500px;">
    <div class="mhdr">
      <div><div class="mtitle" id="rmTitle">Tambah Kamar</div><div class="msub">Heritage Room Management</div></div>
      <button class="mclose" onclick="closeM('roomM')"><i data-lucide="x"></i></button>
    </div>
    <form id="rmForm" method="POST" class="mbody">
      @csrf
      <input type="hidden" name="_method" id="rmMethod" value="POST">
      <div class="fg2">
        <div class="fg"><label class="fl">Nomor Kamar</label><input type="text" name="number" required class="fc" style="font-family:'DM Mono',monospace;"></div>
        <div class="fg">
          <label class="fl">Tipe</label>
          <select name="type" required class="fc">
            <option style="background:#100806;">Single</option>
            <option style="background:#100806;">Double</option>
            <option style="background:#100806;">Deluxe</option>
            <option style="background:#100806;">Suite</option>
          </select>
        </div>
      </div>
      <div class="fg"><label class="fl">Harga / Malam (IDR)</label><input type="number" name="price" required class="fc" placeholder="850000"></div>
      <div class="fg"><label class="fl">Deskripsi</label><textarea name="description" rows="3" class="fc" style="resize:none;line-height:1.6;" placeholder="Deskripsi kamar..."></textarea></div>
      <div class="mfoot" style="margin:0 -24px -22px;margin-top:8px;">
        <button type="button" onclick="closeM('roomM')" class="btn btn-ghost">Batal</button>
        <button type="submit" class="btn btn-gold"><i data-lucide="save"></i> Simpan Kamar</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
const addUrl  = "{{ route('admin.manage-rooms.store') }}";
const baseUrl = "{{ url('admin/manage-rooms') }}";

function openRoomM(data=null){
  const f=document.getElementById('rmForm'), t=document.getElementById('rmTitle'), m=document.getElementById('rmMethod');
  if(data){
    f.action = baseUrl+'/'+data.id;
    m.value  = 'PUT';
    f.number.value      = data.number;
    f.type.value        = data.type;
    f.price.value       = data.price;
    f.description.value = data.description||'';
    t.textContent='Edit Kamar';
  } else {
    f.action=addUrl; m.value='POST';
    f.reset(); t.textContent='Tambah Kamar';
  }
  openM('roomM');
}
function editRoom(data){ openRoomM(data); }
@endsection
