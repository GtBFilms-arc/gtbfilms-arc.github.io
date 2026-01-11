//////////////////////////////////////////////////////////////////////
//JavaScripts for Yapig (Yet Another PHP image gallery);
//Distributed under GPL license.
//
// -> Thanks to Thomas ORIEUX
//
//Yapig's Home Page: http://yapig.sourceforge.net
////////////////////////////////////////////////////////////////////////

/**
 * sets the arguments size for an image.
 */

function setsize(imgid,width,height){
    var obj=MM_findObj(imgid);
    obj.width=Math.round(width);
    obj.height=Math.round(height);
}

/**
 * sets image size zoomx, imgid
 */

function zoom(imgid,zoomx) {
    var obj=MM_findObj(imgid);
    var w=obj.width;
    var h=obj.height;
    obj.width=Math.round(w*zoomx);
    obj.height=Math.round(h*zoomx);
}

/**
* For preloading images
*/

function preload(src){
    tmp= new Image;tmp.src=src;
    zoomImg();
    
}

/**
* Select an object (got from Macromedia Dreamweaver)
*/
function MM_findObj(n, d) { //v4.01
    var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
      d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
    if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
    for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
    if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function zoomImg() {

var obj=MM_findObj('myimage');
var src_w=obj.width;
var src_h=obj.height;
var ratio=src_h/src_w;
var w,h;
//Only modify if image is too big.
//document.write("ratio: " + ratio + " h:" +  src_h + ' w:' + src_w );
if (max_size!=0) { 
  if ((src_h>max_size)||(src_w>max_size)) {

    if (ratio>=1) { //h>
        w=max_size/ratio;
        h=max_size;
    }
    else {
        w=max_size;
        h=ratio*max_size;
    }
    //Round is done on function.
    setsize('myimage',w,h);
  } //if src_h
 } //if max_size
} //function
    



/****************************************************************

   User comments check Functions 

*****************************************************************/


/**
 * Valida que la dirección de correo tenga un formato adecuado
 */
function checkEmail(obj) {
  var ok=true;

    re=/(^[0-9a-zA-Z_\.-]{1,}@[0-9a-zA-Z_\-]{1,}\.[0-9a-zA-Z_\-]{2,}$)/
      if (!re.test(obj.value)) {
        /*
        alert("e-mail: example@example.com");
        //obj.value="";
        */
        ok = false;
      }


    return(ok);
}

function checkWeb(obj) {

 var ok=true;

    re=/^http:\/\//
      if (!re.test(obj.value)) {
        obj.value= "http://" + obj.value;
     }

return(ok);
}


function formCheck(obj){

  var cadena = "";
  var bgColor="#FFDDDD";
  var borderColor="#FF0000";
  var all_ok = true;

    //Set default style values
	
     obj.web.style.backgroundColor="";
     obj.web.style.borderColor="";

     obj.mail.style.backgroundColor="";
     obj.mail.style.borderColor="";


if ( (obj.mail.value != "") && (checkEmail(obj.mail) == false) ) {
    cadena = cadena + "e-mail: example@example.com\n";
    email=false;
    all_ok = false;
}

if (cadena != "") {
    alert(cadena);
    if (!email) {
       obj.mail.style.backgroundColor=bgColor;
       obj.mail.style.borderColor=borderColor;
    }
}

  return(all_ok);

}


function slideshow(){     
     nw=window.open(document.getElementById('popuplnk').href,'Slideshow','height=600,width=700,location=no,scrollbars=yes,menubars=no,toolbars=no,resizable=yes');
     nw.opener=self;
      return false;
 }
