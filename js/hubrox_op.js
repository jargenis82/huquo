function dataTable(customerName) {

	$('#tOpportunity')
			.DataTable(
					{
						searching : true,
						"scrollY" : "auto",
						"bInfo" : false,
						"paging" : true,
						destroy : true,
						"sAjaxSource" : "../controladores/json_consultas/json_consulta.php?customerName="
								+ customerName
					});

}

function dataTableQuote() {
	var data = [
			[
					"20171702a1",
					"02/17/2017",
					"50.000",
					"<span id='btn_quote' class='btn btn-default btn-xs glyphicon glyphicon-file' data-modulo='' data-accion='' onclick='dataTableQuote()'></span>",
					"<span id='btn_quote' class='btn btn-default btn-xs glyphicon glyphicon-download-alt' data-modulo='' data-accion='' onclick='dataTableQuote()'></span>",

			],
			[
					"20171702a1",
					"05/20/2017",
					"75.000",

					"<span id='btn_quote' class='btn btn-default btn-xs glyphicon glyphicon-file' data-modulo='' data-accion='' onclick='dataTableQuote()'></span>",
					"<span id='btn_quote' class='btn btn-default btn-xs glyphicon glyphicon-download-alt' href='../archivos/quote.pdf' onclick='quote()' ></span>",

			] ]

	$('#tQuote').DataTable({
		searching : true,
		"scrollY" : "auto",
		"bInfo" : false,
		"paging" : true,
		destroy : true,

		data : data

	});
	$( '#prueba').html( "<span	class='btn btn-primary btn-sm' glyphicon glyphicon-plus biselado'		id='addRow' data-accion='add' onclick='newQuote()'>NEW QUOTE</span>" );

}

function quote() {
	window.location.assign("../archivos/quote.pdf");
}
function newQuote(){
	window.location.assign("../controladores/quote_vs2.php");

}
