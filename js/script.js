// ambil elemen2 yang dibutuhkan

var keyword=document.getElementById('keyword');
var tombolCari=document.getElementById('tombol-cari');
var container=document.getElementById('container');


//tambahkan event ketika keyword ditulis
keyword.addEventListener('keyup',function() {
   // buat obejct ajax
   var xhr=new XMLHttpRequest();
   
   // cek kesiapan ajax
    xhr.onreadystatechange=function(){
        if (this.readyState == 4 && this.status == 200) {
            container.innerHTML = xhr.responseText;
          }
        };
        xhr.open('GET', 'ajax/ajaxMhs2.php?keyword='+keyword.value, true);
        xhr.send();
});