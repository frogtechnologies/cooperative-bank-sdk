<?

interface HttpRequest
{
    public function setOptions($options);
    public function execute();
    public function getInfo($name);
    public function close();
}

class CurlRequest implements HttpRequest
{
    private $handle = null;

    public function __construct($url) {
        $this->handle = curl_init($url);
    }

    public function setOptions($options) {
        curl_setopt_array($this->handle, $options)
    }

    public function execute() {
        return curl_exec($this->handle);
    }

    public function getInfo($name) {
        return curl_getinfo($this->handle, $name);
    }

    public function close() {
        curl_close($this->handle);
    }
}