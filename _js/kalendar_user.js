var datum=new Date();       
var d=datum.getDate();      
var m=datum.getMonth()+1;   
var r=datum.getFullYear(); 
var aktual_d=d; 
var aktual_m=m;
var aktual_r=r;
  
function kalendar(mesiac,rok) {
  var dnes=new Date();
  var nazovMesiaca=new Array("","Január","Február","Marec","Apríl","Máj","Jún","Júl","August","September","Október","November","December");
  var datum=new Date(rok,mesiac-1,1);  
  var denTyzn=datum.getDay();          
  if(denTyzn==0){
    denTyzn=7; 
  }
  var pocetDniMesiaca = new Array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
  if (((rok % 4 == 0) && (rok % 100 != 0)) || (rok % 400 == 0)) pocetDniMesiaca[2] = 29;
  var tab="<table border=1><thead><tr><th ";
  if (rok!=dnes.getFullYear() || mesiac!=dnes.getMonth()+1){
    tab+="onclick='pred()'";
  }else{
    tab+="style='color:gray;'";
  }
  tab+=">&lsaquo;&lsaquo;</th><th colspan='5'>"+nazovMesiaca[mesiac]+" "+rok+"</th><th onclick='dal()'>&rsaquo;&rsaquo;</th></tr></thead><tbody><tr><th>Po</th><th>Ut</th><th>Str</th><th>Št</th><th>Pia</th><th>So</th><th style='background-color:gold;'>Ne</th></tr><tr>";
  var i=1;
  for(; i<denTyzn; i++) {
    tab+="<td></td>";
  }
  for(den=1; den<=pocetDniMesiaca[mesiac]; den++) {
    if(rok==aktual_r && mesiac==aktual_m && den==aktual_d) {
      tab+="<td class='kalendar_aktual'>"+den+"</td>";
    }else{
      tab+="<td ";
      if (rok==dnes.getFullYear() && mesiac==dnes.getMonth()+1 && den==dnes.getDate()){
        tab+="class='kalendar_dnes' ";
      }else if (i%7==0){
        tab+="class='kalendar_nedela' ";
      }
      if (new Date(rok,mesiac-1,den)<dnes){
        tab+="style='color:gray;' ";
      }else{
        tab+="onclick='daj_terminy("+den+','+mesiac+','+rok+")'";
      }
      tab+=">"+den+"</td>";
    }
    if(i % 7 == 0 && den!=pocetDniMesiaca[mesiac]) {
      tab+="</tr><tr>";
    } 
    i++;
  }
  for(i=i-1; i % 7 !=0; i++) {
    tab+="<td></td>";
  }
  tab+="</tr></tbody></table>";
  return tab;
}
  
function dal() { 
  if(m==12) {
    r++; 
    m=1;
  }else{
    m++;
  }
  zobrazKalendar(m,r);
}
  
function pred() {
  if(m==1) {
    r--; 
    m=12;
  }else{
    m--;
  }
  zobrazKalendar(m,r);
}
  
function daj_terminy(den,mesiac,rok){
  var form=document.createElement('form');
  form.method='post';
  var datum=document.createElement('input');
  datum.name='datum';
  datum.value=rok+"-"+("0"+mesiac).slice(-2)+"-"+("0"+den).slice(-2);
  form.appendChild(datum);
  document.body.appendChild(form);
  form.submit();    
}  
  
function zobrazKalendar(mesiac,rok) {
  document.getElementById("kalendar_box").innerHTML=kalendar(mesiac,rok); 
}