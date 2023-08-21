let paso=1;const pasoInicial=1,pasoFinal=3,cita={id:"",nombre:"",fecha:"",hora:"",servicios:[]};function iniciarApp(){mostrarSecion(),tabs(),botonesPaginador(),paginaSiguiente(),paginaAnterior(),consultarAPI(),idCliente(),nombreCliente(),seleccionarFecha(),seleccionarHora()}function mostrarSecion(){const e=document.querySelector(".mostrar");e&&e.classList.remove("mostrar");const t="#paso-"+paso;document.querySelector(t).classList.add("mostrar");const o=document.querySelector(".actual");o&&o.classList.remove("actual");document.querySelector(`[data-paso="${paso}"]`).classList.add("actual")}function tabs(){document.querySelectorAll(".tabs button").forEach(e=>{e.addEventListener("click",(function(e){paso=parseInt(e.target.dataset.paso),mostrarSecion(),botonesPaginador()}))})}function botonesPaginador(){const e=document.querySelector("#anterior"),t=document.querySelector("#siguiente");1===paso?(e.classList.add("ocultar"),t.classList.remove("ocultar")):3===paso?(e.classList.remove("ocultar"),t.classList.add("ocultar"),mostrarResumen()):2===paso&&(t.classList.remove("ocultar"),e.classList.remove("ocultar")),mostrarSecion()}function paginaAnterior(){document.querySelector("#anterior").addEventListener("click",(function(){paso<=1||(paso--,botonesPaginador())}))}function paginaSiguiente(){document.querySelector("#siguiente").addEventListener("click",(function(){paso>=3||(paso++,botonesPaginador())}))}async function consultarAPI(){try{const e=location.origin+"/api/servicios",t=await fetch(e);mostrarServicios(await t.json())}catch(e){console.log(e)}}function mostrarServicios(e){e.forEach(e=>{const{id:t,nombre:o,precio:a}=e,n=document.createElement("P");n.classList.add("nombre-servcio"),n.textContent=o;const c=document.createElement("p");c.classList.add("precio-servicio"),c.textContent="$"+a;const r=document.createElement("DIV");r.classList.add("servicio"),r.dataset.idServicio=t,r.onclick=function(){seleccionarServicio(e)},r.appendChild(n),r.appendChild(c),document.querySelector("#servicios").appendChild(r)})}function seleccionarServicio(e){const{servicios:t}=cita,{id:o}=e,a=document.querySelector(`[data-id-servicio = "${o}"]`);t.some(e=>e.id===o)?(cita.servicios=t.filter(e=>e.id!==o),a.classList.remove("seleccionado")):(cita.servicios=[...t,e],a.classList.add("seleccionado"))}function idCliente(){cita.id=document.querySelector("#id").value}function nombreCliente(){cita.nombre=document.querySelector("#nombre").value}function seleccionarFecha(){const e=document.querySelector("#fecha"),t=document.querySelector("#alertaCita");e.addEventListener("input",(function(e){const o=new Date(e.target.value).getUTCDay();[6,0].includes(o)?(e.target.value="",mostrarAlerta("Fines de semana no abrimos","error",t)):cita.fecha=e.target.value}))}function seleccionarHora(){document.querySelector("#hora").addEventListener("input",(function(e){const t=e.target.value.split(":")[0],o=document.querySelector("#alertaCita");t<9||t>18?(e.target.value="",mostrarAlerta("Abrimos de 9:00 am a 7:00 pm","error",o)):cita.hora=e.target.value}))}function mostrarAlerta(e,t,o,a=!0){const n=document.querySelector(".alerta");n&&n.remove();const c=document.createElement("DIV");c.textContent=e,c.classList.add("alerta"),c.classList.add(t),o.appendChild(c),a&&setTimeout(()=>{c.remove()},3e3)}function mostrarResumen(){const e=document.querySelector(".resumen-contenido");for(;e.firstChild;)e.removeChild(e.firstChild);if(Object.values(cita).includes("")||0===cita.servicios.length)return void mostrarAlerta("Faltan datos o servicios :(","error",e,!1);const{nombre:t,fecha:o,hora:a,servicios:n}=cita,c=document.createElement("H3");c.textContent="Tu cita",e.appendChild(c);const r=document.createElement("P");r.innerHTML="<span>Nombre: </span> "+t,e.appendChild(r);const i=new Date(o),s=i.getMonth(),d=i.getDate()+2,l=i.getFullYear(),u=new Date(Date.UTC(l,s,d)).toLocaleDateString("es-MX",{weekday:"long",year:"numeric",month:"long",day:"numeric"}),m=document.createElement("P");m.innerHTML="<span>Fecha: </span> "+u,e.appendChild(m);const p=document.createElement("P");p.innerHTML=`<span>Hora: </span> ${a} horas`,e.appendChild(p);const v=document.createElement("H3");v.textContent="Tus servicios",e.appendChild(v),n.forEach(t=>{const{id:o,nombre:a,precio:n}=t,c=document.createElement("DIV");c.classList.add("contenedor-servicio");const r=document.createElement("P");r.innerHTML="<span>Servicio: </span> "+a;const i=document.createElement("p");i.innerHTML=`<span>Precio: </span> $${n} `,c.appendChild(r),c.appendChild(i),e.appendChild(c)});const h=document.createElement("BUTTON");h.classList.add("boton"),h.textContent="Reservar cita",h.onclick=reservarCita,e.appendChild(h)}async function reservarCita(){const{nombre:e,fecha:t,hora:o,servicios:a,id:n}=cita,c=a.map(e=>e.id),r=new FormData;r.append("fecha",t),r.append("hora",o),r.append("usuarioId",n),r.append("servicios",c);try{const e=location.origin+"/api/citas",t=await fetch(e,{method:"POST",body:r}),o=await t.json();console.log(o),o.resultado&&Swal.fire({icon:"success",title:"Cita Creada",text:"Genial ahora tenemos una Cita!",button:"OK"}).then(()=>{setTimeout(()=>{window.location.reload()},10)})}catch(e){Swal.fire({icon:"error",title:"Oops Hubo un error",text:"¡Tu cita no se ha agendado!"})}}document.addEventListener("DOMContentLoaded",(function(){iniciarApp()}));