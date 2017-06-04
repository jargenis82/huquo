function dataTable(customerName) {

	$('#tOpportunity')
			.DataTable(
					{
						searching : true,
						"scrollY" : "auto",
						"bInfo" : false,
						"paging" : true,
						destroy : true,
						"sAjaxSource" : "../controllers/json_consultas/json_consulta.php?customerName="
								+ customerName
					});

}
