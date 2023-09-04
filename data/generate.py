import csv
import requests as req
import os
import warnings
warnings.filterwarnings("ignore")
# insert your api key
#API_KEY = os.getenv('HISTORISKA_API_KEY')
API_KEY = '0'
ENDPOINT = 'http://localhost:8000/api/admin/cards/create'

def send_requests(row):
    data = {
        'name': row['Nom'],
        'description': row['Description'],
        'rarity' : 1,
        'code' : row['Rang'],
        'birth' : row['Naissance'],
        'death' : row['Mort'],
        'img' : row['Image'],
        'country' : row['Pays'],
        'continent' : row['Continent'],
        'category' : row['Domaine']
    }

    headers = {"Authorization" : API_KEY}

    r = req.post(ENDPOINT, json=data, headers=headers)

    print(r.content.decode(encoding='utf-8'))

def read_file(filename):
    with open(filename, 'r') as f:
        cr = csv.DictReader(f)

        for row in cr:
            if row['Nom'] != '':
                send_requests(row)

if __name__ == "__main__":
    read_file('personnages.csv')
