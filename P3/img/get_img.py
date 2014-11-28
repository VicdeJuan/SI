import urllib
import urllib2
import os
import re
#	os.system('wget %s -o aux'%url)

os.system('export PGHOST=localhost && export PGUSER=alumnodb &&  export PGPASSWORD=alumnodb')

movie_file = open("pelis.txt","r");

movies = movie_file.readlines();

for i in xrange(1,len(movies)):
	url = "http://www.omdbapi.com/?"+urllib.urlencode({'t' : movies[i].strip()[:70]})
	movie_info =  urllib2.urlopen(url)
	string =  movie_info.readlines()
	if string[0].find("Movie not found!") == -1:
		string =  string[0][string[0].find('Poster') + 9::]
		if string.find("jpg") != -1:
			string =  string[:string.find("jpg")+3]
			movieid = re.search('(\s)([0-9]+)\n',movies[i]).group(2)

			#para descargar
			#os.system("wget -O - {0} > img/'{1}'.jpg".format(string,movieid))

			#para actualizar la db
			command = "psql olakase -c \"update imdb_movies set url_to_img = \'{0}\' where movieid={1}\"".format(string,movieid)
			print "update imdb_movies set url_to_img = \'{0}\' where movieid={1};".format(string,movieid)