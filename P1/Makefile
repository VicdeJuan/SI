SOURCES := $(wildcard *.base.html)
GEN_HTML := $(patsubst %.base.html, %.html, $(SOURCES))

all: $(GEN_HTML)

%.html: header.html %.base.html footer.html
	cat $^ > $@	
