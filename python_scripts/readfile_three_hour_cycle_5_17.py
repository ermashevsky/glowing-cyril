#!/usr/bin/env python
# -*- coding: utf-8 -*-
import os
import paramiko
import time
import progressbar
import re
import datetime
from sqlalchemy import create_engine, MetaData, Table, Column, Integer
import shutil
import commands


def loadProgress(blsize, size):
    dldsize = min(blsize, size)
    p = float(dldsize) / size
    bar.update(p)


def sizeof_fmt(num):
    for x in ['bytes', 'KB', 'MB', 'GB']:
        if num < 1024.0 and num > -1024.0:
            return "%3.1f%s" % (num, x)
        num /= 1024.0
    return "%3.1f%s" % (num, 'TB')


def findall(pattern, string):
    res = {}
    for match in re.finditer(pattern, string):
        if match.group(0) not in res:
            res[match.group(0)] = match.start()
    return res

bar = progressbar.ProgressBar(maxval=1.0, widgets=[
                              'Загрузка файла ',
                              progressbar.Bar(left='[', marker='*', right=']'),
                              progressbar.Percentage()
                              ]).start()

start = time.time()

server, username, password = ('91.196.5.17', 'den', '555den')
ssh = paramiko.SSHClient()
# paramiko.util.log_to_file('logs/log_filename')
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
# In case the server's key is unknown,
# we will be adding it automatically to the list of known hosts
ssh.load_host_keys(os.path.expanduser(
    os.path.join("~", ".ssh", "known_hosts")))
# Loads the user's local known host file.
ssh.connect(server, username=username, password=password)
ssh_stdin, ssh_stdout, ssh_stderr = ssh.exec_command(
    'cd /mera/billing/; ls -t|head -1;')
# print "output", ssh_stdout.read()  # Reading output of the executed command

result = ssh_stdout.read().strip()
remote_path = '/mera/billing/' + result
local_path = "/tmp/full_tmp.log"
sftp = ssh.open_sftp()

file_size = sftp.lstat(remote_path)
sftp.get(remote_path, local_path, callback=loadProgress)
print'\n'
error = ssh_stderr.read()

finish = time.time()
print 'Получено:', sizeof_fmt(file_size.st_size), 'за %d' % (finish - start), 'секунд'
print'\n'

test3filehandle = open(
    "/home/agent/virtualenvs/loganalyzer/app/full_diff_file.txt", "w")
                       # creating a file handle to write
old_lines = file(local_path).read().split('\n')
new_lines = file(
    "/home/agent/virtualenvs/loganalyzer/app/logs/tmp.log").read().split('\n')

old_lines_set = set(old_lines)
new_lines_set = set(new_lines)

old_added = old_lines_set - new_lines_set
old_removed = new_lines_set - old_lines_set
print 'Парсинг файла ...'
for line in old_lines:
    if line in old_added:
        # print '-', line
        test3filehandle.write(line + '\n')
    # elif line in old_removed:
        # print '+', line


for line in new_lines:
    if line in old_added:
        # print '-',line
        test3filehandle.write(line + '\n')
    # elif line in old_removed:
        # print '+', line

# New lines block end
# считываем файл как массив строк
out = commands.getoutput(
    "cat /home/agent/virtualenvs/loganalyzer/app/full_diff_file.txt | wc -l")

print 'Найдено ' + out + ' новых строк'

ins = open("/home/agent/virtualenvs/loganalyzer/app/full_diff_file.txt", "r")
lines = ins.xreadlines()

engine = create_engine(
    'mysql+mysqldb://root:11235813@localhost/mera_logs', echo=False)

meta = MetaData(engine)
logs = Table('Logs', meta, autoload=True)

i = logs.insert()

for line in lines:
        newline = line.rstrip('\n')

        mydictionary = dict(re.findall(r'([^,\s]+)=([^,]+)', newline))

        if mydictionary.get('SETUP-TIME') is not None and mydictionary.get('CONNECT-TIME') is not None and mydictionary.get('DISCONNECT-TIME') is not None:
            setup_time, zone, day_of_week, setup_date = (
                mydictionary.get('SETUP-TIME').split(' ', 3))
            connect_time, zone, day_of_week, connect_date = (
                mydictionary.get('CONNECT-TIME').split(' ', 3))
            disconnect_time, zone, day_of_week, disconnect_date = (
                mydictionary.get('DISCONNECT-TIME').split(' ', 3))

        sd = datetime.datetime.strptime(setup_date, "%b %d %Y")
        setup_time_date = sd.strftime('%Y-%m-%d')
        
        st = datetime.datetime.strptime(setup_time, "%H:%M:%S.000")
        setup_time_time = st.strftime('%H:%M:%S')

        cd = datetime.datetime.strptime(connect_date, "%b %d %Y")
        connect_time_date = cd.strftime('%Y-%m-%d')

        ct = datetime.datetime.strptime(connect_time, "%H:%M:%S.000")
        connect_time_time = ct.strftime('%H:%M:%S')

        dd = datetime.datetime.strptime(disconnect_date, "%b %d %Y")
        disconnect_time_date = dd.strftime('%Y-%m-%d')

        dt = datetime.datetime.strptime(disconnect_time, "%H:%M:%S.000")
        disconnect_time_time = dt.strftime('%H:%M:%S')
        
        mydictionary.update(
            {'SETUP_TIME_TIME': setup_time_time, 'SETUP_TIME_DATE': setup_time_date,
             'CONNECT_TIME_TIME': connect_time_time, 'CONNECT_TIME_DATE': connect_time_date, 'DISCONNECT_TIME_TIME': disconnect_time_time,
             'DISCONNECT_TIME_DATE': disconnect_time_date, 'TIMESTAMP':setup_time_date +" "+setup_time_time})

        if mydictionary.get('CALLID') is not None and mydictionary.get('PDD-REASON') is not None:
            i.execute(mydictionary)

print 'Записано в базу ' + out + ' строк успешно.'
shutil.move(local_path, '/home/agent/virtualenvs/loganalyzer/app/logs/tmp.log')
os.remove('/home/agent/virtualenvs/loganalyzer/app/full_diff_file.txt')
