function MIOLO_Currency(field)
{
	var value = field.value;
	var v = '';
    for ( var i=0; i < value.length; i++ )
    {
        var c = value.charAt(i);
        if ( (c >= '0') && (c <= '9') )
        {
			v += c; 
        }
    }
	var l = v.length;
	if (l == 0)
	{
		return true;
	}
	if (l < 3)
	{
		alert('D�gitos insuficientes para valor monet�rio!');
    	field.focus();
		return true;
	}
	v = v.slice(0,l-2) + ',' + v.slice(l-2,l);
	v = addCurrency(v); 
	field.value = v;
}

function removeCurrency( strValue ) {
  var objRegExp = /\(/;
  var strMinus = '';

  //check if negative
  if(objRegExp.test(strValue)){
    strMinus = '-';
  }
  objRegExp = /\)|\(|[\.]/g;
  strValue = strValue.replace(objRegExp,'');
  if(strValue.indexOf('$') >= 0){
    strValue = strValue.substring(1, strValue.length);
  }
  return strMinus + strValue;
}

function addCurrency( strValue ) {
  var objRegExp = /-?[0-9]+\,[0-9]{2}$/;

  if( objRegExp.test(strValue)) {
    objRegExp.compile('^-');
    strValue = addDecimalPoints(strValue);
    if (objRegExp.test(strValue)){
      strValue = '(' + strValue.replace(objRegExp,'') + ')';
    }
  }
  return strValue;
}