
/*************************************************************************************************************************************
 * ********************************************************************************************************************************* *
 * ********************************************************************************************************************************* *
 * **********************************                                                             ********************************** *
 * **********************************         GROUP OF FUNCTIONS DEVELOPED IN JAVASCRIPT          ********************************** *
 * **********************************                                                             ********************************** *
 * ********************************************************************************************************************************* *
 * ********************************************************************************************************************************* *
 *************************************************************************************************************************************/



/* Used to check in realtime a phone number in which there could be included dashes (guiones)
 * Called from "pendingCVs.php" (and also in "upload.php", although in this last file is inherently written)
 */ 
function checkDashedNumbers(e){
	var tecla = e.which || e.keyCode;
	var patron = /[0-9\\-]/;
	var te = String.fromCharCode(tecla);
	return (patron.test(te) || tecla == 9 || tecla == 8);
}



/* Checks whether a file has a proper extension to upload a massive amount of data
 * Called from onchange in "admGenOptions.php"
 */
function checkMassFileExtension(fileId){
	var fileItself = document.getElementById(fileId).value;
	
	var fileArray = fileItself.split(".");
	var fileExt = (fileArray[fileArray.length-1]);
	var acceptedExts = /(csv|txt)$/i.test(fileExt);
	if(!acceptedExts){
		var cleared = document.getElementById(fileId).value = "";
		alert ("\'"+fileExt+"\' no es una extensión válida para subir datos masivos.");
		return false;
	}
}




/* Used to ensure that only numbers are written in a field
 * Called from "pendingCVs.php" (also in "upload.php", but in this php is inherently written)
 */
function checkOnlyNumbers(e){
	var tecla = e.which || e.keyCode;
	var patron = /\d/; // Solo acepta números
	var te = String.fromCharCode(tecla);
	return (patron.test(te) || tecla == 9 || tecla == 8);
}



/* Captures 2 strings wished to match themselves to be a new password
 * Called from onsubmit in "validatefront.php"
 */
function checkPasswordES() {
	var formElements = document.getElementById("form");
	var passwd1 = formElements[0];
	var passwd2 = formElements[1];

	if(passwd1.value != passwd2.value) {
		alert("Ambas contraseñas deben coincidir");
		return false;
	}
	if(passwd1.strlen < 6) {
		alert("La clave debe tener al menos 6 caracteres");
		return false;
	}
	if(passwd1.strlen < 6) {
		alert("La clave no puede tener más de 16 caracteres");
		return false;
	}
	if(!passwd1.match(/[a-z]/)) {
		alert("La clave debe tener al menos una letra minúscula");
		return false;
	}
	if(!passwd1.match(/[A-Z]/)) {
		alert("La clave debe tener al menos una letra mayúscula");
		return false;
	}
	if(!passwd1.match(/[0-9]/)) {
		alert("La clave debe tener al menos un carácter numérico");
		return false;
	}
	else{
		alert("Contraseña actualizada con éxito");
		formElements.submit();
		return true;
	}
}



/* Checks whether an input string corresponds to a VALID date in format YYYY-MM-DD
 * PRE: yankieDate is NOT empty (must be checked in the function that calls this one)
 * Entry (yankieDate): Input string where must be a date in format YYYY-MM-DD
 * Called from "upload.php"
 */
function checkYankieDate(yankieDate){
	var pattern = new RegExp('((19|20)[0-9]{2})-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])');
	
	if(yankieDate.length > 0){
		if(pattern.test(yankieDate)){
			//Input string matches pattern
			return true;
		}
		else{
			return false;
		}
	}
}




/* Captures 2 passwords sent from a form and tells if they both are equal each other
 * Called from onsubmit in "personalData.php"
 * If wished, it can be controlled here if form is also blanked, under limited characters or over-limited characters and more...
 */
