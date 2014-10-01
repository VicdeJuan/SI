<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">
<html>
<body>
<xsl:for-each select="catalog/movie">
  <div style="background-color:teal;color:white;padding:4px">
    <xsl:value-of select="img"/>
    <xsl:value-of select="score"/>
    </div>
  <div style="margin-left:20px;margin-bottom:1em;font-size:10pt">
    <p>
    <xsl:value-of select="description"/>
    <xsl:value-of select="title"/>
    <xsl:value-of select="genre"/> 
    </p>
  </div>
</xsl:for-each>
</body>
</html>
</xsl:template>
</xsl:stylesheet>