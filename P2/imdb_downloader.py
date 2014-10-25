#!/opt/local/bin/python2.7
# Requisito: http://imdbpy.sourceforge.net/downloads.html#source-code
# 
# Como lxml se carga las cabeceras del XML, las dejo aqui:
#i
# <?xml version="1.0" encoding="UTF-8"?>
# <?xml-stylesheet type="text/xsl" href="style/moviecatalog.xsl"?>
# <!DOCTYPE note SYSTEM "data/catalog.dtd">

from imdbpie import Imdb
import sys
import random
from lxml import etree
import urllib
import os

imdb = Imdb()
movies_xml = etree.parse('data/movies.xml')
url_opener = urllib.URLopener()
random.seed()

def get_movie(movie_id):
	print "Retrieving movie {0}...".format(movie_id)
	movie = imdb.find_movie_by_id(movie_id)

	next_id = len(movies_xml.getroot())

	image_url = "img/" + movie_id + ".jpg"

	if os.path.isfile(image_url):
		print "{0} already exists".format(image_url)
	else:
		print "Downloading image {0} ({1})".format(image_url, movie.title.encode('utf-8'))
		url_opener.retrieve(movie.cover_url, image_url)

	movie_xml = etree.Element("movie")
	etree.SubElement(movie_xml, "id").text = str(next_id)
	etree.SubElement(movie_xml, "image").text = image_url
	etree.SubElement(movie_xml, "score").text = str(movie.rating)
	etree.SubElement(movie_xml, "genre").text = str(movie.genres[0])
	etree.SubElement(movie_xml, "title").text = movie.title
	etree.SubElement(movie_xml, "year").text = str(movie.year)
	etree.SubElement(movie_xml, "description").text = movie.plot_outline
	etree.SubElement(movie_xml, "price").text = str(random.randint(1, 8))

	return movie_xml

if len(sys.argv) < 2:
	print 'usage: imdb_downloader.py [movie name | top50]'
	sys.exit(1)

if sys.argv[1] == "top50":
	print "Retrieving Top 50 movies"
	top50 = imdb.top_250()[0:50]

	for m in top50:
		movies_xml.getroot().append(get_movie(m['tconst']))

else:
	movie = imdb.find_by_title(sys.argv[1])[0]

	if movie is None:
		print 'Not found.'
		sys.exit(1)

	movie_xml = get_movie(movie['imdb_id'])

	movies_xml.getroot().append(movie_xml)

f = open('data/movies.xml', 'w')
f.write(etree.tostring(movies_xml.getroot(), pretty_print=True))
f.close()