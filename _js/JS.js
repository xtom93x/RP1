function admin_change(e){
  if (document.getElementById('admin_yes').checked){
    document.getElementById('prava_admin').style.display='table-row';
  }else document.getElementById('prava_admin').style.display='none';
}

function potvrd_zmaz_user(e){
  var con=confirm('Naozaj chceš zmazať tohoto bojovníka?');
  if (!con){
    e.preventDefault();
  }
}