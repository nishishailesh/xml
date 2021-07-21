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
				<th><xsl:value-of select="/discharge_card/patient_demography/name"/></th>
				<th colspan="2">
						<xsl:value-of select="/discharge_card/patient_demography/address"/>
						(Ph:<xsl:value-of select="/discharge_card/patient_demography/phone"/>)
				</th>
				<th>DOB:<xsl:value-of select="/discharge_card/patient_demography/date_of_birth"/></th>
				<th>Age:<xsl:value-of select="/discharge_card/patient_demography/age/years"/> Yr <xsl:value-of select="/discharge_card/patient_demography/age/months"/> Mo</th>
			</tr>
			<tr>
				<th>Psychiatry Index:<xsl:value-of select="/discharge_card/patient_demography/psychiatry_index"/></th>
				<th><xsl:value-of select="/discharge_card/patient_demography/mrd"/></th>
				<th><xsl:value-of select="/discharge_card/patient_demography/indoor_number"/></th>
				<th><xsl:value-of select="/discharge_card/patient_demography/outdoor_number"/></th>
				<th>MLC:<xsl:value-of select="/discharge_card/patient_demography/mlc_case/number"/><br></br><xsl:value-of select="/discharge_card/patient_demography/mlc_case/date"/></th>
			</tr>
			<tr>
				<th colspan="2">Admission: <xsl:value-of select="/discharge_card/patient_demography/date_of_admission"/></th>
				<th colspan="3">Discharge: <xsl:value-of select="/discharge_card/patient_demography/date_of_discharge"/></th>
			</tr>			
			</table>
			
		  <table class="table table-bordered">
			<tr><th colspan="10"><h3>Clinical Information</h3></th></tr>
			<tr>
				<td><b>Diagnosis:</b><br/>
					<pre><xsl:value-of   name="string" select="/discharge_card/clinical_information/diagnosis/text"/></pre>
				</td>
				<th>ICD:<xsl:value-of select="/discharge_card/clinical_information/diagnosis/icd"/></th>
			</tr>
			<tr><th colspan="3"><h4>Presenting Complain</h4></th></tr>
			<tr>
				<td colspan="3" ><xsl:value-of disable-output-escaping="yes" select="/discharge_card/clinical_information/summary/presenting_complain"/></td>
			</tr>
			<tr>
				<td colspan="2"><b>Past History: </b><pre><xsl:value-of select="/discharge_card/clinical_information/summary/past_history"/></pre></td>
				<td><b>Family History: </b><pre><xsl:value-of select="/discharge_card/clinical_information/summary/family_history"/></pre></td>
			</tr>						
			<tr>
				<td><b>Substance History: </b><pre><xsl:value-of select="/discharge_card/clinical_information/summary/substance_history"/></pre></td>
				<td><b>Personal History: </b><pre><xsl:value-of select="/discharge_card/clinical_information/summary/personal_history"/></pre></td>
				<td><b>Patient Management Problmes: </b><pre><xsl:value-of select="/discharge_card/clinical_information/summary/patient_management_problems"/></pre></td>
			</tr>
			</table>
			<table  class="table table-bordered">
			<tr>
				<td>
					<b>Mental Status Examination on admission: </b><pre><xsl:value-of select="/discharge_card/clinical_information/mental_status_examination_on_admission"/></pre>
				</td>
                <td>
					<b>Mental Status Examination on discharge: </b><pre><xsl:value-of select="/discharge_card/clinical_information/mental_status_examination_on_discharge"/></pre>
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
				<td><xsl:value-of disable-output-escaping="yes"  select="/discharge_card/clinical_information/laboratory_investigations"/></td>			
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

</xsl:stylesheet>

