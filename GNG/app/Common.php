<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use File;
use DB;

class Common extends Model
{

    public static function send_push($topic,$title="GNG",$message,$plateform="",$image="",$flag=0)
    {
        if($flag == 1){
            
            $customData =  array("message" =>$message);
                
            $url = 'https://fcm.googleapis.com/fcm/send';

            $api_key = env('FCM_TOKEN');

            // $fields = array (
            //     'registration_ids' => array (
            //         $topic
            //     ),
            //     'data' => $customData
            // );

            $body = $message;
            $notification = array('title' =>$title , 'body' => $body, 'sound' => 'default', 'badge' => '1');
            $fields = array('to' => '/topics/Veggi', 'notification' => $notification,'priority'=>'high');

            $headers = array(
                'Content-Type:application/json',
                'Authorization:key='.$api_key
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            // print_r(json_encode($fields));
            $result = curl_exec($ch);
          
            if ($result === FALSE) {
                die('FCM Send Error: ' . curl_error($ch));
            }
            curl_close($ch);
            
            return $result;

        }else{
            if($plateform == 1)
            {
                $customData =  array("message" =>$message);
                
                $url = 'https://fcm.googleapis.com/fcm/send';

                $api_key = env('FCM_TOKEN');

                // $fields = array (
                //     'registration_ids' => array (
                //         $topic
                //     ),
                //     'data' => $customData
                // );

                $body = $message;
                $notification = array('title' => $title , 'body' => $body, 'sound' => 'default', 'badge' => '1');
                $fields = array('to' => $topic, 'notification' => $notification,'priority'=>'high');

                $headers = array(
                    'Content-Type:application/json',
                    'Authorization:key='.$api_key
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                // print_r(json_encode($fields));
                $result = curl_exec($ch);
                if ($result === FALSE) {
                    die('FCM Send Error: ' . curl_error($ch));
                }
                curl_close($ch);
                
                return $result;
            }
            else
            {
                $url = 'https://fcm.googleapis.com/fcm/send';

                $api_key = env('FCM_TOKEN');

                $msg = array ( 'title' => $title, 'body' => $message);

                $message = array(
                    "message" => $title,
                    "data" => $message,
                );
        
                $data = array('registration_ids' => array($topic));
                $data['data'] = $message;
                $data['notification'] = $msg;
                $data['notification']['sound'] = "default";
            
                $headers = array(
                    'Content-Type:application/json',
                    'Authorization:key='.$api_key
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                //echo json_encode($data);
                $result = curl_exec($ch);
                if ($result === FALSE) {
                    die('FCM Send Error: ' . curl_error($ch));
                }
                curl_close($ch);
                // print_r($result);
                return $result;
            }
        }

    }

}
