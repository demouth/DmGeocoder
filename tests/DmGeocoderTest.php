<?php

class EmployeeTest extends PHPUnit_Framework_TestCase
{
	
	public function testAddressオブジェクトのプロパティ()
	{
		$addresses = Dm_Geocoder::geocode('沖縄県八重山郡与那国町与那国');
		$address = $addresses[0];
		$this->assertInstanceOf('Dm_Geocoder_Address',$address);
		$this->assertSame($address->prefectureCode,47);
		$this->assertSame($address->municipalityCode,47382);
		$this->assertSame($address->localCode,473820001000);
		$this->assertSame($address->lat,24.468119);
		$this->assertSame($address->lng,123.004341);
		$this->assertEquals($address->prefectureName,'沖縄県');
		$this->assertEquals($address->municipalityName,'八重山郡与那国町');
		$this->assertEquals($address->localName,'与那国');
		$this->assertEquals(count($addresses), 1);
	}
	
	public function testCSVの一番最初と一番最後の行()
	{
		//一番最初
		$addresses = Dm_Geocoder::geocode('北海道札幌市中央区旭ヶ丘一丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'北海道');
		$this->assertEquals($address->municipalityName,'札幌市中央区');
		$this->assertEquals($address->localName,'旭ヶ丘一丁目');
		$this->assertEquals(count($addresses), 1);
		
		//一番最後
		$addresses = Dm_Geocoder::geocode('北海道目梨郡羅臼町礼文町');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'北海道');
		$this->assertEquals($address->municipalityName,'目梨郡羅臼町');
		$this->assertEquals($address->localName,'礼文町');
		$this->assertEquals(count($addresses), 1);
	}
	
	public function test半角数字の番地()
	{
		$addresses = Dm_Geocoder::geocode('北海道札幌市中央区大通西17丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'北海道');
		$this->assertEquals($address->municipalityName,'札幌市中央区');
		$this->assertEquals($address->localName,'大通西十七丁目');
		$this->assertEquals(count($addresses), 1);
	}
	
	public function test全角数字の番地()
	{
		$addresses = Dm_Geocoder::geocode('北海道札幌市北区百合が原１１丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'北海道');
		$this->assertEquals($address->municipalityName,'札幌市北区');
		$this->assertEquals($address->localName,'百合が原十一丁目');
	}
	
	public function test全角漢数字の番地()
	{
		$addresses = Dm_Geocoder::geocode('青森県弘前市大字館野二丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'青森県');
		$this->assertEquals($address->municipalityName,'弘前市');
		$this->assertEquals($address->localName,'大字館野二丁目');
		$this->assertEquals(count($addresses), 1);
	}
	
	public function test検索結果０件()
	{
		$addresses = Dm_Geocoder::geocode('ほげほげ');
		$this->assertEquals(count($addresses), 0);
	}
	
	public function test半角スペース有り()
	{
		$addresses = Dm_Geocoder::geocode('  宮城県  塩竈市 千賀の台   二丁目  ');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'宮城県');
		$this->assertEquals($address->municipalityName,'塩竈市');
		$this->assertEquals($address->localName,'千賀の台二丁目');
		$this->assertEquals(count($addresses), 1);
	}
	
	public function test全角スペース有り()
	{
		$addresses = Dm_Geocoder::geocode('　宮　城　県　塩竈市　千賀　の　台　二丁目　');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'宮城県');
		$this->assertEquals($address->municipalityName,'塩竈市');
		$this->assertEquals($address->localName,'千賀の台二丁目');
		$this->assertEquals(count($addresses), 1);
	}
	
	public function test県名省略()
	{
		$addresses = Dm_Geocoder::geocode('塩竈市千賀の台二丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'宮城県');
		$this->assertEquals($address->municipalityName,'塩竈市');
		$this->assertEquals($address->localName,'千賀の台二丁目');
		$this->assertEquals(count($addresses), 1);
	}
	
	public function test郵便番号等の文字列()
	{
		//参考1 中華Dining 東海飯店 大門本店
		//http://r.gnavi.co.jp/a136700/map/
		$addresses = Dm_Geocoder::geocode('〒105-0012 東京都港区芝大門2-4-18');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'東京都');
		$this->assertEquals($address->municipalityName,'港区');
		$this->assertEquals($address->localName,'芝大門二丁目');
		$this->assertEquals(count($addresses), 1);
		
		//参考2 ららぽーとTOKYO-BAY
		//http://tokyobay.lalaport.net/
		$addresses = Dm_Geocoder::geocode('〒273-8530 千葉県船橋市浜町 2-1-1');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'千葉県');
		$this->assertEquals($address->municipalityName,'船橋市');
		$this->assertEquals($address->localName,'浜町二丁目');
		$this->assertEquals(count($addresses), 1);
		
	}
	
