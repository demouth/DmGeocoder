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
 * Dm_Geocoder_Address
 * 
 * CSVの1行分を表すオブジェクト
 * 
 * @author demouth.net
 */
class Dm_Geocoder_Address
{
	
	/**
	 * 都道府県コード
	 * @var int
	 */
	public $prefectureCode = 0;
	
	/**
	 * 都道府県名
	 * @var string
	 */
	public $prefectureName = '';
	
	/**
	 * 市区町村コード
	 * @var int
	 */
	public $municipalityCode = 0;
	
	/**
	 * 市区町村名
	 * @var string
	 */
	public $municipalityName = '';
	
	/**
	 * 大字町丁目コード
	 * @var int
	 */
	public $localCode = 0;
	
	/**
	 * 大字町丁目名
	 * @var string
	 */
	public $localName = '';
	
	/**
	 * 緯度
	 * @var float
	 */
	public $lat=0.0;
	
	/**
	 * 経度
	 * @var float
	 */
	public $lng=0.0;
	
	/**
	 * csvの1行分の文字列をもとに取り込む
	 * 
	 * @param string $row CSVの1行分の文字列
	 * @return self
	 */
	public function importCsv($row)
	{
		
		$row = str_getcsv($row);
		
		$this->prefectureCode = (int)$row[0];
		$this->prefectureName = (string)$row[1];
		$this->municipalityCode = (int)$row[2];
		$this->municipalityName = (string)$row[3];
		$this->localCode = (int)$row[4];
		$this->localName = (string)$row[5];
		$this->lat = (float)$row[6];
		$this->lng = (float)$row[7];
		
		return $this;
	}
	
}
