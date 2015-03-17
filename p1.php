<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<!-- http://www.lawebdelprogramador.com/foros/AJAX/1043682-Agregar-campos-a-un-form.html -->
<script> 
function addExtraLang(id){
	var tabla = document.getElementById(id); 
	var tbody = document.getElementById(tabla.id).tBodies[0]; 
	var row = tbody.rows[0].cloneNode(true); 
	var id = 1; 
	while(document.getElementById(tabla.id+'_fila_'+id)){
		id++;
	}
	if (id<=7){
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


</script> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title>Documento sin título</title> 
</head> 

<body> 
	<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1"> 
		<table id="tabla_1" align="center">
			<!-- 
			<thead> 
				<tr valign="baseline"> 
					<td nowrap="nowrap" align="center">Numfactura:</td> 
					<td align="center">Descripcion:</td> 
					<td align="center">Cantidad:</td> 
					<td> </td> 
				</tr> 
			</thead>
			-->
			
			<tbody> 
				<tr id="clonable" style="display:none"> 
					<td nowrap="nowrap" align="right"><input id='numfactu' name='numfactura' type="text" value="" size="32" /></td> 
					<td>
					
					<label id="uploadFormLabel" class="control-label col-sm-2" for="blankLangLevel">Level/s: </label>
								<div class="col-sm-3">
									<select class="form-control" name="blankLangLevel">
										<option selected value="">Choose level...</option>
										<option value="A1">A1</option>
										<option value="A2">A2</option>
										<option value="B1">B1</option>
										<option value="B2">B2</option>
										<option value="C1">C1</option>
										<option value="C2">C2</option>
										<option value="mothertongue">Mother tongue</option>
									</select>
								</div>
					</td>
					<td><a href="#" onClick="delExtraLang(this.parentNode.parentNode)">Eliminar</a></td> 
				</tr> 
				<tr id="tabla_1_fila_1" > 
					<td nowrap="nowrap">
					
					<label id="uploadFormLabel" class="control-label col-sm-2" for="blankLanguage">Language: </label>
					<input name="numfactura" type="text" id="numfactura" value="" size="32" placeholder="nada" />
					
					
					</td>
					
					<td>
					
					<label id="uploadFormLabel" class="control-label col-sm-2" for="blankLangLevel">Level/s: </label>
								<div class="col-sm-3">
									<select class="form-control" name="blankLangLevel">
										<option selected value="">Choose level...</option>
										<option value="A1">A1</option>
										<option value="A2">A2</option>
										<option value="B1">B1</option>
										<option value="B2">B2</option>
										<option value="C1">C1</option>
										<option value="C2">C2</option>
										<option value="mothertongue">Mother tongue</option>
									</select>
								</div>
					</td>
					
					<td> </td> 
				</tr> 
			</tbody> 
			<tr valign="baseline"> 
			<td nowrap="nowrap" align="center"><a href="javascript:addExtraLang( 'tabla_1' );">Agregar Fila</a></td> 
			<td colspan="3" align="center"><input type="submit" value="Insertar registro" /></td> 
			</tr> 
		</table> 
		<input type="hidden" name="MM_insert" value="form1" /> 
	</form> 
</body> 
</html> 
