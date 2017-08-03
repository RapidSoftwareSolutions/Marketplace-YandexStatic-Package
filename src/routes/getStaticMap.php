<?php

    $app->post('/api/YandexStatic/getStaticMap', function ($request, $response) {


    // all param
    // alias => vendor name
    $option = array(
    'mapType' => 'l',
    'mapCenter' => 'll',
    'viewportRange' => 'spn',
    'zoom' => 'z',
    'size' => 'size',
    'scale' => 'scale',
    'markersDefinitions' => 'pt',
    'geoFiguresDefinitions' => 'pl',
    'lang' => 'lang',
    'key' => 'key'
    );
    $arrayType = array('mapType');
    $tildaType = array('markersDefinitions','geoFiguresDefinitions');
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


    //alias for element map type
    foreach($postData['args']['mapType'] as $key => $value)
    {
      if($value == 'satellite')
      {
        $postData['args']['mapType'][$key] = 'sat';
      }

      if($value == 'geographicalNames')
      {
        $postData['args']['mapType'][$key] = 'skl';
      }
    }

    $part = explode(',',$postData['args']['mapCenter']);
        if(!empty($part[0]) && !empty($part[1]))
        {
          $part[0] = trim($part[0]);
          $part[1] = trim($part[1]);
        }

    $postData['args']['mapCenter'] = implode(',',array_reverse($part));

    //set alias
    foreach($option as $alias => $value)
    {
      if(!empty($postData['args'][$alias]))
      {
          if(in_array($alias,$arrayType))
          {
            $postData['args'][$alias] = implode(',',$postData['args'][$alias]);
          }

          if(in_array($alias,$tildaType))
          {
            $postData['args'][$alias] = implode('~',$postData['args'][$alias]);
          }

          $queryParam[$value] = $postData['args'][$alias];
      }
    }


    //for include traffic
    if(isset($postData['args']['showTraffic']) && $postData['args']['showTraffic'] == 'on')
    {
      $queryParam['l'] .= ',trf';
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
           $result['contextWrites']['to'] = array('status' => 'success','result' => ["link" => $fullUrl]);
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

