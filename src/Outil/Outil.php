<?php 
namespace App\Outil; 
/**
 * Description of Outilclass
 *
 * @author alfalil soufiane
 */
class Outil {





    public static function jour_fr($mois, $lang = "fr")
    {

        if ($lang == "en") {
        } elseif ($lang == "ar") {

            switch ($mois) {

                case 'Monday':

                    $mois = 'الاثنين';

                    break;

                case 'Tuesday':

                    $mois = 'الثلاثاء';

                    break;

                case 'Wednesday':

                    $mois = 'الأربعاء';

                    break;

                case 'Thursday':

                    $mois = 'الخميس';

                    break;

                case 'Friday':

                    $mois = 'الجمعة';

                    break;

                case 'Saturday':

                    $mois = 'السبت';

                    break;

                case 'Sunday':

                    $mois = 'الأحد';

                    break;

                default:

                    $mois = '';
            }
        } elseif ($lang == "fr") {

            switch ($mois) {

                case 'Monday':

                    $mois = 'Lundi';

                    break;

                case 'Tuesday':

                    $mois = 'Mardi';

                    break;

                case 'Wednesday':

                    $mois = 'Mercredi';

                    break;

                case 'Thursday':

                    $mois = 'Jeudi';

                    break;

                case 'Friday':

                    $mois = 'Vendredi';

                    break;

                case 'Saturday':

                    $mois = 'Samedi';

                    break;

                case 'Sunday':

                    $mois = 'Dimanche';

                    break;

                default:

                    $mois = '';
            }
        }

        return $mois;
    }

    public static function month_fr($mois, $lang = "fr")
    {





        if ($lang == "ar") {

            switch ($mois) {

                case 'January':

                    $mois = 'يناير';

                    break;

                case 'February':

                    $mois = 'فبراير';

                    break;

                case 'March':

                    $mois = 'مارس';

                    break;

                case 'April':

                    $mois = 'ابريل';

                    break;

                case 'May':

                    $mois = 'ماي';

                    break;

                case 'June':

                    $mois = 'يونيو';

                    break;

                case 'July':

                    $mois = 'يوليوز';

                    break;

                case 'August':

                    $mois = 'غشث';

                    break;

                case 'September':

                    $mois = 'شتنبر';

                    break;

                case 'October':

                    $mois = 'أكتوبر';

                    break;

                case 'November':

                    $mois = 'نونبر';

                    break;

                case 'December':

                    $mois = 'دجنبر';

                    break;

                default:

                    $mois = '';

                    break;
            }
        } elseif ($lang == "fr") {

            switch ($mois) {

                case 'January':

                    $mois = 'Janvier';

                    break;

                case 'February':

                    $mois = 'Fevrier';

                    break;

                case 'March':

                    $mois = 'Mars';

                    break;

                case 'April':

                    $mois = 'Avril';

                    break;

                case 'May':

                    $mois = 'Mai';

                    break;

                case 'June':

                    $mois = 'Juin';

                    break;

                case 'July':

                    $mois = 'Juillet';

                    break;

                case 'August':

                    $mois = 'Aout';

                    break;

                case 'September':

                    $mois = 'Septembre';

                    break;

                case 'October':

                    $mois = 'Octobre';

                    break;

                case 'November':

                    $mois = 'Novembre';

                    break;

                case 'December':

                    $mois = 'Decembre';

                    break;

                default:

                    $mois = '';

                    break;
            }
        }

        return $mois;
    }




    static public function getDateFormating($actualite_date = "", $lang = "fr")
    {

        if ($actualite_date == "") {

            $actualite_date = date("Y-m-d");
        }

        //return $date_format; 



        $datee = date_create($actualite_date);

        $date_format = Outil::jour_fr(date_format($datee, "l"), $lang) . " " . date_format($datee, "d") . " " . Outil::month_fr(date_format($datee, "F"), $lang) . " " . date_format($datee, "Y");

        return $date_format;
    }




    static public function getDateFormatingsansday($actualite_date = "", $lang = "fr")
    {
        if ($actualite_date == "") {
            $actualite_date = date("Y-m-d");
        }
        //return $date_format; 
        $datee = date_create($actualite_date);
        $date_format = date_format($datee, "d") . " " . Outil::month_fr(date_format($datee, "F"), $lang) . " " . date_format($datee, "Y");
        return $date_format;
    }



    public static function downloadFile($full_path)
    {



        $file_name = basename($full_path);

        ini_set('zlib.output_compression', 0);

        $date = gmdate(DATE_RFC1123);

        header('Pragma: public');

        header('Cache-Control: must-revalidate, pre-check=0, post-check=0, max-age=0');

        header('Content-Tranfer-Encoding: none');

        header('Content-Length: ' . filesize($full_path));

        header('Content-MD5: ' . base64_encode(md5_file($full_path)));

        header('Content-Type: application/octetstream; name="' . $file_name . '"');

        header('Content-Disposition: attachment; filename="' . $file_name . '"');

        header('Date: ' . $date);

        header('Expires: ' . gmdate(DATE_RFC1123, time() + 1));

        header('Last-Modified: ' . gmdate(DATE_RFC1123, filemtime($full_path)));

        readfile($full_path);

        exit;
    } 

 

 


 




}