function equalPassword() {
	var formElements = document.getElementById("form");	
	var passwd1 = formElements[0];
	var passwd2 = formElements[1];

	if (passwd1.value==passwd2.value) {
		alert("Contraseña actualizada con éxito");
		formElements.submit();
		return true;
	} else {
		alert("Ambas contraseñas deben coincidir");
		return false;
	}
}



/* Calculates a future date adding X years to the input date, given in format YYYY-MM-DD
 * PRE: givenDate is NOT empty (must be checked in the function that calls this one)
 * Entry (givenDate): Input given date in format 'YYYY-MM-DD'
 * Entry (years): Integer which indicates the number of years to be added
 * Exit (resultDate): String that represents Date in format "YYYY-MM-DD"
 * Called from internal "jsIsAdult" JS function
 */
function jsAddYearsToDate(givenDate, numYears){
	if(checkYankieDate(givenDate)){
		var oldYear = givenDate.substring(0,4);
		var resultYear = parseInt(oldYear)+parseInt(numYears);
		return givenDate.replace(oldYear, resultYear);
	}
	else{
		return false;
	}
}




/* Checks whether a DNI or NIE is valid or not
 * Called from "upload.php"
 */
function jsCheckDNI_NIE(nie){
	var inputNie = document.getElementById(nie).value;
	
	if(inputNie == ""){
		alert('Error: DNI/NIE cannot be empty.');
		document.formu.blanknie.select();
		document.formu.blanknie.focus();
	}
	else{
		dniRegExp = /^\d{8}[A-Z]$/;
		nieRegExp = /^[XYZ]\d{7}[A-Z]$/;

		if(dniRegExp.test(inputNie) == true){
			//DNI case. Extracting letter
			noLetterDNI = inputNie.substr(0, inputNie.length-1);
			dniLetter = inputNie.substr(inputNie.length-1,1);
			noLetterDNI = noLetterDNI % 23;
			letterValue = 'TRWAGMYFPDXBNJZSQVHLCKET';
			letterValue = letterValue.substring(noLetterDNI,noLetterDNI+1);
			if(letterValue != dniLetter){
				alert('Error: Check your DNI. Letter does not match the number.')
				document.formu.blanknie.select();
				document.formu.blanknie.focus();
			}
			else{
				//Correct DNI. Everything's OK. Don't need to return anything
			}
		}
		else{
			if(nieRegExp.test(inputNie) == true){
				//NIE case. Replacing first letter by proper number
				controlLetter = 'TRWAGMYFPDXBNJZSQVHLCKE';
				dniAux = inputNie;
				dniAux = dniAux.replace('X', '0');
				dniAux = dniAux.replace('Y', '1');
				dniAux = dniAux.replace('Z', '2');
				controlLetterPos = dniAux.substr(0, dniAux.length-1) % 23;
				if(inputNie.charAt(8) == controlLetter.charAt(controlLetterPos)){
					//Correct NIE. Everything's OK. Don't need to return anything
					alert('NIE debuti');
				}
				else{
					alert('Error: Check your NIE. Letter does not match the number.')
					document.formu.blanknie.select();
					document.formu.blanknie.focus();
				}
			}
			else{
				//Neither DNI nor NIE. Wrongly written
				alert('Error: Check your DNI/NIE. Wrong format.')
				document.formu.blanknie.select();
				document.formu.blanknie.focus();
			}
		}
	}
}





/* Compares an input given date with a current date
 * Entry (prevDate): String that corresponds to Date in format YYYY-MM-DD
 * Exit: Boolean that returns '1' if curDate is newer/older. '0' if not
 */
function jsCompareWithCurDate(prevDate){
	var curDate = new Date();
	var year = prevDate.substring(0,4);
	var month = prevDate.substring(5,7)-1;
	var day = prevDate.substring(8,10);
	var isAdultDate = new Date(year, month, day);
	var remaining = curDate - isAdultDate;
	
	if(remaining > 0){
		//adult
		return true;
	}
	else{
		return false;
	}
}



