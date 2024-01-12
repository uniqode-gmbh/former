# Former: A CraftCMS 4 Module (alpha)

> **Please note: This is currently very alpha. We don't recommend to use it in your own projects at the moment.**

Comfortable handling forms with CraftCMS (> v4) and also sets local SMTP configs in dev environments.

---


### Module setup

---

1. Make sure the `email` property is set in *config/custom.php* (via *.env*)
2. Make sure the local SMTP properties are available in the *.env* file.
```apacheconf
    # Mailer
    SMTP_HOSTNAME=
    SMTP_USERNAME=
    SMTP_PASSWORD=
    SMTP_PORT=587
    SMTP_ENCRYPTION_METHOD=TLS
    SMTP_USE_AUTH=true
```
3. Add the module in *config/app.php* to the `modules` and `bootstrap` sections
4. Copy the */migrations/m231214_152920_create_former_table.php* to your root */migrations* folder
4. There should be a migration file called *migrations/m231214_152920_create_former_table.php*. Do the migration `php craft migrate/all`.

### How it works

---
*[tbd]*



### How to use

----

1. Create the form embed partial in the project (ex. /templates/partials/form.twig).
```html
    {% do view.registerAssetBundle("modules\\former\\Assets") %}

    <form data-former action="{{ action is defined ? action : actionUrl('/former/send') }}" method="{{ method is defined ? method : 'POST' }}">
        {{ csrfInput() }}
        <input type="hidden" name="@template" value="{{ template is defined ? template : 'generic' }}">
        <input type="hidden" name="@type" value="{{ type is defined ? type : 'contact' }}">
        <input type="hidden" name="@language" value="{{ language is defined ? language : 'de' }}">
        {% if postHook is defined  %}<input type="hidden" name="@postHook" value="{{ postHook }}">{% endif %}
        {% block form %} {% endblock %}
    <form>
```

2. Embed the form partial
```html
    {% embed '_partials/form' with { type: 'contact', template: 'contact' } %}
        {% block form %}
            <input type="hidden" name="form" value="Website leads form">
            <!-- your fields here -->
            <button type="submit">
                <span>Send</span>
            </button>
        {% endblock %}
    {% endembed %}
```
*Make sure that the submit button has the `<span>` inside because of loading animation*


### Form options

---

*[tbd]*
