#!/usr/bin/python

import urllib
import urllib2
import re
import multiprocessing

def get_movie_img(movie):
	url_opener = urllib.URLopener()
	param = { 't': movie.strip()[:70] }
	url = "http://www.omdbapi.com/?" + urllib.urlencode(param)
	movie_info = urllib2.urlopen(url)
	string =  movie_info.readlines()

	if string[0].find("Movie not found!") == -1:
		string = string[0][string[0].find('Poster') + 9::]
		if string.find("jpg") != -1:
			string = string[:string.find("jpg")+3]
			movieid = re.search('(\s)([0-9]+)\n', movie).group(2)
			image_url = "img/" + movieid + ".jpg"
			# url_opener.retrieve(string, image_url)
			print "Retrieved {0}".format(movieid)
			return "UPDATE imdb_movies SET url_to_img = \'{0}\' WHERE movieid = {1} ;\n".format(image_url, movieid)

	return ""

if __name__ == '__main__':
	movie_file = open("pelis.txt", "r")
	movies = movie_file.readlines()
	sql = open("images.sql", "w")
	max_downloads = 250
	movies_to_download = min(len(movies), max_downloads)
	print "Starting..."
	pool = multiprocessing.Pool(10)
	sql_statements = pool.map(get_movie_img, movies[:movies_to_download])
	sql.writelines(sql_statements)

	print "Done. Total {0} records".format(movies_to_download)
