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
 * Dm_Geocoder_GISCSV
 * 
 * csvファイルのアクセサ
 * 大字・町丁目レベルのCSVを返す
 * 
 * @see http://nlftp.mlit.go.jp/isj/
 * @author demouth.net
 */
class Dm_Geocoder_GISCSV
{
	
	/**
	 * 都道府県コード
	 * @var string
	 */
	protected $prefectureCode;
	
	/**
	 * csvファイルの対象年度
	 * @example 2012
	 * @var int
	 */
	public static $year = 2012;
	
	/**
	 * コンストラクタ
	 * @param string $prefectureCode 都道府県コード
	 */
	public function __construct($prefectureCode)
	{
		$this->prefectureCode = $prefectureCode;
	}
	
	/**
	 * csvを読み込む
	 * @return string csvの中身をそのまま文字列で返す
	 */
	public function read()
	{
		
		$csvPath = self::buildCsvFilePath($this->prefectureCode);
		
		$buf = mb_convert_encoding(
			file_get_contents($csvPath), 
			mb_internal_encoding(), 
			'sjis-win'
		);
		
		return $buf;
		
	}
	
	/**
	 * csvファイルのパスを返す
	 * @param string $prefectureCode 都道府県コード
	 * @return string csvのパス
	 */
	protected static function buildCsvFilePath($prefectureCode)
	{
		$DS = DIRECTORY_SEPARATOR;
		$path = __DIR__
			.$DS.'GISCSV'
			.$DS.'csv'
			.$DS.$prefectureCode.'_'.self::$year.'.csv';
		return $path;
	}
	
}
