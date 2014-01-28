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
 * Dm_Geocoder_Prefecture
 * 
 * 県にまつわる定数クラス
 * 
 * @author demouth.net
 */
class Dm_Geocoder_Prefecture
{
	
	/**
	 * 都道府県コードがキーで、バリューが都道府県名の配列
	 * @var array
	 */
	protected static $prefectures = array(
		'01'=>'北海道',
		'02'=>'青森県',
		'03'=>'岩手県',
		'04'=>'宮城県',
		'05'=>'秋田県',
		'06'=>'山形県',
		'07'=>'福島県',
		'08'=>'茨城県',
		'09'=>'栃木県',
		'10'=>'群馬県',
		'11'=>'埼玉県',
		'12'=>'千葉県',
		'13'=>'東京都',
		'14'=>'神奈川県',
		'15'=>'新潟県',
		'16'=>'富山県',
		'17'=>'石川県',
		'18'=>'福井県',
		'19'=>'山梨県',
		'20'=>'長野県',
		'21'=>'岐阜県',
		'22'=>'静岡県',
		'23'=>'愛知県',
		'24'=>'三重県',
		'25'=>'滋賀県',
		'26'=>'京都府',
		'27'=>'大阪府',
		'28'=>'兵庫県',
		'29'=>'奈良県',
		'30'=>'和歌山県',
		'31'=>'鳥取県',
		'32'=>'島根県',
		'33'=>'岡山県',
		'34'=>'広島県',
		'35'=>'山口県',
		'36'=>'徳島県',
		'37'=>'香川県',
		'38'=>'愛媛県',
		'39'=>'高知県',
		'40'=>'福岡県',
		'41'=>'佐賀県',
		'42'=>'長崎県',
		'43'=>'熊本県',
		'44'=>'大分県',
		'45'=>'宮崎県',
		'46'=>'鹿児島県',
		'47'=>'沖縄県',
	);
	
