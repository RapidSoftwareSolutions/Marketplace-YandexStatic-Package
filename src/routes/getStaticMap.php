<?php
    $app->post('/api/YandexStatic/getStaticMap', function ($request, $response) {

    //optional params
    $option = array('spn','z','size','scale','pt','scale','pt','pl','lang','key');


    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['l','ll']);


    if(!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback']=='error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $post_data = $validateRes;
    }

    $url = 'https://static-maps.yandex.ru/1.x/';
    $type = implode(',',$post_data['args']['l']);

  
    $cords = $post_data['args']['ll'];
    $client = $this->httpClient;
    $queryParam = array('l' => $type,'ll' => $cords);

    foreach($option as $key => $value)
    {
      if(!empty($post_data['args'][$value]))
      {
        $queryParam[$value] = $post_data['args'][$value];
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
