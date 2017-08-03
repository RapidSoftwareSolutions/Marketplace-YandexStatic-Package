<?php

    $app->post('/api/YandexStatic/getStaticMap', function ($request, $response) {


    // all param
    // alias => vendor name
    $option = array(
    'mapType' => 'l',
    'mapCenter' => 'll',
    'mapExtent' => 'spn',
    'mapCaliber' => 'z',
    'size' => 'size',
    'scale' => 'scale',
    'descLabel' => 'pt',
    'descFigure' => 'pl',
    'lang' => 'lang',
    'key' => 'key'
    );
    $arrayType = array('mapType');

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['mapType','mapCenter']);


    if(!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback']=='error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    $url = 'https://static-maps.yandex.ru/1.x/';
    $client = $this->httpClient;
        
    $part = explode(',',$postData['args']['mapCenter']);
        if(!empty($part[0]) && !empty($pArt[1]))
{
  $part[0] = trim($part[0]);
  $part[1] = trim($part[1]);
}

    $postData['args']['mapCenter'] = implode(',',array_reverse($part));

    foreach($option as $alias => $value)
    {
      if(!empty($postData['args'][$alias]))
      {
          if(in_array($alias,$arrayType))
          {
            $postData['args'][$alias] = implode(',',$postData['args'][$alias]);
          }
          $queryParam[$value] = $postData['args'][$alias];
      }
    }



    $resp =  $client->request('GET', $url ,['query' => $queryParam ] );
   try {
       if(in_array($resp->getStatusCode(), ['200', '201', '202', '203', '204'])) {
           $result['callback'] = 'success';
           $fullUrl = $url.'?';
           foreach($queryParam as $key => $value)
           {
             $fullUrl .= '&'.$key.'='.$value;
           }
           $result['contextWrites']['to'] =  $fullUrl;
       } else {
           $result['callback'] = 'error';
           $result['contextWrites']['to']['status_code'] = 'API_ERROR';
           $result['contextWrites']['to']['status_msg'] = "Wrong param";
       }
   } catch (\GuzzleHttp\Exception\ClientException $exception) {
       $responseBody = $exception->getResponse()->getBody()->getContents();
       if(empty(json_decode($responseBody))) {
           $out = $responseBody;
       } else {
           $out = json_decode($responseBody);
       }
       $result['callback'] = 'error';
       $result['contextWrites']['to']['status_code'] = 'API_ERROR';
       $result['contextWrites']['to']['status_msg'] = "Wrong param";
   } catch (GuzzleHttp\Exception\ServerException $exception) {
       $responseBody = $exception->getResponse()->getBody()->getContents();
       if(empty(json_decode($responseBody))) {
           $out = $responseBody;
       } else {
           $out = json_decode($responseBody);
       }
       $result['callback'] = 'error';
       $result['contextWrites']['to']['status_code'] = 'API_ERROR';
       $result['contextWrites']['to']['status_msg'] = "Wrong param";
   } catch (GuzzleHttp\Exception\ConnectException $exception) {
       $responseBody = $exception->getResponse()->getBody(true);
       $result['callback'] = 'error';
       $result['contextWrites']['to']['status_code'] = 'INTERNAL_PACKAGE_ERROR';
       $result['contextWrites']['to']['status_msg'] = 'Something went wrong inside the package.';
   }
   return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});
