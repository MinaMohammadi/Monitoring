#!/bin/bash

source /root/Phonebook/REST-API/venv/bin/activate

pip install -r /root/Phonebook/REST-API/requirements.txt

python3 /root/Phonebook/REST-API/app.py
