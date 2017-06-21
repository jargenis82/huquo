<style>
th {
	background-color: #58ACFA;
	color: white;
}

table {
	border-collapse: collapse;
	width: 100%;
}

th, td {
	padding: 4px;
	text-align: left;
	border-bottom: 1px solid #ddd;
}

tr:hover {
	background-color: #f5f5f5
}
</style>
<br>
<table align=left border="0" width="100%">
	<tr>
		<th align="left"><label> CUSTOMER </label></th>
		<th align="left"><label> SHIP TO </label></th>
	</tr>
	<tr>
		<td>[var.customerInfor;htmlconv=no;noerr]</td>
		<td>[var.shipTo;noerr]</td>
	</tr>
</table>
<br>

<table align=left border="0" width="100%">
	<tr>
		<th align="center" width="380px"><label>DESCRIPTION</label></th>
		<th align="right" width="70px"><label>UNIT PRICE</label></th>
		<th align="center" width="95px"><label>QTY</label></th>
		<th align="center" width="100px"><label>AMOUNT (US$)</label></th>
	</tr>
	<tr>
		<td align="left">[products.desc;block=tr;noerr]</td>
		<td align="right">[products.price;noerr]</td>
		<td align="center">[products.qty;noerr]</td>
		<td align="right">[products.amount;noerr]</td>
	</tr>
	<tr>
		<td colspan="2">
			<table align=left border="0" width="600px">
				<tr>
					<th align="left"><label>TERMS AND CONDITIONS </label></th>
				</tr>
				<tr>
					<td><ol>
							<li>Customer will be billed after indicating acceptance of
								this quote</li>
							<li>Payment will be due prior to delivery of service and
								goods</li>
							<li>CIF-Miami. Freight to Miami is included in this price.</li>
							<li>Please fax or mail the signed price quote to the address
								above</li>
						</ol></td>
				</tr>
				<tr>
					<td>
						<p>Customer Acceptance (sign below):</p> <label>Print
							Name_______________ </label>
					</td>
				</tr>
			</table>
		</td>
		<td colspan="2">
			<table border="0">
				<tr>
					<td width="50px"><label> Discount</label></td>
					<td align="right" width="100px">[var.discountVal;noerr]</td>
				</tr>
				<tr>
					<td><label> SubTotal(US$)</label></td>
					<td align="right">[var.sunTotal;noerr]</td>
				</tr>
				<tr>
					<td><label>HST Rate</label></td>
					<td align="right">[var.hstRate;noerr]</td>
				</tr>
				<tr>
					<td><label>HST (US$)</label></td>
					<td align="right">[var.hstUst;noerr]</td>
				</tr>
				<tr>
					<td><label>TOTAL(US$)</label></td>
					<td align="right">[var.total;noerr]</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>