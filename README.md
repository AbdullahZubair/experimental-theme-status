# Experimental Theme Status

Suppresses the status report warning for an experimental theme that you
have deliberately set as your site's default or admin theme.

## Why I built this

I started using Drupal's new admin theme while it was still labeled
experimental, because I had already tried it out and was happy with it.
It was a decision I made on purpose, not something I stumbled into. But
every time I checked my site's status report, Drupal warned me about it
anyway, the same way it would warn about a theme nobody had reviewed at
all.

I did not want to just silence status report warnings in general, since
that would hide a real problem right alongside the one I wanted to
ignore. What I wanted was something narrower: quiet this one specific
warning, and only when the theme it is about is one I actually chose on
purpose. So this module checks whatever theme is currently set as your
site's default or admin theme, and only removes the warning if that
particular theme is the experimental one. Any other experimental theme
sitting unused in your codebase still gets flagged normally, exactly as
it should.

It does not check for one theme by name anywhere in the code. It asks
your site what your active themes actually are, right now, and reacts to
that. So it keeps working automatically if you move to a different
experimental theme later, without needing a single change.

## This does not need to be removed once your theme goes stable

Drupal marks new themes as experimental until they have been tested
widely enough, and only then removes the warning itself. Once whatever
theme you are using graduates to stable, Drupal stops calling it
experimental, and this module simply has nothing left to suppress at
that point. It does not break, and it does not need to be uninstalled,
it just goes quiet. If you ever adopt another experimental theme down
the line, it starts doing its job again automatically.

## What this module does

Implements `hook_runtime_requirements_alter()`, the current Drupal core
hook for altering status report requirements, to remove the experimental
theme warning only when every experimental theme present on the site is
also the active default or admin theme. Any other experimental theme that
is installed but not actively used still triggers the normal warning, so
this never hides a real oversight.

No Drupal core files are modified.

## Requirements

Drupal core 11.2 or later, since `hook_runtime_requirements_alter()` was
introduced in that release.

## Installation

This module is not on Packagist or drupal.org, so Composer does not know
where to find it until you tell it. Run this once, from your project's
root folder, to register this repository:

```
composer config repositories.experimental-theme-status vcs https://github.com/AbdullahZubair/experimental-theme-status
```

That adds an entry to your project's `composer.json` under
`repositories`, pointing at this GitHub repository. You only need to do
this once per project, not once per version.

Then require and enable the module as usual:

```
composer require abdullahzubair/experimental-theme-status
drush en experimental_theme_status -y
drush cr
```

If you are not installing it through Composer, you can also place the
module folder directly in `modules/custom/experimental_theme_status` and
enable it the same way with `drush en`.

## Verifying it works

After enabling the module, a quick way to check it took effect:

- Visit your site's status report at Reports > Status report. If your
  active default or admin theme is experimental, the "Experimental
  themes installed" warning should no longer appear.
- If you have any other experimental theme installed but not set as
  your default or admin theme, the warning should still appear,
  mentioning that theme, since this module never hides a real
  oversight.
- Disable the module and reload the status report, the warning should
  come back for your active theme, confirming the module was doing
  something rather than the warning being gone for an unrelated reason.
