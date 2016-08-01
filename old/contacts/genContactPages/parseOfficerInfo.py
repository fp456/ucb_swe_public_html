#!/usr/bin/python
import sys, csv

contactIn = sys.argv[1] # "Contact Info" csv from the Google Doc
funIn = sys.argv[2] # "Fun Questions" csv from the Google Doc
outDir = sys.argv[3] # Desired output directory


top = "<!--#include virtual=\"/header_footer/header.html\" -->\n"
bottom = "\n\n<!--#include virtual=\"/header_footer/footer.html\" -->\n"

contactFile = csv.reader(open(contactIn, 'r'), delimiter=",", quotechar="\"")
contactColNames = contactFile.next()
cNameInd = contactColNames.index("Name")
cMailInd = contactColNames.index("Preferred Email")
cMajorInd = contactColNames.index("Major/Year")

outFile = open("contacts.shtml", 'w')
outFile.write(top)
outFile.write("<div class=\"contacts\">\n<h2 class=\"title\">2011-2012 Contact Information</h2>\n" + \
                  "<p>\n" + \
                  "Please email <script type=\"text/javascript\">mail2('swe.berkeley', 'gmail', '0', '', 'swe.berkeley[at]gmail.com')</script>\n" + \
                  "if you have any questions that you wish to direct to all officers. " + \
                  "Click on the photograph to learn more about the officer.\n"+\
                  "</p>\n")

major = dict([])#originally for major, now stores more
prev = None
for l in contactFile:
    if len(l) == 0:
        continue
    if len(l) == 1:
        sub = l[0]
        if sub == "Execs":
            sub = "Executives"
        outFile.write("<h3>" + sub + "</h3>\n")
        continue
    
    name = l[cNameInd]
    fname = '_'.join(str.lower(name).split())
    #mail = str.replace(str.replace(l[cMailInd], "@", "[at]"), ".", "[dot]")
    rawMail = l[cMailInd]
    mail = "<script type=\"text/javascript\">mail2('" + \
        rawMail.split("@")[0] + "', '" + \
        rawMail.split("@")[1] + "', " + \
        "'-1', '', '" + \
        str.replace(str.replace(l[cMailInd], "@", "[at]"), ".", "[dot]") + "'"  + \
        ")</script>"
    pos = l[0]
    if pos == "":
        pos = prev[0]#two people, one position
    if pos =="Career and Collegiate Development":
        pos = "Career Development"#shorten
    if pos == "EWI Coordinators":
        pos = "EWI Coordinator"
    imgFile = "/contacts/headshots/" + fname + ".jpg"
    try:
        tmp = open("../headshots/" + fname + ".jpg", 'rb')
        tmp.close()
    except:
        imgFile ="/contacts/headshots/blank_fb.jpg" 
    funnyImg = imgFile.split('.')[0] + "_funny." + imgFile.split('.')[1]
    img = "<a href=\"/contacts/individual/" + fname + ".shtml\">"+\
        "<img src='" + imgFile + "' onmouseover=\"this.src='" + funnyImg + "';\" onmouseout=\"this.src='" + imgFile + "';\"></a>"
    if name in ("Amy Herr", "Kristen Salinas"):
        img = "<a><img src=\"" + imgFile + "\"></a>"

    outFile.write("\n<div class=\"contact\">\n" +\
                      "<h4>" + pos + "</h4>\n" +\
                      "<p>\n" +\
                      name + ":\n" +\
                      mail + "<br />\n"  +\
                      img  + "\n"  +\
                      "</p>\n" +\
                      "</div>\n")
    prev = l
    maj = ""
    try:
        maj = l[cMajorInd]
    except:
        maj = ""
    major[name] = (mail, maj, img)
outFile.write("</div>\n")# contacts div
outFile.write(bottom)
outFile.close()


funFile = csv.reader(open(funIn, 'r'), delimiter=",", quotechar="\"")
funColNames = map(lambda s: s.split('(')[0].strip(), funFile.next())
for l in funFile:
    officer =  l[0]
    outFile = open(outDir + '/' + '_'.join(str.lower(l[0]).split()) + ".shtml", 'w')
    outFile.write(top)
    outFile.write("<div class=\"individual\">\n")
    outFile.write("<h2 class=\"title\">" + officer + "</h2>\n")
    try:
        outFile.write("<h3></h3>\n<p>" + major[officer][2] + "</p>\n")
        outFile.write("<h3>Email:</h3>\n<p>" + major[officer][0] + "</p>\n")
        outFile.write("<h3>Major/Year:</h3>\n<p>" + major[officer][1] + "</p>\n")
    except:
        continue

    for k in range(1, len(funColNames)):#skip the name
        if (k > len(l) - 1):
            l.insert(k,"")

        if not funColNames[k].split()[0].strip() == "Best":
            continue
        if funColNames[k].split()[0].strip() in ("What", "When", "Comments", "Hometown"):
            continue

        outFile.write("<h3>" + funColNames[k] + ":</h3>\n")
        outFile.write("<p>\n")
        outFile.write(l[k] + "\n")
        outFile.write("</p>\n\n")
    outFile.write("</div>\n")# individual div
    outFile.write(bottom)
