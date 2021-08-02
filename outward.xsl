<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php ="http://php.net/xsl">
	<xsl:template match="/">
		<table class="table">
			<tr>
				<td width="60%"></td>

				<td width="40%">
					<p><b>From:</b></p>
					<p><xsl:value-of select="/outward/sender/department" /></p>
					<p><xsl:value-of select="/outward/sender/name" /></p>
					<p><xsl:value-of select="/outward/sender/address" /></p>
					<p>Phone:<xsl:value-of select="/outward/sender/phone" /></p>				
				</td>
			</tr>
		</table>

		<hr></hr>
					<p><b>To:</b><xsl:value-of select="/outward/recipient" /></p>
					<p><b>Subject:</b><xsl:value-of select="/outward/subject" /></p>
					<p><b><xsl:value-of select="/outward/greeting" />,</b></p>		
		<hr></hr>

		<p><xsl:value-of  disable-output-escaping="yes" select="/outward/description" /></p>

		<table class="table">
			<tr>
				<td width="60%"></td>
				<td width="40%">
					<p>Signature:</p>
					<p><xsl:value-of select="/outward/signature/name" /></p>
					<p><xsl:value-of select="/outward/signature/post" /></p>
					<p><xsl:value-of select="/outward/signature/department" /></p>
					<p><xsl:value-of select="/outward/signature/institute" /></p>
				</td>
			</tr>
		</table>

		<hr></hr>
		
		<p>Submitted Through:<xsl:value-of select="/outward/submitted_through" /></p>
		<p>Forwarded To:<xsl:value-of  disable-output-escaping="yes" select="/outward/forwarded_to" /></p>
		
	</xsl:template>

</xsl:stylesheet>


