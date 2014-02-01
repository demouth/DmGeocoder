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
 * Dm_Geocoder
 * 
 * ジオコーディングライブラリ
 * 住所から緯度経度を調べたり（ジオコーディング）、緯度経度から住所を調べたりすること（逆ジオコーディング）が可能
 * 
 * 街区レベル位置参照情報 国土交通省
 * 
 * <pre>
//ジオコーディング
$addressList = Dm_Geocoder::geocode('沖縄県八重山郡与那国町与那国');
$address = $addressList[0];
echo $address->lat;//24.468119
echo $address->lng;//123.004341

//逆ジオコーディング
$addressList = Dm_Geocoder::reverseGeocode(35.6882074,139.7001416);
$address = $addressList[0];
echo $address->prefectureName;//東京都
echo $address->municipalityName;//渋谷区
echo $address->localName;//代々木二丁目
</pre>
 * 
 * @author demouth.net
 */
class Dm_Geocoder
{
	/**
	 * ジオコーディング
	 * 住所を元に地理情報を返す（緯度経度など）
	 * 
	 * @param string $address 検索対象の住所文字列
	 * @return Dm_Geocoder_Address[] 
	 */
	public static function geocode($address)
	{
		if(!is_string($address)) return array();
		
		$query = new Dm_Geocoder_Query($address);
		$finded = Dm_Geocoder_GISCSV_Finder::find($query);
		
		return $finded;
		
	}
	
	/**
	 * 逆ジオコーディング
	 * 引数の緯度経度に近い地理情報を近い順に複数返す。
	 * 
	 * @param float $lat   緯度
	 * @param float $lng   軽度
	 * @param int   $limit 取得件数
	 * @return Dm_Geocoder_Address[]
	 */
	public static function reverseGeocode($lat,$lng,$limit=10)
	{
		$prefectures = array_keys(Dm_Geocoder_Prefecture::get());
		
		$addressList = array();
		
		//都道府県のCSV毎ループ
		foreach ($prefectures as $prefecture) {
			
			$location = Dm_Geocoder_Prefecture::getLocation($prefecture);
			$tolerance = 1.0;//ある程度の範囲までを対象とする
			if($location['minLat']-$tolerance > $lat) continue;
			if($location['maxLat']+$tolerance < $lat) continue;
			if($location['minLng']-$tolerance > $lng) continue;
			if($location['maxLng']+$tolerance < $lng) continue;
			
			$reader = new Dm_Geocoder_GISCSV_Reader($prefecture);
			
			// CSVの１行毎にループ
			// $rowはDm_Geocoder_Addressオブジェクト
			foreach($reader as $key => $row){
				
				$count = count($addressList);
				$distance = self::location_distance(
					$row->lat,
					$row->lng,
					$lat,
					$lng
				);
				
				if($count<$limit){
					
					$addressList[] = array(
						'address'=>$row,
						'distance'=>$distance['distance']
					);
					//１件目なら後続の処理は飛ばす
					if($count===0) continue;
					//距離順にソート
					usort($addressList, array(__CLASS__,'compareDistance'));
					
				}else{
					
					//保持している中で一番遠いaddressオブジェクト
					$end = end($addressList);
					$endDistance = $end['distance'];
					
					$nowDistance = self::location_distance($row->lat,$row->lng,$lat,$lng);
					$nowDistance = $nowDistance['distance'];
					
					if( $endDistance > $nowDistance ){
						$addressList[] = array(
							'address'=>$row,
							'distance'=>$distance['distance']
						);
						//近い順にソートする
						usort($addressList, array(__CLASS__,'compareDistance'));
						//1つ追加したので、（一番遠い）末尾から1つ削除する
						array_pop($addressList);
					}
				}
				
			}
		}
		
		foreach($addressList as $key => $row){
			$addressList[$key] = $row['address'];
		}
		
		return $addressList;
		
	}
	
