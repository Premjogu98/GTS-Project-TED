import string
import lxml.etree
from bs4 import BeautifulSoup
import string
import re
from datetime import datetime
import sys, os
import os
from lxml import etree
import Global_var
import html
from translate import Scrap_data


def Xml_TO_HTMLfile():
    Loop = True
    while Loop == True:
        try:
            test = []
            path = 'D:\\PycharmProjects\\TED_Project\\TED XML FILE'
            for filename in os.listdir(path):
                if not filename.endswith('.xml'): continue
                fullname = os.path.join(path, filename)
                tree = etree.parse(fullname)
                test.append(tree)
            print("XML File Collected :", len(test))
            XML_text = ''
            path = 'D:\\PycharmProjects\\TED_Project\\TED XML FILE'
            # path = 'D:\\'
            a = 0
            for filename in os.listdir(path):
                if not filename.endswith('.xml'): continue
                fullname = os.path.join(path, filename)
                tree = etree.parse(fullname)
                # print(tree)
                XML_text = etree.tostring(tree, pretty_print=True)
                XML_text = str(XML_text).replace('\\n', '')
                # print(XML_text)
                XML_text = html.unescape(str(XML_text))
                TED_FIELD = []
                for data in range(34):
                    TED_FIELD.append('')
                # ======================================================================================================

                TD_DOCUMENT_TYPE = str(XML_text).partition("<TD_DOCUMENT_TYPE CODE")[2].partition("</TD_DOCUMENT_TYPE>")[0].strip()
                TD_DOCUMENT_TYPE = str(TD_DOCUMENT_TYPE).partition(">")[2].partition("<")[0].strip()
                # print(TD_DOCUMENT_TYPE)
                a += 1
                print(a, "TD_DOCUMENT_TYPE: "+TD_DOCUMENT_TYPE)
                TED_FIELD[1] = TD_DOCUMENT_TYPE

                # ======================================================================================================

                Document_Language = str(XML_text).partition("<LG_ORIG>")[2].partition("</LG_ORIG>")[0].strip()  # Document Language
                # print(Document_Language)
                a += 1
                print(a, "Document_Language: " + Document_Language)
                TED_FIELD[2] = Document_Language

                # ======================================================================================================

                ML_TI_DOC = str(XML_text).partition('<ML_TI_DOC LG="' + str(Document_Language) + '">')[2].partition("</ML_TI_DOC>")[0].strip()  # Title
                ML_TI_DOC = str(ML_TI_DOC).partition("<TI_TEXT>")[2].partition("</TI_TEXT>")[0].strip()
                clean = re.compile('<.*?>')
                ML_TI_DOC = re.sub(clean, '', ML_TI_DOC)  # Remove HTML
                # print(ML_TI_DOC)

                if ML_TI_DOC == "":
                    ML_TI_DOC = str(XML_text).partition('<ML_TI_DOC LG="EN">')[2].partition("</ML_TI_DOC>")[0].strip()  # Title
                    ML_TI_DOC = str(ML_TI_DOC).partition("<TI_TEXT>")[2].partition("</TI_TEXT>")[0].strip()
                    clean = re.compile('<.*?>')
                    ML_TI_DOC = re.sub(clean, '', ML_TI_DOC)  # Remove HTML
                    # print(ML_TI_DOC)
                a += 1
                print(a, "ML_TI_DOC: " + ML_TI_DOC)
                TED_FIELD[3] = ML_TI_DOC

                # ======================================================================================================

                DOC_ID = str(XML_text).partition('DOC_ID="')[2].partition('"')[0].strip()  # Document ID
                # print(DOC_ID)
                a += 1
                print(a, "DOC_ID: " + DOC_ID)
                TED_FIELD[4] = DOC_ID

                # ======================================================================================================

                DATE_PUB = str(XML_text).partition('<DATE_PUB>')[2].partition('</DATE_PUB>')[0].strip()  # Document ID
                DATE_PUB = datetime.strptime(DATE_PUB, '%Y%m%d')
                DATE_PUB = DATE_PUB.strftime("%Y-%m-%d")
                # Year = DATE_PUB[0:4]
                # Month = DATE_PUB[4:6]
                # Day = DATE_PUB[6:8]
                # DATE_PUB = Year+"-"+Month+"-"+Day
                # print(DATE_PUB)
                a += 1
                print(a, "DATE_PUB: " + DATE_PUB)
                TED_FIELD[5] = DATE_PUB

                # ======================================================================================================

                NO_OJ = str(XML_text).partition('<NO_OJ>')[2].partition('</NO_OJ>')[0].strip()  # NO_OJ
                # print(NO_OJ)
                a += 1
                print(a, "NO_OJ: " + NO_OJ)
                TED_FIELD[6] = NO_OJ

                # ======================================================================================================

                Details = str(XML_text).partition('" LG="' + str(Document_Language) + '"')[2].partition("</OBJECT_CONTRACT>")[0].strip()  # Details
                if Details == "":
                    Details = str(XML_text).partition('LG="EN" FORM')[2].partition("</CONTRACTING_BODY>")[0].strip()  # Details
                if Details == "":
                    Details = str(XML_text).partition('" LG="EN"')[2].partition("</CONTRACTING_BODY>")[0].strip()

                TOWN = str(Details).partition("<TOWN>")[2].partition("</TOWN>")[0].strip()  # TOWN
                a += 1
                print(a, "TOWN: " + TOWN)
                TED_FIELD[7] = TOWN

                # ======================================================================================================

                OFFICIALNAME = str(Details).partition("<OFFICIALNAME>")[2].partition("</OFFICIALNAME>")[0].strip()  # OFFICIALNAME
                if OFFICIALNAME == "":
                    OFFICIALNAME = str(Details).partition("<ORGANISATION>")[2].partition("</ORGANISATION>")[0].strip()
                a += 1
                print(a, "OFFICIALNAME: " + OFFICIALNAME)
                TED_FIELD[8] = OFFICIALNAME

                # ======================================================================================================

                ADDRESS = str(Details).partition('<ADDRESS>')[2].partition("</ADDRESS>")[0].strip()
                if ADDRESS == "":
                    ADDRESS = str(Details).partition('<BLK_BTX>')[2].partition("</BLK_BTX>")[0].lstrip(',').strip()
                # print(ADDRESS)
                a += 1
                print(a, "ADDRESS: " + ADDRESS)
                TED_FIELD[9] = ADDRESS

                # ======================================================================================================

                POSTAL_CODE = str(Details).partition('<POSTAL_CODE>')[2].partition("</POSTAL_CODE>")[0].strip()  # POSTAL_CODE
                # print(POSTAL_CODE)
                a += 1
                print(a, "POSTAL_CODE: " + POSTAL_CODE)
                TED_FIELD[10] = POSTAL_CODE

                # ======================================================================================================

                CONTACT_POINT = str(Details).partition('<CONTACT_POINT>')[2].partition("</CONTACT_POINT>")[0].strip()  # CONTACT_POINT
                # print(CONTACT_POINT)
                a += 1
                print(a, "CONTACT_POINT: " + CONTACT_POINT)
                TED_FIELD[11] = CONTACT_POINT

                # ======================================================================================================

                ATTENTION = str(Details).partition('<ATTENTION>')[2].partition("</ATTENTION>")[0].strip()  # ATTENTION
                # print(ATTENTION)
                a += 1
                print(a, "ATTENTION: " + ATTENTION)
                TED_FIELD[12] = ATTENTION

                # ======================================================================================================

                PHONE = str(Details).partition('<PHONE>')[2].partition("</PHONE>")[0].strip()  # PHONE
                # print(PHONE)
                a += 1
                print(a, "PHONE: " + PHONE)
                TED_FIELD[13] = PHONE

                # ======================================================================================================

                E_MAIL = str(Details).partition('<E_MAIL>')[2].partition("</E_MAIL>")[0].strip()  # PHONE
                # print(E_MAIL)
                a += 1
                print(a, "E_MAIL: " + E_MAIL)
                TED_FIELD[14] = E_MAIL

                # ======================================================================================================

                FAX = str(Details).partition('<FAX>')[2].partition("</FAX>")[0].strip()  # PHONE
                # print(FAX)
                a += 1
                print(a, "FAX: " + FAX)
                TED_FIELD[15] = FAX

                # ======================================================================================================

                LG = Document_Language  # Document Main Language
                # print(LG)
                a += 1
                print(a, "LG: " + LG)
                TED_FIELD[16] = LG

                # ======================================================================================================

                COUNTRY = str(XML_text).partition('<ISO_COUNTRY')[2].partition(">")[0].strip()  # PHONE
                COUNTRY = str(COUNTRY).partition('"')[2].partition('"')[0].strip()
                # print(COUNTRY)
                a += 1
                print(a, "COUNTRY: " + COUNTRY)
                TED_FIELD[17] = COUNTRY

                # ======================================================================================================

                ML_TI_DOC = str(XML_text).partition("<AA_AUTHORITY_TYPE")[2].partition("</AA_AUTHORITY_TYPE>")[0].strip()
                ML_TI_DOC = str(ML_TI_DOC).partition(">")[2].strip()
                # print(ML_TI_DOC)
                a += 1
                print(a, "ML_TI_DOC: " + ML_TI_DOC)
                TED_FIELD[18] = ML_TI_DOC

                # ======================================================================================================

                DS_DATE_DISPATCH = str(XML_text).partition('<DS_DATE_DISPATCH>')[2].partition("</DS_DATE_DISPATCH>")[0].strip()  # DS_DATE_DISPATCH
                DS_DATE_DISPATCH = datetime.strptime(DS_DATE_DISPATCH, '%Y%m%d')
                DS_DATE_DISPATCH = DS_DATE_DISPATCH.strftime("%Y-%m-%d")
                # print(DS_DATE_DISPATCH)
                a += 1
                print(a, "DS_DATE_DISPATCH: " + DS_DATE_DISPATCH)
                TED_FIELD[19] = DS_DATE_DISPATCH

                # ======================================================================================================

                NC_CONTRACT_NATURE = str(XML_text).partition("<NC_CONTRACT_NATURE")[2].partition("</NC_CONTRACT_NATURE>")[0].strip()
                NC_CONTRACT_NATURE = str(NC_CONTRACT_NATURE).partition(">")[2].strip()
                # print(NC_CONTRACT_NATURE)
                a += 1
                print(a, "NC_CONTRACT_NATURE: " + NC_CONTRACT_NATURE)
                TED_FIELD[20] = NC_CONTRACT_NATURE

                # ======================================================================================================

                PR_PROC = str(XML_text).partition("<PR_PROC")[2].partition("</PR_PROC>")[0].strip()
                PR_PROC = str(PR_PROC).partition(">")[2].strip()
                # print(PR_PROC)
                a += 1
                print(a, "PR_PROC: " + PR_PROC)
                TED_FIELD[21] = PR_PROC

                # ======================================================================================================

                # TD_DOCUMENT_TYPE = str(XML_text).partition("<TD_DOCUMENT_TYPE")[2].partition("</TD_DOCUMENT_TYPE>")[0].strip()
                # TD_DOCUMENT_TYPE = str(TD_DOCUMENT_TYPE).partition(">")[2].strip()
                # # print(TD_DOCUMENT_TYPE)
                # a += 1
                # print(a, "TD_DOCUMENT_TYPE: " + TD_DOCUMENT_TYPE)
                # TED_FIELD[22] = TD_DOCUMENT_TYPE

                URL_BUYER = str(XML_text).partition('<URL_BUYER>')[2].partition("</URL_BUYER>")[0].strip()
                a += 1
                print(a, "URL_BUYER: " + URL_BUYER)
                TED_FIELD[22] = URL_BUYER
                # ======================================================================================================

                RP_REGULATION = str(XML_text).partition("<RP_REGULATION")[2].partition("</RP_REGULATION>")[0].strip()
                RP_REGULATION = str(RP_REGULATION).partition(">")[2].strip()
                # print(RP_REGULATION)
                a += 1
                print(a, "RP_REGULATION: " + RP_REGULATION)
                TED_FIELD[23] = RP_REGULATION

                # ======================================================================================================

                TY_TYPE_BID	= str(XML_text).partition("<TY_TYPE_BID")[2].partition("</TY_TYPE_BID>")[0].strip()
                TY_TYPE_BID	= str(TY_TYPE_BID).partition(">")[2].strip()
                # print(TY_TYPE_BID)
                a += 1
                print(a, "TY_TYPE_BID: " + TY_TYPE_BID)
                TED_FIELD[24] = TY_TYPE_BID

                # ======================================================================================================

                AC_AWARD_CRIT = str(XML_text).partition("<AC_AWARD_CRIT")[2].partition("</AC_AWARD_CRIT>")[0].strip()
                AC_AWARD_CRIT = str(AC_AWARD_CRIT).partition(">")[2].strip()
                # print(AC_AWARD_CRIT)
                a += 1
                print(a, "AC_AWARD_CRIT: " + AC_AWARD_CRIT)
                TED_FIELD[25] = AC_AWARD_CRIT

                # ======================================================================================================
                CPV_CODE = ''
                final_cpv_list = []
                cpv_list = []
                Proper_cpv = ''
                count_string = XML_text.count('CPV_CODE CODE')
                if count_string > 1:
                    count_string = XML_text.count('CPV_CODE CODE')  # How many Times This(ORIGINAL_CPV CODE) String Came
                    for cpv in range(count_string):
                        CPV_CODE = str(XML_text).partition("<CPV_CODE CODE=\"")[2].partition('"/>')[0].strip()
                        cpv_list.append(CPV_CODE)
                        XML_text = str(XML_text).replace('<CPV_CODE CODE="' + str(CPV_CODE) + '"', '')
                    for num in cpv_list:
                        if num not in final_cpv_list:  # Remove Duplicate CPV From LIST
                            final_cpv_list.append(num)
                    for c in final_cpv_list:
                        Proper_cpv = Proper_cpv + c + ','
                    CPV_CODE = Proper_cpv.rstrip(',')
                else:
                    CPV_CODE = str(XML_text).partition("<CPV_MAIN>")[2].partition("</CPV_MAIN>")[0].strip()
                    CPV_CODE = str(CPV_CODE).partition('"')[2].partition('"')[0].strip()
                # print(CPV_CODE)

                if CPV_CODE == '':
                    count_string = XML_text.count('ORIGINAL_CPV CODE') or XML_text.count('ORIGINAL_CPV') # How many Times This(ORIGINAL_CPV CODE) String Came
                    for cpv in range(count_string):
                        CPV_CODE = str(XML_text).partition("<ORIGINAL_CPV CODE=")[2].partition("</ORIGINAL_CPV>")[0].strip()
                        CPV_CODE = str(CPV_CODE).partition('"')[2].partition('">')[0].strip()
                        cpv_list.append(CPV_CODE)
                        XML_text = str(XML_text).replace('<ORIGINAL_CPV CODE="' + str(CPV_CODE) + '"', '')
                    for num in cpv_list:
                        if num not in final_cpv_list:  # Remove Duplicate CPV From LIST
                            final_cpv_list.append(num)
                    for c in final_cpv_list:
                        Proper_cpv = Proper_cpv + c + ','
                    CPV_CODE = Proper_cpv.rstrip(',')
                a += 1
                print(a, "CPV_CODE: " + CPV_CODE)
                TED_FIELD[26] = CPV_CODE

                # ======================================================================================================

                NUTS = str(Details).partition("<ORIGINAL_NUTS CODE")[2].partition("</ORIGINAL_NUTS>")[0].strip()
                NUTS = str(NUTS).partition('>')[2].strip()
                if NUTS == "":
                    NUTS = str(Details).partition('NUTS CODE="')[2].partition('"')[0].strip()
                # print(NUTS)
                a += 1
                print(a, "NUTS: " + NUTS)
                TED_FIELD[27] = NUTS

                # ======================================================================================================

                IA_URL_GENERAL = str(XML_text).partition("<IA_URL_GENERAL>")[2].partition("</IA_URL_GENERAL>")[0].strip()
                # print(IA_URL_GENERAL)
                a += 1
                print(a, "IA_URL_GENERAL: " + IA_URL_GENERAL)
                TED_FIELD[28] = IA_URL_GENERAL

                # ======================================================================================================

                DIRECTIVE = str(XML_text).partition("<DIRECTIVE")[2].partition(">")[0].replace('VALUE', '').replace('"/', '').replace('"', '').replace('=', '').strip()
                # print(DIRECTIVE)
                a += 1
                print(a, "DIRECTIVE: " + DIRECTIVE)
                TED_FIELD[29] = DIRECTIVE

                # ======================================================================================================

                SHORT_DESCR_Document = str(Details).partition("<SHORT_DESCR>")[2].partition("</SHORT_DESCR>")[0].strip()
                a += 1
                print(a, "SHORT_DESCR_Document: " + SHORT_DESCR_Document)
                TED_FIELD[30] = SHORT_DESCR_Document

                # ======================================================================================================

                DOC_URL = str(XML_text).partition('<URI_DOC LG="'+str(Document_Language)+'">')[2].partition("</URI_DOC>")[0].strip()
                a += 1
                print(a, "DOC_URL: " + DOC_URL)
                TED_FIELD[32] = DOC_URL
                # print(SHORT_DESCR)

                # ======================================================================================================




                # print(SHORT_DESCR)

                # ======================================================================================================

                SHORT_DESCR_Insert = SHORT_DESCR_Document.replace('<P>', '').replace('</P>','')
                SHORT_DESCR_Insert = re.sub(' +', ' ', SHORT_DESCR_Insert)
                clean = re.compile('<.*?>')
                SHORT_DESCR_Insert = re.sub(clean, '', SHORT_DESCR_Insert)  # Remove HTML
                # print(SHORT_DESCR_Insert)
                # for TED_data in range(len(TED_FIELD)):
                #     print(TED_data, end=' ')
                #     print(TED_FIELD[TED_data])

                # ======================================================================================================

                # create_HTML_File(TED_FIELD)
                # Scrap_data(TED_FIELD)
                Loop = False
                a = 0
                print('\n============================================================================================================================\n')
            print('All Process Done')
            quit()
        except Exception as e:
            exc_type, exc_obj, exc_tb = sys.exc_info()
            fname = os.path.split(exc_tb.tb_frame.f_code.co_filename)[1]
            print("Error ON : ", sys._getframe().f_code.co_name + "--> " + str(e), "\n", exc_type, "\n",fname, "\n", exc_tb.tb_lineno)
            Loop = True


