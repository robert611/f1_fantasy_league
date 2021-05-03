let form = document.getElementById('registration-form');

const registrationValidation = {
    isUsernameTaken: false,
    isEmailTaken: false,
    validateFormData: async function(event)
    {
        event.preventDefault();

        let isValid = true;

        document.getElementById('registration-error-messages-container').textContent = "";

        let username = document.getElementById('username-input').value;
        let password = document.getElementById('password-input').value;
        let repeatedPassword = document.getElementById('password-repeat-input').value;

        if (username.length < 6 || username.length > 32)
        {
            addErrorMessage("Username must be beetwen 6 and 32 characters");
            isValid = false;
        }

        if ((password.length < 8 || password.length > 64) || (repeatedPassword.length < 8 || repeatedPassword.length > 64))
        {
            addErrorMessage("Password must be beetwen 8 and 64 characters");
            isValid = false;
        }

        if (password !== repeatedPassword)
        {
            addErrorMessage("Your passwords do not match");
            isValid = false;
        }

        if (username === password)
        {
            addErrorMessage("Username and password must be diffrent");
            isValid = false;
        }

        await this.checkIfUsernameIsTaken(form);
        await this.checkIfEmailIsTaken(form);

        if (isValid === true && this.isUsernameTaken === false && this.isEmailTaken === false)
        {
            form.submit();
        }
    },
    checkIfUsernameIsTaken: async function(form) {
        this.isUsernameTaken = false;

        await fetch('/api/registration/is/username/taken', {
            method: 'POST',
            body: new FormData(form)
        })
        .then(response => response.json())
        .then(data => {
            if (data.is_username_taken == true) {
                addErrorMessage("Given username is already taken");
                this.isUsernameTaken = true;
            }
        });
    },
    checkIfEmailIsTaken: async function(form) {
        this.isEmailTaken = false;

        await fetch('/api/registration/is/email/taken', {
            method: 'POST',
            body: new FormData(form)
        })
        .then(response => response.json())
        .then(data => {
            if (data.is_email_taken == true) {
                addErrorMessage("Given email is already taken");
                this.isEmailTaken = true;
            }
        });
    }
}

function addErrorMessage(message)
{
    let errorMessagesContainer = document.getElementById('registration-error-messages-container');

    errorMessagesContainer.appendChild(createAlertWidget(message));
}

function createAlertWidget(message)
{
    let div = document.createElement('div');

    div.classList = 'alert alert-danger mt-2';

    div.textContent = message;

    return div;
}

form.addEventListener('submit', (event) => {registrationValidation.validateFormData(event)});