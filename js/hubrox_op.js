function dataTable(customerName) {
	var valueSearch=true;
	if(customerName==null)valueSearch=false;
	$('#tOpportunity')
			.DataTable(
					{
						searching : valueSearch,
						"scrollY" : "auto",
						"bInfo" : false,
						"paging" : true,
						destroy : true,
						"initComplete": function(settings, json) {
						    window.parent.ajustarIframe();
						  },
						  "columnDefs": [
						                 { className: "dt-center", "targets": [ 0,1,2,3,4,5 ] }
						               ],
						"sAjaxSource" : "../controladores/json_consultas/json_consulta.php?organizationId="
								+ organizationId
					});
			dataTableQuote(null);
}

function dataTableQuote(opportunityId) {
	var valueSearch=true;
	if(opportunityId==null)valueSearch=false;
	$('#tQuote').DataTable({
		searching : valueSearch,
		"scrollY" : "auto",
		"bInfo" : false,
		"paging" : true,
		destroy : true,
		"initComplete": function(settings, json) {
		    window.parent.ajustarIframe();
		  },
		  "columns": [
		              { className: "dt-center" },
		              { className: "dt-center" },
		              { className: "dt-center" },
		              { className: "dt-head-center dt-body-right" },
		              { className: "dt-center" },
		              { className: "dt-center" }
		            ],
		  
		  
		  
		"sAjaxSource" : "../controladores/json_consultas/json_consulta_quote.php?opportunityId="+opportunityId
	});
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
function openPdfQuote(quoteId,newTag){
	if (newTag) {
		window.open("../controladores/pdf/quote_pdf.php?quoteId="+quoteId);
	} else {
		window.parent.location = "../controladores/pdf/quote_pdf.php?quoteId="+quoteId;
	}	
}