def create_HTML_File(TED_FIELD):
    Loop = True
    while Loop == True:
        try:
            Html_wala_Tag = "<table align=\"center\" border=\"1\" style=\"width:98%;border-spacing:0;border-collapse: collapse;border:1px solid #666666; margin-top:5px; margin-bottom:5px;\">" + \
                            "<tr><td colspan=\"2\"; style=\"background-color:#146faf; font-weight: bold; padding:7px;border-bottom:1px solid #666666; color:white;\">Tender Details</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">TD_DOCUMENT_TYPE</td><td style=\"padding:7px;\">" + str(TED_FIELD[1]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">Document_Language</td><td style=\"padding:7px;\">" + str(TED_FIELD[2]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">ML_TI_DOC</td><td style=\"padding:7px;\">" + str(TED_FIELD[3]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">DOC_ID</td><td style=\"padding:7px;\">" + str(TED_FIELD[4]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">DATE_PUB</td><td style=\"padding:7px;\">" + str(TED_FIELD[5]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">NO_OJ</td><td style=\"padding:7px;\">" + str(TED_FIELD[6]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">TOWN</td><td style=\"padding:7px;\">" + str(TED_FIELD[7]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">OFFICIALNAME</td><td style=\"padding:7px;\">" + str(TED_FIELD[8]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">ADDRESS</td><td style=\"padding:7px;\">" + str(TED_FIELD[9]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">POSTAL_CODE</td><td style=\"padding:7px;\">" + str(TED_FIELD[10]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">CONTACT_POINT</td><td style=\"padding:7px;\">" + str(TED_FIELD[11]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">ATTENTION</td><td style=\"padding:7px;\">" + str(TED_FIELD[12]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">PHONE</td><td style=\"padding:7px;\">" + str(TED_FIELD[13]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">E_MAIL</td><td style=\"padding:7px;\">" + str(TED_FIELD[14]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">FAX</td><td style=\"padding:7px;\">" + str(TED_FIELD[15]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">LG</td><td style=\"padding:7px;\">" + str(TED_FIELD[16]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">COUNTRY</td><td style=\"padding:7px;\">" + str(TED_FIELD[17]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">ML_TI_DOC</td><td style=\"padding:7px;\">" + str(TED_FIELD[18]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">DS_DATE_DISPATCH</td><td style=\"padding:7px;\">" + str(TED_FIELD[19]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">NC_CONTRACT_NATURE</td><td style=\"padding:7px;\">" + str(TED_FIELD[20]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">PR_PROC</td><td style=\"padding:7px;\">" + str(TED_FIELD[21]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">URL_BUYER </td><td style=\"padding:7px;\">" + str(TED_FIELD[22]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">RP_REGULATION</td><td style=\"padding:7px;\">" + str(TED_FIELD[23]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">TY_TYPE_BID</td><td style=\"padding:7px;\">" + str(TED_FIELD[24]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">AC_AWARD_CRIT</td><td style=\"padding:7px;\">" + str(TED_FIELD[25]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">CPV_CODE</td><td style=\"padding:7px;\">" + str(TED_FIELD[26]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">NUTS</td><td style=\"padding:7px;\">" + str(TED_FIELD[27]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">IA_URL_GENERAL </td><td style=\"padding:7px;\">" + str(TED_FIELD[28]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">DIRECTIVE</td><td style=\"padding:7px;\">" + str(TED_FIELD[29]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">DESCRIPTION</td><td style=\"padding:7px;\">" + str(TED_FIELD[30]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">SHORT_DESCRIPTION</td><td style=\"padding:7px;\">" + str(TED_FIELD[31]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">Tender Link </td><td style=\"padding:7px;\">""<a href=" + str(TED_FIELD[32]) + " target=\"_blank\">View</a>""</td></tr>" + "</tr></table>"
            HTML_File_String = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\">" + \
                               "<head><link rel=\"shortcut icon\" type=\"image/png\" href=\"https://www.tendersontime.com/favicon.ico\"/><meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\" /><title>Tender Document</title></head>" + \
                               "<body>" + Html_wala_Tag + "</body></html>"
            basename = "PY030"
            Current_dateTime = datetime.now().strftime("%Y%m%d%H%M%S%f")
            Fileid = "".join([basename, Current_dateTime])
            File_path = 'D:\\' + Fileid + '.html'
            file1 = open(File_path, "w", encoding='utf-8')
            file1.write(str(HTML_File_String))
            file1.close()
            Global_var.Total_HTML += 1
            print('HTML File Created: ', str(Global_var.Total_HTML))
            Loop = False
        except Exception as e:
            exc_type, exc_obj, exc_tb = sys.exc_info()
            fname = os.path.split(exc_tb.tb_frame.f_code.co_filename)[1]
            print("Error ON : ", sys._getframe().f_code.co_name + "--> " + str(e), "\n", exc_type, "\n", fname, "\n",
                  exc_tb.tb_lineno)
            Loop = True



Xml_TO_HTMLfile()
