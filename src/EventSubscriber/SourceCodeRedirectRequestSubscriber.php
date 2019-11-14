<?php

namespace Drupal\atge_source_code_redirect\EventSubscriber;

use Drupal\atge_source_code_redirect\SourceCodeRedirectRepository;
use Drupal\atge_source_code_redirect\SourceCodeRedirectCookieManager;
use Drupal\atge_source_code_redirect\SourceCodeRedirectReturnRedirect;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class SourceCodeRedirectRequestSubscriber.
 */
class SourceCodeRedirectRequestSubscriber implements EventSubscriberInterface {

  /**
   * The repository for source code redirect entities.
   *
   * @var \Drupal\atge_source_code_redirect\SourceCodeRedirectRepository
   */
  protected $sourceCodeRedirectRepository;

  /**
   * The cookie manager for source codes.
   *
   * @var \Drupal\atge_source_code_redirect\SourceCodeRedirectCookieManager
   */
  protected $sourceCodeRedirectCookieManager;

  /**
   * The repository for getting redirects.
   *
   * @var \Drupal\atge_source_code_redirect\SourceCodeRedirectReturnRedirect
   */
  protected $sourceCodeRedirectReturnRedirect;

  /**
   * SourceCodeRedirectRequestSubscriber constructor.
   *
   * @param \Drupal\atge_source_code_redirect\SourceCodeRedirectRepository $sourceCodeRedirectRepository
   *   The repository for source code redirect entities.
   * @param \Drupal\atge_source_code_redirect\SourceCodeRedirectCookieManager $sourceCodeRedirectCookieManager
   *   The cookie manager for source codes.
   * @param \Drupal\atge_source_code_redirect\SourceCodeRedirectReturnRedirect $sourceCodeRedirectReturnRedirect
   *   The repository for getting redirects.
   */
  public function __construct(SourceCodeRedirectRepository $sourceCodeRedirectRepository, SourceCodeRedirectCookieManager $sourceCodeRedirectCookieManager, SourceCodeRedirectReturnRedirect $sourceCodeRedirectReturnRedirect) {
    $this->sourceCodeRedirectRepository = $sourceCodeRedirectRepository;
    $this->sourceCodeRedirectCookieManager = $sourceCodeRedirectCookieManager;
    $this->sourceCodeRedirectReturnRedirect = $sourceCodeRedirectReturnRedirect;
  }

  /**
   * This method is called when the kernel.request is dispatched.
   *
   * @param \Symfony\Component\EventDispatcher\Event $event
   *   The dispatched event.
   */
  public function onKernelRequestCheckSourceCodeRedirect(Event $event) {
    // Step 1: get the redirect.
    $redirect = $this->sourceCodeRedirectReturnRedirect->getRedirect($event);

    // Step 2: if no redirect, return early.
    if (empty($redirect)) {
      return;
    }

    // Step 3: find source code related to redirect.
    $source_code = $this->sourceCodeRedirectRepository->findMatchingSourceCodeRedirect($redirect);

    // Step 4: if no source code, return.
    if (empty($source_code)) {
      return;
    }

    // Step 5: set cookie.
    $this->sourceCodeRedirectCookieManager->setCookie($source_code);

    // TODO: Confirm with Acquia whether we should set a no-cache header or if that causes more problems.
    // Prevent this route from being cached since there's a set cookie header.
    //\Drupal::service('page_cache_kill_switch')->trigger();
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    // This needs to run before Redirect::onKernelRequestCheckRedirect(), which
    // has a priority of 33. Otherwise, that aborts the request if no matching
    // route is found.
    $events[KernelEvents::REQUEST][] = ['onKernelRequestCheckSourceCodeRedirect', 34];
    return $events;
  }

}
