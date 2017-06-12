function dataTable(customerName) {

	$('#tOpportunity')
			.DataTable(
					{
						searching : true,
						"scrollY" : "auto",
						"bInfo" : false,
						"paging" : true,
						destroy : true,
						"sAjaxSource" : "../controladores/json_consultas/json_consulta.php?organizationId="
								+ organizationId
					});

}

function dataTableQuote() {

	$('#tQuote').DataTable({
		searching : true,
		"scrollY" : "auto",
		"bInfo" : false,
		"paging" : true,
		destroy : true,
		"sAjaxSource" : "../controladores/json_consultas/json_consulta_quote.php"
		

	});
	$( '#prueba').html( "<span	class='btn btn-primary btn-sm' glyphicon glyphicon-plus biselado'		id='addRow' data-accion='add' onclick='newQuote()'>NEW QUOTE</span>" );

}

function quote() {
	window.open("../archivos/quote.pdf");	
}
function newQuote(){
	window.open("../controladores/quote_vs2.php?organizationId="+organizationId);

}
function addNewProduct() {
	idTxtDescrip++;
 xajax_addNewProduct(idTxtDescrip);
}
