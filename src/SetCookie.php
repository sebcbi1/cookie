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
    private $response ;

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

        $this->cookies[$name]->setValue('deleted');
        $this->cookies[$name]->setExpire((new \DateTime())->setTimestamp(0));

        return $this->response->withHeader('Set-Cookie', array_map('strval', $this->cookies));
    }


    private function decodeCookiesFromHeaders()
    {
        $headers = $this->response->getHeader('set-cookie');
        foreach ($headers as $line) {
            $name = $value = $expires =  $path = $domain = $secure = $httponly = null;
            $pieces = array_filter(array_map('trim', explode(';', $line)));

            list($name, $value) = explode('=', $pieces[0], 2);
            $pieces = array_slice($pieces, 1);

            // Add the cookie pieces into the parsed data array
            foreach ($pieces as $part) {
                $cookieParts = explode('=', $part, 2);
                $key = strtolower(trim($cookieParts[0]));
                $value = isset($cookieParts[1])
                    ? trim($cookieParts[1], " \n\r\t\0\x0B")
                    : true;
                if (in_array($key, ['path', 'domain', 'secure', 'httponly'])) {
                    $$key = $value;
                }
                if ($key == 'expires') {
                    $expires = new \DateTime($value);
                }

            }
            $this->cookies[$name] = new Cookie($name, $value, $expires, $path, $domain, $secure, $httponly);
        }
    }




}
