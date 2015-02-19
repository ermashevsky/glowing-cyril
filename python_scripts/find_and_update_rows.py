#!/usr/bin/env python
# -*- coding: utf8 -*-

from models import getPhoneNumbers, getRegion, updateRow, getPhoneLongNumbers, updateLongRow
import datetime
import re
import profile
import pstats


xyu = dict()
keys = 0
long_numb = dict()
keyz = 0

for x in getPhoneNumbers():

    # if (re.findall('#', x[0]) == 'None'):
    keys = keys + 1
    print x[0]
    #xyu[keys] = getRegion(x[0])
    # else:
    # my1 = str(x[0]).split('#')
    #     print my1
    #     if my1[0] != '':
    #         keys = keys + 1
    #         xyu[keys] = getRegion(my1[0])


def updateRows(xyu):
    for xxx in xyu.values():
        updateRow(xxx[0], xxx[1], xxx[2])
