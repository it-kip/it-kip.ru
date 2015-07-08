function mask(e){
    if(e.keyCode != 8 && e.charCode != 8 && e.keyCode != 37 && e.charCode != 37 && e.keyCode != 39 && e.charCode != 39 && e.keyCode != 46 && e.charCode != 46)
        if ((e.keyCode < 48 || e.keyCode > 57) && (e.charCode < 48 || e.charCode > 57))
            return false;
        else
            return true;
    else
        return true;
}

function jqmask(e){
    if(!mask(e)){
        var targ = e.target||e.srcElement;
        if(targ.id == undefined) targ.id = ("" + Math.random()).slice(2,7);
        var sib = targ.nextSibling;
        if(sib == null || sib.id != "jqmask_" + targ.id){
            var sib = targ.parentNode.insertBefore(document.createElement('span'), sib);
            sib.id = "jqmask_" + targ.id;
            sib.style.display = 'none';
            sib.style.color = 'red';
            sib.innerHTML = 'Только цифры';
        }
        $(sib).show().fadeOut("slow");
        return false;
    }
}

function isValidEmail (email, strict){
 if ( !strict ) email = email.replace(/^\s+|\s+$/g, '');
 return (/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,4}$/i).test(email);
}