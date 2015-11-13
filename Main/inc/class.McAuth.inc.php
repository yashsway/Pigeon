<?php
/* McAuth */
class McAuth
{	
	
	//everhting is done through this function, $iv=dont worry about it, leave it as it is.
    public static function getMcAuthParameters($McAuthToken, $key, $iv = "satt2012")
    {

    	    $decryptedToken = McAuth::decryptToken($McAuthToken, $key, $iv);
	        $decompressedToken = McAuth::decompressToken($decryptedToken);

        return McAuth::parseDecryptedToken($decompressedToken); //returns an associative array
    }

    public static function decryptToken($encryptedText, $key, $iv = "satt2012")
    {
        $encryptedText = McAuth::hexDecode($encryptedText);
        $decryptedString = mcrypt_decrypt(MCRYPT_3DES, $key, $encryptedText, MCRYPT_MODE_CBC, $iv);
        $decryptedString = McAuth::pkcs7_pad($decryptedString);

        return rtrim($decryptedString, "\0\4");
    }


    public static function hexDecode($str)
    {
        $string='';
        for ($i=0; $i < strlen($str)-1; $i+=2)
            $string .= chr(hexdec($str[$i].$str[$i+1]));

        return $string;
    }

    public static function pkcs7_pad($text)
    {
        $blocksize = mcrypt_get_block_size(MCRYPT_3DES, MCRYPT_MODE_CBC);
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    public static function decompressToken($str)
    {
        return gzinflate(substr($str,10,-8));
//         return gzdecode($str);
    }

    private static function parseDecryptedToken($stringToParse)
    {
        $paramIndex = strpos($stringToParse, "!");

        if ($paramIndex == false)
        {
            $paramIndex = strpos($stringToParse, "=");

            if ($paramIndex == false)
            {
                //contains no authz field
                $authzs = explode(":", $stringToParse); //splits parameters
                if (count($authzs) >=2)
                    return $authzs;
                else
                    return $stringToParse;
            }
            else
            {
                //contains one authz field.
                $output = array();
                $nvp = explode("=", $stringToParse, 2); //splits parameters

                $authz_name = $nvp[0];
                $authz_value = $nvp[1];

                $output[$authz_name] = $authz_value;
                $params = explode(";", $authz_value);

                $temp_out = array();
                foreach( $params as $param )
                {
                    list($x,$y) = explode(":", $param, 2);
                    $temp_out[$x] = $y;

                }
                $output[$authz_name] = $temp_out;
                $output[$authz_name]['all_token'] = $authz_value;
                return $output;
            }
        }
        else
        {
            //contains multiple authz fields. E.g.
            //authz=referenceNo:0001234567;applicIdNo:0123467;!authzStPgm=referenceNo:00013457;studentNo:01234567;
            $authzs = explode("!", $stringToParse); //splits parameters
            $output = array();
            foreach ($authzs as $authz)
            {
                $nvp = explode("=", $authz, 2); //splits parameters

                $authz_name = $nvp[0];
                $authz_value = $nvp[1];

                $output[$authz_name] = $authz_value;
                $params = explode(";", $authz_value);

                $temp_out = array();
                foreach( $params as $param )
                {
                    list($x,$y) = explode(":", $param, 2);
                    $temp_out[$x] = $y;

                }
                $output[$authz_name] = $temp_out;
                $output[$authz_name]['all_token'] = $authz_value;
            }
            return $output;
        }
    }

    public static function gzdecode($data)
    {
        return gzinflate(substr($data,10,-8));
    }
}