<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php ="http://php.net/xsl">
  <xsl:template match="/">
	  <table class="table">
			<tr>
				<th><h1 class="text-center"><xsl:value-of select="/discharge_card/institute_location/name"/></h1></th>
				<th><h1 class="text-center"><xsl:value-of select="/discharge_card/institute_location/address"/></h1></th>
			</tr>
			<tr>
				<th class="text-center"><h3>Department: <xsl:value-of select="/discharge_card/institute_location/department"/></h3></th>
				<th class="text-center"><h3>Contact: <xsl:value-of select="/discharge_card/institute_location/phone"/></h3></th>
			</tr>	
					
      </table>
		  <table class="table table-striped">
			<tr>
				<th><h5 ><xsl:value-of select="/discharge_card/patient_demography/name"/></h5></th>
				<th colspan="2"><h5 ><xsl:value-of select="/discharge_card/patient_demography/address"/></h5></th>
				<th><h5 >DOB:<xsl:value-of select="/discharge_card/patient_demography/date_of_birth"/></h5></th>
				<th><h5 >Age:<xsl:value-of select="/discharge_card/patient_demography/age/years"/> Yr <xsl:value-of select="/discharge_card/patient_demography/age/months"/> Mo</h5></th>
			</tr>
			<tr>
				<th><h5 >Psychiatry Index:<xsl:value-of select="/discharge_card/patient_demography/psychiatry_index"/></h5></th>
				<th><h5 ><xsl:value-of select="/discharge_card/patient_demography/mrd"/></h5></th>
				<th><h5 ><xsl:value-of select="/discharge_card/patient_demography/indoor_number"/></h5></th>
				<th><h5 ><xsl:value-of select="/discharge_card/patient_demography/outdoor_number"/></h5></th>
				<th><h5 >MLC:<xsl:value-of select="/discharge_card/patient_demography/mlc_case/number"/>/<xsl:value-of select="/discharge_card/patient_demography/mlc_case/date"/></h5></th>
			</tr>
			<tr>
				<th ><h5>Admission: <xsl:value-of select="/discharge_card/patient_demography/date_of_admission"/></h5></th>
				<th ><h5>Discharge: <xsl:value-of select="/discharge_card/patient_demography/date_of_discharge"/></h5></th>
				<th ><h5>Database ID: <xsl:value-of select="/discharge_card/XML/ID"/></h5></th>
				<th colspan="2" ><h5>XML Template: <xsl:value-of select="/discharge_card/XML/name"/></h5></th>
			</tr>			
      </table>
  </xsl:template>
</xsl:stylesheet>
