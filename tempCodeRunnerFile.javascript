var arr = ['aa','fdsfs','cbxfd','bb','saf','vcx'];
var divId = document.getElementById("divId");
var str='';
for(var i=0;i<arr.length;i++){
	str+=arr[i];
	if(arr[i+1]=="aa:" || arr[i+1]=="bb:"){
		str+='</br>';
	}
}
print(str)