from datetime import datetime
import sys, os
import Global_var


def create_HTML_File(TED_FIELD):
    Loop = True
    while Loop == True:
        try:
            Html_wala_Tag = "<table align=\"center\" border=\"1\" style=\"width:98%;border-spacing:0;border-collapse: collapse;border:1px solid #666666; margin-top:5px; margin-bottom:5px;\">" + \
                            "<tr><td colspan=\"2\"; style=\"background-color:#146faf; font-weight: bold; padding:7px;border-bottom:1px solid #666666; color:white;\">Tender Details</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">Tender ID </td><td style=\"padding:7px;\">" + str(TED_FIELD[4]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">Title </td><td style=\"padding:7px;\">" + str(TED_FIELD[3]) + "</td></tr>" + \
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
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">TD_DOCUMENT_TYPE</td><td style=\"padding:7px;\">" + str(TED_FIELD[22]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">RP_REGULATION</td><td style=\"padding:7px;\">" + str(TED_FIELD[23]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">TY_TYPE_BID</td><td style=\"padding:7px;\">" + str(TED_FIELD[24]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">AC_AWARD_CRIT</td><td style=\"padding:7px;\">" + str(TED_FIELD[25]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">CPV_CODE</td><td style=\"padding:7px;\">" + str(TED_FIELD[26]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">NUTS</td><td style=\"padding:7px;\">" + str(TED_FIELD[27]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">IA_URL_GENERAL</td><td style=\"padding:7px;\">" + str(TED_FIELD[28]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">URL_BUYER</td><td style=\"padding:7px;\">" + str(TED_FIELD[33]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">DIRECTIVE</td><td style=\"padding:7px;\">" + str(TED_FIELD[29]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">DESCRIPTION</td><td style=\"padding:7px;\">" + str(TED_FIELD[30]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">SHORT_DESCRIPTION</td><td style=\"padding:7px;\">" + str(TED_FIELD[31]) + "</td></tr>" + \
                            "<tr bgcolor=\"#ffffff\" onmouseover=\"this.style.backgroundColor='#def3ff'\" onmouseout=\"this.style.backgroundColor=''\"><td style=\"padding:7px;\">Tender Link </td><td style=\"padding:7px;\">""<a href=" + str(TED_FIELD[32]) + \
                            " target=\"_blank\">View</a>""</td></tr>" + "</tr></table>"
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