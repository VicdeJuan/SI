SOURCES := $(wildcard base/*.base.html)
GEN_HTML := $(patsubst base/%.base.html, %.html, $(SOURCES))

all: $(GEN_HTML)

%.html: base/header.html base/%.base.html base/footer.html
	cat $^ > $@	
