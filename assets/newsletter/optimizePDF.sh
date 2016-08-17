#!/bin/bash
#Requires Ghostscript
#For further compression, try adding '-dPDFSETTINGS=/screen' (optimize for viewing on a screen)

in=$1
fname=`basename $in .pdf`
out="${fname}_opt.pdf"
gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4  -dNOPAUSE -dQUIET -dBATCH -sOutputFile=$out $in