/* Checks whether a person is adult or not, according input date. It doesn't matter date format
 * Entry (birthDate): Input date that represents birthdate
 * Entry (legalAge): Integer used to know the minimum legal age
 * Exit (): Bool
 */
function jsIsAdult(birthDate, legalAge){
	var inDate = document.getElementById(birthDate).value;
	
	if(inDate == ""){
		alert('Error: Birthdate is empty.');
		document.formu.blankbirthdate.select();
		document.formu.blankbirthdate.focus();
	}
	else{
		if(checkYankieDate(inDate)){
			if(resultDate = jsAddYearsToDate(inDate, legalAge)){
				if(jsCompareWithCurDate(resultDate)){
					//everything's OK. User is over legal age. Don't need to return anything
				}
				else{
					alert('Error: Your birthdate indicates you are not an adult.');
					document.formu.blankbirthdate.select();
					document.formu.blankbirthdate.focus();
				}
			}
			else{
				alert('Error: Check your birthdate. Wrong format.');
				document.formu.blankbirthdate.select();
				document.formu.blankbirthdate.focus();
			}
		}
		else{
			alert('Error: Check your birthdate. Wrong format.');
			document.formu.blankbirthdate.select();
			document.formu.blankbirthdate.focus();
		}
	}
}




/* Checks whether a given input date is well-formatted and is if it is also older than current date
 * Entry (prevDate): Date in format YYYY-MM-DD
 * Exit: Boolean that confirms if date is correct and older than current or not
 */
/*
function jsIsPreviousDate(prevDate){
	//alert(prevDate);
	var pDate = document.getElementById(prevDate).value;
	alert(pDate);
	//Calls to jsCompareWithCurDate function
}
*/
/*
var busy = 0;
function jsIsPreviousDate(prevDate){
	if(busy) return;
	busy = 1;
	
	var pDate = document.getElementById(prevDate).value;
	
	//if(pDate.value == ""){
	if(pDate.value == null){
		//do nothing
		busy = 0;
	}
	else{
		if(checkYankieDate(pDate) && jsCompareWithCurDate(pDate)){
			//everything's OK. Don't need to return anything
			busy = 0;
		}
		else{
			alert('Error: Selected date must be a past date.');
			document.getElementById(prevDate).focus;
			document.getElementById(prevDate).select;
			setTimeout('busy = 0', 1);
		}
	}
}
*/
function jsIsPreviousDate(prevDate){
	var pDate = document.getElementById(prevDate).value;
	
	if(pDate != ""){
		if(checkYankieDate(pDate)){
			if(jsCompareWithCurDate(pDate)){
				//everything's OK. User is over legal age. Don't need to return anything
			}
			else{
				alert('Error: Selected date must be a past date.');
				document.formu.blankdrivingdate.select();
				document.formu.blankdrivingdate.focus();
			}
		}
		else{
			alert('Error: Wrong date format.');
			document.formu.blankdrivingdate.select();
			document.formu.blankdrivingdate.focus();
		}
	}
}





/* Subtract (Restar) an existing date to current date, returning the difference in days
 * Called from onsubmit in "validatefront.php"
 */
function subToCurrentDateYMD() {
	var dat = Date();
	var curDate = Date(dat.getFullYear() + "-" + (dat.getMonth()+1) + "+" + dat.getDate());
	var d = new Date(); //establecemos la fecha de hoy
	
	//solo requerimos el año, mes, día
	//d.getFullYear() extrae el año
	//(d.getMonth() + 1 ) extrae el mes
	//d.getDate() extrae el día.

	//Establecemos la fecha inicio con los parametros anteriores
	var fechaInicio= new Date(d.getFullYear()+ "/" + (d.getMonth() + 1 ) + "/" + d.getDate());

	//Establecemos la fecha final
	var fechaFinal= new Date("2013/07/30");            

	//Restamos la fechaFinal menos fechaInicio, 
	//esto establece la diferencia entre las fechas
	var fechaResta= fechaFinal-fechaInicio;

	//Transformamos el tiempo de diferencia en días.
	fechaResta=(((fechaResta/1000)/60)/60)/24;            

	//imprimir
	document.write("Faltan: " + fechaResta + " días.");

}



