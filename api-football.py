#!/usr/bin/env python
import config

import requests
import json
import urllib.parse
from requests.structures import CaseInsensitiveDict

def toTextFile(file_name, type):
    textfile = open(file_name, "w", encoding="utf8")
    for x in range(len(type)):
        textfile.write(str(type[x]) + "\n")
    textfile.close()

url = "https://api-football-v1.p.rapidapi.com/v3/fixtures"

querystring = {"live":"all"}

headers = {
    'x-rapidapi-key': config.x_rapidapi_key,
    'x-rapidapi-host': config.x_rapidapi_host
    }

response = requests.request("GET", url, headers=headers, params=querystring)

scores = []
elapsed = []

venues = []
competitions = []
cities = []
countries = []

venues_new = []
competitions_new = []
cities_new = []
countries_new = []

for data in response.json()['response']:
    scores.append(data['teams']['home']['name'] + " " + str(data['goals']['home']) + 
    " - " + str(data['goals']['away']) + " " + data['teams']['away']['name'] + " " +
    str(data['fixture']['status']['elapsed']) + "'")
    competitions.append(data['league']['name'])
    venues.append(data['fixture']['venue']['name'])
    cities.append(data['fixture']['venue']['city'])
    countries.append(data['league']['country'])

conv = lambda i : i or ''
venues_new = [conv(i) for i in venues]
competitions_new = [conv(i) for i in competitions]
cities_new = [conv(i) for i in cities]
countries_new = [conv(i) for i in countries]

lon = []
lat = []
venues_formatted = []

for x in range(len(scores)):
    if len(cities_new[x]) == 0:
        cities_new[x] = None
        countries_new[x] = None
        venues_new[x] = None
        competitions_new[x] = None
        scores[x] = None
        
for x in range(len(scores)):
    venues_formatted.append(urllib.parse.quote(str(cities_new[x]) + 
    ", " + str(countries_new[x])))

for x in range(len(scores)):
    url = "https://api.geoapify.com/v1/geocode/search?text=" + venues_formatted[x] + "&apiKey=" + config.geoapify_key

    headers = CaseInsensitiveDict()
    headers["Accept"] = "application/json"

    response = requests.get(url, headers=headers)

    lon_var = ""
    lat_var = ""

    for data in response.json()['features']:
        lon_var = data['properties']['lon']
        lat_var = data['properties']['lat']

    lon.append(lon_var)
    lat.append(lat_var)

toTextFile("text-files/lon.txt", lon)
toTextFile("text-files/lat.txt", lat)
toTextFile("text-files/scores.txt", scores)
toTextFile("text-files/venues.txt", venues_new)
toTextFile("text-files/competitions.txt", competitions_new)

print("Success")