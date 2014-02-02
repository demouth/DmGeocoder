#DmGeocorder

[![License](https://poser.pugx.org/demouth/dmgeocoder/license.png)](https://packagist.org/packages/demouth/dmgeocoder)
[![Latest Stable Version](https://poser.pugx.org/demouth/dmgeocoder/v/stable.png)](https://packagist.org/packages/demouth/dmgeocoder) 
[![Latest Unstable Version](https://poser.pugx.org/demouth/dmgeocoder/v/unstable.png)](https://packagist.org/packages/demouth/dmgeocoder)

PHP製の日本国内用ジオコーディングライブラリです。  
住所から緯度経度を調べたり（ジオコーディング）、緯度経度から住所を調べたりすること（逆ジオコーディング）が可能です。

##特徴

- 一般的にプログラムからジオコーディングをする時、Google Geocording APIのようなジオコーディングAPIを利用しますが、当ライブラリは地理情報をライブラリ内に持ちジオコーディングAPIを利用しません。これによりジオコーディングAPIを利用した実装とは違い、回数制限など気にせず利用することが可能です。これがこのライブラリ最大の特徴です。
- 当ライブラリのソースをサーバーに配置するだけで利用可能です（ジオコーディングAPIやDBを使っていない為特にライブラリを利用するための準備、サーバー設定は不要）。
- 丁番号程度の多少の表記の揺らぎについては対応しています（詳しくは使い方を参照）。
- 日本国内のみに対応していて、国外のジオコーディングは出来ません。
- 緯度経度は世界測地系です。
- 精度は大字・町丁目レベルで、街区情報は持ちません。
- 位置情報は平成24年度版のデータを利用しています。翌年版のデータが公開されたらcsvディレクトリに配置されているcsvファイルを差し替えれば利用可能です。
- PHP5.0以上で利用可能で、PHP5.3以下でも使えるようにnamespaceは使用していません。

##使い方

####使い方１　住所名から緯度経度を取得する

住所文字列をDm_Geocoder::geocode()の第一引数に渡すと、引数を元に住所検索を行い、詳細な住所情報を返します。
この住所情報には緯度経度情報を含みます。  
可能性の高い位置情報が複数存在した場合は、それらの住所をすべて返します。

```php
$addresses = Dm_Geocoder::geocode('沖縄県八重山郡与那国町与那国');

echo count($addresses); // 1 (この場合は1)
$address = $addresses[0];

echo get_class($address); // Dm_Geocoder_Address
echo $address->lat; // 24.468119 (緯度)
echo $address->lng; // 123.004341 (経度)
echo $address->prefectureName; // 沖縄県 (都道府県名)
echo $address->municipalityName; // 八重山郡与那国町 (市区町村名)
echo $address->localName; // 与那国 (大字町丁目名)
echo $address->prefectureCode; // 47 (都道府県コード)
echo $address->municipalityCode; // 47382 (市区町村コード)
echo $address->localCode; // 473820001000 (大字町丁目コード)
```

検索結果が複数件存在した場合、より可能性の高い住所を複数返します。
```php
//検索結果が複数存在する場合
$addresses = Dm_Geocoder::geocode('東京都港区芝公園');
echo count($addresses); // 4 (検索結果に一〜四丁目と、複数存在する)
echo $addresses[0]->localName; // 芝公園一丁目
echo $addresses[1]->localName; // 芝公園二丁目
echo $addresses[2]->localName; // 芝公園三丁目
echo $addresses[3]->localName; // 芝公園四丁目

//住所の絞り込みが十分で、検索結果が1つまで絞りこまれている場合
$addresses = Dm_Geocoder::geocode('東京都港区芝公園一丁目');
echo count($addresses); // 1

//存在しない住所名での検索で、マッチする検索結果が1件も存在しない場合
$addresses = Dm_Geocoder::geocode('ほげほげ');
echo count($addresses); // 0
```

住所の表記ゆらぎをある程度サポートしていますので、下記例のような検索が可能です。  
ただし下記の例に出てこないような検索、例えば「新宿区」を「しんじゅくく」といったものには対応していません。

```php
//検索文字列の数字表記のゆらぎを吸収しているため、
//下記の3つはどれも同じ結果を返します
$addresses = Dm_Geocoder::geocode('北海道札幌市中央区大通西17丁目');
$addresses = Dm_Geocoder::geocode('北海道札幌市中央区大通西１７丁目');
$addresses = Dm_Geocoder::geocode('北海道札幌市中央区大通西十七丁目');

//郵便番号が含まれていたり、スペースが含まれていたり、
//大字町丁目以降の住所が含まれていても検索可能です
//この場合郵便番号と大字町丁目以降の住所は無視されます
//例： 中華Dining 東海飯店 大門本店 http://r.gnavi.co.jp/a136700/map/
$addresses = Dm_Geocoder::geocode('〒105-0012 東京都港区芝大門2-4-18');
echo $addresses[0]->localName; // 芝大門二丁目

//県名の省略も可能です
//ただしこの場合、多少検索処理に時間がかかります
$addresses = Dm_Geocoder::geocode('塩竈市千賀の台二丁目');

//県名だけの検索も可能です
//この場合、この県に所属する「大字町丁目」分の結果が返ります
$addresses = Dm_Geocoder::geocode('愛媛県');
echo count($addresses); // 2439
```

####使い方２　緯度経度から該当する住所を検索する

Dm_Geocoder::reverseGeocode(緯度,軽度)と渡すと、その緯度経度から近い順に住所情報を複数返します。
```php
$addresses = Dm_Geocoder::reverseGeocode(39.761437, 140.089602);
$addresses[0]->prefectureName; // 秋田県
$addresses[0]->municipalityName; // 秋田市
$addresses[0]->localName; // 将軍野青山町
$addresses[1]->localName; // 将軍野堰越
$addresses[2]->localName; // 寺内字通穴
$addresses[3]->localName; // 将軍野東四丁目
    :
    :

//該当する住所が日本国内に存在しな場合は結果を返しません
$addresses = Dm_Geocoder::reverseGeocode(10.0, 100.0);
echo count($addresses); // 0
```

第三引数に検索結果の返却数を指定できます
```php
//第三引数に渡した数分の住所を返します
$addresses = Dm_Geocoder::reverseGeocode(35.6882074,139.7001416, 3);
echo count($addresses); // 3

//第三引数を省略した場合、デフォルトで10件返します
$addresses = Dm_Geocoder::reverseGeocode(35.6882074,139.7001416);
echo count($addresses); // 10
```

##include/reqiure方法

下記のいずれかの方法でライブラリを読み込んでください。  
読み込みができていればクラスを使える状態になっています。

####composerを利用する場合  

下記のcomposer.jsonを書いてinstallしてください。
```javascript
{
    "require": {
        "demouth/dmgeocoder": "1.0.0"
    }
}
```

####手動でファイルをrequireする場合

ソースをダウンロードして、下記のようにsrcディレクトリ以下のソースを読み込んでください。

```php
//autoloaderを使わず、Classファイルを手動で読み込む場合は下記ファイルをすべて読み込んでください
$LIB_DIR = realpath(dirname(__FILE__).'/../src/').'/';
require_once $LIB_DIR.'Dm/Geocoder.php';
require_once $LIB_DIR.'Dm/Geocoder/Address.php';
require_once $LIB_DIR.'Dm/Geocoder/Prefecture.php';
require_once $LIB_DIR.'Dm/Geocoder/Query.php';
require_once $LIB_DIR.'Dm/Geocoder/GISCSV.php';
require_once $LIB_DIR.'Dm/Geocoder/GISCSV/Finder.php';
require_once $LIB_DIR.'Dm/Geocoder/GISCSV/Reader.php';
```


##注意点

- UTF-8以外はサポートしません。mb_internal_encoding('UTF-8');にした状態で、検索文字列にはUTF-8の文字列を渡してください。検索結果もUTF-8で返します。