	/**
	 * _debugPrefLocation
	 * 
	 * 県に所属する大字町丁目の緯度経度の最小と最大をechoします。
	 * デバッグ用メソッドです。
	 * 
	 * @return void
	 */
	public static function _debugPrefLocation()
	{
		
		$prefectures = array_keys(Dm_Geocoder_Prefecture::get());
		
		$prefectureLocation = array();
		
		foreach ($prefectures as $prefecture) {
			$reader = new Dm_Geocoder_GISCSV_Reader($prefecture);
			foreach($reader as $key => $row){
				if(!isset($prefectureLocation[$prefecture])){
					$prefectureLocation[$prefecture] = array(
						'minLat'=>$row->lat,
						'maxLat'=>$row->lat,
						'minLng'=>$row->lng,
						'maxLng'=>$row->lng
					);
				}else{
					if($row->lat < $prefectureLocation[$prefecture]['minLat']){
						$prefectureLocation[$prefecture]['minLat'] = $row->lat;
					}
					if($row->lat > $prefectureLocation[$prefecture]['maxLat']){
						$prefectureLocation[$prefecture]['maxLat'] = $row->lat;
					}
					if($row->lng < $prefectureLocation[$prefecture]['minLng']){
						$prefectureLocation[$prefecture]['minLng'] = $row->lng;
					}
					if($row->lng > $prefectureLocation[$prefecture]['maxLng']){
						$prefectureLocation[$prefecture]['maxLng'] = $row->lng;
					}
				}
			}
			
		}
		
		$varExport = var_export($prefectureLocation,true);
		echo str_replace('  ', "\t", $varExport);
		echo "\n";
	}
	
	/**
	 * //GPSなどの緯度経度の２点間の直線距離を求める（世界測地系）
	 * 
	 * //$lat1, $lon1 --- A地点の緯度経度
	 * //$lat2, $lon2 --- B地点の緯度経度
	 * @param float $lat1
	 * @param float $lon1
	 * @param float $lat2
	 * @param float $lon2
	 * @return array
	 * @see http://kudakurage.hatenadiary.com/entry/20100319/1268986000
	 */
	protected static function location_distance($lat1, $lon1, $lat2, $lon2){
		$lat_average = deg2rad( $lat1 + (($lat2 - $lat1) / 2) );//２点の緯度の平均
		$lat_difference = deg2rad( $lat1 - $lat2 );//２点の緯度差
		$lon_difference = deg2rad( $lon1 - $lon2 );//２点の経度差
		$curvature_radius_tmp = 1 - 0.00669438 * pow(sin($lat_average), 2);
		$meridian_curvature_radius = 6335439.327 / sqrt(pow($curvature_radius_tmp, 3));//子午線曲率半径
		$prime_vertical_circle_curvature_radius = 6378137 / sqrt($curvature_radius_tmp);//卯酉線曲率半径
		
		//２点間の距離
		$distance = pow($meridian_curvature_radius * $lat_difference, 2) + pow($prime_vertical_circle_curvature_radius * cos($lat_average) * $lon_difference, 2);
		$distance = sqrt($distance);
		
		$distance_unit = round($distance);
		if($distance_unit < 1000){//1000m以下ならメートル表記
			$distance_unit = $distance_unit."m";
		}else{//1000m以上ならkm表記
			$distance_unit = round($distance_unit / 100);
			$distance_unit = ($distance_unit / 10)."km";
		}
		
		//$hoge['distance']で小数点付きの直線距離を返す（メートル）
		//$hoge['distance_unit']で整形された直線距離を返す（1000m以下ならメートルで記述 例：836m ｜ 1000m以下は小数点第一位以上の数をkmで記述 例：2.8km）
		return array("distance" => $distance, "distance_unit" => $distance_unit);
	}
	
	/**
	 * distanceを比較します
	 * @param array $a
	 * @param array $b
	 * @return int
	 */
	protected static function compareDistance($a,$b)
	{
		return $a['distance'] > $b['distance'] ? 1 : -1;
	}
	
}
