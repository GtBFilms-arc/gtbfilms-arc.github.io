<html><body>
Morevil Web Chat Lite 2.4  www.morevil.com
<?
if(isset($dd)){$d=$dd;}
else if(isset($_POST['dd'])){$d=$_POST['dd'];}
else if(isset($HTTP_POST_VARS['dd'])){$d=$HTTP_POST_VARS['dd'];}
else {echo "\n<!--xxx-->\n</body></html>";exit();}
$d=explode("\n",str_replace("\\", "", $d));
foreach($d as $w){$x=substr($w,0,1);$$x=substr($w,1);}
$tp=30;$st=$p."chat.txt";$in=$p."chat.tmp";$sm=$p."chat.dat";
$r=array($st,$in,$sm);
$q="\$-\$$l\n";
if($a==1){if(f1()){ao();}}
else if($a==2 || $a==4){
if($j!="n" && $I=of($in,"r+")){fseek($I, $j+22); fwrite($I, time());fclose($I);}
if($a==4){
if($T= of($st, "a")){fwrite ($T, "d$b :   $k\n");fclose($T);}
$q.= "k\n";
clearstatcache();
}
if($T=of($st,"r")){
fseek($T, $i); $q.=fread($T, 10000)."\ng".filesize($st)."\n"; fclose($T);
}
}
else if($a==3){if(f1()){lo();}}
else if($a==9 && $j!="n" && $b!=""){
$q.="uu\n";
if($I=of($in,"r+")){
fseek($I, $j);
if(trim(fread($I, 22))==$b){
fseek($I, $j);
fwrite($I, str_repeat(" ",32));fclose($I);
if($T= of($st, "a")){fwrite ($T, "f$b\n");fclose($T);}
}
}
}
echo "\n$q\n\$-\$\n";
function f1(){
global $q, $r, $i, $p;
if($i==""){
foreach($r as $f){
if(!file_exists ($f)){
if(!($F = fopen($f, "a"))){$q.="n$p\n";return 0;}
fclose($F);chmod($f,0777);
}
}
}
return 1;
}
function lo(){
global $q, $tp, $in, $st, $sm, $b, $c;
if(strlen($b)>20 || strlen($b)==0){$q.="c3\n";return;}
if(($I = of($in,"r+")) && ($T = of($st, "a"))){
$n=-1; $tp=time()-$tp;
fseek($I, 0);
$m="";if(filesize($in)>0){$m = fread($I, filesize($in));}
$l = strlen($m) - 3;
for($i=0;$i<$l;$i+=32){
$s = rtrim(substr($m,$i,22));  $t = substr($m,$i+22,10);
if($t>0){
if($t>$tp){
if($s==$b){$q.="c1\n";$n=-2;break;}
}
else{
fseek($I, $i); fwrite($I, str_repeat(" ",32));
fwrite($T, "l$s\n");
if($n==-1){$n=$i;}
}
}else if($n==-1){$n=$i;}
}
if($n!=-2){
if($n==-1){$n=$i;}
if($M=of($sm,"r+")){
$m = "";if(filesize($sm)>0){$m=fread($M, filesize($sm));}else{fwrite($M, " ");}
$uo = str_replace("=", " ", pack("V",crc32($b))); $po = str_replace("=", " ", pack("V",crc32($c)));
if($x=strpos($m,"=$uo")){ $u=2;if("=$uo$po" != substr($m,$x,9)){$q.="c2\n";$u=1;}}
if($u!=1){
if($c!="" && $u!=2){fseek($M, 0, SEEK_END); fwrite($M, "=$uo$po");}
fseek($I, $n);  fwrite($I, str_pad($b,22).time());
fseek($T, 0, SEEK_END);  fwrite($T, "h$b\n");
$q.="a$b\ni$n\n";
}
}fclose($M);
}
fclose($I);fclose($T);
}else{$q.="c4\n";}
}
function ao(){
global $q, $r, $tp, $in, $st, $b;
if(($I = of($in,"r+")) && ($T = of($st, "a"))){
$a=0; $tp=time()-$tp;
fseek($I, 0);
$m = fread($I, filesize($in));
$l = strlen($m) - 3;
for($i=0;$i<$l;$i+=32){
$s = rtrim(substr($m,$i,22));  $t = substr($m,$i+22,10);
if($t>0){
$a=1;
if($t>$tp){$q.="e$s\n"; }
else{
fseek($I, $i); fwrite($I, str_repeat(" ",32));
fwrite($T, "l$s\n");
}
}
}
if($a==0){if(filesize($st)>2048){ftruncate($T, 0);}ftruncate($I, 0);}
fclose($I);fclose($T);
$q.="y1\ng".filesize($st)."\n";
}
}
function of($b,$c){while($i++ < 2){if($a = fopen($b,$c)){return $a;}usleep(200);}return 0;}
?>
</body></html>
