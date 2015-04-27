<?php
/**
 * FacebookComponent class
 */
class ClsFacebookShare
{
    public $content;
    public $url;
    public $user;
    public $fb;


    /* app */
    public static function app($class = __CLASS__)
    {
        $facebook = new ClsFacebook();
        $classes = new $class();
        $classes->fb = $facebook->fb;
        $classes->user = $facebook->fb->getUser();
        $classes->setLinkDefault();
        return $classes;
    }

    /**
     * set Link Default
     */
    public function setLinkDefault()
    {
        $path = Yii::app()->request->requestUri;
        $host = Yii::app()->request->hostInfo;
        $this->url($host.$path);
    }

    /* set url */
    public function url($url)
    {
        if($url == null) {
            $this->setLinkDefault();
        } else {
            $this->url = $url;
        }
        return $this;
    }

    /* set content */
    public function content($content)
    {
        $this->content = $content;
        return $this;
    }

    /* share facebook*/
    public function share()
    {

        if ($this->user) {
            try {
                // Proceed knowing you have a logged in user who's authenticated.
                $this->fb->api('/me/feed/', 'post', array(
                    'link' => $this->url,
                    'message' => $this->content
                ));

            } catch (FacebookApiException $e) {
                error_log($e);
                $user = null;
            }
        }
    }
}
