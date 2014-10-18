<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">

<html>
<head>
  <link rel="stylesheet" href="../style/main.css" />
  <link rel="stylesheet" href="../style/movie.css" />
  <meta charset="utf-8" />
</head>
<body>
  <div class="body-container">
    <div class="scroller">
      <div class="inner-scroll">
        <div class="main-container">

          <xsl:for-each select="catalog/movie">
            <div class="movies">
              <xsl:element name="img">
                <xsl:attribute name="src">
                  <xsl:value-of select="img"/>
                </xsl:attribute>
                <xsl:attribute name="class">movie_img</xsl:attribute>
              </xsl:element>
              <p class="movie_title"> <xsl:value-of select="title"/></p>
              <p class="movie_description">
                <p class="movie_genre"> <xsl:value-of select="genre"/> </p>
                <xsl:value-of select="description"/><br />
              </p>
            </div>
          </xsl:for-each>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

</xsl:template>
</xsl:stylesheet>
