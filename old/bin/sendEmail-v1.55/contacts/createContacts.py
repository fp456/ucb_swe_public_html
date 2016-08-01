#!/usr/bin/python
import sys, csv

contactIn = sys.argv[1] # "Contact Info" csv from the Google Doc
# funIn = sys.argv[2] # "Fun Questions" csv from the Google Doc
outDir = sys.argv[2] # Desired output directory


top = "<!--#include virtual=\"/header_footer/header.html\" -->\n"
bottom = "\n\n<!--#include virtual=\"/header_footer/footer.html\" -->\n"

contactFile = csv.reader(open(contactIn, 'r'), delimiter=",", quotechar="\"")
contactColNames = contactFile.next()
cNameInd = contactColNames.index("Name")
cMailInd = contactColNames.index("Preferred Email")
cMajorInd = contactColNames.index("Major/Year")
print "cmailind", cMailInd

outFile = open("contacts.shtml", 'w')
outFile.write(top)
outFile.write("<div class=\"contacts\">\n<h2 class=\"title\">2012-2013 Contact Information</h2>\n" + \
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
        tmp = open("./headshots/" + fname + ".jpg", 'rb')
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
    print "finished", l[cNameInd]
outFile.write("</div>\n")# contacts div
outFile.write(bottom)
outFile.close()
