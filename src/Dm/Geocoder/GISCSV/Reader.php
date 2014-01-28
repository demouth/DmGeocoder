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
 * Dm_Geocoder_GISCSV_Reader
 * 
 * csvファイルを読み込みDm_Geocoder_Addressに変換して返します
 * 
 * <pre>
 * $reader = new Dm_Geocoder_GISCSV_Reader($prefecture);
 * foreach($reader as $key => $row){ // CSVの１行毎にループ
 * 	$row;// $rowはDm_Geocoder_Addressオブジェクト
 * }
 * </pre>
 * 
 * @author demouth.net
 */
class Dm_Geocoder_GISCSV_Reader implements Iterator
{
	/**
	 * イテレーションのカレント行
	 * @var int
	 */
	protected $position = 0;
	
	/**
	 * 1行分のCSVの文字列を配列で複数保持する
	 * @var array
	 */
	protected $lines = array();
	
	/**
	 * 県コード
	 * @var string
	 */
	protected $prefectureCode;
	
	/**
	 * コンストラクタ
	 * @param string $prefectureCode 県コード
	 */
	public function __construct($prefectureCode)
	{
		$this->prefectureCode = $prefectureCode;
	}
	
	/**
	 * 配列でまとめて返す
	 * @param string $prefectureCode 県コード
	 * @return Dm_Geocoder_Address[]
	 */
	public static function readAll($prefectureCode)
	{
		$tmp = array();
		$reader = new self($prefectureCode);
		foreach($reader as $key => $row) $tmp[] = $row;
		return $tmp;
	}
	
	/**
	 * 初期化（イテレーション用）
	 * @return void
	 */
	public function rewind()
	{
		//1行目はヘッダ行なので読み飛ばす
		$this->position = 1;
		
		if(!$this->lines){
			
			//CSVを読み込み
			$giscsv = new Dm_Geocoder_GISCSV($this->prefectureCode);
			$buf = $giscsv->read();
			
			//1行ごとに分解
			$ret = array();
			$lines = explode("\r\n", $buf);
			array_pop($lines);
			foreach ($lines as $line) {
				$ret[] = $line;
			}
			
			$this->lines = $ret;
		}
	}
	
	/**
	 * 現在行を返す（イテレーション用）
	 * @return Dm_Geocoder_Address
	 */
	public function current()
	{
		$row = $this->lines[$this->position];
		$address = new Dm_Geocoder_Address();
		$address->importCsv($row);
		return $address;
	}
	/**
	 * 現在行の行数（イテレーション用）
	 * @return int
	 */
	public function key()
	{
		return $this->position;
	}
	/**
	 * 次の行数を返す（イテレーション用）
	 * @return void
	 */
	public function next()
	{
		$this->position++;
	}
	/**
	 * 現在行が存在するか？（イテレーション用）
	 * @return bool
	 */
	public function valid()
	{
		if(!isset($this->lines[$this->position])){
			return false;
		}
		if(count($this->lines[$this->position])===0){
			return false;
		}
		return true;
	}
}
