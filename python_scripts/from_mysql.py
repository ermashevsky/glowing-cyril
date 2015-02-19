#!/usr/bin/env python
# -*- coding: utf-8 -*-


import MySQLdb
from elasticsearch import Elasticsearch
 
mydb = MySQLdb.connect(host='localhost', user='root', passwd='11235813', db='mera_logs',use_unicode=True, charset='utf8')


cursor = mydb.cursor()

query = "select PhoneCode, Region from PhoneCode"
cursor.execute (query)

es = Elasticsearch()

#columns = tuple( d[0] for d in cursor.description )
for row in cursor.fetchall():
    
    jsonData = {'PhoneCode':row[0], 'Region':row[1]}
    #print jsonData
    es.index(index="logs_index", doc_type="phonecodes-type", body=jsonData)
    es.indices.refresh(index="logs_index")
            

