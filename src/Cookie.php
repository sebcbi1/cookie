<?php

/**
 * Project: auth-demo
 * User: sebcbi1
 * Date: 02/02/16
 * Time: 11:38
 */
namespace ResponseCookie;

class Cookie
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var DateTime
     */
    protected $expire ;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $domain;

    /**
     * @var boolean
     */
    protected $secure = false;

    /**
     * @var boolean
     */
    protected $httpOnly = false;

    /**
     * Cookie constructor.
     * @param string   $name
     * @param string   $value
     * @param DateTime $expire
     * @param string   $path
     * @param string   $domain
     * @param bool     $secure
     * @param bool     $httpOnly
     */
    public function __construct($name, $value = null, DateTime $expire = null, $path = null, $domain = null, $secure = null, $httpOnly = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->expire = $expire;
        $this->path = $path;
        $this->domain = $domain;
        $this->secure = $secure;
        $this->httpOnly = $httpOnly;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Cookie
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return Cookie
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getExpire()
    {
        return $this->expire;
    }

    /**
     * @param DateTime $expire
     * @return Cookie
     */
    public function setExpire($expire)
    {
        $this->expire = $expire;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Cookie
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     * @return Cookie
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isSecure()
    {
        return $this->secure;
    }

    /**
     * @param boolean $secure
     * @return Cookie
     */
    public function setSecure($secure)
    {
        $this->secure = $secure;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isHttpOnly()
    {
        return $this->httpOnly;
    }

    /**
     * @param boolean $httpOnly
     * @return Cookie
     */
    public function setHttpOnly($httpOnly)
    {
        $this->httpOnly = $httpOnly;

        return $this;
    }


    public function setDeleted()
    {
        $this->setValue('deleted');
        $this->setExpires((new \DateTime())->setTimestamp(0));
    }

    public function __toString()
    {
        $str = $this->name . '=' . $this->value . '; ';

        if (!empty($this->expire)) {
            $str .= 'Expires=' . gmdate('D, d M Y H:i:s \G\M\T', $this->expire) . '; ';
        }

        if (!empty($this->path)) {
            $str .= 'Path=' . $this->path. '; ';
        }

        if (!empty($this->domain)) {
            $str .= 'Domain=' . $this->domain. '; ';
        }

        if (!empty($this->secure)) {
            $str .= 'Secure; ';
        }

        if (!empty($this->httpOnly)) {
            $str .= 'HttpOnly; ';
        }

        return rtrim($str,'; ');
    }

}
