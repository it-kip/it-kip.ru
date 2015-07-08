<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class parser extends CI_Controller {


	function get_page( $url, $ref = false ){
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
		//curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt( $ch, CURLOPT_COOKIEJAR, 'cookie.txt' );
		curl_setopt ($ch, CURLOPT_COOKIEFILE, 'cookie.txt' );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_ENCODING, "" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
		if( $ref ) curl_setopt($ch, CURLOPT_REFERER, $ref);
		return curl_exec( $ch );
	}


//        function month(){
//            
//            for($i = 2000; $i <= 2025; $i++){
//                for($j = 1; $j <= 12; $j++){
//                    $jj = $j < 10? '0'.$j: $j;
//                    $this->db->insert("Месяц", array('Месяц_name' => $i.'-'.$jj.'-01', 'Месяц_parent' => $i));
//                }
//            }
//        }



	function regions($p = false, $pg = false){
            
            $this->load->model('model_kolap', 'kolap');
            header("Content-Type: text/html; charset=utf-8");
            set_time_limit(0);
            error_reporting('E_ALL');
            ini_set('display_errors', 1);
            include 'application/libraries/phpQuery/phpQuery.php';

            $url = 'http://cbr.ru/regions/OLAP.asp';

            //$this->load->library('parser');

            $res = $this->get_page( $url );

            $url = 'http://cbr.ru/regions/OLAP.asp?y1=2001&y2='.date('Y').'&ST1.x=%CF%F0%EE%E4%EE%EB%E6%E8%F2%FC';

            $res = $this->get_page( $url );

            $url = 'http://cbr.ru/regions/OLAP.asp?RG=RUSS&RG=Cent_O&RG=BELG&RG=BRIANSK&RG=VL-R&RG=VORO&RG=IVAN-O&RG=KL-A&RG=KOST&RG=KURSK&RG=LIPE&RG=MOSK&RG=MOSK-O&RG=ORL&RG=RZ&RG=SMOL&RG=TAMBOV&RG=TVER&RG=TULA&RG=JAROS&RG=NW_O&RG=ARX&RG=NENEC&RG=VOLOG&RG=SP-G&RG=KA-D&RG=LENIN_O&RG=MURM_O&RG=NOVG_O&RG=PSK&RG=KAREL&RG=KOMI&RG=Z_O&RG=KRAS_KR&RG=ASTR_O&RG=VL-D&RG=ROST&RG=ADG_R&RG=KALM_R&RG=SKO&RG=STAVR&RG=ING_R&RG=DAG-N&RG=KB_R&RG=SOA_R&RG=KAR_R&RG=CHACH_R&RG=PW_O&RG=NNOV&RG=KIROV&RG=SAM&RG=OREN_O&RG=PENZ_O&RG=PER_O&RG=SARAT&RG=UL_O&RG=BASH&RG=MARI&RG=MOR_R&RG=TAT-N&RG=UDM&RG=CUV_R&RG=URAL_O&RG=KURG&RG=EK&RG=TUMEN&RG=UGRA&RG=YAM_NENEC&RG=CHEL&RG=SIB_O&RG=ALTAI_KR&RG=KR-K&RG=IRK_O&RG=KEM_O&RG=NOV-K&RG=OM&RG=TOM_O&RG=CHIT_O&RG=BUR_R&RG=ALT_R&RG=TIV_R&RG=XAK_R&RG=DV_O&RG=PRIM&RG=XA&RG=AMUR&RG=KAMC_O&RG=MAG_O&RG=SAH_O&RG=CUKAO&RG=SAHA_R&RG=EVR&RG=CR_O&RG=CRIMEA&RG=SEVAST&ST2.x=%CF%F0%EE%E4%EE%EB%E6%E8%F2%FC+%3E%3E';

            $res = $this->get_page( $url );
            //$res  = iconv("WINDOWS-1251","UTF-8",$res);

            $document = phpQuery::newDocumentHTML($res);
            $inps = $document->find('div.fieldset dl:first dd input');

            foreach ($inps as $inp){
                $val = pq($inp)->val();
                if($p && $p != $val) continue;
                $this->regions_pg4($val, $pg);
                //$this->regions_pg4(str_replace("*", "", $val));
                //exit();
            }

            //print_r( file_get_contents(  'cookie.txt'  ) );
            //print_r( iconv("WINDOWS-1251","UTF-8",$res) );
	}
        
        function regions_pg4($tabl, $pg_start){
            
            echo "<hr>".$tabl."<br>";
            
            $id = $this->db->get_where('Показатель', array('Показатель_цбр' => $tabl))->row_array();
            $id = $id['Показатель_id'];
            
            $url = "http://cbr.ru/regions/OLAP.asp?RTBL={$tabl}&ST3.x=%CF%F0%EE%E4%EE%EB%E6%E8%F2%FC+%3E%3E";
            $res = $this->get_page( $url );
            
            //preg_match_all('|<[^>]+>(.*)</[^>]+>|U', iconv("WINDOWS-1251","UTF-8",$res), $match, PREG_PATTERN_ORDER);
            preg_match_all('#<input[^>](.*)</input><br#uUsi', iconv("WINDOWS-1251","UTF-8",$res), $match);
                
            foreach($match[1] as $inp){
                preg_match_all("#value='(.*)'#uUsi", $inp, $m_val);
                preg_match_all("#.* type='radio' .*>(.*)#uSsi", $inp, $m_txt);
                //print_r($m_val);
                //print_r($m_txt);
                $pok = $this->kolap->set_rec('Показатель', array('Показатель_name' => $m_txt[1][0], 'Показатель_parent' => $id));
                //print_r(array('Показатель_name' => $m_txt[1][0], 'Показатель_parent' => $id));
                $pg = $m_val[1][0];
                if($pg_start && $pg_start != $pg) continue;
                $this->regions_pg5($pg, $pok['Показатель_id']);
                //exit();
            }
            
            //$document = phpQuery::newDocumentHTML($res);
            //$inps = $document->find('div.fieldset dl:first dd input');
            
            //print_r( iconv("WINDOWS-1251","UTF-8",$res) );
        }
        
        function regions_pg5($pg, $pok){ 
            echo $pg."<br>";
            $url = "http://cbr.ru/regions/OLAP.asp?RI={$pg}&RESULT.x=%C3%EE%F2%EE%E2%EE%21";
            $res = $this->get_page( $url );
            
            $date = array();
            $reg = array();
            //$data = array();
            
            $document = phpQuery::newDocumentHTML($res);
            $trs = $document->find('table.data tr');
            $tr_idx = 0;
            foreach ($trs as $tr){
                $tds = pq($tr)->find('td');
                $td_idx = 0; //echo "<br>".count($tds);
                foreach($tds as $td){
                    //echo $tr_idx." ".$td_idx."<br>";
                    
                    if(!$tr_idx && $td_idx) {
                        $dat = pq($td)->text();
                        $dat = $this->parse_date($dat);
                        $this->db->where("`Месяц_parent` is not null");
                        $dat = $this->db->get_where("Месяц", array("Месяц_name" => $dat))->row_array();
                        $date[] = (int)$dat['Месяц_id'];//print_r($date);
                        //$data[$dat] = array();
                    }
                        
                    if($tr_idx && !$td_idx){
                        $r = pq($td)->text();
                        $r = str_replace(
                            array('Всего по России', "г. "), 
                            array('Российская Федерация', ''),
                            $r
                        );
                        //$r = $r == 'Всего по России'? 'Российская Федерация': str_replace("г. ", "", $r);
                        //echo "<br>".$r;
                        $r = $this->db->get_where("Регионы", array("Регионы_name" => $r))->row_array();
                        $reg[] = (int)$r['Регионы_id'];
                    }
                    
                    if($tr_idx && $td_idx) {
//                        $v = $this->parse_data( pq($td)->find('a')->text() );
                        $v = pq($td)->find('a')->text();echo $v."<br>";
                        //$v = iconv("WINDOWS-1251","UTF-8",$v); echo $v."<br>";
                        $v = preg_replace(array('#(,)#ui', '#([^\d.])#ui'), array('.', ''), $v);
                        $v = (float)$v; echo $v."<br>"; // не робит str_replace
                        if($v)
                            $this->kolap->set_slices('Показатели_регионов', 
                                array(
                                    'Показатели_регионов_Показатель' => (int)$pok,
                                    'Показатели_регионов_Месяц' => $date[$td_idx - 1],
                                    'Показатели_регионов_Регионы' => $reg[$tr_idx - 1]
                                ), array(
                                    'value' => $v
                                )
                            );
                    }
                    $td_idx++;
                }
                $tr_idx++;
            }
        }
        
        function parse_date($date){ 
            $date = substr($date, 0, stripos($date, '20') + 4);
            $month = array(
                'январь ',
                'февраль ',
                'март ',
                'апрель ',
                'май ',
                'июнь ',
                'июль ',
                'август ',
                'сентябрь ',
                'октябрь ',
                'ноябрь ',
                'декабрь '
            );
            
            $replace = array('01.01.', '01.02.', '01.03.', '01.04.', '01.05.', '01.06.', '01.07.', '01.08.', '01.09.', '01.10.', '01.11.', '01.12.');
            //echo str_replace($month, $replace, $date)."<br>";
            return date("Y-m-d", strtotime(str_replace($month, $replace, strtolower($date)))); //??
        }
        
        function parse_data($data){echo $data." => ";
            $data = str_replace(array(" ", "&nbsp;", "&NBSP;", ","), array("", "", "", "."), $data);echo $data."<br>";
            return (float)$data;
        }
}
