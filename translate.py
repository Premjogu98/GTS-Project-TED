import re
import time
import urllib.request
import urllib.parse
import sys, os
import string
from datetime import datetime
import Global_var
import requests
import html


def Translate(text_without_translate):
    String2 = ""
    try:
        String2 = str(text_without_translate)
        url = "https://translate.google.com/m?hl=en&sl=auto&tl=en&ie=UTF-8&prev=_m&q=" + str(String2) + ""
        response1 = requests.get(str(url))
        response2 = response1.url
        user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36'
        headers = {'User-Agent': user_agent, }
        request = urllib.request.Request(response2, None, headers)  # The assembled request
        time.sleep(1)
        response = urllib.request.urlopen(request)
        htmldata: str = response.read().decode('utf-8')
        time.sleep(1)
        trans_data = re.search(r'(?<=dir="ltr" class="t0">).*?(?=</div>)', htmldata).group(0)
        trans_data = html.unescape(str(trans_data))
        return trans_data
    except:
        return String2


def Scrap_data(TED_FIELD):
    Loop = True
    while Loop == True:
        try:
            SagField = []
            for data in range(42):
                SagField.append('')

            Document_Language = TED_FIELD[2]
            # ======================================================================================================
            SagField[24] = TED_FIELD[5]
            Submission_date = SagField[24]  # Tender Submission Date
            if Submission_date != "":
                # ======================================================================================================

                Email = TED_FIELD[14]  # Email
                SagField[1] = Email

                # ======================================================================================================

                # ======================================================================================================
                # Address
                Contact_Person = ''
                Address = ''
                POSTAL_CODE = ''
                PHONE = ''
                Fax = ''
                if Document_Language != "EN":
                    Contact_Person = TED_FIELD[11]  # Contact_Person
                    Contact_Person = Translate(Contact_Person)
                    Address = TED_FIELD[9]  # Address
                    Address = Translate(Address)
                    POSTAL_CODE = TED_FIELD[10]  # POSTAL_CODE
                    PHONE = TED_FIELD[13]  # PHONE
                    Fax = TED_FIELD[15]
                    Proper_Address = "Contact Person: " + Contact_Person+"<br>\n""Address: " + Address + ', Postal Code: ' + POSTAL_CODE + "<br>\n""Phone: " + PHONE + "Fax: " + Fax
                    SagField[2] = Proper_Address
                else:
                    Proper_Address = "Contact Person: " + Contact_Person + "<br>\n""Address: " + Address + ', Postal Code: ' + POSTAL_CODE + "<br>\n""Phone: " + PHONE + "Fax: " + Fax
                    SagField[2] = Proper_Address
                # ======================================================================================================

                # ======================================================================================================
                # Purchaser
                if Document_Language != "EN":
                    OFFICIALNAME = TED_FIELD[8]
                    OFFICIALNAME = Translate(OFFICIALNAME)
                    SagField[12] = OFFICIALNAME.upper()
                else:
                    OFFICIALNAME = TED_FIELD[8]
                    SagField[12] = OFFICIALNAME.upper()
                # ======================================================================================================

                # ======================================================================================================
                # Tender no
                SagField[13] = TED_FIELD[4]
                # ======================================================================================================

                # ======================================================================================================
                # BUYER URL
                URL_BUYER = TED_FIELD[8]
                SagField[8] = URL_BUYER
                # ======================================================================================================

                # ======================================================================================================
                # Country
                SagField[7] = "EU"
                # ======================================================================================================

                # ======================================================================================================
                # notice type
                SagField[14] = "2"

                SagField[22] = "0"

                SagField[26] = "0.0"

                SagField[27] = "0"  # Financier

                # ======================================================================================================
                # Tender URL
                Tender_URL = TED_FIELD[32]
                SagField[28] = Tender_URL

                # ======================================================================================================
                SagField[31] = "TED"

            else:
                print('DeadLine was Not Given')
            Loop = False
        except Exception as e:
            exc_type, exc_obj, exc_tb = sys.exc_info()
            fname = os.path.split(exc_tb.tb_frame.f_code.co_filename)[1]
            print("Error ON : ", sys._getframe().f_code.co_name + "--> " + str(e), "\n", exc_type, "\n", fname, "\n",
                  exc_tb.tb_lineno)
            Loop = True