// ESTA LA DEJO DE EJEMPLO PARA VER SI DESDE ELLA PUEDO SACAR LA FUNCION ANTERIOR
function DiferenciaFechas (formulario) {  

	//Obtiene los datos del formulario  
	CadenaFecha1 = formulario.fecha1.value; 
	CadenaFecha2 = formulario.fecha2.value;  

	//Obtiene dia, mes y año  
	var fecha1 = new fecha( CadenaFecha1 );     
	var fecha2 = new fecha( CadenaFecha2 ); 

	//Obtiene objetos Date  
	var miFecha1 = new Date( fecha1.anio, fecha1.mes, fecha1.dia );
	var miFecha2 = new Date( fecha2.anio, fecha2.mes, fecha2.dia ); 

	//Resta fechas y redondea  
	var diferencia = miFecha1.getTime() - miFecha2.getTime();
	//var dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
	var segundos = Math.floor(diferencia / 1000);
	alert ('La diferencia es de ' + dias + ' dias,\no ' + segundos + ' segundos.');

	return false;
}  



function fecha( cadena ) {  

	//Separador para la introduccion de las fechas  
	var separador = "⁄"  

	//Separa por dia, mes y año  
	if ( cadena.indexOf( separador ) != -1 ) {  
		var posi1 = 0  
		var posi2 = cadena.indexOf( separador, posi1 + 1 )  
		var posi3 = cadena.indexOf( separador, posi2 + 1 )  
		this.dia = cadena.substring( posi1, posi2 )  
		this.mes = cadena.substring( posi2 + 1, posi3 )  
		this.anio = cadena.substring( posi3 + 1, cadena.length )  
	} else {  
		this.dia = 0  
		this.mes = 0  
		this.anio = 0     
	}  
} 





/************************************************************************************************************************************
 * ***************   GROUP OF FUNCTIONS USED TO CONFIRM ANY TYPE OF ACTION (activations, creations, deletions...)   *************** *
 ************************************************************************************************************************************/



/* Double-checks deletion of an existing career
 * Called in "admGenOptions.php"
 */
function confirmCareerDeletionES(id) {
	return confirm('¿Confirma que desea borrar esta Profesión?');
}



/* Double-checks deletion of an existing PENDING CV
 * Called in "pendingCVs.php"
 */
function confirmPendingCVDeletion(id) {
	return confirm('Are you sure you want to delete this CV and its assigned user?');
}



/* Double-checks deletion of an existing CHECKED CV (Only available for SuperAdmin profile)
 * Called in "checkedCVs.php"
 */
function confirmCheckedCVDeletion(id) {
	return confirm('Are you sure you want to delete this CV and its assigned user?');
}



/* Double-checks sending of Candidate's CV submit
 * Called from "upload.php"
 */
function confirmFormSendES(){
	/*
	if(confirm('¿Ha confirmado que todos sus datos están correctamente introducidos?')){
		document.formu.submit();
	}
	*/
	return confirm('¿Confirma que ha revisado todos sus datos y que desea enviar el formulario?');
}



/* Double-checks deletion of an existing language
 * Called in "admGenOptions.php"
 */
function confirmLangDeletionES(id) {
	return confirm('¿Confirma que desea borrar este idioma?');
}



/* Double-checks deletion of an existing User
 * Called from "onclick" method in "admCurUsers.php"
 * Entry (id): Number/indentifier of user to be deleted if confirmed
 */
function confirmUserDeletionES(id) {
	return confirm('Si se trata de un Candidato también se borrará su CV. ¿Esta seguro?');
}





/************************************************************************************************************
 * **********   GROUP OF FUNCTIONS USED TO MAKE APPEAR/DISAPPEAR NEW FIELDS IN "blankform.php"   ********** *
 ************************************************************************************************************/



var mailcount = 0;

