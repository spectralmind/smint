#!/bin/bash
# online resources:
# http://steve-parker.org/sh/sh.shtml
# http://www.freeos.com/guides/lsst/
# http://www.ss64.com/bash/

echo "Extracting mp3 file ID3 tags ... "

# if less than 1 argument is provided, display info
if [ $# -lt 1 ]; then
  echo "please provide input path as parameter"
  exit
fi

# only filename 
#find "$1" -name *.mp3 -exec mp3info2 -p "\"%f\"\t\"%a\"\t\"%t\"\t\"%l\"\t\"%g\"\t\"%y\"\n" '{}' >> id3.txt \;

# filename with path
find "$1" -name *.mp3 -exec mp3info2 -p "\"%F\"\t\"%a\"\t\"%t\"\t\"%l\"\t\"%g\"\t\"%y\"\n" '{}' >> id3.txt \;


# filename with path
# for werzowa title taken from id3v1 
# find "$1" -name *.mp3 -exec mp3info2 -C title=ID3v1 -p "\"%F\"\t\"%a\"\t\"%t\"\t\"%l\"\t\"%g\"\t\"%y\"\n" '{}' >> id3.txt \;
