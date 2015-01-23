<?php

// borrows code examples from comments in http://us.php.net/fsockopen
class WebTools
{
    static function do_get_request($url)
    {
        $parsed = parse_url($url);
        $host = $parsed['host'];
        $port = 80;
        if (array_key_exists('port', $parsed)) {
            $port = $parsed['port'];
        }
        $path = $parsed['path'];
        $query = isset($parsed['query']) ? "?" . $parsed['query'] : "";
        $fragment = isset($parsed['fragment']) ? "#" . $parsed['fragment'] : "";
        $path = "$path$query$fragment";
        $request = "GET $path HTTP/1.0\r\nHost: $host\r\n\r\n";
        $fp = fsockopen($host, $port, $errno, $errstr, 30);
        if ($fp) {
            fputs($fp, $request);
            $result = '';
            $headerpassed = false;
            while (!feof($fp)) {
                $result .= fgets($fp, 4096);
            }

            $hunks = explode("\r\n\r\n", $result);
            fclose($fp);
            array_shift($hunks);
            return implode($hunks);
        }

    }

    static function do_get_request_http11($url)
    {
        $parsed = parse_url($url);
        $host = $parsed['host'];
        $port = 80;
        if (array_key_exists('port', $parsed)) {
            $port = $parsed['port'];
        }
        $path = $parsed['path'];
        $query = isset($parsed['query']) ? "?" . $parsed['query'] : "";
        $fragment = isset($parsed['fragment']) ? "#" . $parsed['fragment'] : "";
        $path = "$path$query$fragment";
        $fp = fsockopen($host, $port, $errno, $errstr, 30);
        $request = "GET $path HTTP/1.1\r\n"
            . "Host: $host\r\n"
            . "Connection: close\r\n\r\n";
        if ($fp) {
            fputs($fp, $request);
            $result = '';
            $headerpassed = false;
            while (!feof($fp)) {
                $result .= fgets($fp, 4096);
            }

            //TODO: hunks are wrong?
            //$hunks = explode("\r\n\r\n", $result);
            fclose($fp);
            //return $hunks[count($hunks) - 1];
            return self::parseHttpResponse($result);
        }
        return null;
    }

    static function parseHttpResponse($content = null)
    {
        if (empty($content)) {
            return "";
        }
        // split into array, headers and content.
        $hunks = explode("\r\n\r\n", trim($content));
        if (!is_array($hunks) or count($hunks) < 2) {
            return "";
        }
        $header = $hunks[count($hunks) - 2];
        $body = $hunks[count($hunks) - 1];
        $headers = explode("\n", $header);
        unset($hunks);
        unset($header);

        if (in_array('Transfer-Coding: chunked', $headers)) {
            return trim(self::unchunkHttpResponse($body));
        } else {
            return trim($body);
        }
    }

    static function unchunkHttpResponse($str = null)
    {
        if (!is_string($str) or strlen($str) < 1) {
            return false;
        }
        $eol = "\r\n";
        $add = strlen($eol);
        $tmp = $str;
        $str = '';
        do {
            $tmp = ltrim($tmp);
            $pos = strpos($tmp, $eol);
            if ($pos === false) {
                return false;
            }
            $len = hexdec(substr($tmp, 0, $pos));
            if (!is_numeric($len) or $len < 0) {
                return false;
            }
            $str .= substr($tmp, ($pos + $add), $len);
            $tmp = substr($tmp, ($len + $pos + $add));
            $check = trim($tmp);
        } while (!empty($check));
        unset($tmp);
        return $str;
    }
}

?>