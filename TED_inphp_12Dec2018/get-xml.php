<?php
//header('Content-Type: text/html; charset=UTF-8');
require_once 'inc/global.inc.php';
require_once 'inc/xml-to-html.php';
ini_set('display_errors', '0');
if (isset($_SESSION['folderName'])) {
    //$tendersType = array("Contract award", "Contract notice", "Public works concession", "Dynamic purchasing system", "Design contest" , "Periodic indicative notice (PIN) without call for competition", "Qualification system without call for competition", "Qualification system with call for competition", "Prior Information Notice", "Call for expressions of interest");
    $tendersType = array("Contract award", "Contract notice", "Public works concession", "Dynamic purchasing system", "Design contest","Design contest notice", "Periodic indicative notice (PIN) without call for competition", "Qualification system without call for competition", "Qualification system with call for competition", "Prior Information Notice", "Call for expressions of interest");
    $folderName = $_SESSION['folderName'];
    $files = scandir('upload/' . $folderName);
    // echo "<pre>";
    // print_r($tendersType);
    $mailCheck = false;
    $i = 1;
    $mailData = 'Dear Sir,<br/>Following is the list of skiped files.<br/><br/><table border="1"><tr><th>ID</th><th>DOC TYPE</th><th>TED DOC NO</th><th>PUB DATE</th></tr>';
    foreach ($files as $file) {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        if ($ext == 'xml' || $ext == 'XML') {
            // 'yes<br/>';
            $path = 'upload/' . $folderName . '/' . $file;
            // echo $path."<br/>"; 
            $fileName = substr($file, 0, -4);
            $data = getHtml($path, $db2, $db2);
            // print_r($data);

            $htmData = $data['html'];
            $doc = $data['doc'];
            $file = $data['fileId'];
            $tedId = $data['tedId'];
            $pubDate = $data['pubDate'];
            //echo $htmData;
            if (!empty($htmData)) {
                if (strlen($htmData) > 10) {
                    $my_file = "htmls/" . $fileName . ".html";
                    $handle = fopen($my_file, 'w') or die('Cannot open file:  ' . $my_file);
                    fwrite($handle, $htmData);
                    fclose($handle);
                    echo $i . "-<a target='_blank' href='$my_file'>$my_file</a>----$doc<br/>";
                    //echo "<br/>".$i."<br/>";
                    //echo $htmData;
                    //echo "<hr/>";
                } else {
                    echo $i . "-$file----$doc<br/>";
                }
                $i++;
            } else {
                if (in_array($doc, $tendersType)) {
                    $mailData .= "<tr><td>$file</td><td>$doc</td><td>$tedId</td><td>$pubDate</td></tr>";
                    $mailCheck = true;
                }
            }
            //break;
        }
    }
    $mailData .='</table>';
    echo $mailData;
    if ($mailCheck) {
        include 'mail.php';
    };
}
?>