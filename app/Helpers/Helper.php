<?php

namespace App\Helpers;
use Carbon\Carbon;
use URL;
class Helper {    
    /*
     * nama bulan dalam bahasa indonesia
     */
    private static $Bulan = array(1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei',6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember');
    
    /*
     * nama bulan dalam bahasa indonesia
     */
    private static $statusPasien = [
                                    0=>'POSITIF MENINGGAL', 
                                    1=>'POSITIF AKTIF',
                                    2=>'POSITIF SEMBUH',
                                    3=>'PDP PROSES',
                                    4=>'PDP SELESAI',
                                    5=>'ODP PROSES',
                                    6=>'ODP SELESAI'
                                ];
    /**
     * digunakan untuk mendapatkan bulan
     */
    public static function getBulan($idx=null) {
        if ($idx === null) {
            return Helper::$Bulan;
        }else{
            return Helper::$Bulan[$idx];
        }
    }    
    /**
     * digunakan untuk memformat tanggal
     * @param type $format
     * @param type $date
     * @return type date
     */
    public static function tanggal($format, $date=null) {
        Carbon::setLocale(app()->getLocale());
        if ($date == null){
            $tanggal=Carbon::parse(Carbon::now())->format($format);
        }else{
            $tanggal = Carbon::parse($date)->format($format);
        }        
        return $tanggal;
    }   
    /**
     * digunakan untuk mendapatkan usia
     */
    public static function getUsia ($birthdate,$currentdate=null)
    {
        $age = $currentdate == null ? date('Y-m-d'):$usia;
        $time_age = strtotime($age);
        $time_tanggal = strtotime($currentdate);

        $myage = date('Y',$time_age) - date('Y',$time_tanggal);
        if (date('z',$time_age) < date('z',$time_tanggal)) 
        {
            $myage--;
        }
        return $myage;
    }
    /**
	* digunakan untuk mem-format uang
	*/
	public static function formatUang ($uang=0,$decimal=2) {
		$formatted = number_format((float)$uang,$decimal,',','.');
        return $formatted;
    }
    /**
	* digunakan untuk mem-format angka
	*/
	public static function formatAngka ($angka=0) {
        $bil = intval($angka);
        $formatted = ($bil < $angka) ? $angka : $bil;
        return $formatted;
    }
    /**
	* digunakan untuk mem-format persentase
	*/
	public static function formatPersen ($pembilang,$penyebut=0,$dec_sep=2) {
        $result=0.00;
		if ($pembilang > 0 && $penyebut > 0) {
            $temp=number_format((float)($pembilang/$penyebut)*100,$dec_sep);
            $result = $temp;
        }
        else
        {
            $result=0;
        }
        return $result;
	}
    /**
	* digunakan untuk mem-format pecahan
	*/
	public static function formatPecahan ($pembilang,$penyebut=0,$dec_sep=2) {
        $result=0;
		if ($pembilang > 0 && $penyebut > 0) {
            $result=number_format((float)($pembilang/$penyebut),$dec_sep);
        }
        return $result;
    }   
    public static function public_path($path = null)
    {
        return rtrim(app()->basePath('storage/app/public/' . $path), '/');
    } 
    public static function getStatusPasien ($status=null)
    {
        if ($status == null)
        {
            return Helper::$statusPasien;
        }
        else
        {
            return Helper::$statusPasien[$status];
        }
    }
}