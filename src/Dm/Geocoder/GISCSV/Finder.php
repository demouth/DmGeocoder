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
 * Dm_Geocoder_GISCSV_Finder
 * 
 * csvを検索文字列で検索し、検索結果としてマッチする候補を複数返す
 * 
 * @author demouth.net
 */
class Dm_Geocoder_GISCSV_Finder
{
	
	/**
	 * csvを検索文字列で検索し、検索結果としてマッチする候補を複数返す
	 * 
	 * 検索速度を速くするため、少し処理が複雑になっています
	 * 都道府県、市区町村、大字町丁目名と、段階的に検索をかけていきます。
	 * 
	 * @param Dm_Geocoder_Query
	 * @return Dm_Geocoder_Address[]
	 */
	public static function find(Dm_Geocoder_Query $query)
	{
		
		// 都道府県を特定できていれば絞り込む
		if($query->prefectureCode){
			//入力された権のみを検索対象とする
			$prefectureCodes = array($query->prefectureCode);
			//検索文字列の頭に県名がついていれば削除する
			$needle = mb_substr($query->address, mb_strlen($query->prefectureName));
			//県名のみの検索の場合は該当県のAddressを全件返して終了
			if(strlen($needle)===0){
				return Dm_Geocoder_GISCSV_Reader::readAll($query->prefectureCode);
			}
		}else{
			//全件を検索対象とする
			$prefectureCodes = array_keys(Dm_Geocoder_Prefecture::get());
			//検索対象文字列
			$needle = $query->address;
		}
		
		$finded = array();
		foreach ($prefectureCodes as $prefectureCode) {
			//CSVを読み込む
			$giscsv = new Dm_Geocoder_GISCSV($prefectureCode);
			$buf = $giscsv->read();
			
			//都道府県名以降の文字列を市区町村名とマッチングして
			//1文字ずつCSVから正規表現で検索する
			$findedInPref = array();
			$code = '\"\d+\"';
			$l = mb_strlen($needle);
			for ($i=1; $i <= $l; $i++) {
				
				$mun = mb_substr($needle,0,$i);
				$mun = preg_quote($mun);
				$mun = '\"'.$mun;
				$pattern = '/^'.$code.',\"[^\"]+\",'.$code.','.$mun.'.+$/m';
				preg_match_all($pattern, $buf , $match);
				
				//1件も一致しなければ終了
				if(count($match[0])===0) break;
				
				//1件でも一致していれば保持する。これまでのものは破棄する。
				$findedInPref = array();
				foreach ($match[0] as $key => $row) {
					$address = new Dm_Geocoder_Address();
					$findedInPref[] = $address->importCsv($row);
				}
			}
			$finded = array_merge($finded,$findedInPref);
		}
		
		//これまでに検索した行は市区町村レベルのマッチングだったので
		//今度は大字町丁目名までを使って1行1行マッチングする
		$addressMatchedMost = array();
		$addressMatchedMostLength = 0;
		foreach ($finded as $key => $row) {
			//市区町村と大字町丁目名で検索する
			$matchesLength = self::forwardMatchesLength(
				$row->municipalityName.$row->localName,
				$needle
			);
			
			if($matchesLength===0) continue;
			
			if($matchesLength > $addressMatchedMostLength){
				$addressMatchedMostLength = $matchesLength;
				$addressMatchedMost = array($row);
			}else if($matchesLength === $addressMatchedMostLength){
				$addressMatchedMost[] = $row;
			}
		}
		return $addressMatchedMost;
		
	}
	
	/**
	 * $aと$bが前方から何文字目まで一致したかを返す
	 * 
	 * @param string $a
	 * @param string $b
	 * @return int 一致した文字数を返す。1文字も一致しなければ0、例えば1文字目まで一致なら1を返す。
	 */
	protected static function forwardMatchesLength($a,$b)
	{
		
		$a = self::mb_str_split($a);
		$b = self::mb_str_split($b);
		
		$matcheLength = 0;
		
		foreach($a as $ak=>$av){
			if(!isset($b[$ak])){
				break;
			}else if($a[$ak] === $b[$ak]){
				$matcheLength++;
			}else{
				break;
			}
		}
		return $matcheLength;
	}
	
	
	/**
	 * mb_splitのマルチバイト対応版
	 * @param string $string
	 * @return string 
	 * @see http://php.benscom.com/manual/ja/function.mb-split.php
	 */
	protected static function mb_str_split($string)
	{
		# Split at all position not after the start: ^ 
		# and not before the end: $ 
		return preg_split('/(?<!^)(?!$)/u', $string ); 
	}
	
}
	