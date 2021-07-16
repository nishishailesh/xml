<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php ="http://php.net/xsl">
  <xsl:template match="/">
		  <table class="table">
				<tr><th colspan="2"><h3 class="text-center">
					Discharge Card : 
					<xsl:value-of select="/discharge_card/institute_location/name"/>,
					<xsl:value-of select="/discharge_card/institute_location/address"/>
					</h3></th>
				</tr>
				<tr>
					<th class="text-center"><h3>Department: <xsl:value-of select="/discharge_card/institute_location/department"/></h3></th>
					<th class="text-center"><h3>Contact: <xsl:value-of select="/discharge_card/institute_location/phone"/></h3></th>
				</tr>					
		  </table>
		  <table class="table table-bordered">
			<tr>
				<th><h5 ><xsl:value-of select="/discharge_card/patient_demography/name"/></h5></th>
				<th colspan="2">
					<h5>
						<xsl:value-of disable-output-escaping="yes" select="/discharge_card/patient_demography/address"/>
						(Ph:<xsl:value-of disable-output-escaping="yes" select="/discharge_card/patient_demography/phone"/>)
					</h5>
				</th>
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
				<th colspan="2"><h5>Admission: <xsl:value-of select="/discharge_card/patient_demography/date_of_admission"/></h5></th>
				<th colspan="3"><h5>Discharge: <xsl:value-of select="/discharge_card/patient_demography/date_of_discharge"/></h5></th>
			</tr>			
			</table>
			
		  <table class="table table-bordered">
			<tr><th colspan="10"><h3>Clinical Information</h3></th></tr>
			<tr>
				<td><b>Diagnosis:</b><br/>
				<xsl:call-template name="nl2br">
					<xsl:with-param   name="string" select="/discharge_card/clinical_information/diagnosis/text"/>
				</xsl:call-template>
				</td>
				<th><h5 >ICD:<xsl:value-of select="/discharge_card/clinical_information/diagnosis/icd"/></h5></th>
			</tr>
			<tr><th colspan="3"><h4>Presenting Complain</h4></th></tr>
			<tr>
				<td colspan="3" ><xsl:value-of disable-output-escaping="yes" select="/discharge_card/clinical_information/summary/presenting_complain"/></td>
			</tr>
			<tr>
				<td colspan="2"><b>Past History: </b><xsl:value-of select="/discharge_card/clinical_information/summary/past_history"/></td>
				<td><b>Family History: </b><xsl:value-of select="/discharge_card/clinical_information/summary/family_history"/></td>
			</tr>						
			<tr>
				<td><b>Sunstance History: </b><xsl:value-of select="/discharge_card/clinical_information/summary/substance_history"/></td>
				<td><b>Personal History: </b><xsl:value-of select="/discharge_card/clinical_information/summary/personal_history"/></td>
				<td><b>Patient Management Problmes: </b><xsl:value-of select="/discharge_card/clinical_information/summary/patient_management_problems"/></td>
			</tr>
			</table>
			<table  class="table table-bordered">
			<tr>
				<td>
					<b>Mental Status Examination on admission: </b><xsl:value-of select="/discharge_card/clinical_information/mental_status_examination_on_admission"/>
				</td>
                                <td>
                                        <b>Mental Status Examination on discharge: </b><xsl:value-of select="/discharge_card/clinical_information/mental_status_examination_on_discharge"/>
                                </td>
			</tr>
			<tr>

				<td><b>Improvement Area: </b><xsl:value-of select="/discharge_card/clinical_information/improvement_area"/></td>
				<td><b>Global Assesment of Functioning: </b><xsl:value-of select="/discharge_card/clinical_information/Global_Assessment_of_Functioning"/></td>
			</tr>			
		</table>

		  <table class="table table-bordered">
			<tr><th colspan="10"><h3>Laboratory Investigations</h3></th></tr>
			<tr>
				<td><xsl:value-of select="/discharge_card/clinical_information/laboratory_investigations"/></td>			
			</tr>
		  </table>
		  <table class="table table-bordered">
			<tr><th colspan="10"><h3>Treatment Given</h3></th></tr>
			<tr>
				<td><b>Pharmacology:</b><xsl:value-of select="/discharge_card/treatment_given/pharmacology"/></td>
				<td><b>Non Pharmacology:</b><xsl:value-of select="/discharge_card/treatment_given/non_pharmacology"/></td>
				<td><b>ECT:</b><xsl:value-of select="/discharge_card/treatment_given/ECT"/></td>
				<td><b>References:</b><xsl:value-of select="/discharge_card/treatment_given/references"/></td>
			</tr>
		  </table>

		  <table class="table table-bordered">
			<tr><th colspan="10"><h3>Advice on Discharge</h3></th></tr>
			<tr>
				<td><b>Pharmacology:</b><xsl:value-of select="/discharge_card/advice_on_discharge/pharmacology"/></td>
				<td><b>Non Pharmacology:</b><xsl:value-of select="/discharge_card/advice_on_discharge/non_pharmacology"/></td>
			</tr>
		  </table>

		  <table class="table table-bordered">
			<tr><th colspan="10"><h3>Notes:</h3></th></tr>
			<tr><th colspan="10"><xsl:value-of select="/discharge_card/notes/p1"/></th></tr>
			<tr><th colspan="10"><xsl:value-of select="/discharge_card/notes/p2"/></th></tr>
			<tr><th colspan="10"><xsl:value-of select="/discharge_card/notes/p3"/></th></tr>
		  </table>	

		  <table class="table table-bordered">
			<tr>
				<td><b>Resident:</b><xsl:value-of select="/discharge_card/authorization/resident"/></td>
				<td><b>Consultant:</b><xsl:value-of select="/discharge_card/authorization/consultant"/></td>
			</tr>
		  </table>	  		  
		
  </xsl:template>
  
<xsl:template name="nl2br">
	<xsl:param name="string"/>
	<xsl:value-of select="normalize-space(substring-before($string,'&#10;'))"/>
	<xsl:choose>
		<xsl:when test="contains($string,'&#10;')">
			<br />
			<xsl:call-template name="nl2br">
				<xsl:with-param name="string" select="substring-after($string,'&#10;')"/>
			</xsl:call-template>
		</xsl:when>
		<xsl:otherwise>
			<xsl:value-of select="$string"/>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>


</xsl:stylesheet>


<!--
	Example:

	<xsl:call-template name="nl2br">
		<xsl:with-param name="string" select="body"/>
	</xsl:call-template>
-->


