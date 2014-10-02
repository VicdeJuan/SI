<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">
<html>
  <head>
  <link rel="stylesheet" type="text/css" href="style/main.css" />
  <meta charset="utf-8" />
  <title>Ola k ase</title>
  </head>

<body>

  <header>

  </header>

  <div class="body-container">
  <!-- Left Menu-->
    <aside class="menu">
      <ul>
        <li><a href="index.html" class="filter-title">Inicio</a></li>
        <li class="filter"><a href="" class="filter-title">Género</a>
          <ul class="filter-items">
            <li><a href="error.html" class="filter-genre">Comedia</a></li>
            <li><a href="error.html" class="filter-genre">Drama</a></li>
            <li><a href="error.html" class="filter-genre">Etc</a></li>
          </ul>
        </li>
        <li><a href="" class="filter-title">Año</a></li>
        <li><a href="" class="filter-title">Etc</a></li>  
      </ul>
    </aside> 
    <!-- End left menu-->


    <!-- Main frame -->
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
<footer>

</footer>
</body>
</html>

</xsl:template>
</xsl:stylesheet>