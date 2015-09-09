#!/bin/bash

echo This script clears the SMINT cache.
echo 1- ./symfony cc
echo 2- Remove pregenerated waveform files and converted audio files

./symfony cc
rm -f web/uploads/mp3uploads/*waveform.txt
rm -f web/uploads/mp3uploads/*ogg
rm -f web/uploads/mp3uploads/*mp3
