<?php
/**
 * This file is part of DmGeocoder.
 * 
 * (c) demouth
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * PHP versions 5
 */

/**
 * Dm_Geocoder_Query
 * 
 * ジオコーディングの検索文字列を表す。
 * 検索文字列を正規化します。
 * 
 * @author demouth.net
 */
class Dm_Geocoder_Query
{
	
	/**
	 * 都道府県コード
	 * @var string
	 */
	public $prefectureCode;
	
	/**
	 * 都道府県名
	 * @var string
	 */
	public $prefectureName;
	
	/**
	 * 正規化された検索文字列
	 * @var string
	 */
	public $address = '';
	
	/**
	 * 正規化前の検索文字列
	 * @var string
	 */
	public $originalQuery = '';
	
	/**
	 * コンストラクタ
	 * @param string $address
	 */
	public function __construct($address)
	{
		$this->originalQuery = $address;
		$this->parse($address);
	}
	
	/**
	 * 検索文字列を正規化してメンバ変数に格納する
	 * @param string $address
	 * @return void
	 */
	protected function parse($address)
	{
		
		//全角数字を半角に。全角スペースを半角に。
		$address = mb_convert_kana($address,'ns');
		
		//半角スペースを削除
		$address = str_replace(' ','',$address);
		
		//県を特定し、検索文字列から県を除外
		foreach (Dm_Geocoder_Prefecture::get() as $code => $name) {
			$splited = preg_split('/'.$name.'/',$address);
			if(count($splited)<=1) continue;
			$this->prefectureCode = $code;
			$this->prefectureName = $name;
			$address = $name.$splited[1];
			break;
		}
		
		//住所中の数値を漢数字に変換する
		$address = preg_replace_callback('/[0-9]+/msu',
		//$address = preg_replace_callback('/[0-9.\.-]+/msu',
		create_function('$mt', 'return Dm_Geocoder_Query::num2kan_decimal($mt[0]);'), $address);
		
		//都道府県名以降の住所
		$this->address = $address;
		
	}
	
	/**
	 * 半角数字を漢数字に変換する（位取り記法）
	 * @param string $instr 半角数字
	 *                          小数、負数に対応；指数表記には未対応
	 *                          カンマは削除
	 * @return string 漢数字
	 * @see http://www.pahoo.org/e-soul/webtech/php03/php03-05-01.shtm
	 */
	public static function num2kan_decimal($instr)
	{
		static $kantbl1 = array(0=>'', 1=>'一', 2=>'二', 3=>'三', 4=>'四', 5=>'五', 6=>'六', 7=>'七', 8=>'八', 9=>'九', '.'=>'．', '-'=>'−');
		static $kantbl2 = array(0=>'', 1=>'十', 2=>'百', 3=>'千');
		static $kantbl3 = array(0=>'', 1=>'万', 2=>'億', 3=>'兆', 4=>'京');
		
		$outstr = '';
		$len = strlen($instr);
		$m = (int)($len / 4);
		//一、万、億、兆‥‥の繰り返し
		for ($i = 0; $i <= $m; $i++) {
			$s2 = '';
			//一、十、百、千の繰り返し
			for ($j = 0; $j < 4; $j++) {
				$pos = $len - $i * 4 - $j - 1;
				if ($pos >= 0) {
					$ch  = substr($instr, $pos, 1);
					$ch1 = isset($kantbl1[$ch]) ? $kantbl1[$ch] : '';
					$ch2 = isset($kantbl2[$j])  ? $kantbl2[$j]  : '';
					//冒頭が「一」の場合の処理
					if ($ch1 != '') {
						if ($ch1 == '一' && $ch2 != '')      $s2 = $ch2 . $s2;
						else                                $s2 = $ch1 . $ch2 . $s2;
					}
				}
			}
			if ($s2 != '')  $outstr = $s2 . $kantbl3[$i] . $outstr;
		}
	
		return $outstr;
	}
	
}
