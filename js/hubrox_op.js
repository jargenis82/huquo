function dataTable(customerName) {

	$('#tOpportunity')
			.DataTable(
					{
						searching : true,
						"scrollY" : "auto",
						"bInfo" : false,
						"paging" : true,
						destroy : true,
						"initComplete": function(settings, json) {
						    window.parent.ajustarIframe();
						  },
						"sAjaxSource" : "../controladores/json_consultas/json_consulta.php?organizationId="
								+ organizationId
					});
}

function dataTableQuote(opportunityId) {
	$('#tQuote').DataTable({
		searching : true,
		"scrollY" : "auto",
		"bInfo" : false,
		"paging" : true,
		destroy : true,
		"sAjaxSource" : "../controladores/json_consultas/json_consulta_quote.php?opportunityId="+opportunityId
	});
	$( '#prueba').html( "<span	class='btn btn-primary btn-sm' glyphicon glyphicon-plus biselado'		id='addRow' data-accion='add' onclick='newQuote()'>NEW QUOTE</span>" );

}

function quote() {
	window.open("../archivos/quote.pdf");	
}
function newQuote(opportunityId){
	window.open("../index.php?page=quote&opportunityId="+opportunityId);
}
function addNewProduct() {
	idTxtDescrip++;
	arrProductSale[idTxtDescrip] = new Array();
	arrProductSale[idTxtDescrip]['product_sale_id'] = "";
	arrProductSale[idTxtDescrip]['quote_line_desc'] = "";
	arrProductSale[idTxtDescrip]['quote_line_price'] = "";
	xajax_addNewProduct(idTxtDescrip);
}
