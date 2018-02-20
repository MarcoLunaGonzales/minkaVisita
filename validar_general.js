function validar(f){
	var i;
	var	tamano_f;
	var aux;
	tamano_f=f.length;
	for(i=0;i<=tamano_f;i++)
	{
		if((f.elements[i].type)=="text")
		{	
			if(f.elements[i].value=="")
			{	alert("El campo "+f.elements[i].name+" esta vacio.");
				f.elements[i].focus();
				return(false);
			}
		}
	}
	return(true);
}
