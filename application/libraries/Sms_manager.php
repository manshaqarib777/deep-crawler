<?php

class Sms_manager{

    protected $user;
    protected $password;
    protected $recepients=array();

    // ========private methods=================
    private function run_curl($url)
    {
        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        // grab URL and pass it to the browser
        $response=curl_exec($ch);

        // close cURL resource, and free up system resources

        curl_close($ch);

        return $response;
    }



    public function set_credentioal($user,$password)
    {
        $this->user= $user;
        $this->password = $password;
    }

    public function get_credit()
    {
        $api_url=sprintf('http://app.planetgroupbd.com/api/command?username=%s&password=%s&cmd=Credits',
            $this->user,
            $this->password);

        $response = $this->run_curl($api_url);

        return $response;
    }


    public function check_delevary_report($msgid)
    {
        /**
         *  Report format is as below
         *  <DeliveryReport>
         *    <message id="044052206110128118" sentdate="2014/05/22 08:11:01" donedate="2014/05/22 08:11:05" status="DELIVERED" gsmerror="0" price="1.0" />
         *    <message id="044052206110129223" sentdate="2014/05/22 08:11:01" donedate="2014/05/22 08:11:44" status="DELIVERED" gsmerror="0" price="1.0" />
         *  </DeliveryReport>;
         */

        if(!is_array($msgid))
        {
            $msgid = array($msgid);
        }

        $strmid = implode(',',$msgid);

        $api_url=sprintf('http://app.planetgroupbd.com/api/v3/dr/pull?user=%s&password=%s&messageid=%s',
            $this->user,
            $this->password,
            $strmid
        );

        $response = $this->run_curl($api_url);

        if($response=='NO_DATA')
        {
            return 'no data';
        }
        else
        {
            $xml= simplexml_load_string($response);

            $result =NULL;
            foreach ($x->message as $msg)
            {

                $id = $msg->attributes()->id.'';
                $sentdate = $msg->attributes()->sentdate.'';
                $donedate = $msg->attributes()->donedate.'';
                $status = $msg->attributes()->status.'';
                $gsmerror =$msg->attributes()->gsmerror.'';
                $price =$msg->attributes()->price.'';

                $result[$id]=array(
                    'sentdate'=>$sentdate,
                    'donedate'=>$donedate,
                    'status'=>$status,
                    'gsmerror'=>$gsmerror,
                    'price'=>$price
                );

                return $result;
            }
        }
    }



    public function send_sms($msg, $recepient)
    {
        if(!is_array($recepient))
        {
            $recepient = array($recepient);
        }

        $str_recepient = implode(',',$recepient);

        $api_url = sprintf('http://app.planetgroupbd.com/api/sendsms/plain?user=%s&password=%s&sender=rajIT&SMSText=%s&GSM=%s',
            $this->user,
            $this->password,
            $msg,
            $str_recepient
        );
	
		$msg = str_replace(' ','%20',$msg);
        $mask=urlencode("XerOne IT");
	    $api_url="http://app.planetgroupbd.com/api/sendsms/plain?user={$this->user}&password={$this->password}&sender={$mask}&SMSText={$msg}&GSM={$str_recepient}";
        
        return $this->run_curl($api_url);

    }

}



?>