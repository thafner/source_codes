<?php

namespace Drupal\atge_source_code_redirect;

use Drupal\Core\PathProcessor\InboundPathProcessorInterface;
use Drupal\redirect\Exception\RedirectLoopException;
use Drupal\redirect\RedirectChecker;
use Drupal\redirect\RedirectRepository;
use Drupal\Core\Routing\RequestContext;
use Drupal\Core\Language\LanguageManagerInterface;
use Symfony\Component\EventDispatcher\Event;

class SourceCodeRedirectReturnRedirect {

  /**
   * @var \Drupal\redirect\RedirectRepository
   */
  protected $redirectRepository;

  /**
   * @var \Drupal\redirect\RedirectChecker
   */
  protected $checker;

  /**
   * A path processor manager for resolving the system path.
   *
   * @var \Drupal\Core\PathProcessor\InboundPathProcessorInterface
   */
  protected $pathProcessor;

  /**
   * @var \Symfony\Component\Routing\RequestContext
   */
  protected $context;

  /**
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * SourceCodeRedirectReturnRedirect constructor.
   *
   * @param \Drupal\redirect\RedirectRepository $redirect_repository
   *   The redirect entity repository.
   * @param \Drupal\redirect\RedirectChecker $checker
   *   The redirect checker service.
   * @param \Drupal\Core\PathProcessor\InboundPathProcessorInterface $path_processor
   *   The path processor.
   * @param \Drupal\Core\Routing\RequestContext $context
   *   Request context.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager service.
   */
  public function __construct(RedirectRepository $redirect_repository, RedirectChecker $checker, InboundPathProcessorInterface $path_processor, RequestContext $context, LanguageManagerInterface $languageManager) {
    $this->redirectRepository = $redirect_repository;
    $this->checker = $checker;
    $this->pathProcessor = $path_processor;
    $this->context = $context;
    $this->languageManager = $languageManager;
  }

  /**
   * Get the redirect for the given kernel HTTP request.
   *
   * This follows the exact same logic as the redirect module.
   *
   * @param \Symfony\Component\EventDispatcher\Event $event
   *   The dispatched event.
   *
   * @return \Drupal\redirect\Entity\Redirect
   *   The redirect associated with the current request.
   *
   *  @see \Drupal\redirect\EventSubscriber\RedirectRequestSubscriber::onKernelRequestCheckRedirect()
   */
  public function getRedirect(Event $event) {
    // Get a clone of the request. During inbound processing the request
    // can be altered. Allowing this here can lead to unexpected behavior.
    // For example the path_processor.files inbound processor provided by
    // the system module alters both the path and the request; only the
    // changes to the request will be propagated, while the change to the
    // path will be lost.
    $request = clone $event->getRequest();

    if (!$this->checker->canRedirect($request)) {
      return NULL;
    }

    // Get URL info and process it to be used for hash generation.
    parse_str($request->getQueryString(), $request_query);

    if (strpos($request->getPathInfo(), '/system/files/') === 0 && !$request->query->has('file')) {
      // Private files paths are split by the inbound path processor and the
      // relative file path is moved to the 'file' query string parameter. This
      // is because the route system does not allow an arbitrary amount of
      // parameters. We preserve the path as is returned by the request object.
      // @see \Drupal\system\PathProcessor\PathProcessorFiles::processInbound()
      $path = $request->getPathInfo();
    }
    else {
      // Do the inbound processing so that for example language prefixes are
      // removed.
      $path = $this->pathProcessor->processInbound($request->getPathInfo(), $request);
    }
    $path = trim($path, '/');

    $this->context->fromRequest($request);

    try {
      $redirect = $this->redirectRepository->findMatchingRedirect($path, $request_query, $this->languageManager->getCurrentLanguage()->getId());
    }
    catch (RedirectLoopException $e) {
      $redirect = NULL;
    }

    return $redirect;
  }

}

