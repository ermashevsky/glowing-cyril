#!/usr/bin/env python
# -*- coding: utf8 -*-

from models import getPhoneNumbers, getRegionLongNumber, updateRow, getPhoneLongNumbers, updateLongRow
import datetime
import re
import profile
import pstats

long_numb = dict()
keyz = 0

for x in getPhoneLongNumbers():

    if (re.findall('#', x[0]) != 'None'):
        keyz = keyz + 1
        my1 = str(x[0]).split('#')
        print my1
        if my1[1] != '':
            keyz = keyz + 1
            long_numb[keyz] = getRegionLongNumber(my1[0], my1[1])


def updateLongRows(long_numb):
    for xxx in long_numb.values():
        updateLongRow(xxx[0], xxx[1], xxx[2], xxx[3])


updateLongRows(long_numb)

profile.run('updateLongRows', 'updateLongRow_prof')
stats = pstats.Stats('updateRow_prof')
stats.strip_dirs()
stats.sort_stats('time')
stats.print_stats(5)
