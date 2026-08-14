const posts=[{id:1,title:'欢迎来到我的博客',content:'这里是第一篇日志。以后会在这里记录技术、游戏和生活。',date:'2026-08-14'}];
function escapeHTML(s){return String(s).replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}
function getPosts(){try{return JSON.parse(localStorage.getItem('posts')||'null')||posts}catch{return posts}}
function savePosts(p){localStorage.setItem('posts',JSON.stringify(p))}
const list=document.getElementById('list');
if(list){list.innerHTML=getPosts().slice().reverse().map(p=>`<article class="card"><div class="post-meta">LOG-${p.id} // ${escapeHTML(p.date||'')}</div><a href="post.html?id=${encodeURIComponent(p.id)}"><div class="post-title">&gt; ${escapeHTML(p.title)}</div></a><div>${escapeHTML(p.content).slice(0,120)}...</div></article>`).join('')||'<div class="card">NO DATA</div>'}
const title=document.getElementById('title');
if(title){const id=new URLSearchParams(location.search).get('id');const p=getPosts().find(x=>String(x.id)===String(id));if(p){title.textContent=p.title;document.getElementById('content').textContent=p.content}else{title.textContent='DATA NOT FOUND'}}
function login(){const u=document.getElementById('user').value,p=document.getElementById('pass').value;if(u==='admin'&&p==='123456'){sessionStorage.setItem('admin','1');location.reload()}else alert('ACCESS DENIED')}
function addPost(){if(sessionStorage.getItem('admin')!=='1')return;const t=document.getElementById('titleInput').value.trim(),c=document.getElementById('contentInput').value.trim();if(!t||!c)return alert('INPUT REQUIRED');const p=getPosts();p.push({id:Date.now(),title:t,content:c,date:new Date().toISOString().slice(0,10)});savePosts(p);alert('LOG SAVED');location.href='index.html'}
if(location.pathname.endsWith('admin.html')){if(sessionStorage.getItem('admin')==='1'){document.getElementById('login').style.display='none';document.getElementById('panel').style.display='block'}}
let aCount=0;document.addEventListener('keydown',e=>{if(e.key.toLowerCase()==='a'){aCount++;if(aCount>=5)location.href='admin.html'}else if(e.key==='/'){const c=prompt('ENTER COMMAND:');if(c==='admin')location.href='admin.html'}});
