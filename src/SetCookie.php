<?php
/**
 * Project: auth-demo
 * User: sebcbi1
 * Date: 02/02/16
 * Time: 14:33
 */

namespace ResponseCookie;

use Psr\Http\Message\ResponseInterface;

class SetCookie
{
    /**
     * @var array Cookie
     */
    private $cookies = [];

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * SetCookie constructor.
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        $this->decodeCookiesFromHeaders();
    }

    public function set(Cookie $cookie)
    {
        $this->cookies[$cookie->getName()] = $cookie;
        return $this->response->withHeader('Set-Cookie', array_map('strval', $this->cookies));
    }

    public function delete($name, $cookieClassName = null)
    {
        if (!isset($this->cookies[$name])) {
            if (is_null($cookieClassName)) {
                $cookieClassName = \Cookie::class;
            }
            $this->cookies[$name] = new $cookieClassName($name);
        }

        $this->cookies[$name]->setValue(null);
        $this->cookies[$name]->setExpire((new \DateTime())->setTimestamp(0));

        return $this->response->withHeader('Set-Cookie', array_map('strval', $this->cookies));
    }

    private function decodeCookiesFromHeaders()
    {
        $headers = $this->response->getHeader('set-cookie');
        foreach ($headers as $line) {
            $cookie                            = $this->decodeHeaderLine($line);
            $this->cookies[$cookie->getName()] = $cookie;
        }
    }

    private function decodeHeaderLine($line)
    {
        $name   = $value   = $expires   = $path   = $domain   = $secure   = $httponly   = null;
        $pieces = array_filter(array_map('trim', explode(';', $line)));

        list($name, $value) = explode('=', $pieces[0], 2);
        $pieces             = array_slice($pieces, 1);

        // Add the cookie pieces into the parsed data array
        foreach ($pieces as $part) {
            $cookieParts = explode('=', $part, 2);
            $key         = strtolower(trim($cookieParts[0]));
            $val         = isset($cookieParts[1])
            ? trim($cookieParts[1], " \n\r\t\0\x0B")
            : true;
            if (in_array($key, ['path', 'domain', 'secure', 'httponly'])) {
                $$key = $val;
            }
            if ($key == 'expires') {
                $expires = new \DateTime($val);
            }

        }
        return new Cookie($name, $value, $expires, $path, $domain, $secure, $httponly);
    }

    public function setSessionCookie()
    {
        if (empty($this->cookies[session_name()])) {
            foreach (headers_list() as $header) {

                if (strpos($header, session_name()) !== false) {
                    $header = trim(str_ireplace('set-cookie:', '', $header));
                    return $this->set($this->decodeHeaderLine($header));
                }
            }
        }
        return $this->response;
    }
    
    public function setCookies()
    {
        foreach (headers_list() as $header) {
            if (strpos(strtolower($header), 'set-cookie:') !== false) {
                $header = trim(str_ireplace('set-cookie:', '', $header));
                $this->response = $this->set($this->decodeHeaderLine($header));
            }
        }
        return $this->response;
    }

}
