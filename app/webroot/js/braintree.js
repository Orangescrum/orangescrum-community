/*!
 * Braintree End-to-End Encryption Library
 * https://www.braintreepayments.com
 * Copyright (c) 2009-2013 Braintree Payment Solutions
 *
 * JSBN
 * Copyright (c) 2005  Tom Wu
 *
 * Both Licensed under the MIT License.
 * http://opensource.org/licenses/MIT
 *
 * ASN.1 JavaScript decoder
 * Copyright (c) 2008-2009 Lapo Luchini <lapo@lapo.it>
 * Licensed under the ISC License.
 * http://opensource.org/licenses/ISC
 */

(function(){function Stream(enc,pos){if(enc instanceof Stream){this.enc=enc.enc;this.pos=enc.pos;}else{this.enc=enc;this.pos=pos;}}
Stream.prototype.get=function(pos){if(pos==undefined)
pos=this.pos++;if(pos>=this.enc.length)
throw'Requesting byte offset '+pos+' on a stream of length '+this.enc.length;return this.enc[pos];}
Stream.prototype.hexDigits="0123456789ABCDEF";Stream.prototype.hexByte=function(b){return this.hexDigits.charAt((b>>4)&0xF)+this.hexDigits.charAt(b&0xF);}
Stream.prototype.hexDump=function(start,end){var s="";for(var i=start;i<end;++i){s+=this.hexByte(this.get(i));switch(i&0xF){case 0x7:s+="  ";break;case 0xF:s+="\n";break;default:s+=" ";}}
return s;}
Stream.prototype.parseStringISO=function(start,end){var s="";for(var i=start;i<end;++i)
s+=String.fromCharCode(this.get(i));return s;}
Stream.prototype.parseStringUTF=function(start,end){var s="",c=0;for(var i=start;i<end;){var c=this.get(i++);if(c<128)
s+=String.fromCharCode(c);else if((c>191)&&(c<224))
s+=String.fromCharCode(((c&0x1F)<<6)|(this.get(i++)&0x3F));else
s+=String.fromCharCode(((c&0x0F)<<12)|((this.get(i++)&0x3F)<<6)|(this.get(i++)&0x3F));}
return s;}
Stream.prototype.reTime=/^((?:1[89]|2\d)?\d\d)(0[1-9]|1[0-2])(0[1-9]|[12]\d|3[01])([01]\d|2[0-3])(?:([0-5]\d)(?:([0-5]\d)(?:[.,](\d{1,3}))?)?)?(Z|[-+](?:[0]\d|1[0-2])([0-5]\d)?)?$/;Stream.prototype.parseTime=function(start,end){var s=this.parseStringISO(start,end);var m=this.reTime.exec(s);if(!m)
return"Unrecognized time: "+s;s=m[1]+"-"+m[2]+"-"+m[3]+" "+m[4];if(m[5]){s+=":"+m[5];if(m[6]){s+=":"+m[6];if(m[7])
s+="."+m[7];}}
if(m[8]){s+=" UTC";if(m[8]!='Z'){s+=m[8];if(m[9])
s+=":"+m[9];}}
return s;}
Stream.prototype.parseInteger=function(start,end){var len=end-start;if(len>4){len<<=3;var s=this.get(start);if(s==0)
len-=8;else
while(s<128){s<<=1;--len;}
return"("+len+" bit)";}
var n=0;for(var i=start;i<end;++i)
n=(n<<8)|this.get(i);return n;}
Stream.prototype.parseBitString=function(start,end){var unusedBit=this.get(start);var lenBit=((end-start-1)<<3)-unusedBit;var s="("+lenBit+" bit)";if(lenBit<=20){var skip=unusedBit;s+=" ";for(var i=end-1;i>start;--i){var b=this.get(i);for(var j=skip;j<8;++j)
s+=(b>>j)&1?"1":"0";skip=0;}}
return s;}
Stream.prototype.parseOctetString=function(start,end){var len=end-start;var s="("+len+" byte) ";if(len>20)
end=start+20;for(var i=start;i<end;++i)
s+=this.hexByte(this.get(i));if(len>20)
s+=String.fromCharCode(8230);return s;}
Stream.prototype.parseOID=function(start,end){var s,n=0,bits=0;for(var i=start;i<end;++i){var v=this.get(i);n=(n<<7)|(v&0x7F);bits+=7;if(!(v&0x80)){if(s==undefined)
s=parseInt(n/40)+"."+(n%40);else
s+="."+((bits>=31)?"bigint":n);n=bits=0;}
s+=String.fromCharCode();}
return s;}
function ASN1(stream,header,length,tag,sub){this.stream=stream;this.header=header;this.length=length;this.tag=tag;this.sub=sub;}
ASN1.prototype.typeName=function(){if(this.tag==undefined)
return"unknown";var tagClass=this.tag>>6;var tagConstructed=(this.tag>>5)&1;var tagNumber=this.tag&0x1F;switch(tagClass){case 0:switch(tagNumber){case 0x00:return"EOC";case 0x01:return"BOOLEAN";case 0x02:return"INTEGER";case 0x03:return"BIT_STRING";case 0x04:return"OCTET_STRING";case 0x05:return"NULL";case 0x06:return"OBJECT_IDENTIFIER";case 0x07:return"ObjectDescriptor";case 0x08:return"EXTERNAL";case 0x09:return"REAL";case 0x0A:return"ENUMERATED";case 0x0B:return"EMBEDDED_PDV";case 0x0C:return"UTF8String";case 0x10:return"SEQUENCE";case 0x11:return"SET";case 0x12:return"NumericString";case 0x13:return"PrintableString";case 0x14:return"TeletexString";case 0x15:return"VideotexString";case 0x16:return"IA5String";case 0x17:return"UTCTime";case 0x18:return"GeneralizedTime";case 0x19:return"GraphicString";case 0x1A:return"VisibleString";case 0x1B:return"GeneralString";case 0x1C:return"UniversalString";case 0x1E:return"BMPString";default:return"Universal_"+tagNumber.toString(16);}
case 1:return"Application_"+tagNumber.toString(16);case 2:return"["+tagNumber+"]";case 3:return"Private_"+tagNumber.toString(16);}}
ASN1.prototype.content=function(){if(this.tag==undefined)
return null;var tagClass=this.tag>>6;if(tagClass!=0)
return(this.sub==null)?null:"("+this.sub.length+")";var tagNumber=this.tag&0x1F;var content=this.posContent();var len=Math.abs(this.length);switch(tagNumber){case 0x01:return(this.stream.get(content)==0)?"false":"true";case 0x02:return this.stream.parseInteger(content,content+len);case 0x03:return this.sub?"("+this.sub.length+" elem)":this.stream.parseBitString(content,content+len)
case 0x04:return this.sub?"("+this.sub.length+" elem)":this.stream.parseOctetString(content,content+len)
case 0x06:return this.stream.parseOID(content,content+len);case 0x10:case 0x11:return"("+this.sub.length+" elem)";case 0x0C:return this.stream.parseStringUTF(content,content+len);case 0x12:case 0x13:case 0x14:case 0x15:case 0x16:case 0x1A:return this.stream.parseStringISO(content,content+len);case 0x17:case 0x18:return this.stream.parseTime(content,content+len);}
return null;}
ASN1.prototype.toString=function(){return this.typeName()+"@"+this.stream.pos+"[header:"+this.header+",length:"+this.length+",sub:"+((this.sub==null)?'null':this.sub.length)+"]";}
ASN1.prototype.print=function(indent){if(indent==undefined)indent='';document.writeln(indent+this);if(this.sub!=null){indent+='  ';for(var i=0,max=this.sub.length;i<max;++i)
this.sub[i].print(indent);}}
ASN1.prototype.toPrettyString=function(indent){if(indent==undefined)indent='';var s=indent+this.typeName()+" @"+this.stream.pos;if(this.length>=0)
s+="+";s+=this.length;if(this.tag&0x20)
s+=" (constructed)";else if(((this.tag==0x03)||(this.tag==0x04))&&(this.sub!=null))
s+=" (encapsulates)";s+="\n";if(this.sub!=null){indent+='  ';for(var i=0,max=this.sub.length;i<max;++i)
s+=this.sub[i].toPrettyString(indent);}
return s;}
ASN1.prototype.posStart=function(){return this.stream.pos;}
ASN1.prototype.posContent=function(){return this.stream.pos+this.header;}
ASN1.prototype.posEnd=function(){return this.stream.pos+this.header+Math.abs(this.length);}
ASN1.decodeLength=function(stream){var buf=stream.get();var len=buf&0x7F;if(len==buf)
return len;if(len>3)
throw"Length over 24 bits not supported at position "+(stream.pos-1);if(len==0)
return-1;buf=0;for(var i=0;i<len;++i)
buf=(buf<<8)|stream.get();return buf;}
ASN1.hasContent=function(tag,len,stream){if(tag&0x20)
return true;if((tag<0x03)||(tag>0x04))
return false;var p=new Stream(stream);if(tag==0x03)p.get();var subTag=p.get();if((subTag>>6)&0x01)
return false;try{var subLength=ASN1.decodeLength(p);return((p.pos-stream.pos)+subLength==len);}catch(exception){return false;}}
ASN1.decode=function(stream){if(!(stream instanceof Stream))
stream=new Stream(stream,0);var streamStart=new Stream(stream);var tag=stream.get();var len=ASN1.decodeLength(stream);var header=stream.pos-streamStart.pos;var sub=null;if(ASN1.hasContent(tag,len,stream)){var start=stream.pos;if(tag==0x03)stream.get();sub=[];if(len>=0){var end=start+len;while(stream.pos<end)
sub[sub.length]=ASN1.decode(stream);if(stream.pos!=end)
throw"Content size is not correct for container starting at offset "+start;}else{try{for(;;){var s=ASN1.decode(stream);if(s.tag==0)
break;sub[sub.length]=s;}
len=start-stream.pos;}catch(e){throw"Exception while decoding undefined length content: "+e;}}}else
stream.pos+=len;return new ASN1(streamStart,header,len,tag,sub);}
var b64map="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";var b64pad="=";function hex2b64(h){var i;var c;var ret="";for(i=0;i+3<=h.length;i+=3){c=parseInt(h.substring(i,i+3),16);ret+=b64map.charAt(c>>6)+b64map.charAt(c&63);}
if(i+1==h.length){c=parseInt(h.substring(i,i+1),16);ret+=b64map.charAt(c<<2);}
else if(i+2==h.length){c=parseInt(h.substring(i,i+2),16);ret+=b64map.charAt(c>>2)+b64map.charAt((c&3)<<4);}
while((ret.length&3)>0)ret+=b64pad;return ret;}
function b64tohex(s){var ret=""
var i;var k=0;var slop;for(i=0;i<s.length;++i){if(s.charAt(i)==b64pad)break;v=b64map.indexOf(s.charAt(i));if(v<0)continue;if(k==0){ret+=int2char(v>>2);slop=v&3;k=1;}
else if(k==1){ret+=int2char((slop<<2)|(v>>4));slop=v&0xf;k=2;}
else if(k==2){ret+=int2char(slop);ret+=int2char(v>>2);slop=v&3;k=3;}
else{ret+=int2char((slop<<2)|(v>>4));ret+=int2char(v&0xf);k=0;}}
if(k==1)
ret+=int2char(slop<<2);return ret;}
function b64toBA(s){var h=b64tohex(s);var i;var a=new Array();for(i=0;2*i<h.length;++i){a[i]=parseInt(h.substring(2*i,2*i+2),16);}
return a;}
var dbits;var canary=0xdeadbeefcafe;var j_lm=((canary&0xffffff)==0xefcafe);function BigInteger(a,b,c){if(a!=null)
if("number"==typeof a)this.fromNumber(a,b,c);else if(b==null&&"string"!=typeof a)this.fromString(a,256);else this.fromString(a,b);}
function nbi(){return new BigInteger(null);}
function am1(i,x,w,j,c,n){while(--n>=0){var v=x*this[i++]+w[j]+c;c=Math.floor(v/0x4000000);w[j++]=v&0x3ffffff;}
return c;}
function am2(i,x,w,j,c,n){var xl=x&0x7fff,xh=x>>15;while(--n>=0){var l=this[i]&0x7fff;var h=this[i++]>>15;var m=xh*l+h*xl;l=xl*l+((m&0x7fff)<<15)+w[j]+(c&0x3fffffff);c=(l>>>30)+(m>>>15)+xh*h+(c>>>30);w[j++]=l&0x3fffffff;}
return c;}
function am3(i,x,w,j,c,n){var xl=x&0x3fff,xh=x>>14;while(--n>=0){var l=this[i]&0x3fff;var h=this[i++]>>14;var m=xh*l+h*xl;l=xl*l+((m&0x3fff)<<14)+w[j]+c;c=(l>>28)+(m>>14)+xh*h;w[j++]=l&0xfffffff;}
return c;}
if(j_lm&&(navigator.appName=="Microsoft Internet Explorer")){BigInteger.prototype.am=am2;dbits=30;}
else if(j_lm&&(navigator.appName!="Netscape")){BigInteger.prototype.am=am1;dbits=26;}
else{BigInteger.prototype.am=am3;dbits=28;}
BigInteger.prototype.DB=dbits;BigInteger.prototype.DM=((1<<dbits)-1);BigInteger.prototype.DV=(1<<dbits);var BI_FP=52;BigInteger.prototype.FV=Math.pow(2,BI_FP);BigInteger.prototype.F1=BI_FP-dbits;BigInteger.prototype.F2=2*dbits-BI_FP;var BI_RM="0123456789abcdefghijklmnopqrstuvwxyz";var BI_RC=new Array();var rr,vv;rr="0".charCodeAt(0);for(vv=0;vv<=9;++vv)BI_RC[rr++]=vv;rr="a".charCodeAt(0);for(vv=10;vv<36;++vv)BI_RC[rr++]=vv;rr="A".charCodeAt(0);for(vv=10;vv<36;++vv)BI_RC[rr++]=vv;function int2char(n){return BI_RM.charAt(n);}
function intAt(s,i){var c=BI_RC[s.charCodeAt(i)];return(c==null)?-1:c;}
function bnpCopyTo(r){for(var i=this.t-1;i>=0;--i)r[i]=this[i];r.t=this.t;r.s=this.s;}
function bnpFromInt(x){this.t=1;this.s=(x<0)?-1:0;if(x>0)this[0]=x;else if(x<-1)this[0]=x+DV;else this.t=0;}
function nbv(i){var r=nbi();r.fromInt(i);return r;}
function bnpFromString(s,b){var k;if(b==16)k=4;else if(b==8)k=3;else if(b==256)k=8;else if(b==2)k=1;else if(b==32)k=5;else if(b==4)k=2;else{this.fromRadix(s,b);return;}
this.t=0;this.s=0;var i=s.length,mi=false,sh=0;while(--i>=0){var x=(k==8)?s[i]&0xff:intAt(s,i);if(x<0){if(s.charAt(i)=="-")mi=true;continue;}
mi=false;if(sh==0)
this[this.t++]=x;else if(sh+k>this.DB){this[this.t-1]|=(x&((1<<(this.DB-sh))-1))<<sh;this[this.t++]=(x>>(this.DB-sh));}
else
this[this.t-1]|=x<<sh;sh+=k;if(sh>=this.DB)sh-=this.DB;}
if(k==8&&(s[0]&0x80)!=0){this.s=-1;if(sh>0)this[this.t-1]|=((1<<(this.DB-sh))-1)<<sh;}
this.clamp();if(mi)BigInteger.ZERO.subTo(this,this);}
function bnpClamp(){var c=this.s&this.DM;while(this.t>0&&this[this.t-1]==c)--this.t;}
function bnToString(b){if(this.s<0)return"-"+this.negate().toString(b);var k;if(b==16)k=4;else if(b==8)k=3;else if(b==2)k=1;else if(b==32)k=5;else if(b==4)k=2;else return this.toRadix(b);var km=(1<<k)-1,d,m=false,r="",i=this.t;var p=this.DB-(i*this.DB)%k;if(i-->0){if(p<this.DB&&(d=this[i]>>p)>0){m=true;r=int2char(d);}
while(i>=0){if(p<k){d=(this[i]&((1<<p)-1))<<(k-p);d|=this[--i]>>(p+=this.DB-k);}
else{d=(this[i]>>(p-=k))&km;if(p<=0){p+=this.DB;--i;}}
if(d>0)m=true;if(m)r+=int2char(d);}}
return m?r:"0";}
function bnNegate(){var r=nbi();BigInteger.ZERO.subTo(this,r);return r;}
function bnAbs(){return(this.s<0)?this.negate():this;}
function bnCompareTo(a){var r=this.s-a.s;if(r!=0)return r;var i=this.t;r=i-a.t;if(r!=0)return(this.s<0)?-r:r;while(--i>=0)if((r=this[i]-a[i])!=0)return r;return 0;}
function nbits(x){var r=1,t;if((t=x>>>16)!=0){x=t;r+=16;}
if((t=x>>8)!=0){x=t;r+=8;}
if((t=x>>4)!=0){x=t;r+=4;}
if((t=x>>2)!=0){x=t;r+=2;}
if((t=x>>1)!=0){x=t;r+=1;}
return r;}
function bnBitLength(){if(this.t<=0)return 0;return this.DB*(this.t-1)+nbits(this[this.t-1]^(this.s&this.DM));}
function bnpDLShiftTo(n,r){var i;for(i=this.t-1;i>=0;--i)r[i+n]=this[i];for(i=n-1;i>=0;--i)r[i]=0;r.t=this.t+n;r.s=this.s;}
function bnpDRShiftTo(n,r){for(var i=n;i<this.t;++i)r[i-n]=this[i];r.t=Math.max(this.t-n,0);r.s=this.s;}
function bnpLShiftTo(n,r){var bs=n%this.DB;var cbs=this.DB-bs;var bm=(1<<cbs)-1;var ds=Math.floor(n/this.DB),c=(this.s<<bs)&this.DM,i;for(i=this.t-1;i>=0;--i){r[i+ds+1]=(this[i]>>cbs)|c;c=(this[i]&bm)<<bs;}
for(i=ds-1;i>=0;--i)r[i]=0;r[ds]=c;r.t=this.t+ds+1;r.s=this.s;r.clamp();}
function bnpRShiftTo(n,r){r.s=this.s;var ds=Math.floor(n/this.DB);if(ds>=this.t){r.t=0;return;}
var bs=n%this.DB;var cbs=this.DB-bs;var bm=(1<<bs)-1;r[0]=this[ds]>>bs;for(var i=ds+1;i<this.t;++i){r[i-ds-1]|=(this[i]&bm)<<cbs;r[i-ds]=this[i]>>bs;}
if(bs>0)r[this.t-ds-1]|=(this.s&bm)<<cbs;r.t=this.t-ds;r.clamp();}
function bnpSubTo(a,r){var i=0,c=0,m=Math.min(a.t,this.t);while(i<m){c+=this[i]-a[i];r[i++]=c&this.DM;c>>=this.DB;}
if(a.t<this.t){c-=a.s;while(i<this.t){c+=this[i];r[i++]=c&this.DM;c>>=this.DB;}
c+=this.s;}
else{c+=this.s;while(i<a.t){c-=a[i];r[i++]=c&this.DM;c>>=this.DB;}
c-=a.s;}
r.s=(c<0)?-1:0;if(c<-1)r[i++]=this.DV+c;else if(c>0)r[i++]=c;r.t=i;r.clamp();}
function bnpMultiplyTo(a,r){var x=this.abs(),y=a.abs();var i=x.t;r.t=i+y.t;while(--i>=0)r[i]=0;for(i=0;i<y.t;++i)r[i+x.t]=x.am(0,y[i],r,i,0,x.t);r.s=0;r.clamp();if(this.s!=a.s)BigInteger.ZERO.subTo(r,r);}
function bnpSquareTo(r){var x=this.abs();var i=r.t=2*x.t;while(--i>=0)r[i]=0;for(i=0;i<x.t-1;++i){var c=x.am(i,x[i],r,2*i,0,1);if((r[i+x.t]+=x.am(i+1,2*x[i],r,2*i+1,c,x.t-i-1))>=x.DV){r[i+x.t]-=x.DV;r[i+x.t+1]=1;}}
if(r.t>0)r[r.t-1]+=x.am(i,x[i],r,2*i,0,1);r.s=0;r.clamp();}
function bnpDivRemTo(m,q,r){var pm=m.abs();if(pm.t<=0)return;var pt=this.abs();if(pt.t<pm.t){if(q!=null)q.fromInt(0);if(r!=null)this.copyTo(r);return;}
if(r==null)r=nbi();var y=nbi(),ts=this.s,ms=m.s;var nsh=this.DB-nbits(pm[pm.t-1]);if(nsh>0){pm.lShiftTo(nsh,y);pt.lShiftTo(nsh,r);}
else{pm.copyTo(y);pt.copyTo(r);}
var ys=y.t;var y0=y[ys-1];if(y0==0)return;var yt=y0*(1<<this.F1)+((ys>1)?y[ys-2]>>this.F2:0);var d1=this.FV/yt,d2=(1<<this.F1)/yt,e=1<<this.F2;var i=r.t,j=i-ys,t=(q==null)?nbi():q;y.dlShiftTo(j,t);if(r.compareTo(t)>=0){r[r.t++]=1;r.subTo(t,r);}
BigInteger.ONE.dlShiftTo(ys,t);t.subTo(y,y);while(y.t<ys)y[y.t++]=0;while(--j>=0){var qd=(r[--i]==y0)?this.DM:Math.floor(r[i]*d1+(r[i-1]+e)*d2);if((r[i]+=y.am(0,qd,r,j,0,ys))<qd){y.dlShiftTo(j,t);r.subTo(t,r);while(r[i]<--qd)r.subTo(t,r);}}
if(q!=null){r.drShiftTo(ys,q);if(ts!=ms)BigInteger.ZERO.subTo(q,q);}
r.t=ys;r.clamp();if(nsh>0)r.rShiftTo(nsh,r);if(ts<0)BigInteger.ZERO.subTo(r,r);}
function bnMod(a){var r=nbi();this.abs().divRemTo(a,null,r);if(this.s<0&&r.compareTo(BigInteger.ZERO)>0)a.subTo(r,r);return r;}
function Classic(m){this.m=m;}
function cConvert(x){if(x.s<0||x.compareTo(this.m)>=0)return x.mod(this.m);else return x;}
function cRevert(x){return x;}
function cReduce(x){x.divRemTo(this.m,null,x);}
function cMulTo(x,y,r){x.multiplyTo(y,r);this.reduce(r);}
function cSqrTo(x,r){x.squareTo(r);this.reduce(r);}
Classic.prototype.convert=cConvert;Classic.prototype.revert=cRevert;Classic.prototype.reduce=cReduce;Classic.prototype.mulTo=cMulTo;Classic.prototype.sqrTo=cSqrTo;function bnpInvDigit(){if(this.t<1)return 0;var x=this[0];if((x&1)==0)return 0;var y=x&3;y=(y*(2-(x&0xf)*y))&0xf;y=(y*(2-(x&0xff)*y))&0xff;y=(y*(2-(((x&0xffff)*y)&0xffff)))&0xffff;y=(y*(2-x*y%this.DV))%this.DV;return(y>0)?this.DV-y:-y;}
function Montgomery(m){this.m=m;this.mp=m.invDigit();this.mpl=this.mp&0x7fff;this.mph=this.mp>>15;this.um=(1<<(m.DB-15))-1;this.mt2=2*m.t;}
function montConvert(x){var r=nbi();x.abs().dlShiftTo(this.m.t,r);r.divRemTo(this.m,null,r);if(x.s<0&&r.compareTo(BigInteger.ZERO)>0)this.m.subTo(r,r);return r;}
function montRevert(x){var r=nbi();x.copyTo(r);this.reduce(r);return r;}
function montReduce(x){while(x.t<=this.mt2)
x[x.t++]=0;for(var i=0;i<this.m.t;++i){var j=x[i]&0x7fff;var u0=(j*this.mpl+(((j*this.mph+(x[i]>>15)*this.mpl)&this.um)<<15))&x.DM;j=i+this.m.t;x[j]+=this.m.am(0,u0,x,i,0,this.m.t);while(x[j]>=x.DV){x[j]-=x.DV;x[++j]++;}}
x.clamp();x.drShiftTo(this.m.t,x);if(x.compareTo(this.m)>=0)x.subTo(this.m,x);}
function montSqrTo(x,r){x.squareTo(r);this.reduce(r);}
function montMulTo(x,y,r){x.multiplyTo(y,r);this.reduce(r);}
Montgomery.prototype.convert=montConvert;Montgomery.prototype.revert=montRevert;Montgomery.prototype.reduce=montReduce;Montgomery.prototype.mulTo=montMulTo;Montgomery.prototype.sqrTo=montSqrTo;function bnpIsEven(){return((this.t>0)?(this[0]&1):this.s)==0;}
function bnpExp(e,z){if(e>0xffffffff||e<1)return BigInteger.ONE;var r=nbi(),r2=nbi(),g=z.convert(this),i=nbits(e)-1;g.copyTo(r);while(--i>=0){z.sqrTo(r,r2);if((e&(1<<i))>0)z.mulTo(r2,g,r);else{var t=r;r=r2;r2=t;}}
return z.revert(r);}
function bnModPowInt(e,m){var z;if(e<256||m.isEven())z=new Classic(m);else z=new Montgomery(m);return this.exp(e,z);}
BigInteger.prototype.copyTo=bnpCopyTo;BigInteger.prototype.fromInt=bnpFromInt;BigInteger.prototype.fromString=bnpFromString;BigInteger.prototype.clamp=bnpClamp;BigInteger.prototype.dlShiftTo=bnpDLShiftTo;BigInteger.prototype.drShiftTo=bnpDRShiftTo;BigInteger.prototype.lShiftTo=bnpLShiftTo;BigInteger.prototype.rShiftTo=bnpRShiftTo;BigInteger.prototype.subTo=bnpSubTo;BigInteger.prototype.multiplyTo=bnpMultiplyTo;BigInteger.prototype.squareTo=bnpSquareTo;BigInteger.prototype.divRemTo=bnpDivRemTo;BigInteger.prototype.invDigit=bnpInvDigit;BigInteger.prototype.isEven=bnpIsEven;BigInteger.prototype.exp=bnpExp;BigInteger.prototype.toString=bnToString;BigInteger.prototype.negate=bnNegate;BigInteger.prototype.abs=bnAbs;BigInteger.prototype.compareTo=bnCompareTo;BigInteger.prototype.bitLength=bnBitLength;BigInteger.prototype.mod=bnMod;BigInteger.prototype.modPowInt=bnModPowInt;BigInteger.ZERO=nbv(0);BigInteger.ONE=nbv(1);function parseBigInt(str,r){return new BigInteger(str,r);}
function linebrk(s,n){var ret="";var i=0;while(i+n<s.length){ret+=s.substring(i,i+n)+"\n";i+=n;}
return ret+s.substring(i,s.length);}
function byte2Hex(b){if(b<0x10)
return"0"+b.toString(16);else
return b.toString(16);}
function pkcs1pad2(s,n){if(n<s.length+11){alert("Message too long for RSA");return null;}
var ba=new Array();var i=s.length-1;while(i>=0&&n>0){var c=s.charCodeAt(i--);if(c<128){ba[--n]=c;}
else if((c>127)&&(c<2048)){ba[--n]=(c&63)|128;ba[--n]=(c>>6)|192;}
else{ba[--n]=(c&63)|128;ba[--n]=((c>>6)&63)|128;ba[--n]=(c>>12)|224;}}
ba[--n]=0;var randomByte=0;var random=0;var shift=0;while(n>2){if(shift==0){random=sjcl.random.randomWords(1,0)[0];}
randomByte=(random>>shift)&0xff;shift=(shift+8)%32;if(randomByte!=0){ba[--n]=randomByte;}}
ba[--n]=2;ba[--n]=0;return new BigInteger(ba);}
function RSAKey(){this.n=null;this.e=0;this.d=null;this.p=null;this.q=null;this.dmp1=null;this.dmq1=null;this.coeff=null;}
function RSASetPublic(N,E){if(N!=null&&E!=null&&N.length>0&&E.length>0){this.n=parseBigInt(N,16);this.e=parseInt(E,16);}
else
alert("Invalid RSA public key");}
function RSADoPublic(x){return x.modPowInt(this.e,this.n);}
function RSAEncrypt(text){var m=pkcs1pad2(text,(this.n.bitLength()+7)>>3);if(m==null)return null;var c=this.doPublic(m);if(c==null)return null;var h=c.toString(16);if((h.length&1)==0)return h;else return"0"+h;}
function RSAEncryptB64(text){var h=this.encrypt(text);if(h)return hex2b64(h);else return null;}
RSAKey.prototype.doPublic=RSADoPublic;RSAKey.prototype.setPublic=RSASetPublic;RSAKey.prototype.encrypt=RSAEncrypt;RSAKey.prototype.encrypt_b64=RSAEncryptB64;"use strict";var sjcl={cipher:{},hash:{},keyexchange:{},mode:{},misc:{},codec:{},exception:{corrupt:function(message){this.toString=function(){return"CORRUPT: "+this.message;};this.message=message;},invalid:function(message){this.toString=function(){return"INVALID: "+this.message;};this.message=message;},bug:function(message){this.toString=function(){return"BUG: "+this.message;};this.message=message;},notReady:function(message){this.toString=function(){return"NOT READY: "+this.message;};this.message=message;}}};if(typeof module!='undefined'&&module.exports){module.exports=sjcl;}
sjcl.cipher.aes=function(key){if(!this._tables[0][0][0]){this._precompute();}
var i,j,tmp,encKey,decKey,sbox=this._tables[0][4],decTable=this._tables[1],keyLen=key.length,rcon=1;if(keyLen!==4&&keyLen!==6&&keyLen!==8){throw new sjcl.exception.invalid("invalid aes key size");}
this._key=[encKey=key.slice(0),decKey=[]];for(i=keyLen;i<4*keyLen+28;i++){tmp=encKey[i-1];if(i%keyLen===0||(keyLen===8&&i%keyLen===4)){tmp=sbox[tmp>>>24]<<24^sbox[tmp>>16&255]<<16^sbox[tmp>>8&255]<<8^sbox[tmp&255];if(i%keyLen===0){tmp=tmp<<8^tmp>>>24^rcon<<24;rcon=rcon<<1^(rcon>>7)*283;}}
encKey[i]=encKey[i-keyLen]^tmp;}
for(j=0;i;j++,i--){tmp=encKey[j&3?i:i-4];if(i<=4||j<4){decKey[j]=tmp;}else{decKey[j]=decTable[0][sbox[tmp>>>24]]^decTable[1][sbox[tmp>>16&255]]^decTable[2][sbox[tmp>>8&255]]^decTable[3][sbox[tmp&255]];}}};sjcl.cipher.aes.prototype={encrypt:function(data){return this._crypt(data,0);},decrypt:function(data){return this._crypt(data,1);},_tables:[[[],[],[],[],[]],[[],[],[],[],[]]],_precompute:function(){var encTable=this._tables[0],decTable=this._tables[1],sbox=encTable[4],sboxInv=decTable[4],i,x,xInv,d=[],th=[],x2,x4,x8,s,tEnc,tDec;for(i=0;i<256;i++){th[(d[i]=i<<1^(i>>7)*283)^i]=i;}
for(x=xInv=0;!sbox[x];x^=x2||1,xInv=th[xInv]||1){s=xInv^xInv<<1^xInv<<2^xInv<<3^xInv<<4;s=s>>8^s&255^99;sbox[x]=s;sboxInv[s]=x;x8=d[x4=d[x2=d[x]]];tDec=x8*0x1010101^x4*0x10001^x2*0x101^x*0x1010100;tEnc=d[s]*0x101^s*0x1010100;for(i=0;i<4;i++){encTable[i][x]=tEnc=tEnc<<24^tEnc>>>8;decTable[i][s]=tDec=tDec<<24^tDec>>>8;}}
for(i=0;i<5;i++){encTable[i]=encTable[i].slice(0);decTable[i]=decTable[i].slice(0);}},_crypt:function(input,dir){if(input.length!==4){throw new sjcl.exception.invalid("invalid aes block size");}
var key=this._key[dir],a=input[0]^key[0],b=input[dir?3:1]^key[1],c=input[2]^key[2],d=input[dir?1:3]^key[3],a2,b2,c2,nInnerRounds=key.length/4-2,i,kIndex=4,out=[0,0,0,0],table=this._tables[dir],t0=table[0],t1=table[1],t2=table[2],t3=table[3],sbox=table[4];for(i=0;i<nInnerRounds;i++){a2=t0[a>>>24]^t1[b>>16&255]^t2[c>>8&255]^t3[d&255]^key[kIndex];b2=t0[b>>>24]^t1[c>>16&255]^t2[d>>8&255]^t3[a&255]^key[kIndex+1];c2=t0[c>>>24]^t1[d>>16&255]^t2[a>>8&255]^t3[b&255]^key[kIndex+2];d=t0[d>>>24]^t1[a>>16&255]^t2[b>>8&255]^t3[c&255]^key[kIndex+3];kIndex+=4;a=a2;b=b2;c=c2;}
for(i=0;i<4;i++){out[dir?3&-i:i]=sbox[a>>>24]<<24^sbox[b>>16&255]<<16^sbox[c>>8&255]<<8^sbox[d&255]^key[kIndex++];a2=a;a=b;b=c;c=d;d=a2;}
return out;}};sjcl.bitArray={bitSlice:function(a,bstart,bend){a=sjcl.bitArray._shiftRight(a.slice(bstart/32),32-(bstart&31)).slice(1);return(bend===undefined)?a:sjcl.bitArray.clamp(a,bend-bstart);},extract:function(a,bstart,blength){var x,sh=Math.floor((-bstart-blength)&31);if((bstart+blength-1^bstart)&-32){x=(a[bstart/32|0]<<(32-sh))^(a[bstart/32+1|0]>>>sh);}else{x=a[bstart/32|0]>>>sh;}
return x&((1<<blength)-1);},concat:function(a1,a2){if(a1.length===0||a2.length===0){return a1.concat(a2);}
var out,i,last=a1[a1.length-1],shift=sjcl.bitArray.getPartial(last);if(shift===32){return a1.concat(a2);}else{return sjcl.bitArray._shiftRight(a2,shift,last|0,a1.slice(0,a1.length-1));}},bitLength:function(a){var l=a.length,x;if(l===0){return 0;}
x=a[l-1];return(l-1)*32+sjcl.bitArray.getPartial(x);},clamp:function(a,len){if(a.length*32<len){return a;}
a=a.slice(0,Math.ceil(len/32));var l=a.length;len=len&31;if(l>0&&len){a[l-1]=sjcl.bitArray.partial(len,a[l-1]&0x80000000>>(len-1),1);}
return a;},partial:function(len,x,_end){if(len===32){return x;}
return(_end?x|0:x<<(32-len))+len*0x10000000000;},getPartial:function(x){return Math.round(x/0x10000000000)||32;},equal:function(a,b){if(sjcl.bitArray.bitLength(a)!==sjcl.bitArray.bitLength(b)){return false;}
var x=0,i;for(i=0;i<a.length;i++){x|=a[i]^b[i];}
return(x===0);},_shiftRight:function(a,shift,carry,out){var i,last2=0,shift2;if(out===undefined){out=[];}
for(;shift>=32;shift-=32){out.push(carry);carry=0;}
if(shift===0){return out.concat(a);}
for(i=0;i<a.length;i++){out.push(carry|a[i]>>>shift);carry=a[i]<<(32-shift);}
last2=a.length?a[a.length-1]:0;shift2=sjcl.bitArray.getPartial(last2);out.push(sjcl.bitArray.partial(shift+shift2&31,(shift+shift2>32)?carry:out.pop(),1));return out;},_xor4:function(x,y){return[x[0]^y[0],x[1]^y[1],x[2]^y[2],x[3]^y[3]];}};sjcl.codec.hex={fromBits:function(arr){var out="",i,x;for(i=0;i<arr.length;i++){out+=((arr[i]|0)+0xF00000000000).toString(16).substr(4);}
return out.substr(0,sjcl.bitArray.bitLength(arr)/4);},toBits:function(str){var i,out=[],len;str=str.replace(/\s|0x/g,"");len=str.length;str=str+"00000000";for(i=0;i<str.length;i+=8){out.push(parseInt(str.substr(i,8),16)^0);}
return sjcl.bitArray.clamp(out,len*4);}};sjcl.codec.utf8String={fromBits:function(arr){var out="",bl=sjcl.bitArray.bitLength(arr),i,tmp;for(i=0;i<bl/8;i++){if((i&3)===0){tmp=arr[i/4];}
out+=String.fromCharCode(tmp>>>24);tmp<<=8;}
return decodeURIComponent(escape(out));},toBits:function(str){str=unescape(encodeURIComponent(str));var out=[],i,tmp=0;for(i=0;i<str.length;i++){tmp=tmp<<8|str.charCodeAt(i);if((i&3)===3){out.push(tmp);tmp=0;}}
if(i&3){out.push(sjcl.bitArray.partial(8*(i&3),tmp));}
return out;}};sjcl.codec.base64={_chars:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",fromBits:function(arr,_noEquals,_url){var out="",i,bits=0,c=sjcl.codec.base64._chars,ta=0,bl=sjcl.bitArray.bitLength(arr);if(_url)c=c.substr(0,62)+'-_';for(i=0;out.length*6<bl;){out+=c.charAt((ta^arr[i]>>>bits)>>>26);if(bits<6){ta=arr[i]<<(6-bits);bits+=26;i++;}else{ta<<=6;bits-=6;}}
while((out.length&3)&&!_noEquals){out+="=";}
return out;},toBits:function(str,_url){str=str.replace(/\s|=/g,'');var out=[],i,bits=0,c=sjcl.codec.base64._chars,ta=0,x;if(_url)c=c.substr(0,62)+'-_';for(i=0;i<str.length;i++){x=c.indexOf(str.charAt(i));if(x<0){throw new sjcl.exception.invalid("this isn't base64!");}
if(bits>26){bits-=26;out.push(ta^x>>>bits);ta=x<<(32-bits);}else{bits+=6;ta^=x<<(32-bits);}}
if(bits&56){out.push(sjcl.bitArray.partial(bits&56,ta,1));}
return out;}};sjcl.codec.base64url={fromBits:function(arr){return sjcl.codec.base64.fromBits(arr,1,1);},toBits:function(str){return sjcl.codec.base64.toBits(str,1);}};if(sjcl.beware===undefined){sjcl.beware={};}
sjcl.beware["CBC mode is dangerous because it doesn't protect message integrity."]=function(){sjcl.mode.cbc={name:"cbc",encrypt:function(prp,plaintext,iv,adata){if(adata&&adata.length){throw new sjcl.exception.invalid("cbc can't authenticate data");}
if(sjcl.bitArray.bitLength(iv)!==128){throw new sjcl.exception.invalid("cbc iv must be 128 bits");}
var i,w=sjcl.bitArray,xor=w._xor4,bl=w.bitLength(plaintext),bp=0,output=[];if(bl&7){throw new sjcl.exception.invalid("pkcs#5 padding only works for multiples of a byte");}
for(i=0;bp+128<=bl;i+=4,bp+=128){iv=prp.encrypt(xor(iv,plaintext.slice(i,i+4)));output.splice(i,0,iv[0],iv[1],iv[2],iv[3]);}
bl=(16-((bl>>3)&15))*0x1010101;iv=prp.encrypt(xor(iv,w.concat(plaintext,[bl,bl,bl,bl]).slice(i,i+4)));output.splice(i,0,iv[0],iv[1],iv[2],iv[3]);return output;},decrypt:function(prp,ciphertext,iv,adata){if(adata&&adata.length){throw new sjcl.exception.invalid("cbc can't authenticate data");}
if(sjcl.bitArray.bitLength(iv)!==128){throw new sjcl.exception.invalid("cbc iv must be 128 bits");}
if((sjcl.bitArray.bitLength(ciphertext)&127)||!ciphertext.length){throw new sjcl.exception.corrupt("cbc ciphertext must be a positive multiple of the block size");}
var i,w=sjcl.bitArray,xor=w._xor4,bi,bo,output=[];adata=adata||[];for(i=0;i<ciphertext.length;i+=4){bi=ciphertext.slice(i,i+4);bo=xor(iv,prp.decrypt(bi));output.splice(i,0,bo[0],bo[1],bo[2],bo[3]);iv=bi;}
bi=output[i-1]&255;if(bi==0||bi>16){throw new sjcl.exception.corrupt("pkcs#5 padding corrupt");}
bo=bi*0x1010101;if(!w.equal(w.bitSlice([bo,bo,bo,bo],0,bi*8),w.bitSlice(output,output.length*32-bi*8,output.length*32))){throw new sjcl.exception.corrupt("pkcs#5 padding corrupt");}
return w.bitSlice(output,0,output.length*32-bi*8);}};};sjcl.misc.hmac=function(key,Hash){this._hash=Hash=Hash||sjcl.hash.sha256;var exKey=[[],[]],i,bs=Hash.prototype.blockSize/32;this._baseHash=[new Hash(),new Hash()];if(key.length>bs){key=Hash.hash(key);}
for(i=0;i<bs;i++){exKey[0][i]=key[i]^0x36363636;exKey[1][i]=key[i]^0x5C5C5C5C;}
this._baseHash[0].update(exKey[0]);this._baseHash[1].update(exKey[1]);};sjcl.misc.hmac.prototype.encrypt=sjcl.misc.hmac.prototype.mac=function(data,encoding){var w=new(this._hash)(this._baseHash[0]).update(data,encoding).finalize();return new(this._hash)(this._baseHash[1]).update(w).finalize();};sjcl.hash.sha256=function(hash){if(!this._key[0]){this._precompute();}
if(hash){this._h=hash._h.slice(0);this._buffer=hash._buffer.slice(0);this._length=hash._length;}else{this.reset();}};sjcl.hash.sha256.hash=function(data){return(new sjcl.hash.sha256()).update(data).finalize();};sjcl.hash.sha256.prototype={blockSize:512,reset:function(){this._h=this._init.slice(0);this._buffer=[];this._length=0;return this;},update:function(data){if(typeof data==="string"){data=sjcl.codec.utf8String.toBits(data);}
var i,b=this._buffer=sjcl.bitArray.concat(this._buffer,data),ol=this._length,nl=this._length=ol+sjcl.bitArray.bitLength(data);for(i=512+ol&-512;i<=nl;i+=512){this._block(b.splice(0,16));}
return this;},finalize:function(){var i,b=this._buffer,h=this._h;b=sjcl.bitArray.concat(b,[sjcl.bitArray.partial(1,1)]);for(i=b.length+2;i&15;i++){b.push(0);}
b.push(Math.floor(this._length/0x100000000));b.push(this._length|0);while(b.length){this._block(b.splice(0,16));}
this.reset();return h;},_init:[],_key:[],_precompute:function(){var i=0,prime=2,factor;function frac(x){return(x-Math.floor(x))*0x100000000|0;}
outer:for(;i<64;prime++){for(factor=2;factor*factor<=prime;factor++){if(prime%factor===0){continue outer;}}
if(i<8){this._init[i]=frac(Math.pow(prime,1/2));}
this._key[i]=frac(Math.pow(prime,1/3));i++;}},_block:function(words){var i,tmp,a,b,w=words.slice(0),h=this._h,k=this._key,h0=h[0],h1=h[1],h2=h[2],h3=h[3],h4=h[4],h5=h[5],h6=h[6],h7=h[7];for(i=0;i<64;i++){if(i<16){tmp=w[i];}else{a=w[(i+1)&15];b=w[(i+14)&15];tmp=w[i&15]=((a>>>7^a>>>18^a>>>3^a<<25^a<<14)+
(b>>>17^b>>>19^b>>>10^b<<15^b<<13)+
w[i&15]+w[(i+9)&15])|0;}
tmp=(tmp+h7+(h4>>>6^h4>>>11^h4>>>25^h4<<26^h4<<21^h4<<7)+(h6^h4&(h5^h6))+k[i]);h7=h6;h6=h5;h5=h4;h4=h3+tmp|0;h3=h2;h2=h1;h1=h0;h0=(tmp+((h1&h2)^(h3&(h1^h2)))+(h1>>>2^h1>>>13^h1>>>22^h1<<30^h1<<19^h1<<10))|0;}
h[0]=h[0]+h0|0;h[1]=h[1]+h1|0;h[2]=h[2]+h2|0;h[3]=h[3]+h3|0;h[4]=h[4]+h4|0;h[5]=h[5]+h5|0;h[6]=h[6]+h6|0;h[7]=h[7]+h7|0;}};sjcl.random={randomWords:function(nwords,paranoia){var out=[],i,readiness=this.isReady(paranoia),g;if(readiness===this._NOT_READY){throw new sjcl.exception.notReady("generator isn't seeded");}else if(readiness&this._REQUIRES_RESEED){this._reseedFromPools(!(readiness&this._READY));}
for(i=0;i<nwords;i+=4){if((i+1)%this._MAX_WORDS_PER_BURST===0){this._gate();}
g=this._gen4words();out.push(g[0],g[1],g[2],g[3]);}
this._gate();return out.slice(0,nwords);},setDefaultParanoia:function(paranoia){this._defaultParanoia=paranoia;},addEntropy:function(data,estimatedEntropy,source){source=source||"user";var id,i,tmp,t=(new Date()).valueOf(),robin=this._robins[source],oldReady=this.isReady(),err=0;id=this._collectorIds[source];if(id===undefined){id=this._collectorIds[source]=this._collectorIdNext++;}
if(robin===undefined){robin=this._robins[source]=0;}
this._robins[source]=(this._robins[source]+1)%this._pools.length;switch(typeof(data)){case"number":if(estimatedEntropy===undefined){estimatedEntropy=1;}
this._pools[robin].update([id,this._eventId++,1,estimatedEntropy,t,1,data|0]);break;case"object":var objName=Object.prototype.toString.call(data);if(objName==="[object Uint32Array]"){tmp=[];for(i=0;i<data.length;i++){tmp.push(data[i]);}
data=tmp;}else{if(objName!=="[object Array]"){err=1;}
for(i=0;i<data.length&&!err;i++){if(typeof(data[i])!="number"){err=1;}}}
if(!err){if(estimatedEntropy===undefined){estimatedEntropy=0;for(i=0;i<data.length;i++){tmp=data[i];while(tmp>0){estimatedEntropy++;tmp=tmp>>>1;}}}
this._pools[robin].update([id,this._eventId++,2,estimatedEntropy,t,data.length].concat(data));}
break;case"string":if(estimatedEntropy===undefined){estimatedEntropy=data.length;}
this._pools[robin].update([id,this._eventId++,3,estimatedEntropy,t,data.length]);this._pools[robin].update(data);break;default:err=1;}
if(err){throw new sjcl.exception.bug("random: addEntropy only supports number, array of numbers or string");}
this._poolEntropy[robin]+=estimatedEntropy;this._poolStrength+=estimatedEntropy;if(oldReady===this._NOT_READY){if(this.isReady()!==this._NOT_READY){this._fireEvent("seeded",Math.max(this._strength,this._poolStrength));}
this._fireEvent("progress",this.getProgress());}},isReady:function(paranoia){var entropyRequired=this._PARANOIA_LEVELS[(paranoia!==undefined)?paranoia:this._defaultParanoia];if(this._strength&&this._strength>=entropyRequired){return(this._poolEntropy[0]>this._BITS_PER_RESEED&&(new Date()).valueOf()>this._nextReseed)?this._REQUIRES_RESEED|this._READY:this._READY;}else{return(this._poolStrength>=entropyRequired)?this._REQUIRES_RESEED|this._NOT_READY:this._NOT_READY;}},getProgress:function(paranoia){var entropyRequired=this._PARANOIA_LEVELS[paranoia?paranoia:this._defaultParanoia];if(this._strength>=entropyRequired){return 1.0;}else{return(this._poolStrength>entropyRequired)?1.0:this._poolStrength/entropyRequired;}},startCollectors:function(){if(this._collectorsStarted){return;}
if(window.addEventListener){window.addEventListener("load",this._loadTimeCollector,false);window.addEventListener("mousemove",this._mouseCollector,false);}else if(document.attachEvent){document.attachEvent("onload",this._loadTimeCollector);document.attachEvent("onmousemove",this._mouseCollector);}
else{throw new sjcl.exception.bug("can't attach event");}
this._collectorsStarted=true;},stopCollectors:function(){if(!this._collectorsStarted){return;}
if(window.removeEventListener){window.removeEventListener("load",this._loadTimeCollector,false);window.removeEventListener("mousemove",this._mouseCollector,false);}else if(window.detachEvent){window.detachEvent("onload",this._loadTimeCollector);window.detachEvent("onmousemove",this._mouseCollector);}
this._collectorsStarted=false;},addEventListener:function(name,callback){this._callbacks[name][this._callbackI++]=callback;},removeEventListener:function(name,cb){var i,j,cbs=this._callbacks[name],jsTemp=[];for(j in cbs){if(cbs.hasOwnProperty(j)&&cbs[j]===cb){jsTemp.push(j);}}
for(i=0;i<jsTemp.length;i++){j=jsTemp[i];delete cbs[j];}},_pools:[new sjcl.hash.sha256()],_poolEntropy:[0],_reseedCount:0,_robins:{},_eventId:0,_collectorIds:{},_collectorIdNext:0,_strength:0,_poolStrength:0,_nextReseed:0,_key:[0,0,0,0,0,0,0,0],_counter:[0,0,0,0],_cipher:undefined,_defaultParanoia:6,_collectorsStarted:false,_callbacks:{progress:{},seeded:{}},_callbackI:0,_NOT_READY:0,_READY:1,_REQUIRES_RESEED:2,_MAX_WORDS_PER_BURST:65536,_PARANOIA_LEVELS:[0,48,64,96,128,192,256,384,512,768,1024],_MILLISECONDS_PER_RESEED:30000,_BITS_PER_RESEED:80,_gen4words:function(){for(var i=0;i<4;i++){this._counter[i]=this._counter[i]+1|0;if(this._counter[i]){break;}}
return this._cipher.encrypt(this._counter);},_gate:function(){this._key=this._gen4words().concat(this._gen4words());this._cipher=new sjcl.cipher.aes(this._key);},_reseed:function(seedWords){this._key=sjcl.hash.sha256.hash(this._key.concat(seedWords));this._cipher=new sjcl.cipher.aes(this._key);for(var i=0;i<4;i++){this._counter[i]=this._counter[i]+1|0;if(this._counter[i]){break;}}},_reseedFromPools:function(full){var reseedData=[],strength=0,i;this._nextReseed=reseedData[0]=(new Date()).valueOf()+this._MILLISECONDS_PER_RESEED;for(i=0;i<16;i++){reseedData.push(Math.random()*0x100000000|0);}
for(i=0;i<this._pools.length;i++){reseedData=reseedData.concat(this._pools[i].finalize());strength+=this._poolEntropy[i];this._poolEntropy[i]=0;if(!full&&(this._reseedCount&(1<<i))){break;}}
if(this._reseedCount>=1<<this._pools.length){this._pools.push(new sjcl.hash.sha256());this._poolEntropy.push(0);}
this._poolStrength-=strength;if(strength>this._strength){this._strength=strength;}
this._reseedCount++;this._reseed(reseedData);},_mouseCollector:function(ev){var x=ev.x||ev.clientX||ev.offsetX||0,y=ev.y||ev.clientY||ev.offsetY||0;sjcl.random.addEntropy([x,y],2,"mouse");},_loadTimeCollector:function(ev){sjcl.random.addEntropy((new Date()).valueOf(),2,"loadtime");},_fireEvent:function(name,arg){var j,cbs=sjcl.random._callbacks[name],cbsTemp=[];for(j in cbs){if(cbs.hasOwnProperty(j)){cbsTemp.push(cbs[j]);}}
for(j=0;j<cbsTemp.length;j++){cbsTemp[j](arg);}}};(function(){try{var ab=new Uint32Array(32);crypto.getRandomValues(ab);sjcl.random.addEntropy(ab,1024,"crypto.getRandomValues");}catch(e){}})();(function(){for(var key in sjcl.beware){if(sjcl.beware.hasOwnProperty(key)){sjcl.beware[key]();}}})();var Braintree={sjcl:sjcl,version:"1.3.5"};Braintree.generateAesKey=function(){return{key:sjcl.random.randomWords(8,0),encrypt:function(plainText){return this.encryptWithIv(plainText,sjcl.random.randomWords(4,0));},encryptWithIv:function(plaintext,iv){var aes=new sjcl.cipher.aes(this.key),plaintextBits=sjcl.codec.utf8String.toBits(plaintext),ciphertextBits=sjcl.mode.cbc.encrypt(aes,plaintextBits,iv),ciphertextAndIvBits=sjcl.bitArray.concat(iv,ciphertextBits);return sjcl.codec.base64.fromBits(ciphertextAndIvBits);}};};Braintree.create=function(publicKey){return new Braintree.EncryptionClient(publicKey);};Braintree.EncryptionClient=function(publicKey){var self=this,hiddenFields=[];self.publicKey=publicKey;self.version=Braintree.version;var createElement=function(tagName,attrs){var element,attr,value;element=document.createElement(tagName);for(attr in attrs){if(attrs.hasOwnProperty(attr)){value=attrs[attr];element.setAttribute(attr,value);}}
return element;};var extractForm=function(object){if(window.jQuery&&object instanceof jQuery){return object[0];}else if(object.nodeType&&object.nodeType===1){return object;}else{return document.getElementById(object);}};var extractIntegers=function(asn1){var parts=[],start,end,data,i;if(asn1.typeName()==="INTEGER"){start=asn1.posContent();end=asn1.posEnd();data=asn1.stream.hexDump(start,end).replace(/[ \n]/g,"");parts.push(data);}
if(asn1.sub!==null){for(i=0;i<asn1.sub.length;i++){parts=parts.concat(extractIntegers(asn1.sub[i]));}}
return parts;};var findInputs=function(element){var found=[],children=element.children,child,i;for(i=0;i<children.length;i++){child=children[i];if(child.nodeType===1&&child.attributes["data-encrypted-name"]){found.push(child);}else if(child.children.length>0){found=found.concat(findInputs(child));}}
return found;};var generateRsaKey=function(){var rawKey,asn1,parts,modulus,exponent;try{rawKey=b64toBA(publicKey);asn1=ASN1.decode(rawKey);}catch(e){throw"Invalid encryption key. Please use the key labeled 'Client-Side Encryption Key'";}
parts=extractIntegers(asn1);if(parts.length!==2){throw"Invalid encryption key. Please use the key labeled 'Client-Side Encryption Key'";}
modulus=parts[0];exponent=parts[1];rsa=new RSAKey();rsa.setPublic(modulus,exponent);return rsa;};var generateHmacKey=function(){return{key:sjcl.random.randomWords(8,0),sign:function(message){var hmac=new sjcl.misc.hmac(this.key,sjcl.hash.sha256),signature=hmac.encrypt(message);return sjcl.codec.base64.fromBits(signature);}};};self.encrypt=function(plaintext){var rsa=generateRsaKey();aes=Braintree.generateAesKey(),hmac=generateHmacKey(),ciphertext=aes.encrypt(plaintext),signature=hmac.sign(sjcl.codec.base64.toBits(ciphertext)),combinedKey=sjcl.bitArray.concat(aes.key,hmac.key),encodedKey=sjcl.codec.base64.fromBits(combinedKey),encryptedKey=rsa.encrypt_b64(encodedKey),prefix="$bt4|javascript_"+self.version.replace(/\./g,"_")+"$";return prefix+encryptedKey+"$"+ciphertext+"$"+signature;};self.encryptForm=function(form){var element,encryptedValue,fieldName,hiddenField,i,inputs,ownerDocument;form=extractForm(form);inputs=findInputs(form);while(hiddenFields.length>0){ownerDocument=hiddenFields[0].ownerDocument;if(ownerDocument.contains&&ownerDocument.contains(hiddenFields[0])){form.removeChild(hiddenFields[0]);}
hiddenFields.splice(0,1);}
for(i=0;i<inputs.length;i++){element=inputs[i];fieldName=element.getAttribute("data-encrypted-name");encryptedValue=self.encrypt(element.value);element.removeAttribute("name");hiddenField=createElement("input",{value:encryptedValue,type:"hidden",name:fieldName});hiddenFields.push(hiddenField);form.appendChild(hiddenField);}};self.onSubmitEncryptForm=function(form,callback){var wrappedCallback;form=extractForm(form);wrappedCallback=function(e){self.encryptForm(form);return(!!callback)?callback(e):e;};if(window.jQuery){window.jQuery(form).submit(wrappedCallback);}else if(form.addEventListener){form.addEventListener("submit",wrappedCallback,false);}else if(form.attachEvent){form.attachEvent("onsubmit",wrappedCallback);}};self.formEncrypter={encryptForm:self.encryptForm,extractForm:extractForm,onSubmitEncryptForm:self.onSubmitEncryptForm};sjcl.random.startCollectors();};window.Braintree=Braintree;if(typeof define==="function"){define("braintree",function(){return Braintree;});}})();