function cerrar(obj) {
	email=document.getElementById("blankdynamiclang"); 
	email.parentNode.removeChild(email.parentNode.childNodes[mailcount+7]);
	mailcount --;
	if (mailcount==0) {
		//retirar el código para borrar la última dirección de mail 
		document.getElementById("addingField").removeChild(document.getElementById("cerrarMail"));
	}
}

function newEntry(inputName,text) {
	newInput = document.createElement("input");
	newInput.type="text";
	newInput.name=inputName;
	newNode = document.createElement("tr");
	newNode.appendChild(document.createElement("td"));
	newNode.appendChild(document.createElement("td"));
	newNode.firstChild.appendChild(document.createTextNode(text));
	newNode.lastChild.appendChild(newInput);

	return newNode;
}

function newLanguage() {
	mailcount ++;
	email=document.getElementById("blankdynamiclang");
	
	//Creo el nuevo campo
	newNode=newEntry("email"+mailcount,"Email alternativo "+mailcount+":");
	//newNode=newNode1+"Email";
	//Muestro el nuevo campo
	email.parentNode.insertBefore(newNode,email);

	//Agregar el código para borrar el último mail
	if (mailcount==1) {
		newClose = document.createElement("a");
		newClose.id="cerrarMail";
		newClose.href="javascript:cerrar(this)";
		newClose.appendChild(document.createTextNode("Borrar último"));
		document.getElementById("addingField").appendChild(newClose);
	}
}








/*******************************************************************************************************************************
 * *************************************************************************************************************************** *
 * *************************************************************************************************************************** *
 * **********************************                                                       ********************************** *
 * **********************************         GROUP OF FUNCTIONS DEVELOPED IN AJAX          ********************************** *
 * **********************************                                                       ********************************** *
 * *************************************************************************************************************************** *
 * *************************************************************************************************************************** *
 *******************************************************************************************************************************/



/*************************************************************************************************************************
 * ****************   GROUP OF FUNCTIONS USED TO CONTROL ADDRESS DEPENDANT BLOCK OF TEXTBOXES/SELECTS   **************** *
 *************************************************************************************************************************/

/* Para las 4 funciones siguientes, la variable de tipo XMLHttpRequest debe ser global para todas ellas.
 * Si la creamos de manera independiente dentro de cada función los SELECT dependientes no funcionarán
 */
function ajaxDelLanguage(str){
	if(str==""){
		document.getElementById("txtHint2").innerHTML="";
		return;
	}
	if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function(){
		if(xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById("txtHint2").innerHTML=xmlhttp.responseText;
		}
	}

	//xmlhttp.open("GET","getcd.php?q="+str,true);
	xmlhttp.open("GET","getLanguageS.php?valuedel="+str,true);
	xmlhttp.send();
}




function ajaxGetAddress(str){
	if(str==""){
		document.getElementById("txtHint").innerHTML="";
		return;
	}
	if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function(){
		if(xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
		}
	}
	//xmlhttp.open("GET","getcd.php?q="+str,true);
	xmlhttp.open("GET","getPostalData.php?value="+str,true);
	xmlhttp.send();
}



function ajaxGetLanguage(str){
	if(str==""){
		document.getElementById("txtHint2").innerHTML="";
		return;
	}
	if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function(){
		if(xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById("txtHint2").innerHTML=xmlhttp.responseText;
		}
	}

	//xmlhttp.open("GET","getcd.php?q="+str,true);
	xmlhttp.open("GET","getLanguageS.php?value="+str,true);
	xmlhttp.send();
}





function ajaxDelLanguage(str){
	if(str==""){
		document.getElementById("txtHint2").innerHTML="";
		return;
	}
	if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function(){
		if(xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById("txtHint2").innerHTML=xmlhttp.responseText;
		}
	}

	//xmlhttp.open("GET","getcd.php?q="+str,true);
	xmlhttp.open("GET","getLanguageS.php?valuedel="+str,true);
	xmlhttp.send();
}