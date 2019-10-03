<?php
/**
 * @Author      by Darren
 * @DateTime    2019-09-11
 * 根据图片获取坐标等(需要开启exif扩展)
 * @param $image 传入的图片
 */
function ImageCoordinate($image)
{
	$results = [];
	$datai= exif_read_data($image);
	if (isset($datai['Make'])) {
		$results['device'] = $datai['Make'];	//设备名

	}
	if (isset($datai['Model'])) {
		$results['device'] = $datai['Model'];	//设备号

	}
	if(isset($datai['GPSLatitude'])){
		$data = 0;
		foreach ($datai['GPSLatitude'] as $key => $value) {
			$tmp = explode('/', $value);
			if ($key === 0) {
				$data += $tmp[0] / $tmp[1];
			} else if ($key === 1) {
				$data += $tmp[0] / $tmp[1] / 60;
			} else {
				$data += $tmp[0] / $tmp[1] / 3600;
			}
		}
		$results['GPSLatitude'] = $data;
	}
	if(isset($datai['GPSLongitude'])){
		$data = 0;
		foreach ($datai['GPSLongitude'] as $key => $value) {
			$tmp = explode('/', $value);
			if ($key === 0) {
				$data += $tmp[0] / $tmp[1];
			} else if ($key === 1) {
				$data += $tmp[0] / $tmp[1] / 60;
			} else {
				$data += $tmp[0] / $tmp[1] / 3600;
			}
		}
		$results['GPSLongitude'] = $data;
	}
	if (empty($datai['GPSLongitude']) || empty($datai['GPSLatitude'])) {
		return null;		//经度与维度,是一对,只要有一对,不满足,就直接返回null
	}
	// 到这里调用接口查询
	$api = 'https://api.i-lynn.cn/poi?location=';
	$res = json_decode(file_get_contents($api . round($results['GPSLongitude'], 6) . ',' . round($results['GPSLatitude'], 6)), true);
	$results['POI'] = $res['regeocode']['formatted_address'];
	return $results;
}


