document.querySelectorAll('form[data-former]').forEach(form =>
{
    // Append loader
    let spinner = document.createElement('i');
    let submit= form.querySelector('button[type=submit]');

    spinner.classList.add('loader', 'hide');
    submit.append(spinner);

    form.addEventListener('submit', ev =>
    {
        ev.preventDefault();

        // Validate
        if (new Bouncer(null, { messages: validationMessages }).validateAll(form).length > 0)
        {
            form.classList.add('has-errors');

            return false;
        }

        form.classList.remove('has-errors');

        // Process
        form.classList.add('processing');
        submit.setAttribute('disabled', true);

        fetch(form.action, {
            method: 'POST',
            cache: 'no-cache',
            body: new FormData(form)
        })
        .then(response =>
        {
            let json =  response.json();
            let container = form.querySelector('[data-as="message-container"]') ?? form;

            container.classList.remove('is-failed', 'is-success');

            json.then(body => {
                response.ok
                    ? container.classList.add('is-success')
                    : container.classList.add('is-failed');

                container.innerHTML = `
                    <div class="form-notification ${response.ok ? 'success' : 'error'}">
                        <figure><img src="${body.icon}"></figure>
                        <span>${body.message}</span>
                    </div>`
            })
        });
    })
})