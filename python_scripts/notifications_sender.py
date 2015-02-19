#!/usr/bin/env python
# -*- coding: utf-8 -*-


import MySQLdb
from smtplib import SMTP
from email.header import Header
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from elasticsearch import Elasticsearch
import datetime
import logging

#logging.basicConfig(level=logging.INFO)
# get trace logger and set level
# get main logger and set level

 
mydb = MySQLdb.connect(host='localhost', user='root', passwd='11235813', db='mera_logs',use_unicode=True, charset='utf8')
es = Elasticsearch()

def sendnotification(*args):
    
    text = ''.join(args)
    m = MIMEText(text, 'plain', "utf-8")
    m['Subject'] = "(MeraLogAnalyzer) Уведомление о превышении показателей"
    m['From'] = "ermashevsky@dialog64.ru"
    m['To'] = "kir@dialog64.ru"
    msg = str(m)
    
    fromaddr = "ermashevsky@dialog64.ru"
    #toaddr = "kir@dialog64.ru"
    toaddr = "ermashevsky@dialog64.ru"

    connect = SMTP('smtp.dialog64.ru',25)
    connect.set_debuglevel(1)
    connect.login("ermashevsky@dialog64.ru", "kk6k29")
    connect.sendmail(fromaddr, toaddr, msg)
    connect.quit()

cursor = mydb.cursor()
query = "SELECT zone, zone_parameter FROM  `phones_zone`"
cursor.execute (query)

nowDateTime = datetime.datetime.today().strftime("%Y-%m-%d %H:%M:00")
oneHourAgoValue = (datetime.datetime.today()-datetime.timedelta(hours=1)).strftime("%Y-%m-%d %H:%M:00")

for row in cursor.fetchall():

## Deprecated    
#    json = {
#    "size": 0, 
#    "query": {
#        "bool": {
#        "must": { "wildcard": { "dst_number_bill": ""+row[0]+"*" }}
#    }
#    },"aggs": {
#        "group_by_timestamp": {
#          "range": {
#            "field": "timestamp",
#            "ranges": [
#              {
#                "gte": ""+oneHourAgoValue+"",
#                "lte": ""+nowDateTime+""
#              }
#            ]
#          },
#    "aggs": {
#       "calls_counter_stat": {
#           "terms": {"field": "dst_number_bill"}
#                    }
#                }
#            }
#        }
#    }
    
    
    json = {
    "size": 0,
    "query": {
        "bool": {
        "must": { "wildcard": { "dst_number_bill": ""+row[0]+"*" }}
    }
    },"aggs": {
        "group_by_timestamp": {
          "filter": {
                     "range": {
                        "timestamp": {
                             "gte": ""+oneHourAgoValue+"",
                             "lte": ""+nowDateTime+""
                         }
                         
                     }
                  },
    "aggs": {
       "calls_counter_stat": {
           "terms": {"field": "dst_number_bill"}
                    }
                }
            }
        }
    }

    notificationsData = es.search(index="logs_index", doc_type="log-type", body=json)

    doc_counter = notificationsData['aggregations']['group_by_timestamp']['doc_count']
    print doc_counter
#    for hit in notificationsData['aggregations']['group_by_timestamp']['doc_count']:
#        print hit
    if (doc_counter>row[1]):
        msg = u"""\nДобрый день, Кирилл Александрович!\n\nВы получили данное уведомление, т.к. в период %(period)s был превышен коэффициент %(coeficient)s по зоне %(zone)s.\n
Данные за указанный период: количество звонков по направлению - %(call_counter)d, что больше на %(expression)s штук.
        """ % {
        'zone': row[0],
        'coeficient': row[1],
        'period':  oneHourAgoValue + " - "+nowDateTime,
        'call_counter':doc_counter,
        'expression': doc_counter-row[1]

        }
        #print msg

        sendnotification(msg)
    
    