	/**
	 * 建物名込みの住所検索
	 * 建物名があったとしても無視して検索を行う
	 * 
	 */
	public function test建物名込み()
	{
		
		//参考 レストラン タテル ヨシノ 芝
		//http://tabelog.com/tokyo/A1314/A131401/13004386/
		
		$addresses = Dm_Geocoder::geocode('東京都港区芝公園1-5-10 芝パークホテル 別館 1F');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'東京都');
		$this->assertEquals($address->municipalityName,'港区');
		$this->assertEquals($address->localName,'芝公園一丁目');
		
		$this->assertEquals(count($addresses), 1);
		
	}
	
	public function test不完全な住所検索()
	{
		
		$addresses = Dm_Geocoder::geocode('東京都港区芝公園');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'東京都');
		$this->assertEquals($address->municipalityName,'港区');
		$this->assertEquals($address->localName,'芝公園一丁目');
		$this->assertEquals($addresses[1]->localName,'芝公園二丁目');
		$this->assertEquals($addresses[2]->localName,'芝公園三丁目');
		$this->assertEquals($addresses[3]->localName,'芝公園四丁目');
		
		$this->assertEquals(count($addresses), 4);
	}
	
	
	public function test都道府県名のみ()
	{
		$addresses = Dm_Geocoder::geocode('愛媛県');
		//県名が同じで市区町村名が違うレコードが2439件ある
		$this->assertEquals(count($addresses), 2439);
	}
	
	public function test都道府県名と市区町村名()
	{
		$addresses = Dm_Geocoder::geocode('香川県仲多度郡まんのう町');
		//市区町村名が同じで大字町丁目名が違うレコードが30件ある
		$this->assertEquals(count($addresses), 30);
	}
	
	/**
	 * すべての県を対象に動作するかどうかを確認する
	 */
	public function test全県分()
	{
		//北海道
		$addresses = Dm_Geocoder::geocode('北海道札幌市北区百合が原十一丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'北海道');
		$this->assertEquals($address->municipalityName,'札幌市北区');
		$this->assertEquals($address->localName,'百合が原十一丁目');
		//青森県
		$addresses = Dm_Geocoder::geocode('青森県弘前市大字館野二丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'青森県');
		$this->assertEquals($address->municipalityName,'弘前市');
		$this->assertEquals($address->localName,'大字館野二丁目');
		//岩手県
		$addresses = Dm_Geocoder::geocode('岩手県盛岡市玉山区玉山字大平');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'岩手県');
		$this->assertEquals($address->municipalityName,'盛岡市');
		$this->assertEquals($address->localName,'玉山区玉山字大平');
		//宮城県
		$addresses = Dm_Geocoder::geocode('宮城県仙台市青葉区吉成台二丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'宮城県');
		$this->assertEquals($address->municipalityName,'仙台市青葉区');
		$this->assertEquals($address->localName,'吉成台二丁目');
		//秋田県
		$addresses = Dm_Geocoder::geocode('秋田県秋田市下浜名ケ沢字カノエツカ');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'秋田県');
		$this->assertEquals($address->municipalityName,'秋田市');
		$this->assertEquals($address->localName,'下浜名ケ沢字カノエツカ');
		//山形県
		$addresses = Dm_Geocoder::geocode('山形県山形市印役町四丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'山形県');
		$this->assertEquals($address->municipalityName,'山形市');
		$this->assertEquals($address->localName,'印役町四丁目');
		//福島県
		$addresses = Dm_Geocoder::geocode('福島県福島市飯野町青木字登木戸');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'福島県');
		$this->assertEquals($address->municipalityName,'福島市');
		$this->assertEquals($address->localName,'飯野町青木字登木戸');
		//茨城県
		$addresses = Dm_Geocoder::geocode('茨城県水戸市松本町');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'茨城県');
		$this->assertEquals($address->municipalityName,'水戸市');
		$this->assertEquals($address->localName,'松本町');
		//栃木県
		$addresses = Dm_Geocoder::geocode('栃木県宇都宮市刈沼町');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'栃木県');
		$this->assertEquals($address->municipalityName,'宇都宮市');
		$this->assertEquals($address->localName,'刈沼町');
		//群馬県
		$addresses = Dm_Geocoder::geocode('群馬県沼田市新町');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'群馬県');
		$this->assertEquals($address->municipalityName,'沼田市');
		$this->assertEquals($address->localName,'新町');
		//埼玉県
		$addresses = Dm_Geocoder::geocode('埼玉県さいたま市大宮区浅間町一丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'埼玉県');
		$this->assertEquals($address->municipalityName,'さいたま市大宮区');
		$this->assertEquals($address->localName,'浅間町一丁目');
		//千葉県
		$addresses = Dm_Geocoder::geocode('千葉県千葉市花見川区作新台六丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'千葉県');
		$this->assertEquals($address->municipalityName,'千葉市花見川区');
		$this->assertEquals($address->localName,'作新台六丁目');
		//東京都
		$addresses = Dm_Geocoder::geocode('東京都中央区日本橋人形町三丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'東京都');
		$this->assertEquals($address->municipalityName,'中央区');
		$this->assertEquals($address->localName,'日本橋人形町三丁目');
		//神奈川県
		$addresses = Dm_Geocoder::geocode('神奈川県横浜市中区末吉町二丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'神奈川県');
		$this->assertEquals($address->municipalityName,'横浜市中区');
		$this->assertEquals($address->localName,'末吉町二丁目');
		//新潟県
		$addresses = Dm_Geocoder::geocode('新潟県新潟市東区津島屋一丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'新潟県');
		$this->assertEquals($address->municipalityName,'新潟市東区');
		$this->assertEquals($address->localName,'津島屋一丁目');
		//富山県
		$addresses = Dm_Geocoder::geocode('富山県富山市館出町一丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'富山県');
		$this->assertEquals($address->municipalityName,'富山市');
		$this->assertEquals($address->localName,'館出町一丁目');
		//石川県
		$addresses = Dm_Geocoder::geocode('石川県金沢市玉鉾三丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'石川県');
		$this->assertEquals($address->municipalityName,'金沢市');
		$this->assertEquals($address->localName,'玉鉾三丁目');
		//福井県
		$addresses = Dm_Geocoder::geocode('福井県福井市照手二丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'福井県');
		$this->assertEquals($address->municipalityName,'福井市');
		$this->assertEquals($address->localName,'照手二丁目');
		//山梨県
		$addresses = Dm_Geocoder::geocode('山梨県富士吉田市上吉田五丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'山梨県');
		$this->assertEquals($address->municipalityName,'富士吉田市');
		$this->assertEquals($address->localName,'上吉田五丁目');
		//長野県
		$addresses = Dm_Geocoder::geocode('長野県松本市北深志三丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'長野県');
		$this->assertEquals($address->municipalityName,'松本市');
		$this->assertEquals($address->localName,'北深志三丁目');
		//岐阜県
		$addresses = Dm_Geocoder::geocode('岐阜県岐阜市千畳敷');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'岐阜県');
		$this->assertEquals($address->municipalityName,'岐阜市');
		$this->assertEquals($address->localName,'千畳敷');
		//静岡県
		$addresses = Dm_Geocoder::geocode('静岡県静岡市葵区美川町');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'静岡県');
		$this->assertEquals($address->municipalityName,'静岡市葵区');
		$this->assertEquals($address->localName,'美川町');
		//愛知県
		$addresses = Dm_Geocoder::geocode('愛知県名古屋市中村区草薙町一丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'愛知県');
		$this->assertEquals($address->municipalityName,'名古屋市中村区');
		$this->assertEquals($address->localName,'草薙町一丁目');
		//三重県
		$addresses = Dm_Geocoder::geocode('三重県桑名市吉津屋町');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'三重県');
		$this->assertEquals($address->municipalityName,'桑名市');
		$this->assertEquals($address->localName,'吉津屋町');
		//滋賀県
		$addresses = Dm_Geocoder::geocode('滋賀県大津市葛川貫井町');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'滋賀県');
		$this->assertEquals($address->municipalityName,'大津市');
		$this->assertEquals($address->localName,'葛川貫井町');
		//京都府
		$addresses = Dm_Geocoder::geocode('京都府京田辺市草内柳田');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'京都府');
		$this->assertEquals($address->municipalityName,'京田辺市');
		$this->assertEquals($address->localName,'草内柳田');
		//大阪府
		$addresses = Dm_Geocoder::geocode('大阪府大阪市都島区毛馬町二丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'大阪府');
		$this->assertEquals($address->municipalityName,'大阪市都島区');
		$this->assertEquals($address->localName,'毛馬町二丁目');
		//兵庫県
		$addresses = Dm_Geocoder::geocode('兵庫県神戸市灘区大土平町二丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'兵庫県');
		$this->assertEquals($address->municipalityName,'神戸市灘区');
		$this->assertEquals($address->localName,'大土平町二丁目');
		//奈良県
		$addresses = Dm_Geocoder::geocode('奈良県奈良市六条三丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'奈良県');
		$this->assertEquals($address->municipalityName,'奈良市');
		$this->assertEquals($address->localName,'六条三丁目');
		//和歌山県
		$addresses = Dm_Geocoder::geocode('和歌山県和歌山市田中町三丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'和歌山県');
		$this->assertEquals($address->municipalityName,'和歌山市');
		$this->assertEquals($address->localName,'田中町三丁目');
		//鳥取県
		$addresses = Dm_Geocoder::geocode('鳥取県鳥取市猪子');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'鳥取県');
		$this->assertEquals($address->municipalityName,'鳥取市');
		$this->assertEquals($address->localName,'猪子');
		//島根県
		$addresses = Dm_Geocoder::geocode('島根県松江市北堀町');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'島根県');
		$this->assertEquals($address->municipalityName,'松江市');
		$this->assertEquals($address->localName,'北堀町');
		//岡山県
		$addresses = Dm_Geocoder::geocode('岡山県岡山市北区津島東二丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'岡山県');
		$this->assertEquals($address->municipalityName,'岡山市北区');
		$this->assertEquals($address->localName,'津島東二丁目');
		//広島県
		$addresses = Dm_Geocoder::geocode('広島県広島市東区矢賀新町五丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'広島県');
		$this->assertEquals($address->municipalityName,'広島市東区');
		$this->assertEquals($address->localName,'矢賀新町五丁目');
		//山口県
		$addresses = Dm_Geocoder::geocode('山口県下関市豊田町大字金道');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'山口県');
		$this->assertEquals($address->municipalityName,'下関市');
		$this->assertEquals($address->localName,'豊田町大字金道');
		//徳島県
		$addresses = Dm_Geocoder::geocode('徳島県徳島市鷹匠町五丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'徳島県');
		$this->assertEquals($address->municipalityName,'徳島市');
		$this->assertEquals($address->localName,'鷹匠町五丁目');
		//香川県
		$addresses = Dm_Geocoder::geocode('香川県高松市浜ノ町');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'香川県');
		$this->assertEquals($address->municipalityName,'高松市');
		$this->assertEquals($address->localName,'浜ノ町');
		//愛媛県
		$addresses = Dm_Geocoder::geocode('愛媛県松山市港山町');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'愛媛県');
		$this->assertEquals($address->municipalityName,'松山市');
		$this->assertEquals($address->localName,'港山町');
		//高知県
		$addresses = Dm_Geocoder::geocode('高知県高知市鏡小山');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'高知県');
		$this->assertEquals($address->municipalityName,'高知市');
		$this->assertEquals($address->localName,'鏡小山');
		//福岡県
		$addresses = Dm_Geocoder::geocode('福岡県北九州市門司区大字畑');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'福岡県');
		$this->assertEquals($address->municipalityName,'北九州市門司区');
		$this->assertEquals($address->localName,'大字畑');
		//佐賀県
		$addresses = Dm_Geocoder::geocode('佐賀県唐津市梨川内');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'佐賀県');
		$this->assertEquals($address->municipalityName,'唐津市');
		$this->assertEquals($address->localName,'梨川内');
		//長崎県
		$addresses = Dm_Geocoder::geocode('長崎県長崎市小峰町');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'長崎県');
		$this->assertEquals($address->municipalityName,'長崎市');
		$this->assertEquals($address->localName,'小峰町');
		//熊本県
		$addresses = Dm_Geocoder::geocode('熊本県熊本市南区奥古閑町');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'熊本県');
		$this->assertEquals($address->municipalityName,'熊本市南区');
		$this->assertEquals($address->localName,'奥古閑町');
		//大分県
		$addresses = Dm_Geocoder::geocode('大分県大分市大字高崎');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'大分県');
		$this->assertEquals($address->municipalityName,'大分市');
		$this->assertEquals($address->localName,'大字高崎');
		//宮崎県
		$addresses = Dm_Geocoder::geocode('宮崎県宮崎市源藤町西田');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'宮崎県');
		$this->assertEquals($address->municipalityName,'宮崎市');
		$this->assertEquals($address->localName,'源藤町西田');
		//鹿児島県
		$addresses = Dm_Geocoder::geocode('鹿児島県枕崎市大塚北町');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'鹿児島県');
		$this->assertEquals($address->municipalityName,'枕崎市');
		$this->assertEquals($address->localName,'大塚北町');
		//沖縄県
		$addresses = Dm_Geocoder::geocode('沖縄県那覇市首里末吉町四丁目');
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'沖縄県');
		$this->assertEquals($address->municipalityName,'那覇市');
		$this->assertEquals($address->localName,'首里末吉町四丁目');
	}
	
	public function test逆ジオコーディング()
	{
		$addresses = Dm_Geocoder::reverseGeocode(39.761437, 140.089602);
		
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'秋田県');
		$this->assertEquals($address->municipalityName,'秋田市');
		$this->assertEquals($address->localName,'将軍野青山町');
		
		$address = $addresses[1];
		$this->assertEquals($address->prefectureName,'秋田県');
		$this->assertEquals($address->municipalityName,'秋田市');
		$this->assertEquals($address->localName,'将軍野堰越');
		
		$address = $addresses[2];
		$this->assertEquals($address->prefectureName,'秋田県');
		$this->assertEquals($address->municipalityName,'秋田市');
		$this->assertEquals($address->localName,'寺内字通穴');
		
		$address = $addresses[3];
		$this->assertEquals($address->prefectureName,'秋田県');
		$this->assertEquals($address->municipalityName,'秋田市');
		$this->assertEquals($address->localName,'将軍野東四丁目');
		
		$address = $addresses[4];
		$this->assertEquals($address->prefectureName,'秋田県');
		$this->assertEquals($address->municipalityName,'秋田市');
		$this->assertEquals($address->localName,'寺内字将軍野');
		
		$address = $addresses[5];
		$this->assertEquals($address->prefectureName,'秋田県');
		$this->assertEquals($address->municipalityName,'秋田市');
		$this->assertEquals($address->localName,'飯島新町一丁目');
		
		$address = $addresses[6];
		$this->assertEquals($address->prefectureName,'秋田県');
		$this->assertEquals($address->municipalityName,'秋田市');
		$this->assertEquals($address->localName,'将軍野東三丁目');
		
		$address = $addresses[7];
		$this->assertEquals($address->prefectureName,'秋田県');
		$this->assertEquals($address->municipalityName,'秋田市');
		$this->assertEquals($address->localName,'飯島字大崩');
		
		$address = $addresses[8];
		$this->assertEquals($address->prefectureName,'秋田県');
		$this->assertEquals($address->municipalityName,'秋田市');
		$this->assertEquals($address->localName,'飯島字大袋');
		
		$address = $addresses[9];
		$this->assertEquals($address->prefectureName,'秋田県');
		$this->assertEquals($address->municipalityName,'秋田市');
		$this->assertEquals($address->localName,'飯島字長野');
		
		$this->assertEquals(count($addresses), 10);
	}
	
	public function test逆ジオコーディング件数指定()
	{
		$addresses = Dm_Geocoder::reverseGeocode(35.6882074,139.7001416, 3);
		
		$address = $addresses[0];
		$this->assertEquals($address->prefectureName,'東京都');
		$this->assertEquals($address->municipalityName,'渋谷区');
		$this->assertEquals($address->localName,'代々木二丁目');
		
		$address = $addresses[1];
		$this->assertEquals($address->prefectureName,'東京都');
		$this->assertEquals($address->municipalityName,'新宿区');
		$this->assertEquals($address->localName,'新宿四丁目');
		
		$address = $addresses[2];
		$this->assertEquals($address->prefectureName,'東京都');
		$this->assertEquals($address->municipalityName,'新宿区');
		$this->assertEquals($address->localName,'西新宿一丁目');
		
		$this->assertEquals(count($addresses), 3);
	}
	
	public function test逆ジオコーディング国外()
	{
		$addresses = Dm_Geocoder::reverseGeocode(10.0, 100.0);
		
		$this->assertEquals(count($addresses), 0);
	}
	
}
