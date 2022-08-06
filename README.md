# Symfony UX Live Component Form playground

Minimal working code examples to demonstrate things I'm talking about elsewhere (PRs, bug reports etc).

*Purpose:*

This app is supposed to demonstrate that the repeated rendering of forms in Symfony UX Live Component doesn't work well:

1. The UX Live Component mechanism doesn't play well with UX Autocompleter: the autocompleter turns back to simple select on live reload
2. The form structure does not change on live reloads: the `ratingValue` field should show up and disappear depending on the `ratingAllowed` checkbox state.

*How to use:*

This app is built with `symfony new` and uses sqlite.

1. `composer install && npm install && npx encore production && bin/console doctrine:schema:update --force` will set you up
2. `symfony serve` will start the server
3. When you open the [default homepage](https://127.0.0.1:8000) for the first time, the system creates some fixtures, so you're good to go.

