let form = document.getElementById('login-form');

function validateLoginFormData(event)
{
    event.preventDefault();

    let isValid = true;

    document.getElementById('login-error-messages-container').textContent = "";

    let username = document.getElementById('username-input').value;
    let password = document.getElementById('password-input').value;

    if (username.length < 6 || username.length > 32)
    {
        addErrorMessage("Username must be beetwen 6 and 32 characters");
        isValid = false;
    }

    if (password.length < 8 || password.length > 64)
    {
        addErrorMessage("Password must be beetwen 8 and 64 characters");
        isValid = false;
    }

    if (isValid === true)
    {
        form.submit();
    }
}

function addErrorMessage(message)
{
    let errorMessagesContainer = document.getElementById('login-error-messages-container');

    errorMessagesContainer.appendChild(createAlertWidget(message));
}

function createAlertWidget(message)
{
    let div = document.createElement('div');

    div.classList = 'alert alert-danger mt-2';

    div.textContent = message;

    return div;
}

form.addEventListener('submit', (event) => {validateLoginFormData(event)});