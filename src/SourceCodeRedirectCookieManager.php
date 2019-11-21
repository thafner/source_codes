<?php


namespace Drupal\demo_source_code_redirect;


class SourceCodeRedirectCookieManager {

  protected $cookie_name = 'CCN2';

  public function setCookie($cookie) {
    // Expire 90 days from now.
    $expire = time() + (90 * 24 * 60 * 60);
    setcookie($this->cookie_name, $cookie, $expire, '/');
  }

  public function getCookie() {
    $request = \Drupal::request();
    if ($request->cookies->has($this->cookie_name)) {
      return $request->cookies->get($this->cookie_name);
    }
    return NULL;
  }

}
