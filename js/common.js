function HTMLEncode(str){
	var i = str.length,
	aRet = [];
	while (i--) {
    var iC = str[i].charCodeAt();
    if (iC < 65 || iC > 127 || (iC>90 && iC<97)) {
      aRet[i] = '&#'+iC+';';
    } else {
      aRet[i] = str[i];
    }
   }
  return aRet.join('');    
}


function price_format(value,row,index){

	var price = parseFloat(value).toFixed(2);
	
	if(isNaN(price))
		price=0;
		
	return "$ "+price;
	
}

