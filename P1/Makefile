SOURCES := $(wildcard base/*.base.html)
GEN_HTML := $(patsubst base/%.base.html, %.html, $(SOURCES))

all: $(GEN_HTML)

style/%.xsl: base/%.base.xsl base/header.html base/footer.html
	cat $< | sed -e "/HEADER/r base/header.html" -e "//d" | sed -e "/FOOTER/r base/footer.html" -e "//d" > $@ 

%.html: base/header.html base/%.base.html base/footer.html
	cat $^ > $@	
