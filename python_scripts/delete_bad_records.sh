#!/bin/bash

/usr/bin/curl -XDELETE 'http://localhost:9200/logs_index/log-type/_query?q=dst_ip:0.0.0.0'
