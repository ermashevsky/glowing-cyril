#!/usr/bin/env python
# -*- coding: utf-8 -*-


import MySQLdb
import re
from elasticsearch import Elasticsearch
 
mydb = MySQLdb.connect(host='localhost', user='root', passwd='11235813', db='mera_logs',use_unicode=True, charset='utf8')

cursor = mydb.cursor()


query = "SELECT `id`, `DST-NUMBER-BILL` as dst_number_bill FROM Logs WHERE `DST-NUMBER-BILL` IS NOT NULL AND Region IS NULL"
cursor.execute (query)
    
for dst_number_bill in cursor.fetchall():
    print dst_number_bill[0]
    try:
        pattern = '#'
        string = 'This is a simple test message for test'
        found = re.findall(pattern, dst_number_bill[1])

    except TypeError:
        print("Oops!  That was no valid number.  Try again...")
        #print dst_number_bill

    if(len(found) > 0):

    #print "=====>"+dst_number_bill
        getNumb = dst_number_bill[1].split("#")
        print getNumb[0]
        print getNumb[1]
        query = "SELECT PhoneCode, Region FROM PhoneCode WHERE "+getNumb[1]+" LIKE CONCAT(  `PhoneCode` ,  '%' ) ORDER BY  `PhoneCode`.`PhoneCode` DESC LIMIT 1"
        cursor.execute (query)
        #print query
    else:

        query = "SELECT PhoneCode, Region FROM PhoneCode WHERE '"+str(dst_number_bill[1])+"' LIKE CONCAT(  `PhoneCode` ,  '%' ) ORDER BY  `PhoneCode`.`PhoneCode` DESC LIMIT 1"
        cursor.execute (query)

    for row in cursor.fetchall():
        
        #print row[0].encode('utf-8')+" "+row[1].encode('utf-8')
        query = "UPDATE Logs SET Region='"+row[1].encode('utf-8')+"', PhoneCode='"+row[0].encode('utf-8')+"' where id="+str(dst_number_bill[0])+""
        cursor.execute (query)
        print query
        
     

