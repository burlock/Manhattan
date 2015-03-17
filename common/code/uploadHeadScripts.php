	<script>
		//Dynamic and extra fields for Languages 
		function addExtraLang(id){
			var tabla = document.getElementById(id); 
			var tbody = document.getElementById(tabla.id).tBodies[0]; 
			var row = tbody.rows[0].cloneNode(true); 
			var id = 1; 
			while(document.getElementById(tabla.id+'_fila_'+id)){
				id++;
			}
			if (id<=30){
				row.id = tabla.id+'_fila_'+id;
				row.style.display = '';
				tbody.appendChild(row);
			}
		}
		
		
		function delExtraLang(fila){
		var id = fila.id;
		if(fila.parentNode.rows.length <= 2 ) return;
		document.getElementById(id).parentNode.removeChild(document.getElementById(id));
		}
		

		//Dynamic and extra fields for Educations 
		function addExtraEduc(id){
			var tabla = document.getElementById(id); 
			var tbody = document.getElementById(tabla.id).tBodies[0]; 
			var row = tbody.rows[0].cloneNode(true); 
			var id = 1; 
			while(document.getElementById(tabla.id+'_fila_'+id)){
				id++;
			}
			if (id<=30){
				row.id = tabla.id+'_fila_'+id;
				row.style.display = '';
				tbody.appendChild(row);
			}
		}
		
		
		function delExtraEduc(fila){
		var id = fila.id;
		if(fila.parentNode.rows.length <= 2 ) return;
		document.getElementById(id).parentNode.removeChild(document.getElementById(id));
		}
		

		//Dynamic and extra fields for Careers 
		function addExtraCareer(id){
			var tabla = document.getElementById(id); 
			var tbody = document.getElementById(tabla.id).tBodies[0]; 
			var row = tbody.rows[0].cloneNode(true); 
			var id = 1; 
			while(document.getElementById(tabla.id+'_fila_'+id)){
				id++;
			}
			if (id<=30){
				row.id = tabla.id+'_fila_'+id;
				row.style.display = '';
				tbody.appendChild(row);
			}
		}
		
		
		function delExtraCareer(fila){
		var id = fila.id;
		if(fila.parentNode.rows.length <= 2 ) return;
		document.getElementById(id).parentNode.removeChild(document.getElementById(id));
		}
		
		
		
		//Function to realtime check characters written in Salary field 
		function checkOnlyNumbers(e){
			tecla = e.which || e.keyCode;
			patron = /\d/; // Solo acepta números
			te = String.fromCharCode(tecla);
			return (patron.test(te) || tecla == 9 || tecla == 8);
		}

		
		//Function used to check in realtime a phone number in which there could be included dashes (guiones) 
		function checkDashedNumbers(e){
			tecla = e.which || e.keyCode;
			//patron = /\d\\-/; // Solo acepta números
			patron = /[0-9\\-]/;
			te = String.fromCharCode(tecla);
			return (patron.test(te) || tecla == 9 || tecla == 8);
		}

		
		//Function to check in realtime photo's extensions 
		function checkJSPhotoExtension(fileId){
			var fileItself = document.getElementById(fileId).value;
			
			var fileArray = fileItself.split(".");
			var fileExt = (fileArray[fileArray.length-1]);
			var acceptedExts = /(jpg|png|jpeg)$/i.test(fileExt);
			if(!acceptedExts){
				var cleared = document.getElementById(fileId).value = "";
				//Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano
				alert ("\'"+fileExt+"\' no es una extensión válida para su fotografía.");
				return false;
			}
		}

		
		//Function to check in realtime doc's extensions 
		function checkJSDocsExtension(fileId){
			var fileItself = document.getElementById(fileId).value;
			
			var fileArray = fileItself.split(".");
			var fileExt = (fileArray[fileArray.length-1]);
			var acceptedExts = /(pdf|doc|docx|xls|xlsx|csv|txt|rtf)$/i.test(fileExt);
			if(!acceptedExts){
				var cleared = document.getElementById(fileId).value = "";
				//Se pone en castellano porque según un correo quieren todos los mensajes emergentes en castellano
				alert ("\'"+fileExt+"\' no es una extensión válida para su documento.");
				return false;
			}
		}
	</script>