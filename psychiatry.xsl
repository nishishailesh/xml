<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php ="http://php.net/xsl">
  <xsl:template match="/">
      <xsl:value-of select="/discharge_card/clinical_information/diagnosis/icd_name"/>
      <xsl:value-of select="php:function('mk_select_from_sql','select name from icd','name','icd_name','icd_name')"/>
      <xsl:value-of select="/discharge_card/clinical_information/diagnosis/text"/>
      <xsl:value-of select="/discharge_card/clinical_information/diagnosis/icd_code"/>
      <xsl:value-of select="php:function('mk_select_from_sql','select name from icd','name','icd_name','icd_name')"/>
  </xsl:template>
</xsl:stylesheet>
