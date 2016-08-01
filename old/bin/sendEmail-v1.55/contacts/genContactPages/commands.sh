#!/bin/sh

./parseOfficerInfo.py contact.csv fun.csv tmp/
cp contacts.shtml ../../contacts.shtml
cp tmp/* ../individual/
