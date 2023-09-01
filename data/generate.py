import csv
import requests as req
import time
import os

# insert your api key
API_KEY = os.getenv('HISTORISKA_API_KEY')
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
        'continent' : 'N/A',
        'category' : row['Domaine']
    }

    headers = {"Authorization" : API_KEY}

    r = req.post(ENDPOINT, json=data, headers=headers)
    print(r.content)

def read_file(filename):
    with open(filename, 'r') as f:
        cr = csv.DictReader(f)

        for row in cr:
            send_requests(row)
            time.sleep(0.2)

if __name__ == "__main__":
    read_file('personnages.csv')
