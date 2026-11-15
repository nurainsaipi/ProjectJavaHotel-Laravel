@extends('layouts.app')

@section('content')
<div class="ph"><div><h1 class="ph-title">3D Room Gallery</h1><p class="ph-sub">Jelajahi kamar heritage secara imersif</p></div></div>
<div class="two-col ai">
  <div class="canvas-c"><div id="three-c" style="width:100%;height:100%;cursor:grab;"></div></div>
  <div style="display:flex;flex-direction:column;gap:12px;">
    @php $seen = []; @endphp
    @foreach($rooms as $rm)
      @if(in_array($rm->type, $seen)) @continue @endif
      @php $seen[] = $rm->type; @endphp
    <div onclick="set3D('{{ $rm->type }}')" class="room-card" id="rc3d-{{ $rm->type }}">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
        <span class="rc-name">{{ $rm->type }} Chamber</span>
        <span class="rc-price">Rp {{ number_format($rm->price,0,',','.') }}</span>
      </div>
      <p class="rc-desc">{{ $rm->description }}</p>
    </div>
    @endforeach
    <div style="padding:16px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r);margin-top:4px;">
      <p class="mono" style="font-size:8.5px;letter-spacing:.18em;text-transform:uppercase;color:var(--text2);margin-bottom:10px;">Kontrol Navigasi</p>
      <div style="display:flex;flex-direction:column;gap:6px;">
        @foreach([['🖱 Klik & Seret','Putar tampilan kamar'],['🔲 Scroll Roda','Zoom in / zoom out'],['📱 Sentuh & Seret','Kontrol di mobile']] as [$icon,$desc])
        <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text2);">{{ $icon }} <span>{{ $desc }}</span></div>
        @endforeach
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
(()=>{
  const c=document.getElementById('three-c');
  if(!c)return;
  const scene=new THREE.Scene();
  const cam=new THREE.PerspectiveCamera(52,1,.1,100);
  const ren=new THREE.WebGLRenderer({antialias:true,alpha:true});
  ren.shadowMap.enabled=true;
  function resize(){ const w=c.clientWidth,h=c.clientHeight||c.clientWidth; ren.setSize(w,h);cam.aspect=w/h;cam.updateProjectionMatrix(); }
  resize();c.appendChild(ren.domElement);
  window.addEventListener('resize',resize);
  scene.add(new THREE.AmbientLight(0xffffff,.45));
  const warm=new THREE.PointLight(0xc9a84c,2.2,18);warm.position.set(3,3.5,3);warm.castShadow=true;scene.add(warm);
  const fill=new THREE.PointLight(0x3a1a0a,.9,14);fill.position.set(-3,1,-2);scene.add(fill);
  const walls=new THREE.Mesh(new THREE.BoxGeometry(11,7,11),new THREE.MeshStandardMaterial({color:0x160803,side:THREE.BackSide,roughness:.92}));scene.add(walls);
  const floor=new THREE.Mesh(new THREE.PlaneGeometry(11,11),new THREE.MeshStandardMaterial({color:0x2a1208,roughness:.9}));floor.rotation.x=-Math.PI/2;floor.position.y=-3.5;floor.receiveShadow=true;scene.add(floor);
  const rug=new THREE.Mesh(new THREE.PlaneGeometry(6,7.5),new THREE.MeshStandardMaterial({color:0x5a1515,roughness:.85}));rug.rotation.x=-Math.PI/2;rug.position.set(0,-3.49,.5);scene.add(rug);
  const woodM=new THREE.MeshStandardMaterial({color:0x3d2010,roughness:.78});
  const whiteM=new THREE.MeshStandardMaterial({color:0xf0ece4,roughness:.85});
  const frame=new THREE.Mesh(new THREE.BoxGeometry(5.5,.7,6.5),woodM);frame.position.y=-3.15;frame.castShadow=true;scene.add(frame);
  const mattr=new THREE.Mesh(new THREE.BoxGeometry(5.2,.9,6.2),whiteM);mattr.position.y=-2.5;scene.add(mattr);
  const hboard=new THREE.Mesh(new THREE.BoxGeometry(5.6,3.2,.35),new THREE.MeshStandardMaterial({color:0x251508,roughness:.72}));hboard.position.set(0,-1.6,-3.2);scene.add(hboard);
  [-1,1].forEach(x=>{const p=new THREE.Mesh(new THREE.BoxGeometry(1.4,.4,.9),whiteM);p.position.set(x,-1.9,-2.2);scene.add(p);});
  const bk=new THREE.Mesh(new THREE.BoxGeometry(5.2,.15,4),new THREE.MeshStandardMaterial({color:0x4a1010,roughness:.9}));bk.position.set(0,-2.02,.8);scene.add(bk);
  const tbl=new THREE.Mesh(new THREE.BoxGeometry(1.3,.12,.9),woodM);tbl.position.set(3.3,.3,-2);scene.add(tbl);
  const goldM=new THREE.MeshStandardMaterial({color:0xc9a84c,emissive:0xc9a84c,emissiveIntensity:.25,metalness:.8,roughness:.3});
  const lBase=new THREE.Mesh(new THREE.CylinderGeometry(.07,.1,.4,12),goldM);lBase.position.set(3.3,.56,-2);scene.add(lBase);
  const lShade=new THREE.Mesh(new THREE.CylinderGeometry(.38,.28,.55,16),new THREE.MeshStandardMaterial({color:0xddb86a,roughness:.7,transparent:true,opacity:.82}));lShade.position.set(3.3,.92,-2);scene.add(lShade);
  const lGlow=new THREE.PointLight(0xd4a444,1.2,4);lGlow.position.set(3.3,.7,-2);scene.add(lGlow);
  const winG=new THREE.Mesh(new THREE.PlaneGeometry(2.5,3),new THREE.MeshStandardMaterial({color:0xc9a84c,emissive:0xc9a84c,emissiveIntensity:.08,transparent:true,opacity:.08}));winG.position.set(0,.5,-5.45);scene.add(winG);
  cam.position.set(7,4.5,10);
  const origin=new THREE.Vector3(0,0,0);
  let drag=false,px=0,py=0,rotY=0.35,rotX=0.15,autoR=true;
  c.addEventListener('mousedown',e=>{drag=true;px=e.clientX;py=e.clientY;autoR=false;c.style.cursor='grabbing';});
  c.addEventListener('touchstart',e=>{drag=true;px=e.touches[0].clientX;py=e.touches[0].clientY;autoR=false;},{passive:true});
  window.addEventListener('mouseup',()=>{drag=false;c.style.cursor='grab';});
  window.addEventListener('touchend',()=>{drag=false;});
  window.addEventListener('mousemove',e=>{if(!drag)return;rotY+=(e.clientX-px)*.008;rotX+=(e.clientY-py)*.005;rotX=Math.max(-.45,Math.min(.55,rotX));px=e.clientX;py=e.clientY;});
  window.addEventListener('touchmove',e=>{if(!drag)return;rotY+=(e.touches[0].clientX-px)*.008;rotX+=(e.touches[0].clientY-py)*.005;rotX=Math.max(-.45,Math.min(.55,rotX));px=e.touches[0].clientX;py=e.touches[0].clientY;},{passive:true});
  c.addEventListener('wheel',e=>{cam.position.multiplyScalar(e.deltaY>0?1.08:.93);cam.position.clampLength(5,20);},{passive:true});
  let t=0;
  function animate(){ requestAnimationFrame(animate);t+=.01; if(autoR)rotY+=.004; const r=cam.position.length(); cam.position.x=Math.sin(rotY)*Math.cos(rotX)*r; cam.position.y=Math.sin(rotX)*r; cam.position.z=Math.cos(rotY)*Math.cos(rotX)*r; cam.lookAt(origin); warm.intensity=2+Math.sin(t*1.5)*.35; lGlow.intensity=1.1+Math.sin(t*2.2)*.2; ren.render(scene,cam); }
  animate();
  window.set3D=function(type){ const cols={Single:0x160803,Deluxe:0x1c0d05,Suite:0x220806}; const rugCols={Single:0x4a1010,Deluxe:0x5a1515,Suite:0x6a0a0a}; walls.material.color.setHex(cols[type]||0x160803); rug.material.color.setHex(rugCols[type]||0x4a1010); document.querySelectorAll('[id^="rc3d-"]').forEach(el=>el.classList.remove('act')); const el=document.getElementById('rc3d-'+type);if(el)el.classList.add('act'); };
})();
@endsection
