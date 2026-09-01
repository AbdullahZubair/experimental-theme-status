<?php

declare(strict_types=1);

namespace Drupal\experimental_theme_status\Hook;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Hook\Attribute\Hook;

/**
 * Alters status report requirements for experimental themes.
 */
final class RequirementsHooks {

  public function __construct(
    private readonly ConfigFactoryInterface $configFactory,
    private readonly ThemeHandlerInterface $themeHandler,
  ) {}

  /**
   * Implements hook_runtime_requirements_alter().
   *
   * Suppresses the experimental themes warning for the site's active
   * default and admin themes, which have been reviewed and accepted. Any
   * other experimental theme still triggers the warning normally.
   */
  #[Hook('runtime_requirements_alter')]
  public function requirementsAlter(array &$requirements): void {
    if (empty($requirements['experimental_themes'])) {
      return;
    }

    $theme_config = $this->configFactory->get('system.theme');
    $active_themes = [
      $theme_config->get('default'),
      $theme_config->get('admin'),
    ];

    $unacknowledged = [];
    foreach ($this->themeHandler->listInfo() as $name => $theme) {
      $lifecycle = $theme->info['lifecycle'] ?? NULL;
      if ($lifecycle === 'experimental' && !in_array($name, $active_themes, TRUE)) {
        $unacknowledged[] = $name;
      }
    }

    if (!$unacknowledged) {
      unset($requirements['experimental_themes']);
    }
  }

}
