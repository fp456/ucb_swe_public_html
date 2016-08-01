#!/usr/bin/python
import sys, csv

funIn = sys.argv[1]
outDir = sys.argv[2]
top = "<!--#include virtual=\"/header_footer/header.html\" -->\n"
bottom = "\n\n<!--#include virtual=\"/header_footer/footer.html\" -->\n"
major = dict([]) 
funFile = csv.reader(open(funIn, 'r'), delimiter=",", quotechar="\"")
funColNames = map(lambda s: s.split('(')[0].strip(), funFile.next())
for l in funFile:
  print l
  print "-------------"
  officer =  l
  rawMail = l[1]
  mail = "<script type=\"text/javascript\">mail2('" + \
  rawMail.split("@")[0] + "', '" + \
  rawMail.split("@")[1] + "', " + \
  "'-1', '', '" + \
  str.replace(str.replace(l[1], "@", "[at]"), ".", "[dot]") + "'"  + \
  ")</script>"
  outFile = open(outDir + '/' + '_'.join(str.lower(l[0]).split()) + ".shtml", 'w')
  fname = '_'.join(str.lower(officer[0]).split())
  name = officer[0]
  outFile.write(top)
  outFile.write("<div class=\"individual\">\n")
  outFile.write("<h2 class=\"title\">" + officer[0] + "</h2>\n")
  imgFile = "/contacts/headshots/" + fname + ".jpg"
  funnyImg = imgFile.split('.')[0] + "_funny." + imgFile.split('.')[1]
  try:
    tmp = open("./headshots/" + fname + ".jpg", 'rb')
    tmp.close()
  except:
    imgFile ="/contacts/headshots/blank_fb.jpg" 
    funnyImg = imgFile.split('.')[0] + "_funny." + imgFile.split('.')[1]
  img = "<a href=\"/contacts/individual/" + fname + ".shtml\">"+\
        "<img src='" + imgFile + "' onmouseover=\"this.src='" + funnyImg + "';\" onmouseout=\"this.src='" + imgFile + "';\"></a>"
  if name in ("Amy Herr", "Kristen Salinas"):
        img = "<a><img src=\"" + imgFile + "\"></a>"
  # try:
  outFile.write("<p>"+img+"</p>\n")
  outFile.write("<h3>Email:</h3>\n<p>" + mail + "</p>\n")
  outFile.write("<h3>Hometown:</h3>\n<p>" + officer[2] + "</p>\n")
  outFile.write("<h3>About:</h3>\n<p>" + officer[3] + "</p>\n")
  # except:
  #   continue

  # # for k in range(1, len(funColNames)):#skip the name
  # #   if (k > len(l) - 1):
  # #     l.insert(k,"")

  # #   if not funColNames[k].split()[0].strip() == "Best":
  # #     continue
  # #   if funColNames[k].split()[0].strip() in ("Hometown","About"):
  # #     continue

  # # outFile.write("<h3>" + funColNames[k] + ":</h3>\n")
  # # outFile.write("<p>\n")
  # outFile.write(l[k] + "\n")
  outFile.write("</div>\n")# individual div
  outFile.write(bottom)