	/**
	 * 都道府県コードがキーで、バリューが都道府県名の緯度経度の最大最小情報
	 * @var array
	 */
	protected static $prefectureLocations = array (
		'01' => 
		array (
			'minLat' => '41.358990',
			'maxLat' => '45.522159',
			'minLng' => '139.359591',
			'maxLng' => '145.796036',
		),
		'02' => 
		array (
			'minLat' => '40.250816',
			'maxLat' => '41.528683',
			'minLng' => '139.869081',
			'maxLng' => '141.680025',
		),
		'03' => 
		array (
			'minLat' => '38.757512',
			'maxLat' => '40.379733',
			'minLng' => '140.709553',
			'maxLng' => '142.043893',
		),
		'04' => 
		array (
			'minLat' => '37.779148',
			'maxLat' => '38.986537',
			'minLng' => '140.279917',
			'maxLng' => '141.644852',
		),
		'05' => 
		array (
			'minLat' => '38.931866',
			'maxLat' => '40.457366',
			'minLng' => '139.703718',
			'maxLng' => '140.984466',
		),
		'06' => 
		array (
			'minLat' => '37.803108',
			'maxLat' => '39.205402',
			'minLng' => '139.542821',
			'maxLng' => '140.610771',
		),
		'07' => 
		array (
			'minLat' => '36.796243',
			'maxLat' => '37.920052',
			'minLng' => '139.266914',
			'maxLng' => '141.040386',
		),
		'08' => 
		array (
			'minLat' => '35.745647',
			'maxLat' => '36.916978',
			'minLng' => '139.695020',
			'maxLng' => '140.839912',
		),
		'09' => 
		array (
			'minLat' => '36.207088',
			'maxLat' => '37.112663',
			'minLng' => '139.375953',
			'maxLng' => '140.266750',
		),
		10 => 
		array (
			'minLat' => '36.054465',
			'maxLat' => '36.844197',
			'minLng' => '138.458277',
			'maxLng' => '139.650073',
		),
		11 => 
		array (
			'minLat' => '35.756088',
			'maxLat' => '36.281862',
			'minLng' => '138.811572',
			'maxLng' => '139.893262',
		),
		12 => 
		array (
			'minLat' => '34.910566',
			'maxLat' => '36.099644',
			'minLng' => '139.756205',
			'maxLng' => '140.865356',
		),
		13 => 
		array (
			'minLat' => '24.785502',
			'maxLat' => '35.862730',
			'minLng' => '138.987577',
			'maxLng' => '142.214196',
		),
		14 => 
		array (
			'minLat' => '35.135584',
			'maxLat' => '35.650541',
			'minLng' => '139.011442',
			'maxLng' => '139.776270',
		),
		15 => 
		array (
			'minLat' => '36.809161',
			'maxLat' => '38.549029',
			'minLng' => '137.681878',
			'maxLng' => '139.693336',
		),
		16 => 
		array (
			'minLat' => '36.325537',
			'maxLat' => '36.975818',
			'minLng' => '136.788522',
			'maxLng' => '137.653588',
		),
		17 => 
		array (
			'minLat' => '36.177323',
			'maxLat' => '37.526739',
			'minLng' => '136.250279',
			'maxLng' => '137.355987',
		),
		18 => 
		array (
			'minLat' => '35.359954',
			'maxLat' => '36.289460',
			'minLng' => '135.453362',
			'maxLng' => '136.805156',
		),
		19 => 
		array (
			'minLat' => '35.211377',
			'maxLat' => '35.893153',
			'minLng' => '138.250292',
			'maxLng' => '139.118939',
		),
		20 => 
		array (
			'minLat' => '35.214825',
			'maxLat' => '36.992618',
			'minLng' => '137.455106',
			'maxLng' => '138.687824',
		),
		21 => 
		array (
			'minLat' => '35.149655',
			'maxLat' => '36.459057',
			'minLng' => '136.357678',
			'maxLng' => '137.573196',
		),
		22 => 
		array (
			'minLat' => '34.600777',
			'maxLat' => '35.427755',
			'minLng' => '137.484201',
			'maxLng' => '139.170033',
		),
		23 => 
		array (
			'minLat' => '34.582701',
			'maxLat' => '35.412321',
			'minLng' => '136.682420',
			'maxLng' => '137.784279',
		),
		24 => 
		array (
			'minLat' => '33.726151',
			'maxLat' => '35.222647',
			'minLng' => '135.856783',
			'maxLng' => '136.982444',
		),
		25 => 
		array (
			'minLat' => '34.819152',
			'maxLat' => '35.661814',
			'minLng' => '135.791721',
			'maxLng' => '136.430282',
		),
		26 => 
		array (
			'minLat' => '34.710434',
			'maxLat' => '35.765180',
			'minLng' => '134.872316',
			'maxLng' => '136.022014',
		),
		27 => 
		array (
			'minLat' => '34.288975',
			'maxLat' => '35.038651',
			'minLng' => '135.095272',
			'maxLng' => '135.740186',
		),
		28 => 
		array (
			'minLat' => '34.168449',
			'maxLat' => '35.664653',
			'minLng' => '134.273617',
			'maxLng' => '135.458420',
		),
		29 => 
		array (
			'minLat' => '33.877658',
			'maxLat' => '34.752104',
			'minLng' => '135.571596',
			'maxLng' => '136.193663',
		),
		30 => 
		array (
			'minLat' => '33.447921',
			'maxLat' => '34.371054',
			'minLng' => '135.070588',
			'maxLng' => '136.005753',
		),
		31 => 
		array (
			'minLat' => '35.090298',
			'maxLat' => '35.603529',
			'minLng' => '133.181192',
			'maxLng' => '134.482049',
		),
		32 => 
		array (
			'minLat' => '34.337773',
			'maxLat' => '36.327765',
			'minLng' => '131.693331',
			'maxLng' => '133.372990',
		),
		33 => 
		array (
			'minLat' => '34.307887',
			'maxLat' => '35.303461',
			'minLng' => '133.303623',
			'maxLng' => '134.391105',
		),
		34 => 
		array (
			'minLat' => '34.051531',
			'maxLat' => '35.071125',
			'minLng' => '132.083881',
			'maxLng' => '133.453881',
		),
		35 => 
		array (
			'minLat' => '33.728280',
			'maxLat' => '34.779117',
			'minLng' => '130.787297',
			'maxLng' => '132.437409',
		),
		36 => 
		array (
			'minLat' => '33.544668',
			'maxLat' => '34.244710',
			'minLng' => '133.693900',
			'maxLng' => '134.807083',
		),
		37 => 
		array (
			'minLat' => '34.030486',
			'maxLat' => '34.556741',
			'minLng' => '133.534539',
			'maxLng' => '134.431332',
		),
		38 => 
		array (
			'minLat' => '32.910744',
			'maxLat' => '34.300807',
			'minLng' => '132.040619',
			'maxLng' => '133.663368',
		),
		39 => 
		array (
			'minLat' => '32.713289',
			'maxLat' => '33.848760',
			'minLng' => '132.493430',
			'maxLng' => '134.303493',
		),
		40 => 
		array (
			'minLat' => '33.003694',
			'maxLat' => '33.990222',
			'minLng' => '130.036903',
			'maxLng' => '131.187369',
		),
		41 => 
		array (
			'minLat' => '32.952364',
			'maxLat' => '33.596026',
			'minLng' => '129.763630',
			'maxLng' => '130.541116',
		),
		42 => 
		array (
			'minLat' => '32.563368',
			'maxLat' => '34.690578',
			'minLng' => '128.598614',
			'maxLng' => '130.376722',
		),
		43 => 
		array (
			'minLat' => '32.106190',
			'maxLat' => '33.158826',
			'minLng' => '129.985399',
			'maxLng' => '131.265842',
		),
		44 => 
		array (
			'minLat' => '32.749071',
			'maxLat' => '33.736245',
			'minLng' => '130.865674',
			'maxLng' => '132.071796',
		),
		45 => 
		array (
			'minLat' => '31.395289',
			'maxLat' => '32.812798',
			'minLng' => '130.752916',
			'maxLng' => '131.839719',
		),
		46 => 
		array (
			'minLat' => '27.032592',
			'maxLat' => '32.290607',
			'minLng' => '128.411414',
			'maxLng' => '131.134366',
		),
		47 => 
		array (
			'minLat' => '24.060641',
			'maxLat' => '27.061963',
			'minLng' => '123.004341',
			'maxLng' => '131.311980',
		),
	);
	
	/**
	 * 都道府県コードがキーで、バリューが都道府県名の配列を返す
	 * @return array
	 */
	public static function get()
	{
		return self::$prefectures;
	}
	
	/**
	 * 都道府県コードから都道府県名を返す
	 * @param string $prefectureCode 都道府県コード
	 * @return string 都道府県名
	 */
	public static function getName($prefectureCode)
	{
		return self::$prefectures[$prefectureCode];
	}
	
	/**
	 * 都道府県コードを受け取り、都道府県名の緯度経度の最大最小情報を返す
	 * @param string $prefectureCode 都道府県コード
	 * @return array
	 */
	public static function getLocation($prefectureCode)
	{
		return self::$prefectureLocations[$prefectureCode];
	}
	
}
